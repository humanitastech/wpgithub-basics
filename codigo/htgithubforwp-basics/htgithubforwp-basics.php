<?php
/**
 * Plugin Name: HumanitasTech GitHub for WordPress basics
 * Plugin URI:  https://github.com/humanitastech/wpgithub-basics
 * Description: Exibe repositórios públicos do GitHub em uma grade curável, responsiva e armazenada em cache.
 * Version:     1.0.0
 * Requires at least: 6.5
 * Requires PHP: 7.4
 * Author:      HumanitasTech
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: htgithubforwp-basics
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('HTGP_BASICS_VERSION')) {
    define('HTGP_BASICS_VERSION', '1.0.0');
}
if (!defined('HTGP_BASICS_FILE')) {
    define('HTGP_BASICS_FILE', __FILE__);
}
if (!defined('HTGP_BASICS_DIR')) {
    define('HTGP_BASICS_DIR', plugin_dir_path(__FILE__));
}
if (!defined('HTGP_BASICS_URL')) {
    define('HTGP_BASICS_URL', plugin_dir_url(__FILE__));
}

require_once HTGP_BASICS_DIR . 'includes/class-htgp-basics-configuration.php';
require_once HTGP_BASICS_DIR . 'includes/class-htgp-basics-github-client.php';
require_once HTGP_BASICS_DIR . 'includes/class-htgp-basics-plugin.php';

HTGP_Basics_Plugin::instance();
