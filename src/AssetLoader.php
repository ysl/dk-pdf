<?php

namespace Dinamiko\DKPDF;

class AssetLoader
{

    public function init()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueStyles'], 15);
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts'], 10);
        add_action('admin_enqueue_scripts', [$this, 'adminEnqueueScripts'], 10, 1);
        add_action('admin_enqueue_scripts', [$this, 'adminEnqueueStyles'], 10, 1);
    }

    public function enqueueStyles()
    {
        wp_register_style(
            'font-awesome',
            '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
            [],
            '4.3.0'
        );
        wp_enqueue_style('font-awesome');

        wp_register_style(
            'dkpdf-frontend',
            plugins_url('dk-pdf/assets/css/frontend.css'),
            [],
            DKPDF_VERSION
        );
        wp_enqueue_style('dkpdf-frontend');
    }

    public function enqueueScripts()
    {
        wp_register_script(
            'dkpdf-frontend',
            plugins_url('dk-pdf/assets/js/frontend.js'),
            ['jquery'],
            DKPDF_VERSION,
            true
        );
        wp_enqueue_script('dkpdf-frontend');
    }

    /**
     * @param string $hook
     */
    public function adminEnqueueStyles($hook)
    {
        if (isset($hook) && $hook === 'toplevel_page_dkpdf_settings') {
            wp_register_style(
                'dkpdf-admin',
                plugins_url('dk-pdf/assets/css/admin.css'),
                [],
                DKPDF_VERSION
            );
            wp_enqueue_style('dkpdf-admin');
        }
    }

    /**
     * @param string $hook
     */
    public function adminEnqueueScripts($hook)
    {
        if (isset($hook) && $hook === 'toplevel_page_dkpdf_settings') {
            wp_enqueue_media();

            wp_register_script(
                'dkpdf-settings-admin',
                plugins_url('dk-pdf/assets/js/settings-admin.js'),
                ['jquery'],
                DKPDF_VERSION
            );
            wp_enqueue_script('dkpdf-settings-admin');

            wp_register_script(
                'dkpdf-ace',
                plugins_url('dk-pdf/assets/js/src-min/ace.js'),
                [],
                DKPDF_VERSION
            );
            wp_enqueue_script('dkpdf-ace');

            wp_register_script(
                'dkpdf-admin',
                plugins_url('dk-pdf/assets/js/admin.js'),
                ['jquery'],
                DKPDF_VERSION
            );
            wp_enqueue_script('dkpdf-admin');
        }
    }
}
