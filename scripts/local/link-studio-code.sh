#!/usr/bin/env bash
#
# Run after initial setup.
# Rerun after updating Newspack plugins via the local dashboard to restore any replaced symlinks.
# Usage: REPO_ROOT=/path/to/repo STUDIO_ROOT=/path/to/studio ./scripts/local/link-studio-code.sh

set -euo pipefail

REPO_ROOT="${REPO_ROOT:-/Users/gusaustin/Dev/projects/angelcityjazz}"
STUDIO_ROOT="${STUDIO_ROOT:-/Users/gusaustin/Dev/local/wordpress/studio/angelcityjazz}"

BACKUP_TIMESTAMP="$(date +"%Y%m%d%H%M%S")"
BACKUP_DIR="${STUDIO_ROOT}/wp-content/.link-studio-code-backups/${BACKUP_TIMESTAMP}"
BACKUP_DIR_CREATED=0

LINKED_THEMES=()
ALREADY_LINKED_THEMES=()
REPAIRED_THEMES=()
BACKED_UP_THEMES=()

LINKED_PLUGINS=()
ALREADY_LINKED_PLUGINS=()
REPAIRED_PLUGINS=()
BACKED_UP_PLUGINS=()

LINKED_MU_PLUGIN=""
ALREADY_LINKED_MU_PLUGIN=""
REPAIRED_MU_PLUGIN=""
WARNINGS=()

log() {
  printf '[link-studio-code] %s\n' "$1"
}

fail() {
  printf '[link-studio-code] ERROR: %s\n' "$1" >&2
  exit 1
}

warn() {
  local message="$1"
  WARNINGS+=("${message}")
  log "WARNING: ${message}"
}

ensure_dir() {
  mkdir -p "$1"
}

ensure_backup_dir() {
  if (( BACKUP_DIR_CREATED == 0 )); then
    mkdir -p "${BACKUP_DIR}"
    BACKUP_DIR_CREATED=1
  fi
}

record_action() {
  local category="$1"
  local action="$2"
  local item="$3"

  case "${category}:${action}" in
    theme:linked) LINKED_THEMES+=("${item}") ;;
    theme:already) ALREADY_LINKED_THEMES+=("${item}") ;;
    theme:repaired) REPAIRED_THEMES+=("${item}") ;;
    theme:backed_up) BACKED_UP_THEMES+=("${item}") ;;
    plugin:linked) LINKED_PLUGINS+=("${item}") ;;
    plugin:already) ALREADY_LINKED_PLUGINS+=("${item}") ;;
    plugin:repaired) REPAIRED_PLUGINS+=("${item}") ;;
    plugin:backed_up) BACKED_UP_PLUGINS+=("${item}") ;;
    mu-plugin:linked) LINKED_MU_PLUGIN="${item}" ;;
    mu-plugin:already) ALREADY_LINKED_MU_PLUGIN="${item}" ;;
    mu-plugin:repaired) REPAIRED_MU_PLUGIN="${item}" ;;
  esac
}

backup_target() {
  local target_path="$1"
  local relative_path="$2"
  local safe_relative_path backup_path

  ensure_backup_dir
  safe_relative_path="${relative_path//\//__}"
  backup_path="${BACKUP_DIR}/${safe_relative_path}"

  log "Backing up: ${target_path} -> ${backup_path}"
  mv "${target_path}" "${backup_path}"
  printf '%s\n' "${backup_path}"
}

ensure_symlink() {
  local category="$1"
  local relative_path="$2"
  local source_path="${REPO_ROOT}/${relative_path}"
  local target_path="${STUDIO_ROOT}/${relative_path}"
  local parent_dir current_target backup_path

  if [[ ! -e "${source_path}" ]]; then
    fail "Source path does not exist: ${source_path}"
  fi

  parent_dir="$(dirname "${target_path}")"
  ensure_dir "${parent_dir}"

  if [[ -L "${target_path}" ]]; then
    current_target="$(readlink "${target_path}")"
    if [[ "${current_target}" == "${source_path}" ]]; then
      log "Already linked: ${relative_path}"
      record_action "${category}" already "${relative_path}"
      return
    fi

    log "Repairing symlink: ${relative_path} -> ${source_path}"
    rm "${target_path}"
    ln -s "${source_path}" "${target_path}"
    record_action "${category}" repaired "${relative_path}"
    return
  fi

  if [[ -e "${target_path}" ]]; then
    backup_path="$(backup_target "${target_path}" "${relative_path}")"
    record_action "${category}" backed_up "${relative_path} -> ${backup_path}"
  fi

  log "Linking: ${relative_path} -> ${source_path}"
  ln -s "${source_path}" "${target_path}"
  record_action "${category}" linked "${relative_path}"
}

