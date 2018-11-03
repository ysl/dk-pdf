<?php

namespace Dinamiko\DKPDF\Admin\Page;

class Support
{
    public function __invoke()
    {
        ?>
        <div class="wrap">
            <h2>DK PDF Support</h2>

            <div class="dkpdf-item">
                <h3>Documentation</h3>
                <p>Everything you need to know for getting DK PDF up and running.</p>
                <p><a href="http://wp.dinamiko.com/demos/dkpdf/documentation/" target="_blank">Go to
                        Documentation</a>
                </p>
            </div>

            <div class="dkpdf-item">
                <h3>Support</h3>
                <p>Having trouble? don't worry, create a ticket in the support forum.</p>
                <p><a href="https://wordpress.org/support/plugin/dk-pdf" target="_blank">Go to
                        Support</a></p>
            </div>
        </div>
    <?php }
}
