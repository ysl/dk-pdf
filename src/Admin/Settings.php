<?php

namespace Dinamiko\DKPDF\Admin;

class Settings
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Fields
     */
    private $fields;

    /**
     * @var array
     */
    private $settings;

    /**
     * @param Fields $fields
     * @param Config $config
     */
    public function __construct(Fields $fields, Config $config)
    {
        $this->fields = $fields;
        $this->config = $config;

        // Initialise settings
        add_action('init', [$this, 'initSettings'], 11);

        // Register plugin settings
        add_action('admin_init', [$this, 'registerSettings']);

        // Add settings page to menu
        add_action('admin_menu', [$this, 'addMenuItem']);

        // Add settings link to plugins page
        add_filter(
            'plugin_action_links_' . plugin_basename(DKPDF_PLUGIN_FILE),
            [$this, 'addSettingsLink']
        );
    }

    /**
     * Initialise settings.
     *
     * @wp-hook init
     * @return void
     */
    public function initSettings()
    {
        $this->settings = apply_filters('dkpdf_settings_fields', $this->config->settings());
    }

    /**
     * Adds DK PDF admin menu.
     *
     * @wp-hook admin_menu
     * @return void
     */
    public function addMenuItem()
    {
        $page = add_menu_page(
            'DK PDF',
            'DK PDF',
            'manage_options',
            'dkpdf_settings',
            new Page\Settings($this->settings)
        );

        add_submenu_page(
            'dkpdf_settings',
            'Addons',
            'Addons',
            'manage_options',
            'dkpdf-addons',
            new Page\Addons()
        );

        add_submenu_page(
            'dkpdf_settings',
            'Support',
            'Support',
            'manage_options',
            'dkpdf-support',
            new Page\Support()
        );

        // settings assets
        add_action('admin_print_styles-' . $page, [$this, 'settingsAssets']);
    }

    /**
     * Load settings JS & CSS
     *
     * @return void
     */
    public function settingsAssets()
    {
        wp_enqueue_media();

        wp_register_script(
            'dkpdf-settings-js',
            plugins_url('dk-pdf/assets/js/settings-admin.js'),
            ['jquery'],
            DKPDF_VERSION
        );
        wp_enqueue_script('dkpdf-settings-js');
    }

    /**
     * Add settings link to plugin list table
     *
     * @param  array $links Existing links
     * @return array Modified links
     */
    public function addSettingsLink($links)
    {
        $settingsLink = '<a href="admin.php?page=' . 'dkpdf_settings">'
            . __('Settings', 'dkpdf') . '</a>';
        array_push($links, $settingsLink);

        return $links;
    }

    /**
     * Register plugin settings
     *
     * @wp-hook admin_init
     * @return void
     */
    public function registerSettings()
    {
        if (is_array($this->settings)) {
            // Check posted/selected tab
            $currentSection = '';
            if (isset($_POST['tab']) && $_POST['tab']) {
                $currentSection = $_POST['tab'];
            } else {
                if (isset($_GET['tab']) && $_GET['tab']) {
                    $currentSection = $_GET['tab'];
                }
            }

            foreach ($this->settings as $section => $data) {
                if ($currentSection && $currentSection !== $section) {
                    continue;
                }

                // Add section to page
                add_settings_section(
                    $section,
                    $data['title'],
                    [$this, 'settingsSection'],
                    'dkpdf_settings'
                );

                foreach ($data['fields'] as $field) {
                    // Validation callback for field
                    $validation = '';
                    if (isset($field['callback'])) {
                        $validation = $field['callback'];
                    }

                    // Register field
                    $optionName = 'dkpdf_' . $field['id'];
                    register_setting('dkpdf_settings', $optionName, $validation);

                    // Add field to page
                    add_settings_field(
                        $field['id'],
                        $field['label'],
                        [$this->fields, 'displayField'],
                        'dkpdf_settings',
                        $section,
                        ['field' => $field, 'prefix' => 'dkpdf_']
                    );
                }

                if (!$currentSection) {
                    break;
                }
            }
        }
    }

    public function settingsSection($section)
    {
        $html = '<p> ' . $this->settings[$section['id']]['description'] . '</p>' . "\n";
        echo wp_kses_post($html);
    }
}
