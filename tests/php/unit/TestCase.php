<?php

namespace Dinamiko\DKPDF\Tests\Unit;

use Brain\Monkey;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function setUp() {

        parent::setUp();
        Monkey\setUp();
    }

    protected function tearDown() {

        Monkey\tearDown();
        parent::tearDown();
    }
}
