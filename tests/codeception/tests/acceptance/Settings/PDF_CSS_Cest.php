<?php

namespace Acceptance;

use DKPDFTester;

class PDF_CSS_Cest {

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

		$I->amOnPage( '/wp-admin/admin.php?page=dkpdf_settings&tab=pdf_css' );
		$I->see( 'PDF CSS', 'h2' );

		$I->seeInField('#dkpdf_pdf_custom_css', '');
		$I->dontSeeCheckboxIsChecked('#print_wp_head');
	}

	/**
	 * Test change setting values
	 */
	public function change_setting_values( DKPDFTester $I ) {

		$I->amOnPage( '/wp-admin/admin.php?page=dkpdf_settings&tab=pdf_css' );
		$I->see( 'PDF CSS', 'h2' );

		$I->executeJS('jQuery("#dkpdf_pdf_custom_css").show()');
		$I->wait(1);

		$I->fillField('#dkpdf_pdf_custom_css', 'body {background:red;}');
		$I->checkOption('#print_wp_head');

		$I->click('Save Settings');
		$I->see('Settings saved.');

		$I->executeJS('jQuery("#dkpdf_pdf_custom_css").show()');
		$I->wait(1);

		$I->seeInField('#dkpdf_pdf_custom_css', 'body {background:red;}');
		$I->seeCheckboxIsChecked('#print_wp_head');
	}
}
