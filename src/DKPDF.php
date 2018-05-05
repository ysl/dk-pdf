<?php # -*- coding: utf-8 -*-
declare(strict_types=1);

namespace Dinamiko\DKPDF;

use Dinamiko\DKPDF\Admin\Fields;
use Dinamiko\DKPDF\Admin\Settings;
use Dinamiko\DKPDF\MetaBox;
use Dinamiko\DKPDF\AssetManager;
use Dinamiko\DKPDF\TemplateLoader;
use Dinamiko\DKPDF\Shortcode;
use Dinamiko\DKPDF\PDF\Display;

class DKPDF
{

    public function init()
    {

        if (is_admin()) {

            $fields = new Fields();
            (new Settings($fields));

            (new MetaBox())->init();
        }

        (new AssetManager())->init();

        $template = new TemplateLoader();
        (new Shortcode($template))->init();

        (new Display($template))->init();
    }
}
