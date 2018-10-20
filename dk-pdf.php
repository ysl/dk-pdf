<?php // phpcs:ignore
/**
 * Plugin Name: DK PDF
 * Version: 1.9.6
 * Plugin URI: http://wp.dinamiko.com/demos/dkpdf
 * Description: WordPress to PDF made easy.
 * Author: Emili Castells
 * Author URI: http://www.dinamiko.com
 * Tested up to: 4.9
 * Requires at least: 4.9
 * License: MIT
 * Text Domain: dkpdf
 * Domain Path: /languages/
 */

namespace Dinamiko\DKPDF;

if (!defined('ABSPATH')) {
    exit;
}

// phpcs:disable Inpsyde.CodeQuality.NoTopLevelDefine.Found
define('DKPDF_VERSION', '1.9.6');
define('DKPDF_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DKPDF_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DKPDF_PLUGIN_FILE', __FILE__);
// phpcs:enable

/**
 * Initialize all the plugin things.
 *
 * @throws \Throwable
 * @return void
 */
function initialize()
{
    try {
        if (is_readable(__DIR__ . '/vendor/autoload.php')) {
            /** @noinspection PhpIncludeInspection */
            include_once __DIR__ . '/vendor/autoload.php';
        }

        load_plugin_textdomain(
            'dkpdf',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages'
        );

        (new DKPDF)->init();
    } catch (\Throwable $error) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            throw $error;
        }

        do_action('dinamiko.dkpdf.error', $error);
    }
}

add_action('plugins_loaded', __NAMESPACE__ . '\\initialize');
