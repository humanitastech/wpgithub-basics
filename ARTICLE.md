---
display_name: "Repository Catalog"
---

# Repository Catalog

Repository Catalog helps WordPress site owners present public GitHub repositories in a catalogue. Its cards show available descriptions, languages, statistics and release information, then take visitors directly to GitHub.

Configure the GitHub profile and add `[ht_github_projects_basics]` to a page. Repositories can be selected, sorted and grouped by supported topics, including `plugin`, `skill`, `theme`, `app`, `library` and `docs`.

## What changes in version 1.1.0

- Cards can display a project name from a quoted `display_name` field at the beginning of `ARTICLE.md` in the latest published stable release tag.
- The repository name remains the technical identifier, and the card keeps its direct GitHub link.
- Without a release, file or valid display name, the card keeps the technical repository name.
- Metadata is read as restricted text. The article body is neither published nor stored in the metadata cache, and the plugin does not create WordPress Posts.

Commit `ARTICLE.md` before creating the release tag. Changing only `main` or `dev` does not change a released presentation name. The extra metadata request shares the existing cache and request budget; the host should account for it when configuring limits.

## Version 1.1.1

The plugin interface and documentation now use English throughout.

## Requirements and configuration

The plugin declares WordPress 6.5 or newer and PHP 7.4 or newer. The host needs outbound HTTPS and the deployment settings described in the [configuration guide](https://github.com/humanitastech/wpgithub-basics/blob/v1.1.1/docs/secure-configuration.md). Configure the profile and selection rules in **Repository Catalog**, then insert the shortcode in a page.

The basics edition reads public repositories. It retains direct repository links and does not include private repository access or article import into WordPress Posts.

## Availability

HumanitasTech maintains the public project under GPL-2.0-or-later. Get the installable package from [GitHub Releases](https://github.com/humanitastech/wpgithub-basics/releases).
