#!/usr/bin/env bash
set -euo pipefail

# Fetch the latest newspack-theme release from GitHub, update the local copy,
# commit the changes, deploy to a Pressable environment via rsync over SSH, then
# SSH in and remove any orphaned files in woocommerce/checkout/ on the server.
#
# Safe test entry point (no commit, no deploy):
#   ./scripts/sync-newspack-theme.sh --dry-run --env stage
#
# Guards:
# - Hard exits if gh is not authenticated.
# - Hard exits if the GitHub download fails.
# - Never edits files directly on the server except deleting confirmed orphans in
#   woocommerce/checkout/.
# - Always shows git diff --stat before offering to commit.
# - Interactive confirmation before every destructive step.

SCRIPT_NAME="$(basename "$0")"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"

THEME_DIR="${PROJECT_ROOT}/wp-content/themes/newspack-theme"
GITHUB_API_URL="https://api.github.com/repos/Automattic/newspack-theme/releases/latest"
SSH_HOST="ssh.pressable.com"
REMOTE_WP_ROOT="/srv/htdocs"
REMOTE_THEME_SUBPATH="wp-content/themes/newspack-theme"

ENV=""
DRY_RUN=0
SKIP_SFTP=0
SSH_USER=""
RELEASE_TAG=""
COMMITTED=0
TMP_EXTRACT_DIR=""

cleanup() {
	if [[ -n "$TMP_EXTRACT_DIR" && -d "$TMP_EXTRACT_DIR" ]]; then
		rm -rf "$TMP_EXTRACT_DIR"
	fi
}
trap cleanup EXIT

usage() {
	cat <<EOF
Usage:
  $SCRIPT_NAME --env stage|production [options]

Required:
  --env stage|production      Target Pressable environment

Options:
  --dry-run                   Download, extract, and show diff — no commit or deploy
  --skip-sftp                 Skip the rsync deploy and orphan check
  --help, -h                  Show this message

Environment variables:
  ACJ_STAGE_SSH_USER          SSH username for stage (host: $SSH_HOST)
  ACJ_PRODUCTION_SSH_USER     SSH username for production (host: $SSH_HOST)

  Requires SSH key at ~/.ssh/id_ed25519_pressable

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

while [[ $# -gt 0 ]]; do
	case "$1" in
		--env=*)
			ENV="${1#*=}"
			shift
			;;
		--env)
			ENV="${2:-}"
			shift 2
			;;
		--dry-run)
			DRY_RUN=1
			shift
			;;
		--skip-sftp)
			SKIP_SFTP=1
			shift
			;;
		--help|-h)
			usage
			exit 0
			;;
		*)
			usage >&2
			die "Unknown argument: $1"
			;;
	esac
done

# --- Validate flags ---

if [[ -z "$ENV" ]]; then
	usage >&2
	die "--env is required"
fi

case "$ENV" in
	stage)
		SSH_USER="${ACJ_STAGE_SSH_USER:-}"
		;;
	production)
		SSH_USER="${ACJ_PRODUCTION_SSH_USER:-}"
		;;
	*)
		die "--env must be 'stage' or 'production', got: $ENV"
		;;
esac

if [[ "$DRY_RUN" -eq 0 && "$SKIP_SFTP" -eq 0 && -z "$SSH_USER" ]]; then
	die "SSH user for '$ENV' is not set. Export ACJ_${ENV^^}_SSH_USER before running without --dry-run or --skip-sftp."
fi

# --- Preflight checks ---

log_step "Preflight checks"

for req in gh jq curl unzip sftp; do
	command_exists "$req" || die "'$req' is required but not found in PATH"
done

GH_TOKEN="$(gh auth token 2>/dev/null || true)"
if [[ -z "$GH_TOKEN" ]]; then
	die "gh is not authenticated. Run 'gh auth login' first."
fi

printf 'gh:          authenticated\n'
printf 'Environment: %s\n' "$ENV"
printf 'Dry run:     %s\n' "$([[ "$DRY_RUN" -eq 1 ]] && printf 'yes' || printf 'no')"
printf 'Skip deploy: %s\n' "$([[ "$SKIP_SFTP" -eq 1 ]] && printf 'yes' || printf 'no')"
if [[ -n "$SSH_USER" ]]; then
	printf 'SSH user:    %s\n' "$SSH_USER"
fi
printf 'SSH host:    %s\n' "$SSH_HOST"
printf 'Theme dir:   %s\n' "$THEME_DIR"

# --- Fetch latest release metadata ---

log_step "Fetching latest newspack-theme release from GitHub"

release_json="$(
	curl -sf \
		-H "Authorization: Bearer ${GH_TOKEN}" \
		-H "Accept: application/vnd.github+json" \
		"$GITHUB_API_URL"
)" || die "Failed to fetch release metadata from $GITHUB_API_URL"

