<?php

namespace Acceptance;

use DKPDFTester;

class Post_Columns_Cest {

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
	 * Test [dkpdf-columns] basic usage
	 * Needs manual inspection.
	 */
	public function dkpdf_columns_basic( DKPDFTester $I ) {

		// Create a post
		$I->amOnPage( '/wp-admin/post-new.php' );
		$I->fillField( '#title', 'DKPDF Columns Shortcode' );

		// Fill post content
		$I->click('#content-html');
		$I->fillField( '#content', '[dkpdf-columns]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vitae ligula et purus rhoncus viverra. Mauris ullamcorper enim at ornare consequat. Proin libero felis, condimentum vel lorem quis, rhoncus facilisis nulla. Integer non eros interdum, iaculis arcu nec, condimentum quam. Nulla consequat turpis id ante eleifend, interdum pellentesque nulla sodales. Nulla facilisi. Curabitur ac volutpat lacus. Morbi egestas molestie elit at iaculis. Donec fermentum id metus sed tincidunt. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nunc pulvinar pellentesque urna, vitae pharetra nibh viverra fermentum. Integer a orci malesuada, sollicitudin justo ac, cursus urna. Aenean sit amet accumsan quam. Nullam porta tortor id mauris sagittis porta. Nullam fermentum mattis fermentum. Aliquam feugiat libero gravida convallis bibendum. Phasellus ut sodales dui, eu fermentum felis. Proin in est odio. Sed lobortis tellus in neque venenatis elementum. Praesent facilisis justo quis quam venenatis dictum. Donec et quam a dui viverra pellentesque quis id dui.[/dkpdf-columns]' );
		$I->executeJS( 'window.scrollTo(0,0);' );
		$I->click( '#publish' );
	}

	/**
	 * Test [dkpdf-columnbreak]
	 * Needs manual inspection.
	 */
	public function dkpdf_columns_columnbreak( DKPDFTester $I ) {

		// Create a post
		$I->amOnPage( '/wp-admin/post-new.php' );
		$I->fillField( '#title', 'DKPDF Column Break Shortcode' );

		// Fill post content
		$I->click('#content-html');
		$I->fillField( '#content', '[dkpdf-columns]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin convallis quam sit amet erat egestas mattis. Vestibulum eros dui, bibendum non ante non, placerat placerat nibh.<ul><li>Pellentesque laoreet arcu lorem</li><li>At sagittis leo suscipit eu</li><li>Nam egestas lorem ornare</li><li>Class aptent taciti sociosqu</li><li>Ad litora torquent per conubia</li><li>Nostra, per inceptos himenaeos.</li></ul>[dkpdf-columnbreak]Vestibulum risus quis, efficitur libero. Morbi ac mattis odio, ut volutpat est. Nulla faucibus est vel turpis lobortis volutpat. Integer tincidunt feugiat tortor ut eleifend. Cras vitae enim elementum, sagittis lorem dignissim, pharetra nulla. Vivamus placerat dignissim metus sit amet vulputate. Vestibulum pellentesque in dolor non luctus.[/dkpdf-columns][dkpdf-columns columns="3" equal-columns="true" gap="20"]Etiam sed euismod neque. Cras tristique massa ante, a tincidunt ipsum sagittis vel. Fusce tristique facilisis neque non semper. Vivamus pharetra risus vitae velit ultricies auctor. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Aliquam condimentum felis arcu, eget mollis ipsum pharetra nec. Aliquam justo sapien, fringilla a erat et, luctus elementum nibh. Curabitur tincidunt gravida eleifend. Vivamus ornare auctor lacus, in eleifend ex gravida ac. Quisque sodales dui odio, nec venenatis neque ultrices eget. Phasellus et sodales lectus. Sed quis cursus augue. Maecenas ornare eros dolor, interdum laoreet massa tristique in.[/dkpdf-columns]' );
		$I->executeJS( 'window.scrollTo(0,0);' );
		$I->click( '#publish' );
	}
}
