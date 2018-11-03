<?php

namespace Dinamiko\DKPDF\Admin\Page;

class Settings
{
    /**
     * @var array
     */
    private $settings;

    /**
     * @param array $settings
     */
    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    public function __invoke()
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
