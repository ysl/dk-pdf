<?php

namespace Acceptance;

use DKPDFTester;

class PDF_Setup_Cest {

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

		$I->amOnPage( '/wp-admin/admin.php?page=dkpdf_settings&tab=dkpdf_setup' );
		$I->see( 'PDF Setup', 'h2' );

		$I->seeCheckboxIsChecked('#page_orientation_vertical');
		$I->seeInField('#font_size', '12');
		$I->seeInField('#margin_left', '15');
		$I->seeInField('#margin_right', '15');
		$I->seeInField('#margin_top', '50');
		$I->seeInField('#margin_bottom', '30');
		$I->seeInField('#margin_header', '15');
		$I->dontSeeCheckboxIsChecked('#enable_protection');
		$I->dontSeeCheckboxIsChecked('#grant_permissions_copy');
		$I->dontSeeCheckboxIsChecked('#grant_permissions_print');
		$I->dontSeeCheckboxIsChecked('#grant_permissions_print-highres');
		$I->dontSeeCheckboxIsChecked('#grant_permissions_modify');
		$I->dontSeeCheckboxIsChecked('#grant_permissions_annot-forms');
		$I->dontSeeCheckboxIsChecked('#grant_permissions_fill-forms');
		$I->dontSeeCheckboxIsChecked('#grant_permissions_extract');
		$I->dontSeeCheckboxIsChecked('#grant_permissions_assemble');
		$I->dontSeeCheckboxIsChecked('#keep_columns');
	}

	/**
	 * Test change setting values
	 */
	public function change_setting_values( DKPDFTester $I ) {

		$I->amOnPage( '/wp-admin/admin.php?page=dkpdf_settings&tab=dkpdf_setup' );
		$I->see( 'PDF Setup', 'h2' );

		$I->checkOption('#page_orientation_horizontal');
		$I->fillField('#font_size', '1');
		$I->fillField('#margin_left', '1');
		$I->fillField('#margin_right', '1');
		$I->fillField('#margin_top', '1');
		$I->fillField('#margin_bottom', '1');
		$I->fillField('#margin_header', '1');
		$I->checkOption('#enable_protection');
		$I->checkOption('#grant_permissions_copy');
		$I->checkOption('#grant_permissions_print');
		$I->checkOption('#grant_permissions_print-highres');
		$I->checkOption('#grant_permissions_modify');
		$I->checkOption('#grant_permissions_annot-forms');
		$I->checkOption('#grant_permissions_fill-forms');
		$I->checkOption('#grant_permissions_extract');
		$I->checkOption('#grant_permissions_assemble');
		$I->checkOption('#keep_columns');

		$I->click('Save Settings');
		$I->see('Settings saved.');

		$I->seeCheckboxIsChecked('#page_orientation_horizontal');
		$I->seeInField('#font_size', '1');
		$I->seeInField('#margin_left', '1');
		$I->seeInField('#margin_right', '1');
		$I->seeInField('#margin_top', '1');
		$I->seeInField('#margin_bottom', '1');
		$I->seeInField('#margin_header', '1');
		$I->seeCheckboxIsChecked('#enable_protection');
		$I->seeCheckboxIsChecked('#grant_permissions_copy');
		$I->seeCheckboxIsChecked('#grant_permissions_print');
		$I->seeCheckboxIsChecked('#grant_permissions_print-highres');
		$I->seeCheckboxIsChecked('#grant_permissions_modify');
		$I->seeCheckboxIsChecked('#grant_permissions_annot-forms');
		$I->seeCheckboxIsChecked('#grant_permissions_fill-forms');
		$I->seeCheckboxIsChecked('#grant_permissions_extract');
		$I->seeCheckboxIsChecked('#grant_permissions_assemble');
		$I->seeCheckboxIsChecked('#keep_columns');
	}
}
