<?php
// Local integration fixture. Requires a disposable WordPress root and an isolated database.
$root = getenv('HTGP_BASICS_TEST_ROOT');
$phase = $argv[1] ?? '';
if (PHP_SAPI !== 'cli' || !$root || !is_file($root . '/.htgp-disposable')) { exit(1); }
if ($phase === 'install') { define('WP_INSTALLING', true); }
$settings = array(
    'api_base_url' => 'https://api.github.com', 'api_version' => '2022-11-28',
    'user_agent' => 'basics-integration', 'timeout_seconds' => 2, 'response_size' => 100000,
    'cache_ttl_seconds' => 300, 'api_page_size' => 20, 'max_owner_length' => 39,
    'max_include_length' => 1000, 'max_repo_length' => 100,
    'profile_url' => 'https://github.com/demo', 'limit' => 10, 'sort' => 'updated',
    'direction' => 'desc', 'type' => 'owner', 'include_forks' => false, 'include_archived' => false,
);
foreach (array(
    'PUBLIC_LINK_ORIGINS' => 'https://github.com', 'PROFILE_ORIGINS' => 'https://github.com',
    'FAILURE_TTL_SECONDS' => 5, 'MAX_BACKOFF_SECONDS' => 10, 'REQUEST_BUDGET_SECONDS' => 10,
    'MAX_REQUESTS' => 10, 'MAX_TIMEOUT_SECONDS' => 5, 'MAX_RESPONSE_SIZE' => 100000,
    'MAX_API_PAGE_SIZE' => 100,
) as $key => $value) { putenv('HTGP_BASICS_' . $key . '=' . $value); }
require $root . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/admin.php';
require_once ABSPATH . 'wp-admin/includes/upgrade.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
function verify_basics($condition, $label) {
    if (!$condition) { throw new RuntimeException($label); }
    echo "PASS: $label\n";
}
add_filter('pre_wp_mail', '__return_true');
add_filter('pre_http_request', function ($result, $args, $url) {
    if (wp_parse_url($url, PHP_URL_HOST) === 'api.wordpress.org') {
        return array('headers' => array(), 'response' => array('code' => 200),
            'body' => '{"offers":[],"plugins":{},"themes":{},"translations":[]}');
    }
    return $result;
}, 10, 3);
$plugin_file = 'htgithubforwp-basics/htgithubforwp-basics.php';
if ($phase === 'install') {
    wp_install('Basics local fixture', 'basics_admin', 'admin@example.test', false, '', wp_generate_password());
    update_option('htgp_basics_settings', $settings);
    $installer = new Plugin_Upgrader(new Automatic_Upgrader_Skin());
    ob_start();
    $result = $installer->install(getenv('HTGP_BASICS_TEST_ZIP'));
    ob_end_clean();
    verify_basics($result === true, 'basics ZIP installed with the WordPress installer');
    verify_basics(activate_plugin($plugin_file) === null, 'basics activates as a standalone plugin');
    exit;
}
verify_basics(HTGP_BASICS_VERSION === '1.0.0' && is_plugin_active($plugin_file), 'basics version and slug are correct');
$calls = array();
add_filter('pre_http_request', function ($result, $args, $url) use (&$calls) {
    $path = wp_parse_url($url, PHP_URL_PATH);
    $calls[] = $path;
    $body = array();
    $status = 404;
    if ($path === '/users/demo/repos') {
        $status = 200;
        foreach (array(array('plugin-demo', array('plugin'), false), array('skill-demo', array('skill'), false),
            array('hidden-demo', array('plugin'), true)) as $row) {
            $body[] = array('name' => $row[0], 'topics' => $row[1], 'private' => $row[2],
                'owner' => array('login' => 'demo'), 'html_url' => 'https://github.com/demo/' . $row[0],
                'description' => 'Synthetic fixture');
        }
    }
    return array('headers' => array(), 'response' => array('code' => $status), 'body' => wp_json_encode($body));
}, 20, 3);
wp_set_current_user(0);
$before = (int) wp_count_posts()->publish;
$html = do_shortcode('[ht_github_projects_basics]');
verify_basics(strpos($html, 'plugin-demo') !== false && strpos($html, 'skill-demo') !== false, 'public repositories render through the native shortcode');
verify_basics(strpos($html, '>Plugins</h2>') !== false && strpos($html, '>Skills</h2>') !== false, 'repository topics produce the expected groups');
verify_basics(strpos($html, 'hidden-demo') === false, 'private repository data is rejected');
verify_basics(strpos($html, 'https://github.com/demo/plugin-demo') !== false, 'catalog links directly to the public repository');
verify_basics((int) wp_count_posts()->publish === $before, 'catalog rendering leaves the editorial post count unchanged');
verify_basics(!shortcode_exists('ht_github_projects'), 'basics registers its own shortcode');
verify_basics(count(array_filter($calls, function ($path) { return strpos($path, '/contents/') !== false || $path === '/user/repos'; })) === 0,
    'outbound requests stay inside the public catalog scope');
$before = count($calls);
do_shortcode('[ht_github_projects_basics]');
verify_basics(count($calls) === $before, 'repeated rendering reuses the cache');
deactivate_plugins($plugin_file);
verify_basics(!is_plugin_active($plugin_file), 'basics deactivation succeeds');
verify_basics(activate_plugin($plugin_file) === null && get_option('htgp_basics_settings') === $settings,
    'reactivation preserves basics settings');
