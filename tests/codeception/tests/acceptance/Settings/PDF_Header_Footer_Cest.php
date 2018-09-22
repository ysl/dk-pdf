<?php

namespace Acceptance;

use DKPDFTester;

class PDF_Header_Footer_Cest {

	public function _before( DKPDFTester $I ) {

		$I->amOnPage('/wp-login.php');
		$I->wait(1);
		$I->fillField(['name' => 'log'], 'admin');
		$I->fillField(['name' => 'pwd'], 'password');
		$I->click('#wp-submit');
	}

	public function _after( DKPDFTester $I ) {}

	/**
	 * Test default setting values
	 */
	public function default_setting_values( DKPDFTester $I ) {

		$I->amOnPage( '/wp-admin/admin.php?page=dkpdf_settings&tab=pdf_header_footer' );
		$I->see( 'PDF Header & Footer', 'h2' );

		$I->seeElement('#dkpdf_pdf_header_image_button');
		$I->seeElement('#dkpdf_pdf_header_image_delete');
		$I->dontSeeCheckboxIsChecked('#pdf_header_show_title');
		$I->dontSeeCheckboxIsChecked('#pdf_header_show_pagination');
		$I->dontSeeCheckboxIsChecked('#pdf_header_show_pagination');
		$I->seeInField('#pdf_footer_text','');
		$I->dontSeeCheckboxIsChecked('#pdf_footer_show_title');
		$I->dontSeeCheckboxIsChecked('#pdf_footer_show_pagination');
	}

	/**
	 * Test change setting values
	 */
	public function change_setting_values( DKPDFTester $I ) {

		$I->amOnPage( '/wp-admin/admin.php?page=dkpdf_settings&tab=pdf_header_footer' );
		$I->see( 'PDF Header & Footer', 'h2' );

		// TODO test add image to Header logo

		$I->checkOption('#pdf_header_show_title');
		$I->checkOption('#pdf_header_show_pagination');
		$I->checkOption('#pdf_header_show_pagination');
		$I->fillField('#pdf_footer_text','Some text.');
		$I->checkOption('#pdf_footer_show_title');
		$I->checkOption('#pdf_footer_show_pagination');

		$I->click('Save Settings');
		$I->see('Settings saved.');

		$I->seeCheckboxIsChecked('#pdf_header_show_title');
		$I->seeCheckboxIsChecked('#pdf_header_show_pagination');
		$I->seeCheckboxIsChecked('#pdf_header_show_pagination');
		$I->seeInField('#pdf_footer_text','Some text.');
		$I->seeCheckboxIsChecked('#pdf_footer_show_title');
		$I->seeCheckboxIsChecked('#pdf_footer_show_pagination');
	}
}
