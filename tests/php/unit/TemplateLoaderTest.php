<?php

namespace Dinamiko\DKPDF\Tests\Unit;

use Dinamiko\DKPDF\TemplateLoader;

class TemplateLoaderTest extends TestCase
{

    public function testTemplatePart()
    {
        $templateLoader = new TemplateLoader();

        \Brain\Monkey\Functions\when('get_template_directory')
            ->justReturn('');

        \Brain\Monkey\Functions\when('is_child_theme')
            ->justReturn(false);

        $templateLoader->templatePart('', '');
    }
}
