#!/usr/bin/env bash
set -euo pipefail

# Deploy the newspack-angelcity-2025 child theme to Pressable by pushing
# master to the pressable-deploy branch. Pressable auto-deploys on push.
# After deploy, flushes WP object cache, transients, and regenerates
# Jetpack Boost Critical CSS over SSH.
#
# Safe test entry point (no push, no cache flush):
#   ./scripts/sync-theme.sh --dry-run --env stage
#
# Guards:
# - Hard exits if not on master branch.
# - Hard exits if working tree has uncommitted changes.
# - Hard exits if SSH connection fails preflight check.
# - Warns if .DS_Store or *.bak-* files found in theme directory.

SCRIPT_NAME="$(basename "$0")"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"

CHILD_THEME_PATH="wp-content/themes/newspack-angelcity-2025"
DEPLOY_BRANCH="pressable-deploy"
SSH_HOST="ssh.pressable.com"
SSH_KEY="${HOME}/.ssh/id_ed25519_pressable"

ENV=""
ENV_UPPER=""
DRY_RUN=0
SKIP_CACHE=0
SSH_USER=""

CACHE_FLUSHED=0
warnings=()

usage() {
	cat <<EOF
Usage:
  $SCRIPT_NAME --env stage|production [options]

Required:
  --env stage|production    Target Pressable environment

Options:
  --dry-run                 Show checklist and diff without pushing or flushing
  --skip-cache              Push to pressable-deploy but skip wp cache flush and wp transient delete
  --help, -h                Show this message

Environment variables:
  ACJ_STAGE_SSH_USER        SSH username for stage (host: $SSH_HOST)
  ACJ_PRODUCTION_SSH_USER   SSH username for production (host: $SSH_HOST)

  Requires SSH key at $SSH_KEY
  SSH is only required when --skip-cache is not set.

Safe test entry point:
  $SCRIPT_NAME --dry-run --env stage
EOF
}

die() {
	printf 'Error: %s\n' "$*" >&2
	exit 1
}

warn() {
	printf 'Warning: %s\n' "$*"
	warnings+=("$*")
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

# ── Flag parsing ──────────────────────────────────────────────────────────────

while [[ $# -gt 0 ]]; do
	case "$1" in
		--env=*)      ENV="${1#*=}";  shift ;;
		--env)        ENV="${2:-}";   shift 2 ;;
		--dry-run)    DRY_RUN=1;      shift ;;
		--skip-cache) SKIP_CACHE=1;   shift ;;
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

case "$ENV" in
	stage)      SSH_USER="${ACJ_STAGE_SSH_USER:-}";      ENV_UPPER="STAGE" ;;
	production) SSH_USER="${ACJ_PRODUCTION_SSH_USER:-}"; ENV_UPPER="PRODUCTION" ;;
	*)          die "--env must be 'stage' or 'production', got: $ENV" ;;
esac

if [[ "$DRY_RUN" -eq 0 && "$SKIP_CACHE" -eq 0 && -z "$SSH_USER" ]]; then
	die "SSH user for '$ENV' is not set. Export ACJ_${ENV_UPPER}_SSH_USER, or run with --skip-cache."
fi

# ── Preflight checks ──────────────────────────────────────────────────────────

log_step "Preflight checks"

command_exists git || die "'git' is required but not found in PATH"

if [[ ! -f "$SSH_KEY" && "$DRY_RUN" -eq 0 && "$SKIP_CACHE" -eq 0 ]]; then
	die "SSH key not found: $SSH_KEY"
fi

cd "$PROJECT_ROOT"

# Must be on master
current_branch="$(git rev-parse --abbrev-ref HEAD)"
if [[ "$current_branch" != "master" ]]; then
	die "Must be on master to deploy (currently on '$current_branch'). Switch to master first."
fi

# Working tree must be clean (staged or unstaged changes — not untracked files)
dirty="$(git status --porcelain | grep -v '^??' || true)"
if [[ -n "$dirty" ]]; then
	die "Working tree has uncommitted changes. Commit or stash before deploying."
fi

# Note any untracked files (advisory only)
untracked="$(git ls-files --others --exclude-standard)"
if [[ -n "$untracked" ]]; then
	warn "Untracked files in working tree — they will NOT be deployed (not tracked by git)"
fi

printf 'Branch:       %s\n' "$current_branch"
printf 'Environment:  %s\n' "$ENV"
printf 'Dry run:      %s\n' "$([[ "$DRY_RUN" -eq 1 ]] && printf 'yes' || printf 'no')"
printf 'Skip cache:   %s\n' "$([[ "$SKIP_CACHE" -eq 1 ]] && printf 'yes' || printf 'no')"

# SSH preflight — only needed when cache flush will run
if [[ "$DRY_RUN" -eq 0 && "$SKIP_CACHE" -eq 0 ]]; then
	printf 'SSH user:     %s\n' "$SSH_USER"
	printf 'SSH host:     %s\n' "$SSH_HOST"
	printf '\nTesting SSH connection to %s@%s...\n' "$SSH_USER" "$SSH_HOST"
	if ! ssh_remote true 2>/dev/null; then
		die "SSH connection to ${SSH_USER}@${SSH_HOST} failed. Check your key and credentials."
	fi
	printf 'SSH:          OK\n'
fi

# ── Check for junk files in theme directory ───────────────────────────────────

theme_abs="${PROJECT_ROOT}/${CHILD_THEME_PATH}"

if [[ -d "$theme_abs" ]]; then
	ds_count="$(find "$theme_abs" -name '.DS_Store' | wc -l | tr -d ' ')"
	if [[ "$ds_count" -gt 0 ]]; then
		warn ".DS_Store files found in theme directory (${ds_count} file(s)) — consider adding to .gitignore"
	fi

	bak_count="$(find "$theme_abs" -name '*.bak-*' | wc -l | tr -d ' ')"
	if [[ "$bak_count" -gt 0 ]]; then
		warn "*.bak-* files found in theme directory (${bak_count} file(s)) — review before deploying"
	fi
