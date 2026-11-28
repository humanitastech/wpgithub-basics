# Repository Catalog

Display public GitHub repositories on a WordPress page. Cards show available descriptions, languages, statistics, and release details and link directly to GitHub. The catalog groups repositories by topic and supports sorting, per-page selection, and filters for forks and archived repositories.

## Installation

1. Download `repository-catalog.zip` from [GitHub Releases](https://github.com/humanitastech/wpgithub-basics/releases). The plugin requires WordPress 6.5 and PHP 7.4 or later.
2. In WordPress, go to **Plugins → Add Plugin → Upload Plugin** and activate the ZIP.
3. Follow the [configuration guide](docs/secure-configuration.md) to set server limits and configure **Settings → Repository Catalog**.
4. Add `[ht_github_projects_basics]` to a Shortcode block. To select repositories for a page, use `[ht_github_projects_basics include="repo-one,repo-two"]`.

## Display names

A card can use a quoted `display_name` field in the front matter of `ARTICLE.md` at the latest stable release tag. If the release, file, or field is missing or invalid, the card uses the repository name. The plugin caches only the validated display name from the article. Each repository with a release can require an additional API request within the configured request budget.

## Development

The plugin source is in `src/repository-catalog`. Configuration uses the `htgp_basics_settings` option and `HTGP_BASICS_*` environment variables. Changes are developed on `dev` and promoted to `main` for release.

Run `php tests/security.php` for offline checks. Run `./build-package.ps1` with PowerShell to generate `dist/repository-catalog.zip` and its SHA-256 checksum. See the [integration test setup](docs/validation-1.0.0.md) for testing an installed package.

## License

GPL-2.0-or-later. Copyright HumanitasTech and contributors.
