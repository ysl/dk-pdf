<?php

namespace Acceptance;

use DKPDFTester;

class Post_Pagebreak_Cest {

	public function _before( DKPDFTester $I ) {

		$I->amOnPage('/wp-login.php');
		$I->wait(1);
		$I->fillField(['name' => 'log'], 'admin');
		$I->fillField(['name' => 'pwd'], 'password');
		$I->click('#wp-submit');

		$I->amOnPage( '/wp-admin/admin.php?page=dkpdf_settings' );

		// check post in types to apply
		$I->checkOption('#pdfbutton_post_types_post');
		$I->click('Save Settings');
	}

	public function _after( DKPDFTester $I ) {}

	/**
	 * Test [dkpdf-pagebreak]
	 * Needs manual inspection.
	 */
	public function dkpdf_pagebreak_basic( DKPDFTester $I ) {

		// Create a post
		$I->amOnPage( '/wp-admin/post-new.php' );
		$I->fillField( '#title', 'DKPDF Pagebreak Shortcode' );

		// Fill post content
		$I->click('#content-html');
		$I->fillField( '#content', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.[dkpdf-pagebreak]Donec vitae ligula et purus rhoncus viverra.' );
		$I->executeJS( 'window.scrollTo(0,0);' );
		$I->click( '#publish' );
	}
}