fi

# ── Pre-deployment checklist ──────────────────────────────────────────────────

log_step "Pre-deployment checklist"

last_commit_hash="$(git log -1 --pretty=format:'%h')"
last_commit_msg="$(git log -1 --pretty=format:'%s')"
last_commit_date="$(git log -1 --pretty=format:'%ci')"

printf '\nBranch:       %s\n' "$current_branch"
printf 'Last commit:  %s  %s\n' "$last_commit_hash" "$last_commit_msg"
printf 'Date:         %s\n' "$last_commit_date"

# Determine compare ref — prefer origin/pressable-deploy for accuracy
compare_ref=""
printf '\nFetching origin/%s...\n' "$DEPLOY_BRANCH"
if git fetch origin "$DEPLOY_BRANCH" 2>/dev/null; then
	compare_ref="origin/${DEPLOY_BRANCH}"
elif git rev-parse --verify "$DEPLOY_BRANCH" >/dev/null 2>&1; then
	compare_ref="$DEPLOY_BRANCH"
	printf '(Could not fetch origin/%s — using local branch)\n' "$DEPLOY_BRANCH"
fi

theme_diff_names=""

if [[ -n "$compare_ref" ]]; then
	printf '\nFiles changed in %s since last deploy:\n' "$CHILD_THEME_PATH"

	diff_stat="$(
		git diff --stat "${compare_ref}..master" -- "$CHILD_THEME_PATH" 2>/dev/null || true
	)"
	theme_diff_names="$(
		git diff --name-only "${compare_ref}..master" -- "$CHILD_THEME_PATH" 2>/dev/null || true
	)"

	if [[ -z "$diff_stat" ]]; then
		printf '  (no changes to %s since last deploy)\n' "$CHILD_THEME_PATH"
	else
		printf '%s\n' "$diff_stat"
	fi

	printf '\nAll commits not yet on %s:\n' "$DEPLOY_BRANCH"
	pending_commits="$(
		git log "${compare_ref}..master" --oneline 2>/dev/null || true
	)"
	if [[ -z "$pending_commits" ]]; then
		printf '  (none — master is already deployed)\n'
	else
		printf '%s\n' "$pending_commits" | awk '{ print "  " $0 }'
	fi
else
	printf '\n(pressable-deploy branch not found at origin or locally — this appears to be the first deploy)\n'
fi

# Show any warnings collected so far
if [[ "${#warnings[@]}" -gt 0 ]]; then
	printf '\nWarnings:\n'
	for w in "${warnings[@]}"; do
		printf '  ! %s\n' "$w"
	done
fi

if [[ "$DRY_RUN" -eq 1 ]]; then
	printf '\nDry run. Stopping before push.\n'
	exit 0
fi

# ── Confirm and push ──────────────────────────────────────────────────────────

if ! confirm "Push master to ${DEPLOY_BRANCH} and deploy to ${ENV}?"; then
	printf 'Aborted by user.\n'
	exit 0
fi

log_step "Pushing master to ${DEPLOY_BRANCH}"

git push origin "master:${DEPLOY_BRANCH}" --force-with-lease

printf 'Push complete. Pressable is picking up the deployment...\n'

# ── Cache flush ───────────────────────────────────────────────────────────────

if [[ "$SKIP_CACHE" -eq 1 ]]; then
	log_step "Skipping cache flush (--skip-cache)"
else
	log_step "Waiting 10 seconds for Pressable to deploy"
	sleep 10

	log_step "Flushing WP object cache on ${ENV}"
	if wp_remote cache flush; then
		printf 'Object cache flushed.\n'
		CACHE_FLUSHED=1
	else
		warn "wp cache flush returned a non-zero exit code — cache may not have been fully cleared"
	fi

	log_step "Deleting all transients on ${ENV}"
	if wp_remote transient delete --all; then
		printf 'Transients deleted.\n'
	else
		warn "wp transient delete --all returned a non-zero exit code"
	fi
fi

# ── Summary ───────────────────────────────────────────────────────────────────

log_step "Summary"
printf 'Deployed:         master → %s\n' "$DEPLOY_BRANCH"
printf 'Environment:      %s\n' "$ENV"
printf 'Last commit:      %s  %s\n' "$last_commit_hash" "$last_commit_msg"

if [[ -n "$theme_diff_names" ]]; then
	printf '\nTheme files changed:\n'
	printf '%s\n' "$theme_diff_names" | awk '{ print "  " $0 }'
else
	printf '\nTheme files changed:  (none — only non-theme changes deployed)\n'
fi

printf '\nObject cache:     %s\n' "$([[ "$CACHE_FLUSHED" -eq 1 ]] && printf 'flushed' || \
	([[ "$SKIP_CACHE" -eq 1 ]] && printf 'skipped (--skip-cache)' || printf 'flush attempted (check warnings)'))"
printf 'Transients:       %s\n' "$([[ "$SKIP_CACHE" -eq 1 ]] && printf 'skipped (--skip-cache)' || printf 'deleted')"
printf 'Critical CSS:     manual step required — regenerate via WP Admin → Jetpack → Boost\n'

if [[ "${#warnings[@]}" -gt 0 ]]; then
	printf '\nWarnings (%s):\n' "${#warnings[@]}"
	for w in "${warnings[@]}"; do
		printf '  ! %s\n' "$w"
	done
fi

printf '\nCompleted at: %s\n' "$(date '+%Y-%m-%d %H:%M:%S %Z')"
