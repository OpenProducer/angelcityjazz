#!/usr/bin/env bash
set -euo pipefail

# Check (and optionally apply) updates for angelcityjazz's GitHub-sourced
# Newspack plugins, replacing newspack-plugin-update-checker.
#
# Background: newspack-plugin-update-checker (wp-content/plugins/
# newspack-plugin-update-checker/, not tracked in this repo's git — see
# CLAUDE.md) polled each Newspack plugin's dedicated Automattic/<slug>
# GitHub repo directly. Those repos were archived into the
# Automattic/newspack-workspace monorepo on 2026-08-06. They still exist
# (HTTP 200, not deleted like Automattic/newspack-theme was) and Automattic
# keeps auto-publishing a "final version, please migrate" placeholder
# release on each one whenever the real monorepo package releases — so the
# checker isn't just stale, it's actively reinstalling those placeholders.
# Confirmed live on this site 2026-08-18: production/dev already show
# "Newspack (WRONG VERSION)" / "Newspack Blocks (WRONG VERSION)" as their
# installed plugin titles as a result.
#
# We are not the maintainer of newspack-plugin-update-checker and are not
# patching it (its vendored YahnisElsts PluginUpdateChecker library has no
# hook to teach it about "{slug}@" monorepo tag prefixes). Deactivate it —
# see --deactivate-checker below — once this script's replacement mechanism
# is confirmed working, not before.
#
# Ground-truthed via the pressable MCP server against all three
# environments (2026-08-18) — do not add plugins to GH_WORKSPACE_PACKAGES
# below without re-confirming they're actually active first:
#   active + GitHub-sourced, everywhere: newspack-plugin, newspack-blocks
#   installed but INACTIVE everywhere (not managed here): newspack-ads,
#     newspack-popups, newspack-sponsors
#   not installed at all: newspack-listings, newspack-media-partners,
#     newspack-rss-enhancements, newspack-supporters
# If one of the inactive ones is ever activated, re-check its current tag
# prefix/asset name in Automattic/newspack-workspace directly before adding
# it here — don't assume it matches what's below. The monorepo moves fast:
# confirmed 2026-08-18 that the core plugin's tag prefix is "newspack" (not
# "newspack-plugin"), while its release zip asset is still named
# newspack-plugin.zip — prefix and zip name intentionally differ for that
# one entry.
#
# Apply mechanism: SSH + WP-CLI, the same validated pattern
# scripts/archive/sync-plugins.sh already used on this exact site (Pressable
# has no Terminus/git-commit path for plugins — they're not tracked in this
# repo's git at all, confirmed in CLAUDE.md). The pressable MCP server's
# install_site_plugins operation can also take a release zip URL directly
# and is a reasonable ad hoc alternative from inside a Claude Code session,
# but this script is meant to be runnable standalone from a terminal like
# sync-newspack-theme.sh, with no MCP/session dependency.
#
# Default (no --apply): fetch latest releases, compare against each
# environment's installed versions over SSH, print the plan, and STOP —
# same "fetch + report + stop" shape as sync-newspack-theme.sh. Nothing is
# installed without --apply, and --apply still asks for interactive
# confirmation before touching anything (same discipline as the archived
# script).
#
# Usage:
#   ./scripts/sync-newspack-plugins.sh --env stage|dev|production [--apply] [--deactivate-checker]
#
# Options:
#   --apply                Actually install updates (asks to confirm first). Without this, report only.
#   --deactivate-checker   After a successful --apply that brings every managed plugin to the
#                          real target version, deactivate newspack-plugin-update-checker.
#                          Ignored without --apply. If any plugin fails to reach its target
#                          version, the checker is left alone — never deactivate and leave a gap.
#   --help, -h              Show this message
#
# Environment variables (same names as the archived script):
#   ACJ_STAGE_SSH_USER, ACJ_PRODUCTION_SSH_USER, ACJ_DEV_SSH_USER
#   Requires SSH key at ~/.ssh/id_ed25519_pressable

SSH_HOST="ssh.pressable.com"
SSH_KEY="${HOME}/.ssh/id_ed25519_pressable"
REMOTE_WP_ROOT="/srv/htdocs"

GH_WORKSPACE_REPO="Automattic/newspack-workspace"
GH_MAX_PAGES=5
GH_PER_PAGE=100

# tag_prefix|wp_slug|zip_asset_name — flat array, not `declare -A`: this
# machine's default /bin/bash is 3.2 (confirmed 2026-08-18), no associative
# arrays. See header comment above for how this table was ground-truthed.
GH_WORKSPACE_PACKAGES=(
	"newspack|newspack-plugin|newspack-plugin.zip"
	"newspack-blocks|newspack-blocks|newspack-blocks.zip"
)

CHECKER_SLUG="newspack-plugin-update-checker"

ENV=""
APPLY=0
DEACTIVATE_CHECKER=0
SSH_USER=""
GH_TOKEN=""

