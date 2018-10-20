<?php # -*- coding: utf-8 -*-
namespace Dinamiko\DKPDF\Tests;

use Dinamiko\DKPDF\Tests\Unit\TestCase;
use Dinamiko\DKPDF\Utils;

class Utils_Test extends TestCase {

	public function test_get_post_types() {

		\Brain\Monkey\Functions\when( 'get_post_types' )
			->justReturn( array() );

		$this->assertInternalType( 'array', Utils::postTypes() );
	}
}
