# Basics 1.1.0 display name validation

The development candidate reviewed on November 24, 2026 used the quoted `display_name` in `ARTICLE.md` from the latest stable release tag. It used the technical repository name if the release, file, or field was absent or invalid. Links went directly to GitHub. The parser rejected HTML, shortcodes, and YAML objects, and cached only the validated name. Public release and article requests used no credential and followed no redirects.

The candidate passed 72 offline assertions and 15 native integration checks on WordPress 6.5, PHP 8.2.35, and MariaDB 10.4.32. The tests covered installation of 1.0.0, upgrading to 1.1.0, retained settings, shortcode output, display names, explicit release tags, anonymous metadata requests, absence of new posts, caching, invalid input, missing releases, and reactivation. GitHub responses were simulated. WordPress reported a blocked connection to WordPress.org, which did not affect the plugin assertions.

PHP lint and `git diff --check` passed. The candidate ZIP matched its eight source files byte for byte. Its SHA-256 was `fa3ac696cc591accfb6b7d7cdd5627288387e07807bc8b70959c2692ad905194`. The archived evidence is in [audit-results/display-name-1.1.0](../audit-results/display-name-1.1.0).

This historical validation did not cover browser use, production, PHP 7.4, multisite, or load testing. An additional request per repository with a release shares the existing request budget.
