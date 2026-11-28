# Secure configuration

Set the API endpoint, approved HTTPS origins, and request limits in the deployment configuration. The catalog requires a valid policy before it sends requests. Keep credentials in the server configuration, outside Git, the ZIP, and WordPress options.

## First-time setup without a token

These values are an example for a test site. Review the limits against your hosting capacity and traffic before using them in production.

Add these constants to `wp-config.php` before it loads `wp-settings.php`, or set the equivalent environment variables. Environment variables take precedence over constants.

~~~php
define('HTGP_BASICS_PUBLIC_LINK_ORIGINS', 'https://github.com');
define('HTGP_BASICS_PROFILE_ORIGINS', 'https://github.com');
define('HTGP_BASICS_FAILURE_TTL_SECONDS', 60);
define('HTGP_BASICS_MAX_BACKOFF_SECONDS', 300);
define('HTGP_BASICS_REQUEST_BUDGET_SECONDS', 10);
define('HTGP_BASICS_MAX_REQUESTS', 11);
define('HTGP_BASICS_MAX_TIMEOUT_SECONDS', 3);
define('HTGP_BASICS_MAX_RESPONSE_SIZE', 524288);
define('HTGP_BASICS_MAX_API_PAGE_SIZE', 5);
~~~

With the plugin active, open **Settings → HT Repository Catalog** and enter the example values.

| Field | Example value |
|---|---|
| HTTPS API endpoint | `https://api.github.com` |
| API version | `2022-11-28` |
| HTTP user agent | `ht-repository-catalog-review` |
| Timeout in seconds | 3 |
| Response size limit in bytes | 524288 |
| Cache duration in seconds | 600 |
| Maximum repositories per request | 5 |
| Account name length limit | 39 |
| Include filter length limit | 512 |
| Repository name length limit | 100 |
| GitHub profile URL | `https://github.com/humanitastech` |
| Number of repositories | 5 |
| Sort by | `full_name` |
| Sort direction | `asc` |
| Repository type | `owner` |

Save the settings and open a page containing `[ht_github_projects_basics]` in a Shortcode block. It should show public repositories with links to GitHub and stable release details where available.

## Connection settings

| Environment variable or constant | Fallback option |
|---|---|
| HTGP_BASICS_GITHUB_API_BASE_URL | api_base_url |
| HTGP_BASICS_GITHUB_API_VERSION | api_version |
| HTGP_BASICS_HTTP_USER_AGENT | user_agent |
| HTGP_BASICS_HTTP_TIMEOUT_SECONDS | timeout_seconds |
| HTGP_BASICS_RESPONSE_SIZE_BYTES | response_size |
| HTGP_BASICS_CACHE_TTL_SECONDS | cache_ttl_seconds |
| HTGP_BASICS_API_PAGE_SIZE | api_page_size |
| HTGP_BASICS_MAX_OWNER_LENGTH | max_owner_length |
| HTGP_BASICS_MAX_INCLUDE_LENGTH | max_include_length |
| HTGP_BASICS_MAX_REPOSITORY_NAME_LENGTH | max_repo_length |

If an environment variable is unset, the plugin checks its constant. If the resulting value is empty, it checks the fallback option where one exists. The required policy and token settings have no database fallback. Enter positive integers using decimal digits without leading zeros.

## Required server policy

| Setting | Purpose |
|---|---|
| HTGP_BASICS_PUBLIC_LINK_ORIGINS | Comma-separated HTTPS origins allowed in public links |
| HTGP_BASICS_PROFILE_ORIGINS | HTTPS origins allowed for the configured profile |
| HTGP_BASICS_FAILURE_TTL_SECONDS | Minimum backoff after an external failure |
| HTGP_BASICS_MAX_BACKOFF_SECONDS | Maximum backoff, including the delay from Retry-After |
| HTGP_BASICS_REQUEST_BUDGET_SECONDS | Total time budget for external requests per PHP request |
| HTGP_BASICS_MAX_REQUESTS | Maximum HTTP calls per client per PHP request |
| HTGP_BASICS_MAX_TIMEOUT_SECONDS | Maximum request timeout |
| HTGP_BASICS_MAX_RESPONSE_SIZE | Maximum response size |
| HTGP_BASICS_MAX_API_PAGE_SIZE | Maximum repositories per API response |

Use canonical HTTPS origins with a lowercase host and an explicit port only where needed. Omit paths, trailing slashes, query strings, fragments, and credentials. An enterprise API endpoint may include a path. Keep the request timeout, response size, page size, and minimum backoff within their respective server limits.

`HTGP_BASICS_GITHUB_TOKEN` is optional. Prefer anonymous requests when the API quota allows. If you configure a token, grant the minimum permissions required and set `HTGP_BASICS_GITHUB_TOKEN_ORIGIN` to the exact origin of the API endpoint. A WordPress administrator cannot use the settings screen to change the token origin. Release and article requests are always anonymous.

## Operation

Verify catalog loading, cache reuse, API error handling, and that users without `manage_options` cannot update the settings.

The cache namespace changes with the endpoint, configuration, plugin version, or credential context. The cache key uses a salted hash of the credential. Cached values contain public metadata, and old entries expire according to the configured TTL.

A shared expiring lock prevents duplicate refreshes. Contending requests return temporary unavailability if no cached data exists. Transport, response status, or schema failures trigger backoff. Anonymous release lookups use a separate backoff when the listing uses a token. Release 404 responses are cached. Expired locks are reclaimed during requests for the current configuration. Administrators should plan cleanup for locks from retired configurations.

The `htgp_basics_security_event` action emits sanitized event codes. Deployments that require a persistent audit log must provide their own listener and retention policy. Catalog HTML is escaped, and the integration only reads from GitHub.
