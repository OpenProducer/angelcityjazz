#!/usr/bin/env bash
set -euo pipefail

# Update WordPress plugins on Pressable environments.
#
# WordPress.org plugins: updated via wp plugin update --all over SSH, with all
#   non-wp-org plugins excluded.
# GitHub (Automattic) plugins: latest release zip downloaded locally, uploaded
#   via SFTP, installed via wp plugin install on the remote.
# Premium plugins: never auto-updated; flagged in summary for manual action.
# Custom/MU plugins and drop-ins: always excluded, never touched.
#
# Safe test entry point (no changes made):
#   ./scripts/sync-plugins.sh --dry-run --env stage
#
# Guards:
# - Hard exits if gh is not authenticated.
# - Hard exits if SSH preflight connection fails.
# - Hard exits if SSH key is missing.
# - Never updates must-use, drop-in, or premium plugins.
# - Skips any GitHub release tagged alpha, beta, or rc.
# - Interactive confirmation before every update action.

SCRIPT_NAME="$(basename "$0")"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

SSH_HOST="ssh.pressable.com"
SSH_KEY="${HOME}/.ssh/id_ed25519_pressable"
REMOTE_WP_ROOT="/srv/htdocs"
REMOTE_PLUGINS_PATH="${REMOTE_WP_ROOT}/wp-content/plugins"

# ── Plugin registry ───────────────────────────────────────────────────────────

# Format: "local-plugin-slug:GitHub-org/repo"
GITHUB_PLUGINS=(
	"newspack-plugin:Automattic/newspack-plugin"
	# Must be updated together with newspack-plugin (class dependency)
	"newspack-newsletters:Automattic/newspack-newsletters"
	"newspack-blocks:Automattic/newspack-blocks"
	"newspack-ads:Automattic/newspack-ads"
	"newspack-popups:Automattic/newspack-popups"
	"newspack-sponsors:Automattic/newspack-sponsors"
	"automattic-for-agencies-client:Automattic/automattic-for-agencies-client"
)

# Never auto-updated. Flagged in summary for manual action.
PREMIUM_PLUGINS=(
	"events-calendar-pro"
	"logo-carousel-pro"
	"PDFThumbnails-premium"
)

# Always excluded from all update steps.
CUSTOM_PLUGINS=(
	"newspack-plugin-update-checker"
	"tribe-ext-tec-tweaks"
	"sso"
	"woocommerce-performance-optimizations"
)

# ── Runtime state ─────────────────────────────────────────────────────────────

ENV=""
ENV_UPPER=""
DRY_RUN=0
WP_ORG_ONLY=0
GITHUB_ONLY=0
SSH_USER=""
GH_TOKEN=""
TMP_DIR=""

WP_ORG_UPDATED=()
WP_ORG_SKIPPED=()
GITHUB_UPDATED=()
GITHUB_SKIPPED=()
ERRORS=()

cleanup() {
	if [[ -n "$TMP_DIR" && -d "$TMP_DIR" ]]; then
		rm -rf "$TMP_DIR"
	fi
}
trap cleanup EXIT

# ── Helpers ───────────────────────────────────────────────────────────────────

usage() {
	cat <<EOF
Usage:
  $SCRIPT_NAME --env stage|production|dev [options]

Required:
  --env stage|production|dev  Target Pressable environment
                              (dev = long-running development work, may not reflect production data)

Options:
  --dry-run                 Show planned updates without making changes
  --wp-org-only             Only update WordPress.org plugins
  --github-only             Only update GitHub (Automattic) plugins
  --help, -h                Show this message

Environment variables:
  ACJ_STAGE_SSH_USER        SSH username for stage (host: $SSH_HOST)
  ACJ_PRODUCTION_SSH_USER   SSH username for production (host: $SSH_HOST)
  ACJ_DEV_SSH_USER          SSH username for dev (host: $SSH_HOST)

  Requires SSH key at $SSH_KEY

Safe test entry point:
  $SCRIPT_NAME --dry-run --env stage
EOF
}