RELEASE_TAG="$(printf '%s' "$release_json" | jq -r '.tag_name')"
if [[ -z "$RELEASE_TAG" || "$RELEASE_TAG" == "null" ]]; then
	die "Could not parse release tag from GitHub API response"
fi

# Prefer a packaged release asset (newspack-theme.zip) over the auto-generated zipball.
RELEASE_DOWNLOAD_URL="$(
	printf '%s' "$release_json" \
		| jq -r '.assets[] | select(.name == "newspack-theme.zip") | .browser_download_url' \
		| head -1
)"
if [[ -z "$RELEASE_DOWNLOAD_URL" || "$RELEASE_DOWNLOAD_URL" == "null" ]]; then
	RELEASE_DOWNLOAD_URL="$(printf '%s' "$release_json" | jq -r '.zipball_url')"
fi
if [[ -z "$RELEASE_DOWNLOAD_URL" || "$RELEASE_DOWNLOAD_URL" == "null" ]]; then
	die "Could not determine a download URL from the GitHub API response"
fi

printf 'Latest release: %s\n' "$RELEASE_TAG"
printf 'Download URL:   %s\n' "$RELEASE_DOWNLOAD_URL"

if [[ -f "${THEME_DIR}/style.css" ]]; then
	local_version="$(grep -m1 'Version:' "${THEME_DIR}/style.css" | sed 's/.*Version:[[:space:]]*//' | tr -d '[:space:]' || true)"
	printf 'Local version:  %s\n' "${local_version:-<unknown>}"
fi

# --- Download ---

log_step "Downloading $RELEASE_TAG"

TMP_EXTRACT_DIR="$(mktemp -d)"
zip_file="${TMP_EXTRACT_DIR}/newspack-theme.zip"

if ! curl -sf -L \
		-H "Authorization: Bearer ${GH_TOKEN}" \
		"$RELEASE_DOWNLOAD_URL" \
		-o "$zip_file"; then
	die "Download failed for $RELEASE_DOWNLOAD_URL"
fi

zip_bytes="$(wc -c <"$zip_file" | tr -d ' ')"
if [[ "$zip_bytes" -lt 1024 ]]; then
	die "Downloaded file is suspiciously small (${zip_bytes} bytes). Aborting."
fi

printf 'Downloaded: %s bytes\n' "$zip_bytes"

# --- Extract ---

log_step "Extracting zip"

unzip -q "$zip_file" -d "${TMP_EXTRACT_DIR}/extracted"

# The zipball typically extracts to Automattic-newspack-theme-{sha}/
# A packaged asset likely extracts to newspack-theme/ directly.
# Try both structures.
extracted_theme_dir=""

# Case 1: newspack-theme/ directly at the root of the extracted tree.
if [[ -d "${TMP_EXTRACT_DIR}/extracted/newspack-theme" ]]; then
	extracted_theme_dir="${TMP_EXTRACT_DIR}/extracted/newspack-theme"
fi

# Case 2: versioned top-level dir (zipball) containing newspack-theme/ inside.
if [[ -z "$extracted_theme_dir" ]]; then
	for candidate in "${TMP_EXTRACT_DIR}/extracted"/*/; do
		[[ -d "$candidate" ]] || continue
		if [[ -d "${candidate}newspack-theme" ]]; then
			extracted_theme_dir="${candidate}newspack-theme"
			break
		fi
	done
fi

# Case 3: versioned top-level dir IS the theme (style.css at root of that dir).
if [[ -z "$extracted_theme_dir" ]]; then
	for candidate in "${TMP_EXTRACT_DIR}/extracted"/*/; do
		[[ -d "$candidate" ]] || continue
		if [[ -f "${candidate}style.css" ]]; then
			extracted_theme_dir="${candidate%/}"
			break
		fi
	done
fi

if [[ -z "$extracted_theme_dir" || ! -d "$extracted_theme_dir" ]]; then
	printf 'Extraction tree (first 4 levels):\n' >&2
	find "${TMP_EXTRACT_DIR}/extracted" -maxdepth 4 >&2
	die "Could not locate the newspack-theme directory inside the downloaded zip"
fi

printf 'Extracted theme: %s\n' "$extracted_theme_dir"

# --- Sync into repo ---

log_step "Syncing theme files into wp-content/themes/newspack-theme/"

mkdir -p "$THEME_DIR"
rsync -a --delete "${extracted_theme_dir}/" "${THEME_DIR}/"

# --- Git diff --stat ---

log_step "Git diff --stat"

cd "$PROJECT_ROOT"

theme_rel="wp-content/themes/newspack-theme"

# Stage new/deleted files so they appear in the diff.
git add --intent-to-add "$theme_rel" 2>/dev/null || true