collect_repo_paths() {
  local base_dir="$1"
  local pattern="$2"

  find "${base_dir}" -mindepth 1 -maxdepth 1 -type d -name "${pattern}" -print | sort
}

print_section_array() {
  local title="$1"
  local array_name="$2"
  local item
  local printed=0

  printf '%s\n' "${title}"
  while IFS= read -r item; do
    printf '  - %s\n' "${item}"
    printed=1
  done < <(
    set +u
    eval 'for item in "${'"${array_name}"'[@]}"; do printf "%s\n" "$item"; done'
  )

  if (( printed == 0 )); then
    printf '  - none\n'
  fi
}

print_summary() {
  local mu_status="none"

  if [[ -n "${LINKED_MU_PLUGIN}" ]]; then
    mu_status="linked: ${LINKED_MU_PLUGIN}"
  elif [[ -n "${ALREADY_LINKED_MU_PLUGIN}" ]]; then
    mu_status="already linked: ${ALREADY_LINKED_MU_PLUGIN}"
  elif [[ -n "${REPAIRED_MU_PLUGIN}" ]]; then
    mu_status="repaired: ${REPAIRED_MU_PLUGIN}"
  fi

  printf '\n'
  printf 'Summary\n'
  print_section_array 'Linked themes:' LINKED_THEMES
  print_section_array 'Already linked themes:' ALREADY_LINKED_THEMES
  print_section_array 'Repaired themes:' REPAIRED_THEMES
  print_section_array 'Backed up themes:' BACKED_UP_THEMES
  print_section_array 'Linked plugins:' LINKED_PLUGINS
  print_section_array 'Already linked plugins:' ALREADY_LINKED_PLUGINS
  print_section_array 'Repaired plugins:' REPAIRED_PLUGINS
  print_section_array 'Backed up plugins:' BACKED_UP_PLUGINS
  printf 'Linked mu-plugin file:\n'
  printf '  - %s\n' "${mu_status}"
  printf 'Backup directory used:\n'
  if (( BACKUP_DIR_CREATED == 1 )); then
    printf '  - %s\n' "${BACKUP_DIR}"
  else
    printf '  - none\n'
  fi
  print_section_array 'Warnings:' WARNINGS
}

main() {
  local relative_path
  local -a theme_paths=("wp-content/themes/newspack-theme")
  local -a plugin_paths=()
  local mu_plugin_relative='wp-content/mu-plugins/woocommerce-performance-optimizations.php'

  log "Using repo root: ${REPO_ROOT}"
  log "Using Studio root: ${STUDIO_ROOT}"

  if [[ ! -d "${REPO_ROOT}" ]]; then
    fail "Repo root does not exist: ${REPO_ROOT}"
  fi

  if [[ ! -d "${STUDIO_ROOT}" ]]; then
    fail "Studio root does not exist: ${STUDIO_ROOT}"
  fi

  while IFS= read -r relative_path; do
    relative_path="${relative_path#${REPO_ROOT}/}"
    [[ "${relative_path}" == 'wp-content/themes/newspack-theme' ]] && continue
    theme_paths+=("${relative_path}")
  done < <(collect_repo_paths "${REPO_ROOT}/wp-content/themes" 'newspack-*')

  while IFS= read -r relative_path; do
    relative_path="${relative_path#${REPO_ROOT}/}"
    plugin_paths+=("${relative_path}")
  done < <(collect_repo_paths "${REPO_ROOT}/wp-content/plugins" 'newspack-*')

  for relative_path in "${theme_paths[@]}"; do
    ensure_symlink theme "${relative_path}"
  done

  for relative_path in "${plugin_paths[@]}"; do
    ensure_symlink plugin "${relative_path}"
  done

  if [[ -e "${REPO_ROOT}/${mu_plugin_relative}" ]]; then
    ensure_symlink mu-plugin "${mu_plugin_relative}"
  else
    warn "Repo mu-plugin not found, left Studio copy untouched: ${mu_plugin_relative}"
  fi

  if [[ -e "${STUDIO_ROOT}/wp-content/uploads" ]]; then
    warn 'Preserved local runtime directory: wp-content/uploads'
  fi

  if [[ -e "${STUDIO_ROOT}/wp-content/plugins.off" ]]; then
    warn 'Preserved local runtime directory: wp-content/plugins.off'
  fi

  if [[ -e "${STUDIO_ROOT}/wp-content/mu-plugins/sqlite-database-integration" ]]; then
    warn 'Preserved local runtime directory: wp-content/mu-plugins/sqlite-database-integration'
  fi

  print_summary
}

main "$@"
