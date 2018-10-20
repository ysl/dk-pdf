<?php # -*- coding: utf-8 -*-
namespace Dinamiko\DKPDF\Tests;

use Dinamiko\DKPDF\TemplateLoader;
use Dinamiko\DKPDF\Shortcode;
use Dinamiko\DKPDF\Tests\Unit\TestCase;

class Shortcode_Test extends TestCase
{

    public function test_pagebreak()
    {

        \Brain\Monkey\Functions\when('get_query_var')
            ->justReturn('pdf');

        $template = $this->createMock(TemplateLoader::class);
        $shortcode = new Shortcode($template);

        $this->assertEquals('<pagebreak />', $shortcode->pagebreak());
    }

    public function test_pagebreak_no_pdf()
    {

        \Brain\Monkey\Functions\when('get_query_var')
            ->justReturn(null);

        $template = $this->createMock(TemplateLoader::class);
        $shortcode = new Shortcode($template);

        $this->assertEquals('', $shortcode->pagebreak());
    }
}
