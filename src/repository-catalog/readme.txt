=== Repository Catalog ===
Contributors: humanitastech
Tags: github, repositories, portfolio, shortcode
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display public GitHub repositories in a catalog grouped by topic.

== Description ==

Show public GitHub repositories on a WordPress page with the [ht_github_projects_basics] shortcode. Cards show available descriptions, languages, dates, and statistics, with direct links to GitHub.

The plugin groups repositories by the plugin, skill, theme, app, library, and docs topics. When any of these topics are present, other repositories appear in a general group. You can choose the order, include or exclude forks and archived repositories, or select repositories for an individual page with [ht_github_projects_basics include="repo-one,repo-two"].

== Installation ==

1. Install and activate the plugin from the WordPress dashboard. To install a ZIP, go to Plugins → Add Plugin → Upload Plugin.
2. Configure the required HTTPS origins and request limits on your server. The [configuration guide](https://github.com/humanitastech/wpgithub-basics/blob/v1.1.1/docs/secure-configuration.md#first-time-setup-without-a-token) includes a token-free example with wp-config.php constants and example values for the settings screen. You need access to the server configuration and your host must allow outgoing HTTPS connections.
3. Go to Settings → Repository Catalog. Enter the API settings and the public GitHub profile URL, choose the number and order of repositories, and save.
4. Add [ht_github_projects_basics] to a Shortcode block and view the page to check the repository cards.

== External services ==

This plugin uses the [GitHub REST API](https://docs.github.com/en/rest) to build the public repository catalog. After an administrator configures the integration and adds the shortcode, the WordPress server contacts the configured HTTPS endpoint when it renders the catalog and needs to populate or refresh its cache.

Requests include the configured profile identifier, pagination, repository type, sorting options, API version, and HTTP user agent. Requests for stable releases and ARTICLE.md also include the owner and repository identifiers. File requests include the release tag. If an optional token is configured, WordPress sends it in the authorization header only for the repository listing and only to the configured token origin. Release and ARTICLE.md requests are anonymous.

WordPress temporarily caches public metadata needed for the cards, including names, descriptions, languages, statistics, topics, links, and release details. From ARTICLE.md, WordPress caches only the validated display name.

* [GitHub Terms of Service](https://docs.github.com/en/site-policy/github-terms/github-terms-of-service)
* [GitHub Privacy Statement](https://docs.github.com/en/site-policy/privacy-policies/github-general-privacy-statement)

== Changelog ==

= 1.1.1 =
* Use English throughout the plugin interface and documentation.

= 1.1.0 =
* Read an optional display name from ARTICLE.md in the latest stable release tag.
* Use the repository name when display metadata is missing or invalid. Card links still go directly to GitHub.
* Cache the validated display name without importing the article body or creating WordPress posts.

= 1.0.0 =
* First basics edition with public repositories, topic grouping, caching, and filters.
