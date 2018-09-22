<?php

namespace Acceptance;

use DKPDFTester;

class DKPDF_Button_Cest {

	public function _before( DKPDFTester $I ) {

		$I->amOnPage('/wp-login.php');
		$I->wait(1);
		$I->fillField(['name' => 'log'], 'admin');
		$I->fillField(['name' => 'pwd'], 'password');
		$I->click('#wp-submit');
	}

	public function _after( DKPDFTester $I ) {}

	/**
	 * Test Post Metabox
	 */
	public function post_metabox( DKPDFTester $I ) {

		// check post in types to apply
		$I->amOnPage( '/wp-admin/admin.php?page=dkpdf_settings' );
		$I->see( 'PDF Button', 'h2' );
		$I->checkOption('#pdfbutton_post_types_post');
		$I->click('Save Settings');
		$I->see('Settings saved.');

		// see metabox with checkbox not checked
		$I->amOnPage( '/wp-admin/post-new.php' );
		$I->seeElement('#_hide_pdfbutton');
		$I->dontSeeCheckboxIsChecked('#_hide_pdfbutton');

		// publish post
		$I->fillField('#title', 'Post MetaBox');
		$I->click('#content-html');
		$I->fillField('#content', '[dkpdf-button]');
		$I->wait(1);
		$I->executeJS('window.scrollTo(0,0);');
		$I->click('#publish');

		// go to post frontend, see PDF button
		$I->amOnPage( '/' );
		$I->see('Post MetaBox');
		$I->click('Post MetaBox');
		$I->seeElement('.dkpdf-button-container');

		// check disable pdf button
		$I->amOnPage( '/wp-admin/edit.php' );
		$I->click('Post MetaBox');
		$I->wait( 1 );
		$I->checkOption('#_hide_pdfbutton');
		$I->executeJS('window.scrollTo(0,0);');
		$I->wait( 1 );
		$I->click('#publish');

		// go to post frontend, dont see PDF button
		$I->amOnPage( '/' );
		$I->click('Post MetaBox');
		$I->dontSeeElement('.dkpdf-button-container');
	}
}
