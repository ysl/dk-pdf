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

    // phpcs:disable Generic.Metrics.NestingLevel.TooHigh
    // phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
    public function __invoke()
    {
        // phpcs:disable WordPress.CSRF.NonceVerification.NoNonceVerification
        // phpcs:disable WordPress.VIP.SuperGlobalInputUsage.AccessDetected
        $settingsUpdated = isset($_GET['settings-updated']);
        if ($settingsUpdated) { ?>
            <div id="message" class="updated">
                <p><?php esc_attr_e('Settings saved.', 'dkpdf'); ?></p>
            </div>
        <?php } ?>

        <div class="wrap" id="dkpdf_settings">
            <h2><?= esc_attr__('DK PDF Settings', 'dkpdf') ?></h2>

            <?php
            // phpcs:disable WordPress.VIP.ValidatedSanitizedInput.MissingUnslash
            // phpcs:disable WordPress.VIP.ValidatedSanitizedInput.InputNotSanitized
            $tab = isset($_GET['tab']) && $_GET['tab']
                ? filter_var($_GET['tab'], FILTER_SANITIZE_STRING)
                : '';
            // phpcs: enable

            if (count($this->settings) > 1) { ?>
                <div class="nav-tab-wrapper">
                    <?php
                    $count = 0;
                    foreach ($this->settings as $section => $data) {
                        $active = !$tab && $count === 0 ? 'nav-tab-active' : '';
                        if ($tab && $section === $tab) {
                            $active = 'nav-tab-active';
                        }

                        $tabLink = add_query_arg(['tab' => $section]);
                        if ($settingsUpdated) {
                            $tabLink = remove_query_arg('settings-updated', $tabLink);
                        } ?>

                        <a href="<?= esc_url($tabLink) ?>" class="nav-tab <?= esc_attr($active) ?>">
                            <?= esc_attr($data['title']) ?>
                        </a>
                        <?php $count++;
                    } ?>
                </div>
            <?php } ?>
        </div>

        <form method="post" action="options.php" enctype="multipart/form-data">
            <?php
            settings_fields('dkpdf_settings');
            do_settings_sections('dkpdf_settings');
            ?>
            <div class="submit">
                <input type="hidden" name="tab" value="<?= esc_attr($tab) ?>"/>
                <input name="Submit" type="submit" class="button-primary"
                       value="<?= esc_attr_e('Save Settings', 'dkpdf') ?>"/>
            </div>
        </form>
    <?php }
}
