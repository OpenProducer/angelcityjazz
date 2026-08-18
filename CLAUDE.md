# Angel City Jazz — Project Context

## Project Overview

Angel City Jazz is a WordPress site hosted on **Pressable**, built on the **Newspack** theme. This repo contains the custom theme and MU plugins for the site.

## Paths

- **Repo:** `/Users/gusaustin/Dev/projects/angelcityjazz`
- **Local Studio:** `/Users/gusaustin/Dev/local/wordpress/studio/angelcityjazz`

## Symlink Rules

Themes and MU plugins are **symlinked FROM the repo INTO Studio**. The symlinks point from the Studio `wp-content/themes/` and `wp-content/mu-plugins/` directories back to the corresponding directories in the repo.

**Never edit theme or MU plugin code directly inside Studio.** Always edit in the repo; changes are reflected in Studio automatically via the symlinks.

## Plugin Rule

Plugins are **not symlinked** and **not tracked or deployed via Git**. They are managed directly per-environment through the Pressable dashboard and each site's WordPress dashboard. Do not track or edit plugins in this repo.

Five plugins — `newspack-plugin`, `newspack-blocks`, `newspack-ads`, `newspack-popups` (wp-admin display name "Newspack Campaigns" — the slug is still `newspack-popups`), and `newspack-sponsors` — aren't on WordPress.org; they're only available via GitHub. `newspack-plugin` and `newspack-blocks` are active everywhere; the other three are installed but deliberately inactive everywhere, kept up to date without being activated (activation is a separate decision). They used to be checked by a third-party plugin, `newspack-plugin-update-checker` (not tracked in this repo's git, removed from tracking by commit `6ecb14f58`), which hardcoded each plugin's old per-package `Automattic/<slug>` GitHub repo URL. Those repos were folded into the `Automattic/newspack-workspace` monorepo on 2026-08-06. They're still reachable (archived, not deleted) and Automattic keeps auto-publishing a "final version, please migrate" placeholder release on each whenever the real monorepo package releases — so the old checker doesn't just go stale, it keeps reinstalling those placeholders (confirmed live on production and dev 2026-08-18, before the fix below: `newspack-plugin`/`newspack-blocks` installs showed `(WRONG VERSION)` in their own plugin titles as a result). `newspack-sponsors` never showed that, but only because the checker's hardcoded slug list has a literal typo (`'newsspack-sponsors'`, double "s") that made it silently skip that plugin entirely — not because it was actually current (it was genuinely two patches behind). `scripts/sync-newspack-plugins.sh` replaces it — see Key Files above. `newspack-plugin-update-checker` is deactivated on all three environments (dev, stage, production — completed 2026-08-18); the script remains the ongoing mechanism for checking/applying future updates to these five plugins.

## Branch Policy

Three Pressable-hosted environments (production, stage, dev), each auto-deploying from its own branch — plus `master` as the untracked source of truth:

- `master` — source of truth; does not auto-deploy anywhere
- `pressable-production` — auto-deploys to production (angelcityjazz.com)
- `pressable-stage` — auto-deploys to stage; **not** a mirror of production — a dedicated workspace for new content/layout testing
- `pressable-dev` — auto-deploys to dev; a regularly-synced clone of production, used to verify theme fixes against real content before they reach production

Only push tested, stable code to `pressable-production`. Periodically merge `master` into the Pressable branches (and vice versa) so they don't silently drift apart. Full detail: wiki → Pressable Environments.

## Deployment Flow

1. Edit code in the repo
2. Test via the Studio symlink (local WordPress instance)
3. Commit to `master`
4. Push to `pressable-dev`, verify against dev's synced content
5. Cherry-pick the approved commit(s) to `pressable-production`, push
6. Pressable auto-deploys each branch to its environment

## CSS Deployment Reminders

When deploying CSS changes to production:

- **Always bump the child theme version number** in `wp-content/themes/newspack-angelcity-2026/style.css` (e.g. `Version: 1.1.1 → 1.1.2`)
- WordPress uses the theme version as a cache-busting query parameter (`style.css?ver=X.X.X`) — without a version bump, Cloudflare and browser caches will continue serving the old stylesheet even after the server file is updated
- After deploying, verify the new version string is visible in the browser's network tab for `style.css`

> **Why this matters:** In July 2026 a CSS fix for the artist-page event date (`time.updated:not(.published)`) was correctly deployed to the server but went unnoticed for an extended debugging session because `style.css?ver=1.1.0` never changed. Every cache layer served the old broken rule until the version was bumped to `1.1.1`.

## Key Files

- `.gitignore` — controls what is excluded from version control
- `.deployignore` — controls what is excluded from Pressable deployments
- `scripts/local/link-studio-code.sh` — sets up symlinks from the repo into the Studio instance
- `scripts/sync-newspack-theme.sh` — manual, human-invoked script that fetches the latest `newspack-theme@X.Y.Z` release from `Automattic/newspack-workspace` (the monorepo `Automattic/newspack-theme` was consolidated into, 2026-08-06) and extracts it into `wp-content/themes/newspack-theme/` for review. Nothing calls it automatically; it stops before committing.
- `scripts/sync-newspack-plugins.sh` — manual, human-invoked script that checks (and, with `--apply`, installs) updates for the five GitHub-only Newspack plugins directly from `Automattic/newspack-workspace`, over SSH + WP-CLI. Never activates a plugin — verified against WP-CLI's own source, and re-checked live after every install. Replaces `newspack-plugin-update-checker` (see Plugin Rule below). `--env stage|dev|production` required; defaults to report-only.

## Known Issues

- **Font case-collision** in `wp-content/fonts/kanit/` on macOS — macOS's case-insensitive filesystem causes issues with this directory. See `docs/local-known-issues.md` for details.

## MU Plugins

Tracked in `wp-content/mu-plugins/` (deployed to Pressable like theme code — not excluded by `.deployignore`):

- **`woocommerce-performance-optimizations.php`** — Local-environment-only WooCommerce optimizations. Guarded by `wp_get_environment_type() === 'local'` so it is a no-op on staging and production. It does three things:
  1. Skips loading the WooCommerce cart/session on pages that are not WooCommerce-related (suppresses the `woocommerce_load_cart` filter on non-Woo pages).
  2. Disables the WooCommerce REST API (`woocommerce_rest_api_enabled`).
  3. Suppresses the `wc-cart-fragments` script payload on non-Woo pages.

  The intent is to reduce WooCommerce overhead during local development on content pages where cart state is irrelevant.

- **`sso.php`** — Single sign-on plugin.
- **`wp-native-php-sessions.php`** (+ `wp-native-php-sessions/` dir) — native PHP session handling.
- **`loader.php`** + **`pantheon-mu-plugin/`** — inherited from this site's prior Pantheon hosting. `loader.php` requires `pantheon-mu-plugin/pantheon.php`, and most of that plugin's logic gates on `$_ENV['PANTHEON_ENVIRONMENT']`, which Pressable never sets — so it's dead code here, not live functionality. Left in place rather than removed; treat as intentionally inert, not a bug.

## AI Integration

- **Claude Code CLI** is launched via `claude` in the terminal. Do not use the VS Code panel extension for MCP-dependent tasks — it does not inherit the shell environment and MCP servers will not connect.
- **GitHub MCP** is configured in `~/.claude/settings.json`. Authentication uses the `gh` CLI token sourced from `~/.zshrc`.
- **WordPress MCP (`wordpress-stage`)** connects to `https://stage-angelcityjazz.mystagingwebsite.com` using the `@automattic/mcp-wordpress-remote@latest` server, configured in `~/.claude/settings.json`. The WordPress Application Password is stored directly in that server's `env` block in `~/.claude/settings.json` (not read from a shell env var, despite a legacy `WP_STAGE_APP_PASSWORD` export sitting unused in `~/.zshrc`).
- **Pressable MCP (`pressable`)** — `https://mcp.pressable.com`, configured in `~/.claude.json`. Covers account-level Pressable operations (site search/status, plugin list/install/update per site, cross-site sync, DNS) that the `wordpress-stage`/`wordpress-studio` servers don't reach.
- **Environment note:** The VS Code extension does not inherit shell environment variables — always launch `claude` from a terminal for any session that requires MCP servers.
- **Staging vs. dev:** The stage site is an independent workspace for new content/layout testing — **not** a production clone. Dev is the production clone, regularly synced, used to verify theme changes against real content. See wiki → Pressable Environments for the full three-environment model.
- **WordPress MCP (Local Studio)**
  - Command: `studio mcp`
  - Config: added to `~/.claude.json` via `claude mcp add --scope user wordpress-studio -- studio mcp`
  - Site: http://localhost:8882/
  - No separate authentication required — Studio handles it automatically
  - Enables: local WP-CLI operations, site info, plugin management against local Studio site
  - Use for local development and testing before pushing to staging or production

## Studio CLI

- Installed and available via the `studio` command
- `studio wp` runs WP-CLI against the local Studio site without a separate WP-CLI installation
- `studio preview create` generates shareable preview URLs for stakeholder review
- `studio mcp` exposes the local Studio site as an MCP server for Claude Code
- `studio code` is an AI agent for building WordPress sites (explore further)
- Always run Studio commands from the repo root or use the `--path` flag
- Studio CLI docs: https://developer.wordpress.com/docs/developer-tools/studio/
- **Plugin updates (local):** run `studio wp plugin update --all` from `/Users/gusaustin/Dev/local/wordpress/studio/angelcityjazz`
  - Requires Studio app to be running with a fresh server process
  - If the command fails with "Could not open input file: /tmp/wp-cli.phar", quit and relaunch the Studio app, then retry
  - Premium plugins (logo-carousel-pro, events-calendar-pro, PDFThumbnails-premium) will fail silently — expected and harmless

## Do Not Commit

- `*.bak-*` files
- `.DS_Store`
- Experimental or versioned theme copies (e.g. an ad hoc `newspack-angelcity-2026-1.0.0/` backup dir) — as a convention only; none has ever actually existed in this repo, and `.gitignore` does not enforce it
