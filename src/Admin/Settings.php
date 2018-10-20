<?php

namespace Dinamiko\DKPDF\Admin;

use Dinamiko\DKPDF\Utils;

class Settings
{

    /**
     * @var array
     */
    private $settings;

    /**
     * @var Fields
     */
    private $fields;

    /**
     * Constructor. Sets up the properties.
     *
     * @param Fields $admin
     */
    public function __construct(Fields $fields)
    {
        $this->fields = $fields;

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
     *
     * @return void
     */
    public function initSettings()
    {
        $this->settings = $this->settingsFields();
    }

    /**
     * Adds DK PDF admin menu.
     *
     * @wp-hook admin_menu
     * @return void
     */
    public function addMenuItem()
    {
        // main menu
        $page = add_menu_page(
            'DK PDF',
            'DK PDF',
            'manage_options',
            'dkpdf_settings',
            [$this, 'settingsPage']
        );

        // Addons submenu
        add_submenu_page(
            'dkpdf_settings',
            'Addons',
            'Addons',
            'manage_options',
            'dkpdf-addons',
            [$this, 'addonsScreen']
        );

        // support
        add_submenu_page(
            'dkpdf_settings',
            'Support',
            'Support',
            'manage_options',
            'dkpdf-support',
            [$this, 'supportScreen']
        );

        // settings assets
        add_action('admin_print_styles-' . $page, [$this, 'settingsAssets']);
    }

    /**
     * Render Support screen.
     *
     * @return void
     */
    public function supportScreen()
    {
        ?>
        <div class="wrap">
            <h2 style="float:left;width:100%;">DK PDF Support</h2>

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

        <?php do_action('dkpdf_after_support'); ?>

    <?php }

    /**
     * Render Addons screen.
     *
     * @return void
     */
    public function addonsScreen()
    {
        ?>
        <div class="wrap">
            <h2>DK PDF Addons</h2>

            <div class="dkpdf-item">
                <h3>DK PDF Generator</h3>
                <p>Allows creating PDF documents with your selected WordPress content, also allows
                    adding a Cover and a Table of contents.</p>
                <p><a href="http://codecanyon.net/item/dk-pdf-generator/13530581"
                      target="_blank">Go to DK PDF Generator</a></p>
            </div>
        </div>

    <?php }

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
     * Build settings fields.
     *
     * @return array Fields to be displayed on settings page.
     */
    private function settingsFields()
    {
        // pdf button settings
        $settings['pdfbtn'] = [
            'title' => __('PDF Button', 'dkpdf'),
            'description' => '',
            'fields' => [
                [
                    'id' => 'pdfbutton_text',
                    'label' => __('Button text', 'dkpdf'),
                    'description' => '',
                    'type' => 'text',
                    'default' => 'PDF Button',
                    'placeholder' => '',
                ],
                [
                    'id' => 'pdfbutton_post_types',
                    'label' => __('Post types to apply:', 'dkpdf'),
                    'description' => '',
                    'type' => 'checkbox_multi',
                    'options' => Utils::postTypes(),
                    'default' => [],
                ],
                [
                    'id' => 'pdfbutton_action',
                    'label' => __('Action', 'dkpdf'),
                    'description' => '',
                    'type' => 'radio',
                    'options' => [
                        'open' => 'Open PDF in new Window',
                        'download' => 'Download PDF directly',
                    ],
                    'default' => 'open',
                ],
                [
                    'id' => 'pdfbutton_position',
                    'label' => __('Position', 'dkpdf'),
                    'description' => '',
                    'type' => 'radio',
                    'options' => [
                        'shortcode' => 'Use shortcode',
                        'before' => 'Before content',
                        'after' => 'After content',
                    ],
                    'default' => 'before',
                ],
                [
                    'id' => 'pdfbutton_align',
                    'label' => __('Align', 'dkpdf'),
                    'description' => '',
                    'type' => 'radio',
                    'options' => ['left' => 'Left', 'center' => 'Center', 'right' => 'Right'],
                    'default' => 'right',
                ],
            ],
        ];

        // pdf setup
        $settings['dkpdf_setup'] = [
            'title' => __('PDF Setup', 'dkpdf'),
            'description' => '',
            'fields' => [
                [
                    'id' => 'page_orientation',
                    'label' => __('Page orientation', 'dkpdf'),
                    'description' => '',
                    'type' => 'radio',
                    'options' => ['vertical' => 'Vertical', 'horizontal' => 'Horizontal'],
                    'default' => 'vertical',
                ],
                [
                    'id' => 'font_size',
                    'label' => __('Font size', 'dkpdf'),
                    'description' => 'In points (pt)',
                    'type' => 'number',
                    'default' => '12',
                    'placeholder' => '12',
                ],
                [
                    'id' => 'margin_left',
                    'label' => __('Margin left', 'dkpdf'),
                    'description' => 'In points (pt)',
                    'type' => 'number',
                    'default' => '15',
                    'placeholder' => '15',
                ],
                [
                    'id' => 'margin_right',
                    'label' => __('Margin right', 'dkpdf'),
                    'description' => 'In points (pt)',
                    'type' => 'number',
                    'default' => '15',
                    'placeholder' => '15',
                ],
                [
                    'id' => 'margin_top',
                    'label' => __('Margin top', 'dkpdf'),
                    'description' => 'In points (pt)',
                    'type' => 'number',
                    'default' => '50',
                    'placeholder' => '50',
                ],
                [
                    'id' => 'margin_bottom',
                    'label' => __('Margin bottom', 'dkpdf'),
                    'description' => 'In points (pt)',
                    'type' => 'number',
                    'default' => '30',
                    'placeholder' => '30',
                ],
                [
                    'id' => 'margin_header',
                    'label' => __('Margin header', 'dkpdf'),
                    'description' => 'In points (pt)',
                    'type' => 'number',
                    'default' => '15',
                    'placeholder' => '15',
                ],
                [
                    'id' => 'enable_protection',
                    'label' => __('Enable PDF protection', 'dkpdf'),
                    'description' => __(
                        'Encrypts PDF file and respects permissions given below',
                        'dkpdf'
                    ),
                    'type' => 'checkbox',
                    'default' => '',
                ],
                [
                    'id' => 'grant_permissions',
                    'label' => __('Protected PDF permissions', 'dkpdf'),
                    'description' => '',
                    'type' => 'checkbox_multi',
                    'options' => [
                        'copy' => 'Copy',
                        'print' => 'Print',
                        'print-highres' => 'Print Highres',
                        'modify' => 'Modify',
                        'annot-forms' => 'Annot Forms',
                        'fill-forms' => 'Fill Forms',
                        'extract' => 'Extract',
                        'assemble' => 'Assemble',
                    ],
                    'default' => [],
                ],
                [
                    'id' => 'keep_columns',
                    'label' => __('Keep columns', 'dkpdf'),
                    'description' => __(
                        'Columns will be written successively (dkpdf-columns shortcode). i.e. there will be no balancing of the length of columns.',
                        'dkpdf'
                    ),
                    'type' => 'checkbox',
                    'default' => '',
                ],
            ],
        ];

        // header & footer settings
        $settings['pdf_header_footer'] = [
            'title' => __('PDF Header & Footer', 'dkpdf'),
            'description' => '',
            'fields' => [
                [
                    'id' => 'pdf_header_image',
                    'label' => __('Header logo', 'dkpdf'),
                    'description' => '',
                    'type' => 'image',
                    'default' => '',
                    'placeholder' => '',
                ],
                [
                    'id' => 'pdf_header_show_title',
                    'label' => __('Header show title', 'dkpdf'),
                    'description' => '',
                    'type' => 'checkbox',
                    'default' => '',
                ],
                [
                    'id' => 'pdf_header_show_pagination',
                    'label' => __('Header show pagination', 'dkpdf'),
                    'description' => '',
                    'type' => 'checkbox',
                    'default' => '',
                ],
                [
                    'id' => 'pdf_footer_text',
                    'label' => __('Footer text', 'dkpdf'),
                    'description' => __('HTML tags: a, br, em, strong, hr, p, h1 to h4', 'dkpdf'),
                    'type' => 'textarea',
                    'default' => '',
                    'placeholder' => '',
                ],
                [
                    'id' => 'pdf_footer_show_title',
                    'label' => __('Footer show title', 'dkpdf'),
                    'description' => '',
                    'type' => 'checkbox',
                    'default' => '',
                ],
                [
                    'id' => 'pdf_footer_show_pagination',
                    'label' => __('Footer show pagination', 'dkpdf'),
                    'description' => '',
                    'type' => 'checkbox',
                    'default' => '',
                ],
            ],
        ];

        // style settings
        $settings['pdf_css'] = [
            'title' => __('PDF CSS', 'dkpdf'),
            'description' => '',
            'fields' => [
                [
                    'id' => 'pdf_custom_css',
                    'label' => __('PDF Custom CSS', 'dkpdf'),
                    'description' => '',
                    'type' => 'textarea_code',
                    'default' => '',
                    'placeholder' => '',
                ],
                [
                    'id' => 'print_wp_head',
                    'label' => __('Use current theme\'s CSS', 'dkpdf'),
                    'description' => __(
                        'Includes the stylesheet from current theme, but is overridden by PDF Custom CSS and plugins adding its own stylesheets.',
                        'dkpdf'
                    ),
                    'type' => 'checkbox',
                    'default' => '',
                ],
            ],
        ];

        $settings = apply_filters('dkpdf_settingsFields', $settings);

        return $settings;
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

    /**
     * Load settings page content
     *
     * @wp-hook add_menu_page
     *
     * @return void
     */
    public function settingsPage()
    {
        if (isset($_GET['settings-updated'])) { ?>
            <div id="message" class="updated">
                <p><?php esc_attr_e('Settings saved.', 'dkpdf'); ?></p>
            </div>
        <?php }

        // Build page HTML
        $html = '<div class="wrap" id="' . 'dkpdf_settings">' . "\n";
        $html .= '<h2>' . __('DK PDF Settings', 'dkpdf') . '</h2>' . "\n";

        $tab = '';
        if (isset($_GET['tab']) && $_GET['tab']) {
            $tab .= $_GET['tab'];
        }

        // Show page tabs
        if (is_array($this->settings) && 1 < count($this->settings)) {
            $html .= '<h2 class="nav-tab-wrapper">' . "\n";
            $count = 0;

            foreach ($this->settings as $section => $data) {
                // Set tab class
                $class = 'nav-tab';
                if (!isset($_GET['tab'])) {
                    if (0 == $count) {
                        $class .= ' nav-tab-active';
                    }
                } else {
                    if (isset($_GET['tab']) && $section == $_GET['tab']) {
                        $class .= ' nav-tab-active';
                    }
                }

                // Set tab link
                $tab_link = add_query_arg(array('tab' => $section));
                if (isset($_GET['settings-updated'])) {
                    $tab_link = remove_query_arg('settings-updated', $tab_link);
                }

                // Output tab
                $html .= '<a href="' . $tab_link . '" class="' . esc_attr($class) . '">' . esc_html($data['title']) . '</a>' . "\n";
                ++$count;
            }

            $html .= '</h2>' . "\n";
        }

        $html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";

        // Get settings fields
        ob_start();
        settings_fields('dkpdf_settings');
        do_settings_sections('dkpdf_settings');
        $html .= ob_get_clean();

        $html .= '<p class="submit">' . "\n";
        $html .= '<input type="hidden" name="tab" value="' . esc_attr($tab) . '" />' . "\n";
        $html .= '<input name="Submit" type="submit" class="button-primary" value="'
            . esc_attr(__('Save Settings', 'dkpdf')) . '" />' . "\n";
        $html .= '</p>' . "\n";
        $html .= '</form>' . "\n";

        $html .= '</div>' . "\n";

        echo $html;
    }
}