die() {
	printf 'Error: %s\n' "$*" >&2
	exit 1
}

log_step() {
	printf '\n[%s] %s\n' "$(date '+%Y-%m-%d %H:%M:%S %Z')" "$*"
}

confirm() {
	local prompt="$1"
	local reply
	printf '\n%s [y/N] ' "$prompt"
	read -r reply
	[[ "$reply" =~ ^[Yy]$ ]]
}

command_exists() {
	command -v "$1" >/dev/null 2>&1
}

ssh_remote() {
	ssh -i "$SSH_KEY" -o StrictHostKeyChecking=accept-new \
		"${SSH_USER}@${SSH_HOST}" -- "$@"
}

wp_remote() {
	ssh_remote wp --no-color "$@"
}

is_prerelease() {
	local tag="$1"
	local flag="$2"
	[[ "$flag" == "true" ]] && return 0
	echo "$tag" | grep -qiE '(alpha|beta|rc)[.\-]?[0-9]*' && return 0
	return 1
}

# ── Flag parsing ──────────────────────────────────────────────────────────────

while [[ $# -gt 0 ]]; do
	case "$1" in
		--env=*)      ENV="${1#*=}";  shift ;;
		--env)        ENV="${2:-}";   shift 2 ;;
		--dry-run)    DRY_RUN=1;      shift ;;
		--wp-org-only)  WP_ORG_ONLY=1;  shift ;;
		--github-only)  GITHUB_ONLY=1;  shift ;;
		--help|-h)    usage; exit 0 ;;
		*)
			usage >&2
			die "Unknown argument: $1"
			;;
	esac
done

# ── Validate flags ────────────────────────────────────────────────────────────

if [[ -z "$ENV" ]]; then
	usage >&2
	die "--env is required"
fi

if [[ "$WP_ORG_ONLY" -eq 1 && "$GITHUB_ONLY" -eq 1 ]]; then
	die "--wp-org-only and --github-only are mutually exclusive"
fi

case "$ENV" in
	stage)      SSH_USER="${ACJ_STAGE_SSH_USER:-}";      ENV_UPPER="STAGE" ;;
	production) SSH_USER="${ACJ_PRODUCTION_SSH_USER:-}"; ENV_UPPER="PRODUCTION" ;;
	dev)        SSH_USER="${ACJ_DEV_SSH_USER:-}";         ENV_UPPER="DEV" ;;
	*)          die "--env must be 'stage', 'production', or 'dev', got: $ENV" ;;
esac

if [[ -z "$SSH_USER" ]]; then
	die "SSH user for '$ENV' is not set. Export ACJ_${ENV_UPPER}_SSH_USER before running."
fi

# ── Preflight checks ──────────────────────────────────────────────────────────

log_step "Preflight checks"

for req in gh jq curl sftp; do
	command_exists "$req" || die "'$req' is required but not found in PATH"
done

if [[ ! -f "$SSH_KEY" ]]; then
	die "SSH key not found: $SSH_KEY"
fi

GH_TOKEN="$(gh auth token 2>/dev/null || true)"
if [[ -z "$GH_TOKEN" ]]; then
	die "gh is not authenticated. Run 'gh auth login' first."
fi

printf 'gh:          authenticated\n'
printf 'Environment: %s\n' "$ENV"
printf 'SSH user:    %s\n' "$SSH_USER"
printf 'SSH host:    %s\n' "$SSH_HOST"
printf 'SSH key:     %s\n' "$SSH_KEY"
printf 'Remote WP:   %s\n' "$REMOTE_WP_ROOT"
printf 'Dry run:     %s\n' "$([[ "$DRY_RUN" -eq 1 ]] && printf 'yes' || printf 'no')"

mode_label="all"
[[ "$WP_ORG_ONLY" -eq 1 ]]  && mode_label="wp-org only"
[[ "$GITHUB_ONLY" -eq 1 ]]  && mode_label="github only"
printf 'Mode:        %s\n' "$mode_label"