die() { printf 'Error: %s\n' "$*" >&2; exit 1; }
log_step() { printf '\n[%s] %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$*"; }

confirm() {
	local prompt="$1" reply
	printf '\n%s [y/N] ' "$prompt"
	read -r reply
	[[ "$reply" =~ ^[Yy]$ ]]
}

usage() {
	cat <<EOF
Usage: $(basename "$0") --env stage|dev|production [options]

Options:
  --apply                Actually install updates (asks to confirm first)
  --deactivate-checker   After a fully successful --apply, deactivate newspack-plugin-update-checker
  --help, -h              Show this message

Environment variables:
  ACJ_STAGE_SSH_USER, ACJ_PRODUCTION_SSH_USER, ACJ_DEV_SSH_USER
EOF
}

while [[ $# -gt 0 ]]; do
	case "$1" in
		--env) ENV="${2:-}"; shift 2 ;;
		--env=*) ENV="${1#*=}"; shift ;;
		--apply) APPLY=1; shift ;;
		--deactivate-checker) DEACTIVATE_CHECKER=1; shift ;;
		--help|-h) usage; exit 0 ;;
		*) usage >&2; die "Unknown argument: $1" ;;
	esac
done

[[ -n "$ENV" ]] || { usage >&2; die "--env is required"; }
case "$ENV" in
	stage)      SSH_USER="${ACJ_STAGE_SSH_USER:-}" ;;
	production) SSH_USER="${ACJ_PRODUCTION_SSH_USER:-}" ;;
	dev)        SSH_USER="${ACJ_DEV_SSH_USER:-}" ;;
	*)          die "--env must be 'stage', 'dev', or 'production', got: $ENV" ;;
esac
[[ -n "$SSH_USER" ]] || die "SSH user for '$ENV' is not set. Export ACJ_$(printf '%s' "$ENV" | tr '[:lower:]' '[:upper:]')_SSH_USER before running."
[[ -f "$SSH_KEY" ]] || die "SSH key not found: $SSH_KEY"

for req in curl jq ssh; do command -v "$req" >/dev/null 2>&1 || die "'$req' is required but not found in PATH"; done

if command -v gh >/dev/null 2>&1; then
	GH_TOKEN="$(gh auth token 2>/dev/null || true)"
fi

ssh_remote() {
	ssh -i "$SSH_KEY" -o StrictHostKeyChecking=accept-new "${SSH_USER}@${SSH_HOST}" -- "$@" </dev/null
}
wp_remote() {
	ssh_remote wp --no-color "$@"
}

log_step "Preflight: ${ENV} (${SSH_USER}@${SSH_HOST})"
ssh_remote true 2>/dev/null || die "SSH connection to ${SSH_USER}@${SSH_HOST} failed. Check your key and credentials."
wp_remote cli version >/dev/null 2>&1 || die "wp-cli not responding at ${REMOTE_WP_ROOT} on ${ENV}."
printf 'SSH: OK   WP-CLI: OK\n'

# ---- fetch the shared release feed, paginated -----------------------------
# One release feed covers ~20 unrelated packages. A single page is not
# guaranteed to contain every tracked package's latest stable release if
# faster-moving packages dominate recent activity, so page forward until
# every distinct tag_prefix below has turned up as a STABLE (non-draft,
# non-prerelease) release at least once. Counting any tag (including
# prereleases) here is the exact bug the newspack-platform reference
# implementation hit: a hotfix wave put a prerelease tag for every package
# on page 1, satisfying a looser check and stopping pagination before the
# real stable releases (on a later page) were ever fetched.
log_step "Fetching release feed from ${GH_WORKSPACE_REPO}"

GH_CACHE="$(mktemp)"
GH_PAGE_TMP="$(mktemp)"
GH_MERGE_TMP="$(mktemp)"
trap 'rm -f "$GH_CACHE" "$GH_PAGE_TMP" "$GH_MERGE_TMP"' EXIT

PREFIXES="$(printf '%s\n' "${GH_WORKSPACE_PACKAGES[@]}" | cut -d'|' -f1 | sort -u | tr '\n' ' ')"