if git diff --quiet HEAD -- "$theme_rel" 2>/dev/null; then
	printf 'No changes detected. Theme is already at %s.\n' "$RELEASE_TAG"
	if [[ "$DRY_RUN" -eq 1 ]]; then
		printf 'Dry run. Nothing to commit or deploy.\n'
		exit 0
	fi
else
	git diff --stat HEAD -- "$theme_rel"

	if [[ "$DRY_RUN" -eq 1 ]]; then
		printf '\nDry run. Stopping before commit.\n'
		exit 0
	fi

	# --- Commit ---

	COMMIT_MSG="Update newspack-theme to ${RELEASE_TAG}"

	if ! confirm "Commit with message: \"${COMMIT_MSG}\"?"; then
		printf 'Aborted by user.\n'
		exit 0
	fi

	log_step "Committing"

	git add "$theme_rel"
	git commit -m "$COMMIT_MSG"
	printf 'Committed: %s\n' "$COMMIT_MSG"
	COMMITTED=1
fi

# --- Deploy ---

REMOTE_THEME_PATH="${REMOTE_WP_ROOT}/${REMOTE_THEME_SUBPATH}"

if [[ "$SKIP_SFTP" -eq 1 ]]; then
	log_step "Skipping deploy (--skip-sftp)"
else
	if ! confirm "Deploy to ${ENV} (${SSH_USER}@${SSH_HOST}:${REMOTE_THEME_PATH})?"; then
		printf 'Deploy skipped by user.\n'
	else
		log_step "Deploying to ${ENV} via SFTP"

		sftp -i ~/.ssh/id_ed25519_pressable -r \
			"${SSH_USER}@${SSH_HOST}:${REMOTE_THEME_PATH}" \
			<<< $'put -r '"${THEME_DIR}/."

		printf 'Deploy complete.\n'

		# --- Orphan check in woocommerce/checkout/ ---

		log_step "Checking for orphaned files in woocommerce/checkout/ on ${ENV}"

		local_checkout_dir="${THEME_DIR}/woocommerce/checkout"
		remote_checkout_path="${REMOTE_THEME_PATH}/woocommerce/checkout"

		remote_files="$(
			ssh -i ~/.ssh/id_ed25519_pressable -o StrictHostKeyChecking=accept-new \
				"${SSH_USER}@${SSH_HOST}" \
				"find '${remote_checkout_path}' -maxdepth 1 -type f -printf '%f\n' 2>/dev/null || true"
		)"

		if [[ -z "$remote_files" ]]; then
			printf 'No files found in remote woocommerce/checkout/ (or the directory does not exist).\n'
		else
			orphans=()
			while IFS= read -r filename; do
				[[ -n "$filename" ]] || continue
				if [[ ! -f "${local_checkout_dir}/${filename}" ]]; then
					orphans+=("$filename")
				fi
			done <<<"$remote_files"

			if [[ "${#orphans[@]}" -eq 0 ]]; then
				printf 'No orphaned files in woocommerce/checkout/.\n'
			else
				printf '\nOrphaned files in remote woocommerce/checkout/ (present on server, absent locally):\n'
				for f in "${orphans[@]}"; do
					printf '  %s\n' "$f"
				done

				if confirm "Delete these ${#orphans[@]} orphaned file(s) on ${ENV}?"; then
					for f in "${orphans[@]}"; do
						ssh -i ~/.ssh/id_ed25519_pressable -o StrictHostKeyChecking=accept-new \
							"${SSH_USER}@${SSH_HOST}" \
							"rm -f '${remote_checkout_path}/${f}'"
						printf 'Deleted: %s\n' "$f"
					done
					printf 'Orphan cleanup complete.\n'
					orphan_cleanup_result="deleted ${#orphans[@]} file(s)"
				else
					printf 'Orphan cleanup skipped by user.\n'
					orphan_cleanup_result="skipped by user (${#orphans[@]} file(s) remain)"
				fi
			fi
		fi
	fi
fi

# --- Summary ---

log_step "Summary"
printf 'Release:        %s\n' "$RELEASE_TAG"
printf 'Environment:    %s\n' "$ENV"
printf 'Committed:      %s\n' "$([[ "$COMMITTED" -eq 1 ]] && printf 'yes' || printf 'no (already at latest)')"
if [[ "$SKIP_SFTP" -eq 1 ]]; then
	printf 'Deployed:       skipped (--skip-sftp)\n'
	printf 'Orphan check:   skipped (--skip-sftp)\n'
else
	printf 'Deployed:       yes\n'
	printf 'Orphan check:   %s\n' "${orphan_cleanup_result:-clean (no orphans found)}"
fi
printf 'Completed at:   %s\n' "$(date '+%Y-%m-%d %H:%M:%S %Z')"