printf '\nTesting SSH connection to %s@%s...\n' "$SSH_USER" "$SSH_HOST"
if ! ssh_remote true 2>/dev/null; then
	die "SSH connection to ${SSH_USER}@${SSH_HOST} failed. Check your key and credentials."
fi
printf 'SSH:         OK\n'

printf 'Testing WP-CLI at %s...\n' "$REMOTE_WP_ROOT"
if ! wp_remote cli version >/dev/null 2>&1; then
	die "wp CLI not responding at ${REMOTE_WP_ROOT} on remote host."
fi
printf 'WP-CLI:      OK\n'

TMP_DIR="$(mktemp -d)"

# ── Build exclusion list for wp plugin update --all ───────────────────────────

exclude_slugs=()
for entry in "${GITHUB_PLUGINS[@]}"; do
	exclude_slugs+=("${entry%%:*}")
done
for slug in "${PREMIUM_PLUGINS[@]}" "${CUSTOM_PLUGINS[@]}"; do
	exclude_slugs+=("$slug")
done
exclude_csv="$(IFS=,; printf '%s' "${exclude_slugs[*]}")"

# ── WordPress.org plugin updates ──────────────────────────────────────────────

if [[ "$GITHUB_ONLY" -eq 0 ]]; then
	log_step "Checking WordPress.org plugin updates"

	available_json="$(
		wp_remote plugin list \
			--update=available \
			--fields=name,version,update_version \
			--format=json 2>/dev/null
	)" || available_json="[]"

	# Guard: ensure we have valid JSON
	if ! printf '%s' "$available_json" | jq '.' >/dev/null 2>&1; then
		available_json="[]"
	fi

	# Filter out excluded slugs
	excl_json="$(printf '%s\n' "${exclude_slugs[@]}" | jq -R . | jq -s .)"
	filtered_json="$(
		printf '%s' "$available_json" \
			| jq --argjson excl "$excl_json" \
				'[.[] | select(.name as $n | $excl | index($n) | not)]'
	)"

	update_count="$(printf '%s' "$filtered_json" | jq 'length')"

	if [[ "$update_count" -eq 0 ]]; then
		printf 'All WordPress.org plugins are up to date.\n'
	else
		printf '\nWordPress.org plugins with available updates (%s):\n' "$update_count"
		printf '%s' "$filtered_json" \
			| jq -r '.[] | "  \(.name): \(.version) → \(.update_version)"'

		if [[ "$DRY_RUN" -eq 1 ]]; then
			printf '\nDry run. Skipping wp plugin update.\n'
			WP_ORG_SKIPPED+=("dry run — ${update_count} update(s) available")
		elif confirm "Update these ${update_count} WordPress.org plugin(s) on ${ENV}?"; then
			log_step "Running wp plugin update --all --exclude=..."

			update_output="$(
				wp_remote plugin update --all \
					--exclude="${exclude_csv}" \
					--format=json 2>/dev/null
			)" || update_output="[]"

			if ! printf '%s' "$update_output" | jq '.' >/dev/null 2>&1; then
				update_output="[]"
			fi

			while IFS= read -r line; do
				[[ -n "$line" ]] && WP_ORG_UPDATED+=("$line")
			done < <(
				printf '%s' "$update_output" \
					| jq -r '.[] | "\(.name): \(.old_version) → \(.new_version)"' 2>/dev/null \
					|| true
			)

			printf 'WordPress.org update complete.\n'
		else
			printf 'WordPress.org update skipped by user.\n'
			WP_ORG_SKIPPED+=("skipped by user")
		fi
	fi
fi

# ── GitHub (Automattic) plugin updates ───────────────────────────────────────

