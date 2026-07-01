#!/usr/bin/env bash
set -euo pipefail

# Fetch the latest newspack-theme release from GitHub, extract it into
# wp-content/themes/newspack-theme/, and show git diff --stat.
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
GITHUB_API_URL="https://api.github.com/repos/Automattic/newspack-theme/releases/latest"

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

log_step "Fetching latest newspack-theme release"

auth_header=""
if command -v gh >/dev/null 2>&1; then
	token="$(gh auth token 2>/dev/null || true)"
	[[ -n "$token" ]] && auth_header="-H \"Authorization: Bearer ${token}\""
fi

release_json="$(
	curl -sf \
		${auth_header:+-H "Authorization: Bearer ${token}"} \
		-H "Accept: application/vnd.github+json" \
		"$GITHUB_API_URL"
)" || die "Failed to fetch release metadata from GitHub"

RELEASE_TAG="$(printf '%s' "$release_json" | grep -m1 '"tag_name"' | sed 's/.*"tag_name": *"\([^"]*\)".*/\1/')"
[[ -n "$RELEASE_TAG" && "$RELEASE_TAG" != "null" ]] || die "Could not parse release tag"

# Prefer packaged asset zip; fall back to zipball_url
DOWNLOAD_URL="$(
	printf '%s' "$release_json" \
		| grep -A2 '"newspack-theme.zip"' \
		| grep '"browser_download_url"' \
		| sed 's/.*"browser_download_url": *"\([^"]*\)".*/\1/' \
		| head -1
)"
if [[ -z "$DOWNLOAD_URL" ]]; then
	DOWNLOAD_URL="$(printf '%s' "$release_json" | grep '"zipball_url"' | head -1 | sed 's/.*"zipball_url": *"\([^"]*\)".*/\1/')"
fi
[[ -n "$DOWNLOAD_URL" && "$DOWNLOAD_URL" != "null" ]] || die "Could not determine download URL"

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
	${auth_header:+-H "Authorization: Bearer ${token}"} \
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
