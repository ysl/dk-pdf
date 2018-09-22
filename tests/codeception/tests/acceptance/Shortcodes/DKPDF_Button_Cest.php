<?php

namespace Acceptance;

use DKPDFTester;

class Post_MetaBox_Cest {

	public function _before( DKPDFTester $I ) {

		$I->amOnPage('/wp-login.php');
		$I->wait(1);
		$I->fillField(['name' => 'log'], 'admin');
		$I->fillField(['name' => 'pwd'], 'password');
		$I->click('#wp-submit');

		// check post in types to apply
		$I->amOnPage( '/wp-admin/admin.php?page=dkpdf_settings' );
		$I->checkOption('#pdfbutton_post_types_post');
		$I->click('Save Settings');
	}

	public function _after( DKPDFTester $I ) {}

	/**
	 * Test [dkpdf-button]
	 */
	public function dkpdf_button( DKPDFTester $I ) {

		// create a post
		$I->amOnPage( '/wp-admin/post-new.php' );
		$I->fillField( '#title', 'DKPDF Button Shortcode' );
		$I->click('#content-html');
		$I->fillField( '#content', '[dkpdf-button]' );
		$I->wait(1);
		$I->executeJS( 'window.scrollTo(0,0);' );
		$I->click( '#publish' );

		// check pdf button in frontend
		$I->amOnPage( '/' );
		$I->see( 'DKPDF Button Shortcode' );
		$I->click( 'DKPDF Button Shortcode' );
		$I->seeElement( '.dkpdf-button-container' );
	}
}
