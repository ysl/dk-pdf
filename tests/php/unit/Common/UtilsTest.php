<?php # -*- coding: utf-8 -*-
namespace Dinamiko\DKPDF\Tests\Common;

use Brain\Monkey;
use Dinamiko\DKPDF\Common\Utils;

class Utils_Test extends \PHPUnit\Framework\TestCase {

	public function test_get_post_types() {

		\Brain\Monkey\Functions\when( 'get_post_types' )
			->justReturn( array() );

		$this->assertInternalType( 'array', Utils::get_post_types() );
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
