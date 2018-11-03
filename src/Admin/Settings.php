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

        add_action('init', [$this, 'initSettings'], 11);
        add_action('admin_init', [$this, 'registerSettings']);
        add_action('admin_menu', [$this, 'createAdminMenu']);
        add_filter(
            'plugin_action_links_' . plugin_basename(DKPDF_PLUGIN_FILE),
            [$this, 'addSettingsLink']
        );
    }

    /**
     * Initialise settings.
     * @wp-hook init
     */
    public function initSettings()
    {
        $this->settings = apply_filters('dkpdf_settings_fields', $this->config->settings());
    }

    /**
     * Adds DK PDF admin menu.
     * @wp-hook admin_menu
     */
    public function createAdminMenu()
    {
        add_menu_page(
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
    }

    /**
     * Add settings link to plugin list table.
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
     * Register plugin settings.
     * @wp-hook admin_init
     */
    public function registerSettings()
    {
        $currentSection = '';
        if (isset($_POST['tab']) && $_POST['tab']) {
            $currentSection = filter_var($_POST['tab'], FILTER_SANITIZE_STRING);
        } elseif (isset($_GET['tab']) && $_GET['tab']) {
            $currentSection = filter_var($_GET['tab'], FILTER_SANITIZE_STRING);
        }

        foreach ($this->settings as $section => $data) {
            if ($currentSection && $currentSection !== $section) {
                continue;
            }

            add_settings_section(
                $section,
                $data['title'],
                [$this, 'settingsSection'],
                'dkpdf_settings'
            );

            $this->registerFields($data, $section);

            if (!$currentSection) {
                break;
            }
        }
    }

    /**
     * Display section description.
     * @param array $section
     */
    public function settingsSection($section)
    {
        $html = '<p> ' . $this->settings[$section['id']]['description'] . '</p>' . "\n";
        echo wp_kses_post($html);
    }

    /**
     * @param array $data
     * @param string $section
     */
    private function registerFields($data, $section)
    {
        foreach ($data['fields'] as $field) {
            $optionName = 'dkpdf_' . $field['id'];
            register_setting('dkpdf_settings', $optionName);

            add_settings_field(
                $field['id'],
                $field['label'],
                [$this->fields, 'displayField'],
                'dkpdf_settings',
                $section,
                ['field' => $field, 'prefix' => 'dkpdf_']
            );
        }
    }
}
