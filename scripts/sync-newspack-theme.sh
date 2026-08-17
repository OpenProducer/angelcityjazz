#!/usr/bin/env bash
set -euo pipefail

# Fetch the latest newspack-theme release from GitHub, extract it into
# wp-content/themes/newspack-theme/, and show git diff --stat.
#
# Automattic/newspack-theme was retired into the Automattic/newspack-workspace
# monorepo (2026-08-06). That repo shares one release feed across ~20 packages,
# tagged `{slug}@{version}` (e.g. newspack-theme@2.25.0), with heavy pre-release
# noise (-alpha.N, -hotfix-*.N suffixes) mixed in. This script filters for the
# newest tag matching `newspack-theme@X.Y.Z` exactly (no suffix) and downloads
# that release's `newspack-theme.zip` asset specifically — NOT the release
# zipball, which on the monorepo is the entire ~20-package workspace, not just
# the theme.
#
# Does NOT commit, push, deploy, or touch any remote server.
#
# Usage:
#   ./scripts/sync-newspack-theme.sh [--dry-run]
#
# Options:
#   --dry-run   Download and extract, show diff, but do not write into the repo

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
THEME_DIR="${PROJECT_ROOT}/wp-content/themes/newspack-theme"
GITHUB_RELEASES_URL="https://api.github.com/repos/Automattic/newspack-workspace/releases"
TAG_PATTERN='^newspack-theme@[0-9]+\.[0-9]+\.[0-9]+$'
ASSET_NAME="newspack-theme.zip"

DRY_RUN=0
TMP_DIR=""

cleanup() {
	[[ -n "$TMP_DIR" && -d "$TMP_DIR" ]] && rm -rf "$TMP_DIR"
}
trap cleanup EXIT

die() { printf 'Error: %s\n' "$*" >&2; exit 1; }

log_step() { printf '\n[%s] %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$*"; }

while [[ $# -gt 0 ]]; do
	case "$1" in
		--dry-run) DRY_RUN=1; shift ;;
		--help|-h)
			printf 'Usage: %s [--dry-run]\n' "$(basename "$0")"
			exit 0
			;;
		*) die "Unknown argument: $1" ;;
	esac
done

# --- Fetch release metadata ---

log_step "Finding latest newspack-theme@X.Y.Z release in Automattic/newspack-workspace"

command -v jq >/dev/null 2>&1 || die "jq is required but not installed"

token=""
if command -v gh >/dev/null 2>&1; then
	token="$(gh auth token 2>/dev/null || true)"
fi

# The monorepo's release feed is shared across ~20 packages, newest-first.
# Page through it collecting every tag that matches newspack-theme@X.Y.Z
# exactly (no -alpha/-hotfix suffix), then take the highest version — do not
# just take the first newspack-theme@ tag encountered, and do not use
# /releases/latest, which reflects whichever package released most recently,
# not necessarily the theme.
RELEASE_TAG=""
for page in 1 2 3 4 5; do
	page_json="$(
		curl -sf \
			${token:+-H "Authorization: Bearer ${token}"} \
			-H "Accept: application/vnd.github+json" \
			"${GITHUB_RELEASES_URL}?per_page=100&page=${page}"
	)" || die "Failed to fetch release metadata from GitHub"

	page_count="$(printf '%s' "$page_json" | jq 'length')"
	[[ "$page_count" -gt 0 ]] || break

	page_tags="$(printf '%s' "$page_json" | jq -r '.[].tag_name' | grep -E "$TAG_PATTERN" || true)"
	[[ -n "$page_tags" ]] && RELEASE_TAG="$(printf '%s\n%s\n' "$RELEASE_TAG" "$page_tags" | grep -v '^$' | sort -t@ -k2 -V | tail -1)"
done

[[ -n "$RELEASE_TAG" ]] || die "Could not find a release tag matching newspack-theme@X.Y.Z in Automattic/newspack-workspace"

# Fetch the specific release (assets aren't reliably in the list response) and
# pull the theme zip asset by name — never fall back to zipball_url, which on
# this monorepo is the entire ~20-package workspace, not just the theme.
release_json="$(
	curl -sf \
		${token:+-H "Authorization: Bearer ${token}"} \
		-H "Accept: application/vnd.github+json" \
		"https://api.github.com/repos/Automattic/newspack-workspace/releases/tags/$(printf '%s' "$RELEASE_TAG" | sed 's/@/%40/')"
)" || die "Failed to fetch release detail for ${RELEASE_TAG}"

