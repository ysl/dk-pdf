<?php
/*
 * Plugin Name: DK PDF
 * Version: 2.0
 * Plugin URI: http://wp.dinamiko.com/demos/dkpdf
 * Description: WordPress to PDF made easy.
 * Author: Emili Castells
 * Author URI: http://www.dinamiko.com
 * Requires at least: 3.9
 * Tested up to: 4.8
 * License: MIT
 * Text Domain: dkpdf
 * Domain Path: /languages/
 */

namespace Dinamiko\DKPDF;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DKPDF_VERSION', '2.0' );
define( 'DKPDF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DKPDF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DKPDF_PLUGIN_FILE', __FILE__ );

/*
 * Initialize all the plugin things.
 *
 * @throws \Throwable
 */
function initialize() {

	try {

		if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
			/** @noinspection PhpIncludeInspection */
			require_once __DIR__ . '/vendor/autoload.php';
		}

		load_plugin_textdomain(
			'dkpdf',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages'
		);

		( new DKPDF )->init();

	} catch ( \Throwable $e ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			throw $e;
		}

		do_action( 'dinamiko.dkpdf.error', $e );
	}
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\\initialize' );
