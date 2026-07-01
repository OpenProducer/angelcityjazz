# Archived Scripts

These scripts were used prior to the Pressable GitHub integration deploy workflow
and are no longer part of the active development process.

## Why archived

- `sync-theme.sh` -- deployed child theme via SFTP and pushed to `pressable-deploy`.
  Both are now handled automatically by Pressable's GitHub integration.
- `sync-plugins.sh` -- updated plugins via SSH/SFTP. Plugins are no longer tracked
  in the repository or deployed via GitHub. All plugin updates are handled via the
  Pressable or WordPress dashboard per environment.
- `sync-all.sh` -- thin wrapper that ran all three scripts in sequence. No longer
  needed.

## Plugin slug reference (extracted from sync-plugins.sh)

The following plugin categories were managed by sync-plugins.sh and are documented
here for reference:

**Always excluded from bulk updates (custom/MU):**
- newspack-plugin-update-checker
- tribe-ext-tec-tweaks
- sso
- woocommerce-performance-optimizations

**Premium plugins (never auto-updated, manual only):**
- events-calendar-pro
- logo-carousel-pro
- PDFThumbnails-premium

**GitHub/Automattic plugins (manual download and install):**
- Update via: download latest release zip from GitHub, copy into repo if theme-related,
  or install directly via Pressable/WP dashboard if plugin-only.
