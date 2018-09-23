<?php

namespace Dinamiko\DKPDF;

use Dinamiko\DKPDF\Admin\Fields;
use Dinamiko\DKPDF\Admin\Settings;
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

        (new AssetLoader())->init();

        $template = new TemplateLoader();
        (new Shortcode($template))->init();

        (new Display($template))->init();
    }
}
