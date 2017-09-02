<?php # -*- coding: utf-8 -*-

namespace Dinamiko\DKPDF;

use Dinamiko\DKPDF\Admin\AdminAPI;
use Dinamiko\DKPDF\Admin\Settings;

class DKPDF {

	public function init() {

		add_action( 'plugins_loaded', array( $this, 'dkpdf_load_textdomain' ) );

		if ( is_admin() ) {
			$admin = new AdminAPI();
			new Settings( $admin );
		}

		/*
			// settings / metaboxes
			if ( is_admin() ) {

				require_once DKPDF_PLUGIN_DIR . 'includes/class-dkpdf-settings.php';
				$settings = new DKPDF_Settings( $this );

				require_once DKPDF_PLUGIN_DIR . 'includes/class-dkpdf-admin-api.php';
				$this->admin = new DKPDF_Admin_API();

				require_once DKPDF_PLUGIN_DIR . 'includes/dkpdf-metaboxes.php';

			}
			// load css / js
			require_once DKPDF_PLUGIN_DIR . 'includes/dkpdf-load-js-css.php';

			// functions
			require_once DKPDF_PLUGIN_DIR . 'includes/dkpdf-functions.php';

			// shortcodes
			require_once DKPDF_PLUGIN_DIR . 'includes/class-dkpdf-template-loader.php';
			require_once DKPDF_PLUGIN_DIR . 'includes/dkpdf-shortcodes.php';
		 */
	}

	public function dkpdf_load_textdomain() {

		load_plugin_textdomain( 'dkpdf', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
}
