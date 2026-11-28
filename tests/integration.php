<?php
$root = getenv('HTGP_INTEGRATION_ROOT');
$phase = $argv[1] ?? '';
if (PHP_SAPI !== 'cli' || !$root || !is_file($root . '/.htgp-disposable')) { exit(2); }
define('WP_CLI', true);
if ($phase === 'install') { define('WP_INSTALLING', true); }
$settings = array(
    'api_base_url' => 'https://api.github.com', 'api_version' => '2022-11-28',
    'user_agent' => 'htgp-isolated-tests', 'profile_url' => 'https://github.com/demo',
    'limit' => 10, 'sort' => 'updated', 'direction' => 'desc', 'type' => 'owner',
    'timeout_seconds' => 2, 'response_size' => 100000, 'cache_ttl_seconds' => 300,
    'api_page_size' => 20, 'max_owner_length' => 39, 'max_include_length' => 1000,
    'max_repo_length' => 100,
);
foreach (array(
    'HTGP_BASICS_PUBLIC_LINK_ORIGINS' => 'https://github.com', 'HTGP_BASICS_PROFILE_ORIGINS' => 'https://github.com',
    'HTGP_BASICS_GITHUB_TOKEN_ORIGIN' => 'https://api.github.com', 'HTGP_BASICS_GITHUB_PRIVATE_TOKEN' => 'synthetic-private',
    'HTGP_BASICS_PRIVATE_CACHE_TTL_SECONDS' => 60,
    'HTGP_BASICS_FAILURE_TTL_SECONDS' => 5, 'HTGP_BASICS_MAX_BACKOFF_SECONDS' => 10,
    'HTGP_BASICS_REQUEST_BUDGET_SECONDS' => 20, 'HTGP_BASICS_MAX_REQUESTS' => 12,
    'HTGP_BASICS_MAX_TIMEOUT_SECONDS' => 5, 'HTGP_BASICS_MAX_RESPONSE_SIZE' => 150000, 'HTGP_BASICS_MAX_API_PAGE_SIZE' => 100,
) as $key => $value) { putenv($key . '=' . $value); }

require $root . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/admin.php';
require_once ABSPATH . 'wp-admin/includes/upgrade.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
function verify($condition, $label) {
    if (!$condition) { throw new RuntimeException($label); }
    echo 'PASS: ' . $label . PHP_EOL;
}
$baseline_plugin_file = 'repository-catalog/repository-catalog.php';
$plugin_file = 'ht-repository-catalog/ht-repository-catalog.php';
if ($phase === 'install') {
    wp_install('Basics isolated', 'basics_test_admin', 'admin@example.test', false, '', wp_generate_password());
    update_option('htgp_basics_settings', $settings);
    $upgrader = new Plugin_Upgrader(new Automatic_Upgrader_Skin());
    ob_start(); $result = $upgrader->install(getenv('HTGP_BASELINE_ZIP')); ob_end_clean();
    verify($result === true, 'baseline ZIP installed through WordPress');
    verify(activate_plugin($baseline_plugin_file) === null, 'baseline activates');
    exit;
}
if ($phase === 'upgrade') {
    verify(HTGP_BASICS_VERSION === '1.1.1', 'baseline version loads before update');
    $upgrader = new Plugin_Upgrader(new Automatic_Upgrader_Skin());
    ob_start(); $result = $upgrader->install(getenv('HTGP_CANDIDATE_ZIP'), array('overwrite_package' => true)); ob_end_clean();
    verify($result === true, '1.1.2 ZIP installs under the new slug');
    deactivate_plugins($baseline_plugin_file);
    verify(activate_plugin($plugin_file) === null, 'new slug activates');
    exit;
}
if ($phase !== 'verify') { exit(2); }
verify(HTGP_BASICS_VERSION === '1.1.2', 'candidate version loads from the installed ZIP');
verify(get_option('htgp_basics_settings') === $settings, 'upgrade preserves basics settings');
$payload = '"Web Security Audit Skill"'; $release_available = true; $calls = array();
add_filter('pre_http_request', function ($result, $args, $url) use (&$payload, &$calls, &$release_available) {
    $calls[] = array('url' => $url, 'args' => $args); $status = 200;
    if (strpos($url, '/releases/latest') !== false) {
        $status = $release_available ? 200 : 404;
        $body = array('tag_name' => 'v1', 'html_url' => 'https://github.com/demo/demo/releases/tag/v1',
            'published_at' => '2026-11-24T12:00:00Z', 'draft' => false, 'prerelease' => false);
    } elseif (strpos($url, '/contents/ARTICLE.md') !== false) {
        verify(empty($args['headers']['Authorization']), 'public metadata lookup has no credential');
        parse_str(wp_parse_url($url, PHP_URL_QUERY), $query);
        verify($query['ref'] === 'refs/tags/v1', 'article lookup pins the published release tag');
        $body = array('type' => 'file', 'path' => 'ARTICLE.md', 'encoding' => 'base64',
            'content' => base64_encode("---\ndisplay_name: " . $payload . "\n---\nUNPUBLISHED_BODY"));
    } else {
        $body = array(array('name' => 'demo', 'html_url' => 'https://github.com/demo/demo', 'private' => false,
            'owner' => array('login' => 'demo'), 'description' => 'Fixture', 'topics' => array('plugin')));
    }
    return array('headers' => array(), 'response' => array('code' => $status), 'body' => wp_json_encode($body));
}, 10, 3);
function fresh_basics_request() {
    global $wpdb;
    $keys = $wpdb->get_col($wpdb->prepare("SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s", $wpdb->esc_like('_transient_htgp_basics_') . '%'));
    foreach ($keys as $key) { delete_transient(substr($key, strlen('_transient_'))); }
    $property = new ReflectionProperty(HTGP_Basics_Plugin::class, 'client'); $property->setAccessible(true);
    $property->setValue(HTGP_Basics_Plugin::instance(), new HTGP_Basics_GitHub_Client(new HTGP_Basics_Configuration()));
}
wp_set_current_user(0);
$posts_before = wp_count_posts()->publish;
$html = do_shortcode('[ht_github_projects_basics]');
verify(strpos($html, '>Web Security Audit Skill</a>') !== false, 'native shortcode displays the released project name');
verify(strpos($html, 'href="https://github.com/demo/demo"') !== false, 'card retains the direct repository URL');
verify(wp_count_posts()->publish === $posts_before && strpos($html, 'UNPUBLISHED_BODY') === false, 'metadata feature neither creates Posts nor publishes the body');
$count = count($calls); do_shortcode('[ht_github_projects_basics]');
verify(count($calls) === $count, 'warm metadata cache avoids extra HTTP calls');
$payload = '!!php/object:payload'; fresh_basics_request(); $html = do_shortcode('[ht_github_projects_basics]');
verify(strpos($html, '>demo</a>') !== false, 'hostile metadata safely falls back in native WordPress');
$release_available = false; fresh_basics_request(); $calls = array(); $html = do_shortcode('[ht_github_projects_basics]');
verify(count($calls) === 2 && strpos($html, '>demo</a>') !== false, 'absent release avoids article/branch lookup');
deactivate_plugins($plugin_file); verify(!is_plugin_active($plugin_file), 'deactivation succeeds');
verify(activate_plugin($plugin_file) === null, 'reactivation succeeds');
verify(get_option('htgp_basics_settings') === $settings, 'reactivation preserves settings');