if [[ "$WP_ORG_ONLY" -eq 0 ]]; then
	log_step "Checking GitHub (Automattic) plugin updates"

	# First pass: collect what needs updating
	# Each entry: "slug|current_version|release_tag|download_url"
	pending_updates=()

	for entry in "${GITHUB_PLUGINS[@]}"; do
		plugin_slug="${entry%%:*}"
		github_repo="${entry##*:}"

		printf '\n  Checking %s (%s)...\n' "$plugin_slug" "$github_repo"

		current_version="$(
			wp_remote plugin get "$plugin_slug" --field=version 2>/dev/null \
				|| printf 'not-installed'
		)"
		printf '    Installed: %s\n' "$current_version"

		release_json="$(
			curl -sf \
				-H "Authorization: Bearer ${GH_TOKEN}" \
				-H "Accept: application/vnd.github+json" \
				"https://api.github.com/repos/${github_repo}/releases/latest"
		)" || {
			printf '    Warning: GitHub API request failed for %s\n' "$github_repo" >&2
			GITHUB_SKIPPED+=("${plugin_slug} (GitHub API error)")
			continue
		}

		release_tag="$(printf '%s' "$release_json" | jq -r '.tag_name')"
		prerelease_flag="$(printf '%s' "$release_json" | jq -r '.prerelease')"

		if [[ -z "$release_tag" || "$release_tag" == "null" ]]; then
			printf '    Warning: No release tag found for %s\n' "$github_repo" >&2
			GITHUB_SKIPPED+=("${plugin_slug} (no release tag)")
			continue
		fi

		printf '    Latest:    %s\n' "$release_tag"

		if is_prerelease "$release_tag" "$prerelease_flag"; then
			printf '    Skipping: %s is a pre-release.\n' "$release_tag"
			GITHUB_SKIPPED+=("${plugin_slug} (pre-release: ${release_tag})")
			continue
		fi

		# Strip leading 'v' for version comparison
		tag_version="${release_tag#v}"
		if [[ "$current_version" == "$tag_version" || "$current_version" == "$release_tag" ]]; then
			printf '    Already at latest. Skipping.\n'
			GITHUB_SKIPPED+=("${plugin_slug} (already at ${release_tag})")
			continue
		fi

		# Prefer a packaged zip asset; fall back to zipball
		download_url="$(
			printf '%s' "$release_json" \
				| jq -r '[.assets[] | select(.name | endswith(".zip"))] | first | .browser_download_url // empty'
		)"
		if [[ -z "$download_url" ]]; then
			download_url="$(printf '%s' "$release_json" | jq -r '.zipball_url')"
		fi
		if [[ -z "$download_url" || "$download_url" == "null" ]]; then
			printf '    Warning: No download URL found for %s %s\n' "$plugin_slug" "$release_tag" >&2
			GITHUB_SKIPPED+=("${plugin_slug} (no download URL)")
			continue
		fi

		pending_updates+=("${plugin_slug}|${current_version}|${release_tag}|${download_url}")
	done

	if [[ "${#pending_updates[@]}" -eq 0 ]]; then
		printf '\nAll GitHub (Automattic) plugins are up to date.\n'
	else
		printf '\nGitHub (Automattic) plugins to update (%s):\n' "${#pending_updates[@]}"
		for item in "${pending_updates[@]}"; do
			IFS='|' read -r slug cur tag _ <<< "$item"
			printf '  %-45s %s → %s\n' "$slug" "$cur" "$tag"
		done

		if [[ "$DRY_RUN" -eq 1 ]]; then
			printf '\nDry run. Skipping GitHub plugin installs.\n'
			for item in "${pending_updates[@]}"; do
				IFS='|' read -r slug _ tag _ <<< "$item"
				GITHUB_SKIPPED+=("${slug} (dry run — would install ${tag})")
			done
		elif confirm "Install these ${#pending_updates[@]} GitHub plugin update(s) on ${ENV}?"; then
			for item in "${pending_updates[@]}"; do
				IFS='|' read -r plugin_slug current_version release_tag download_url <<< "$item"

				log_step "Updating ${plugin_slug} to ${release_tag}"

				local_zip="${TMP_DIR}/${plugin_slug}.zip"
				remote_zip="/tmp/${plugin_slug}-$$.zip"

				# Download zip locally
				printf 'Downloading %s...\n' "$release_tag"
				if ! curl -sf -L \
						-H "Authorization: Bearer ${GH_TOKEN}" \
						"$download_url" \
						-o "$local_zip"; then
					printf 'Error: download failed for %s\n' "$plugin_slug" >&2
					ERRORS+=("${plugin_slug}: download failed")
					continue
				fi

				zip_bytes="$(wc -c <"$local_zip" | tr -d ' ')"
				if [[ "$zip_bytes" -lt 1024 ]]; then
					printf 'Error: zip for %s is suspiciously small (%s bytes)\n' \
						"$plugin_slug" "$zip_bytes" >&2
					ERRORS+=("${plugin_slug}: zip too small (${zip_bytes} bytes)")
					continue
				fi
				printf 'Downloaded: %s bytes\n' "$zip_bytes"

				# Upload via SFTP
				printf 'Uploading to remote /tmp/...\n'
				sftp -i "$SSH_KEY" "${SSH_USER}@${SSH_HOST}" <<EOF
