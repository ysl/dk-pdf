<?php # -*- coding: utf-8 -*-

namespace Dinamiko\DKPDF\Tests\Display;

use Dinamiko\DKPDF\Common\TemplateLoader;
use Dinamiko\DKPDF\PDF\Display;
use Brain\Monkey;

class Display_Test extends \PHPUnit\Framework\TestCase {

	protected function setUp() {

		parent::setUp();
		Monkey\setUp();
	}

	protected function tearDown() {

		Monkey\tearDown();
		parent::tearDown();
	}

	public function test_remove_button_true() {

		\Brain\Monkey\Functions\when( 'get_query_var' )
			->justReturn( 'pdf' );

		$template = $this->createMock( TemplateLoader::class );
		$display = new Display( $template );

		$this->assertTrue( $display->remove_button() );
	}

	public function test_remove_button_false() {

		\Brain\Monkey\Functions\when( 'get_query_var' )
			->justReturn( null );

		// TODO Fix test.
		$this->markTestIncomplete(
			'Error: Call to undefined function Dinamiko\DKPDF\PDF\get_post_type()'
		);

		$template = $this->createMock( TemplateLoader::class );
		$display = new Display( $template );

		$this->assertFalse( $display->remove_button() );
	}
}
