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

/*
\Brain\Monkey\Functions\when('get_post')
->justReturn((object)array(
'ID' => 12
));
*/

		global $post;
		$post = new class {
			public $ID;
		};
		$post->ID = 1;

		\Brain\Monkey\Functions\when( 'get_post_type' )
			->justReturn( 'post' );

		\Brain\Monkey\Functions\when( 'get_option' )
			->justReturn( array('post') );

		\Brain\Monkey\Functions\when( 'is_archive' )
			->justReturn( false );
		\Brain\Monkey\Functions\when( 'is_front_page' )
			->justReturn( false );
		\Brain\Monkey\Functions\when( 'is_home' )
			->justReturn( false );


		/*
		// TODO Fix test.
		$this->markTestIncomplete(
			'Error: Call to undefined function Dinamiko\DKPDF\PDF\get_post_type()'
		);
		*/

		$template = $this->createMock( TemplateLoader::class );
		$display = new Display( $template );

		$this->assertFalse( $display->remove_button() );
	}
}