put ${local_zip} ${remote_zip}
bye
EOF

				# Install via WP-CLI on remote
				printf 'Installing via wp plugin install...\n'
				if ! wp_remote plugin install "$remote_zip" --force; then
					printf 'Error: wp plugin install failed for %s\n' "$plugin_slug" >&2
					ERRORS+=("${plugin_slug}: wp plugin install failed")
					ssh_remote "rm -f '${remote_zip}'" 2>/dev/null || true
					continue
				fi

				# Clean up remote temp file
				ssh_remote "rm -f '${remote_zip}'" 2>/dev/null || true

				printf 'Updated: %s → %s\n' "$plugin_slug" "$release_tag"
				GITHUB_UPDATED+=("${plugin_slug}: ${current_version} → ${release_tag}")
			done
		else
			printf 'GitHub plugin updates skipped by user.\n'
			for item in "${pending_updates[@]}"; do
				IFS='|' read -r slug _ tag _ <<< "$item"
				GITHUB_SKIPPED+=("${slug} (skipped by user)")
			done
		fi
	fi
fi

# ── Summary ───────────────────────────────────────────────────────────────────

log_step "Summary"
printf 'Environment:  %s\n' "$ENV"
printf 'Dry run:      %s\n' "$([[ "$DRY_RUN" -eq 1 ]] && printf 'yes' || printf 'no')"
printf 'Completed at: %s\n' "$(date '+%Y-%m-%d %H:%M:%S %Z')"

printf '\nWordPress.org updated (%s):\n' "${#WP_ORG_UPDATED[@]}"
if [[ "${#WP_ORG_UPDATED[@]}" -eq 0 ]]; then
	printf '  (none)\n'
else
	for item in "${WP_ORG_UPDATED[@]}"; do printf '  %s\n' "$item"; done
fi

if [[ "${#WP_ORG_SKIPPED[@]}" -gt 0 ]]; then
	printf '\nWordPress.org skipped:\n'
	for item in "${WP_ORG_SKIPPED[@]}"; do printf '  %s\n' "$item"; done
fi

printf '\nGitHub (Automattic) updated (%s):\n' "${#GITHUB_UPDATED[@]}"
if [[ "${#GITHUB_UPDATED[@]}" -eq 0 ]]; then
	printf '  (none)\n'
else
	for item in "${GITHUB_UPDATED[@]}"; do printf '  %s\n' "$item"; done
fi

if [[ "${#GITHUB_SKIPPED[@]}" -gt 0 ]]; then
	printf '\nGitHub skipped:\n'
	for item in "${GITHUB_SKIPPED[@]}"; do printf '  %s\n' "$item"; done
fi

printf '\nPremium plugins — manual update required:\n'
for slug in "${PREMIUM_PLUGINS[@]}"; do
	printf '  %s\n' "$slug"
done

printf '\nAlways excluded (custom/MU):\n'
for slug in "${CUSTOM_PLUGINS[@]}"; do
	printf '  %s\n' "$slug"
done

if [[ "${#ERRORS[@]}" -gt 0 ]]; then
	printf '\nErrors (%s):\n' "${#ERRORS[@]}"
	for item in "${ERRORS[@]}"; do printf '  %s\n' "$item"; done
fi