DOWNLOAD_URL="$(printf '%s' "$release_json" | jq -r --arg name "$ASSET_NAME" '.assets[] | select(.name == $name) | .browser_download_url')"
[[ -n "$DOWNLOAD_URL" && "$DOWNLOAD_URL" != "null" ]] \
	|| die "Release ${RELEASE_TAG} has no asset named '${ASSET_NAME}' — check https://github.com/Automattic/newspack-workspace/releases/tag/$(printf '%s' "$RELEASE_TAG" | sed 's/@/%40/') for the current asset list"

printf 'Latest release: %s\n' "$RELEASE_TAG"
printf 'Download URL:   %s\n' "$DOWNLOAD_URL"

if [[ -f "${THEME_DIR}/style.css" ]]; then
	local_ver="$(grep -m1 'Version:' "${THEME_DIR}/style.css" | sed 's/.*Version:[[:space:]]*//' | tr -d '[:space:]' || true)"
	printf 'Local version:  %s\n' "${local_ver:-<unknown>}"
fi

# --- Download ---

log_step "Downloading $RELEASE_TAG"

TMP_DIR="$(mktemp -d)"
zip_file="${TMP_DIR}/newspack-theme.zip"

curl -sf -L \
	${token:+-H "Authorization: Bearer ${token}"} \
	"$DOWNLOAD_URL" \
	-o "$zip_file" || die "Download failed"

zip_bytes="$(wc -c <"$zip_file" | tr -d ' ')"
[[ "$zip_bytes" -gt 1024 ]] || die "Downloaded file is suspiciously small (${zip_bytes} bytes)"
printf 'Downloaded: %s bytes\n' "$zip_bytes"

# --- Extract ---

log_step "Extracting"

unzip -q "$zip_file" -d "${TMP_DIR}/extracted"

extracted_theme_dir=""

# Case 1: newspack-theme/ directly at extraction root
if [[ -d "${TMP_DIR}/extracted/newspack-theme" ]]; then
	extracted_theme_dir="${TMP_DIR}/extracted/newspack-theme"
fi

# Case 2: versioned top-level dir containing newspack-theme/ inside
if [[ -z "$extracted_theme_dir" ]]; then
	for candidate in "${TMP_DIR}/extracted"/*/; do
		[[ -d "${candidate}newspack-theme" ]] && { extracted_theme_dir="${candidate}newspack-theme"; break; }
	done
fi

# Case 3: versioned top-level dir IS the theme (has style.css at root)
if [[ -z "$extracted_theme_dir" ]]; then
	for candidate in "${TMP_DIR}/extracted"/*/; do
		[[ -f "${candidate}style.css" ]] && { extracted_theme_dir="${candidate%/}"; break; }
	done
fi

[[ -n "$extracted_theme_dir" && -d "$extracted_theme_dir" ]] \
	|| die "Could not locate newspack-theme/ inside the downloaded zip"

printf 'Extracted theme: %s\n' "$extracted_theme_dir"

if [[ "$DRY_RUN" -eq 1 ]]; then
	printf '\nDry run. Stopping before sync.\n'
	exit 0
fi

# --- Sync into repo ---

log_step "Syncing into wp-content/themes/newspack-theme/"

mkdir -p "$THEME_DIR"
rsync -av --delete "${extracted_theme_dir}/" "${THEME_DIR}/"

# --- Diff ---

log_step "git diff --stat"

cd "$PROJECT_ROOT"
git add --intent-to-add wp-content/themes/newspack-theme 2>/dev/null || true

if git diff --quiet HEAD -- wp-content/themes/newspack-theme 2>/dev/null; then
	printf 'No changes — theme is already at %s.\n' "$RELEASE_TAG"
else
	git diff --stat HEAD -- wp-content/themes/newspack-theme
fi

# --- Done ---

printf '\nReview the diff above, then commit manually:\n'
printf '  git add wp-content/themes/newspack-theme/\n'
printf '  git commit -m "Update newspack-theme to %s"\n' "$RELEASE_TAG"
printf 'Then push to the relevant branch.\n'
