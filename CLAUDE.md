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

Plugins are **not symlinked**. They are managed on Pressable dev and promoted to production via Data Transfer. Do not track or edit plugins in this repo.

## Branch Policy

- `main` — development history; this is where active work happens
- `pressable-deploy` — production deployment branch; Pressable deploys from this branch

Only push tested, stable code to `pressable-deploy`.

## Deployment Flow

1. Edit code in the repo
2. Test via the Studio symlink (local WordPress instance)
3. Commit to `main`
4. Push to `pressable-deploy`
5. Pressable picks up the deployment automatically

## Key Files

- `.gitignore` — controls what is excluded from version control
- `.deployignore` — controls what is excluded from Pressable deployments
- `.githooks` — project-level git hooks
- `scripts/local/link-studio-code.sh` — sets up symlinks from the repo into the Studio instance

## Known Issues

- **Font case-collision** in `wp-content/fonts/kanit/` on macOS — macOS's case-insensitive filesystem causes issues with this directory. See `docs/local-known-issues.md` for details.

## MU Plugins

One MU plugin is tracked in `wp-content/mu-plugins/`:

- **`woocommerce-performance-optimizations.php`** — Local-environment-only WooCommerce optimizations. Guarded by `wp_get_environment_type() === 'local'` so it is a no-op on staging and production. It does three things:
  1. Skips loading the WooCommerce cart/session on pages that are not WooCommerce-related (suppresses the `woocommerce_load_cart` filter on non-Woo pages).
  2. Disables the WooCommerce REST API (`woocommerce_rest_api_enabled`).
  3. Suppresses the `wc-cart-fragments` script payload on non-Woo pages.

  The intent is to reduce WooCommerce overhead during local development on content pages where cart state is irrelevant.

## AI Integration

- **Claude Code CLI** is launched via `claude` in the terminal. Do not use the VS Code panel extension for MCP-dependent tasks — it does not inherit the shell environment and MCP servers will not connect.
- **GitHub MCP** is configured in `~/.claude/settings.json`. Authentication uses the `gh` CLI token sourced from `~/.zshrc`.
- **WordPress MCP (`wordpress-stage`)** connects to `https://stage-angelcityjazz.mystagingwebsite.com` using the `@automattic/mcp-wordpress` server. It is also configured in `~/.claude/settings.json`.
- **`WP_STAGE_APP_PASSWORD`** must be exported in `~/.zshenv` so it is available when Claude Code launches. If it is not set at launch time, the MCP server will fail to authenticate and no WordPress tools will be available.
- **Environment note:** The VS Code extension does not inherit shell environment variables — always launch `claude` from a terminal for any session that requires MCP servers.
- **Staging vs. dev:** The staging site (`stage-angelcityjazz.mystagingwebsite.com`) is a production clone used for content updates and fixes. Long-term development work happens locally and is deployed via `pressable-deploy`.
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

## Do Not Commit

- `*.bak-*` files
- `.DS_Store`
- Experimental or versioned theme copies such as `newspack-angelcity-2025-1.0.0/`