echo "[]" > "$GH_CACHE"
for page in $(seq 1 "$GH_MAX_PAGES"); do
	if ! curl -sf ${GH_TOKEN:+-H "Authorization: Bearer ${GH_TOKEN}"} \
			"https://api.github.com/repos/${GH_WORKSPACE_REPO}/releases?per_page=${GH_PER_PAGE}&page=${page}" \
			-o "$GH_PAGE_TMP"; then
		printf '  WARN  page %s: fetch failed — stopping pagination\n' "$page"
		break
	fi

	jq -s '.[0] + .[1]' "$GH_CACHE" "$GH_PAGE_TMP" > "$GH_MERGE_TMP" 2>/dev/null \
		|| { printf '  WARN  page %s: could not parse — stopping pagination\n' "$page"; break; }
	mv "$GH_MERGE_TMP" "$GH_CACHE"
	GH_MERGE_TMP="$(mktemp)"

	PAGE_LEN="$(jq 'length' "$GH_PAGE_TMP" 2>/dev/null || echo 0)"

	MISSING="$(PREFIXES="$PREFIXES" jq -r --arg prefixes "$PREFIXES" '
		[.[] | select((.tag_name // "" | contains("@")) and (.draft|not) and (.prerelease|not)) | (.tag_name | split("@")[0])] as $found
		| ($prefixes | split(" ") | map(select(length > 0))) as $want
		| ($want - $found) | join(" ")
	' "$GH_CACHE")"

	if [[ -z "$MISSING" ]]; then
		break
	fi
	if [[ "$PAGE_LEN" -lt "$GH_PER_PAGE" ]]; then
		printf '  WARN  reached end of release feed with no stable release for: %s\n' "$MISSING"
		break
	fi
done

# ---- resolve each tracked package's latest stable release -----------------
declare -a PLAN_SLUG=() PLAN_INSTALLED=() PLAN_TARGET=() PLAN_URL=()

for entry in "${GH_WORKSPACE_PACKAGES[@]}"; do
	IFS='|' read -r tag_prefix wp_slug zip_name <<< "$entry"

	release_json="$(jq -c --arg prefix "${tag_prefix}@" '
		[.[] | select((.draft|not) and (.prerelease|not) and (.tag_name // "" | startswith($prefix)))][0] // empty
	' "$GH_CACHE")"

	if [[ -z "$release_json" ]]; then
		printf '  WARN  %s: no stable release found for tag prefix "%s@" in the first %s page(s) — skipping\n' "$wp_slug" "$tag_prefix" "$GH_MAX_PAGES"
		continue
	fi

	tag="$(printf '%s' "$release_json" | jq -r '.tag_name')"
	target_version="${tag#${tag_prefix}@}"

	installed_version="$(wp_remote plugin get "$wp_slug" --field=version 2>/dev/null || true)"
	if [[ -z "$installed_version" ]]; then
		printf '  SKIP  %s: not installed on %s\n' "$wp_slug" "$ENV"
		continue
	fi

	asset_url="$(printf '%s' "$release_json" | jq -r --arg name "$zip_name" '.assets[] | select(.name == $name) | .browser_download_url')"
	if [[ -z "$asset_url" || "$asset_url" == "null" ]]; then
		printf '  WARN  %s: release %s has no asset named %s — skipping\n' "$wp_slug" "$tag" "$zip_name"
		continue
	fi

	if [[ "$installed_version" == "$target_version" ]]; then
		printf '  up to date  %s: %s\n' "$wp_slug" "$installed_version"
		continue
	fi

	printf '  update  %s: %s -> %s\n' "$wp_slug" "$installed_version" "$target_version"
	PLAN_SLUG+=("$wp_slug")
	PLAN_INSTALLED+=("$installed_version")
	PLAN_TARGET+=("$target_version")
	PLAN_URL+=("$asset_url")
done

if [[ "${#PLAN_SLUG[@]}" -eq 0 ]]; then
	printf '\nAll tracked plugins are up to date on %s. Nothing to do.\n' "$ENV"
	exit 0
fi

if [[ "$APPLY" -eq 0 ]]; then
	printf '\nReport only (no --apply given). To install these updates on %s:\n' "$ENV"
	printf '  ./%s --env %s --apply\n' "$(basename "$0")" "$ENV"
	exit 0
fi

# ---- apply -------------------------------------------------------------
printf '\n%s update(s) planned on %s.\n' "${#PLAN_SLUG[@]}" "$ENV"
confirm "Install these on ${ENV}?" || { printf 'Aborted.\n'; exit 0; }

FAILED=0
for i in "${!PLAN_SLUG[@]}"; do
	slug="${PLAN_SLUG[$i]}"
	target="${PLAN_TARGET[$i]}"
	url="${PLAN_URL[$i]}"

	log_step "Installing ${slug} ${target}"
	if wp_remote plugin install "$url" --force; then
		new_version="$(wp_remote plugin get "$slug" --field=version 2>/dev/null || true)"
		if [[ "$new_version" == "$target" ]]; then
			printf '  OK  %s now at %s\n' "$slug" "$new_version"
		else
			printf '  WARN  %s installed but reports version "%s", expected "%s" — verify manually\n' "$slug" "$new_version" "$target"
			FAILED=1
		fi
	else
		printf '  ERROR  wp plugin install failed for %s\n' "$slug"
		FAILED=1
	fi
done

if [[ "$FAILED" -ne 0 ]]; then
	printf '\nOne or more installs did not verifiably reach their target version.\n'
	printf 'Not touching %s — deactivating it now would leave a gap if any managed plugin is still on the wrong build.\n' "$CHECKER_SLUG"
	exit 1
fi

printf '\nAll managed plugins verified at their target version on %s.\n' "$ENV"

if [[ "$DEACTIVATE_CHECKER" -eq 1 ]]; then
	log_step "Deactivating ${CHECKER_SLUG}"
	if wp_remote plugin deactivate "$CHECKER_SLUG" 2>/dev/null; then
		printf '  OK  %s deactivated on %s\n' "$CHECKER_SLUG" "$ENV"
	else
		printf '  WARN  could not deactivate %s on %s (already inactive, or not installed?) — check manually\n' "$CHECKER_SLUG" "$ENV"
	fi
fi
