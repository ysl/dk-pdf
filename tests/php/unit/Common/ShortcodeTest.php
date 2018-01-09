<?php # -*- coding: utf-8 -*-
namespace Dinamiko\DKPDF\Tests\Common;

use Brain\Monkey;
use Dinamiko\DKPDF\Common\TemplateLoader;
use Dinamiko\DKPDF\Common\Shortcode;

class Shortcode_Test extends \PHPUnit\Framework\TestCase {

	public function test_pagebreak() {

		\Brain\Monkey\Functions\when( 'get_query_var' )
			->justReturn( 'pdf' );

		$template = $this->createMock( TemplateLoader::class );
		$shortcode  = new Shortcode( $template );

		$this->assertEquals( '<pagebreak />', $shortcode->pagebreak() );
	}

	public function test_pagebreak_no_pdf() {

		\Brain\Monkey\Functions\when( 'get_query_var' )
			->justReturn( null );

		$template = $this->createMock( TemplateLoader::class );
		$shortcode  = new Shortcode( $template );

		$this->assertEquals( '', $shortcode->pagebreak() );
	}

	protected function setUp() {

		parent::setUp();
		Monkey\setUp();
	}

	protected function tearDown() {

		Monkey\tearDown();
		parent::tearDown();
	}
}
