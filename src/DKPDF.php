<?php # -*- coding: utf-8 -*-

namespace Dinamiko\DKPDF;

use Dinamiko\DKPDF\Admin\AdminAPI;
use Dinamiko\DKPDF\Admin\Settings;
use Dinamiko\DKPDF\Common\AssetManager;
use Dinamiko\DKPDF\Common\MetaBox;

class DKPDF {

	public function init() {

		if ( is_admin() ) {

			$admin = new AdminAPI();
			( new Settings( $admin ) );

			( new MetaBox() )->init();
		}

		( new AssetManager() )->init();

		/*
			// functions
			require_once DKPDF_PLUGIN_DIR . 'includes/dkpdf-functions.php';

			// shortcodes
			require_once DKPDF_PLUGIN_DIR . 'includes/class-dkpdf-template-loader.php';
			require_once DKPDF_PLUGIN_DIR . 'includes/dkpdf-shortcodes.php';
		 */
	}
}
