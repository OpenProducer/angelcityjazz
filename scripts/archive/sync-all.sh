#!/usr/bin/env bash
set -euo pipefail

# Wrapper that runs all three sync scripts in the required order for a
# complete environment update. Each sub-script handles its own confirmations.
#
# Safe test entry point (no changes made):
#   ./scripts/sync-all.sh --dry-run --env stage

SCRIPT_NAME="$(basename "$0")"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

ENV=""
DRY_RUN=0

usage() {
	cat <<EOF
Usage:
  $SCRIPT_NAME --env stage|production|dev [options]

Required:
  --env stage|production|dev  Target Pressable environment

Options:
  --dry-run                   Pass --dry-run to all three sync scripts
  --help, -h                  Show this message

Runs all three sync scripts in order:
  1. sync-newspack-theme.sh  (parent theme)
  2. sync-plugins.sh         (all plugins)
  3. sync-theme.sh           (child theme + pressable-deploy push)

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

run_step() {
	local num="$1"
	local script="$2"
	shift 2
	log_step "Step ${num}/3: ${script}"
	if ! "${SCRIPT_DIR}/${script}" "$@"; then
		printf '\nError: Step %s (%s) failed. Stopping.\n' "$num" "$script" >&2
		exit 1
	fi
}

# ── Flag parsing ──────────────────────────────────────────────────────────────

while [[ $# -gt 0 ]]; do
	case "$1" in
		--env=*)   ENV="${1#*=}"; shift ;;
		--env)     ENV="${2:-}"; shift 2 ;;
		--dry-run) DRY_RUN=1; shift ;;
		--help|-h) usage; exit 0 ;;
		*)
			usage >&2
			die "Unknown argument: $1"
			;;
	esac
done

# ── Validate ──────────────────────────────────────────────────────────────────

if [[ -z "$ENV" ]]; then
	usage >&2
	die "--env is required"
fi

case "$ENV" in
	stage|production|dev) ;;
	*) die "--env must be 'stage', 'production', or 'dev', got: $ENV" ;;
esac

for script in sync-newspack-theme.sh sync-plugins.sh sync-theme.sh; do
	[[ -x "${SCRIPT_DIR}/${script}" ]] || die "${script} not found or not executable in ${SCRIPT_DIR}"
done

passthrough_flags=()
[[ "$DRY_RUN" -eq 1 ]] && passthrough_flags+=("--dry-run")

case "$ENV" in
	production) site_name="angelcityjazz" ;;
	*)          site_name="${ENV}-angelcityjazz" ;;
esac

# ── Preview ───────────────────────────────────────────────────────────────────

dry_run_suffix="$([[ "$DRY_RUN" -eq 1 ]] && printf ' --dry-run' || printf '')"

printf '\nThis will run all three sync scripts against %s in sequence:\n' "$ENV"
printf '  1. sync-newspack-theme.sh --env %s%s\n' "$ENV" "$dry_run_suffix"
printf '  2. sync-plugins.sh --env %s%s\n'        "$ENV" "$dry_run_suffix"
printf '  3. sync-theme.sh --env %s%s\n'          "$ENV" "$dry_run_suffix"

if ! confirm "Proceed with full sync on ${ENV}?"; then
	printf 'Aborted by user.\n'
	exit 0
fi

# ── Production pre-flight ─────────────────────────────────────────────────────

if [[ "$ENV" == "production" && "$DRY_RUN" -eq 0 ]]; then
	printf '\nPRODUCTION DEPLOYMENT — Pre-flight checklist:\n'
	printf '\nBefore proceeding, create an on-demand backup by running this\n'
	printf 'prompt in Claude Code:\n'
	printf '\n  '\''Using the pressable MCP server, create an on-demand backup\n'
	printf '   for angelcityjazz and confirm it completes successfully'\''\n'
	if ! confirm "Have you created a backup?"; then
		printf 'Aborted. Create a backup before deploying to production.\n'
		exit 0
	fi
fi

# ── Run steps ─────────────────────────────────────────────────────────────────

run_step 1 "sync-newspack-theme.sh" --env "$ENV" ${passthrough_flags[@]+"${passthrough_flags[@]}"}
run_step 2 "sync-plugins.sh"        --env "$ENV" ${passthrough_flags[@]+"${passthrough_flags[@]}"}
run_step 3 "sync-theme.sh"          --env "$ENV" ${passthrough_flags[@]+"${passthrough_flags[@]}"}

# ── Final summary ─────────────────────────────────────────────────────────────

log_step "All steps complete"
printf 'Environment:  %s\n' "$ENV"
printf 'Dry run:      %s\n' "$([[ "$DRY_RUN" -eq 1 ]] && printf 'yes' || printf 'no')"
printf 'Completed at: %s\n' "$(date '+%Y-%m-%d %H:%M:%S %Z')"

printf '\nNext steps:\n'
printf '\n  a) Clear edge cache — run this prompt in Claude Code:\n'
printf '       "Using the pressable MCP server, clear the edge cache for %s"\n' "$site_name"
printf '\n  b) Regenerate Jetpack Boost Critical CSS:\n'
printf '       WP Admin → Jetpack → Boost → Regenerate Critical CSS\n'

if [[ "$ENV" == "production" && "$DRY_RUN" -eq 0 ]]; then
	printf '\n  Rollback: verify your pre-flight backup completed successfully\n'
	printf '  before closing this session. If you need to rollback, restore\n'
	printf '  via Pressable dashboard or the MCP server.\n'
fi
