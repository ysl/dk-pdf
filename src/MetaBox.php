<?php # -*- coding: utf-8 -*-
declare(strict_types=1);

namespace Dinamiko\DKPDF;

class MetaBox
{

    public function init()
    {
        add_action('add_meta_boxes', [$this, 'metaboxSetup']);
        add_action('save_post', [$this, 'metaboxSave']);
    }

    /**
     * Add metabox to selected post types.
     * @wp-hook add_meta_boxes
     * @return void
     */
    public function metaboxSetup()
    {
        $pdfButtonPostTypes = get_option('dkpdf_pdfbutton_post_types');

        if ($pdfButtonPostTypes) {
            foreach ($pdfButtonPostTypes as $postType) {
                add_meta_box(
                    'post-data',
                    __('DK PDF', 'dkpdf'),
                    [$this, 'metaboxContent'],
                    $postType,
                    'normal',
                    'high'
                );
            }
        }
    }

    /**
     * Save metabox data
     * @wp-hook save_post
     * @param int $postId
     * @return int|bool
     */
    public function metaboxSave(int $postId)
    {
        if(!wp_verify_nonce($_POST['dkpdf_nonce'], 'dkpdf_nonce_action')) {
            return false;
        }

        if (!current_user_can('edit_post', $postId)) {
            return false;
        }

        $fieldData = $this->getCustomFieldsSettings();
        $fields = array_keys($fieldData);

        foreach ($fields as $field) {
            $fieldValue = isset($_POST[$field])
                ? filter_var($_POST[$field], FILTER_SANITIZE_STRING)
                : '';

            return update_post_meta($postId, $field, $fieldValue);
        }
    }

    /**
     * Add content to metabox.
     * @wp-hook add_meta_box
     * @return void
     */
    public function metaboxContent()
    {
        global $post_id;
        $fields = get_post_custom($post_id);
        $fieldData = $this->getCustomFieldsSettings();

        $html = $this->createHtml($fieldData, $fields);

        $allowedHtml = $this->allowedHtml();
        echo wp_kses($html, $allowedHtml);
    }

    /**
     * Creates metabox html content.
     * @param array $fieldData
     * @param array $fields
     * @return string
     */
    public function createHtml(array $fieldData, array $fields): string
    {
        $html = '';

        if (count($fieldData) === 0) {
            return $html;
        }

        $html .= '<table class="form-table">' . "\n";
        $html .= '<tbody>' . "\n";

        foreach ($fieldData as $key => $value) {
            $data = $value['default'];

            if (isset($fields[$key]) && isset($fields[$key][0])) {
                $data = $fields[$key][0];
            }

            $html .= '<tr valign="top"><th scope="row">' . $value['name']
                . '</th><td><input name="' . esc_attr($key)
                . '" type="checkbox" id="' . esc_attr($key) . '" ' . checked('on', $data, false)
                . ' /> <label for="' . esc_attr($key) . '"><span class="description">'
                . $value['description'] . '</span></label>' . "\n";
            $html .= '</td></tr>' . "\n";
        }

        $html .= '</tbody>' . "\n";
        $html .= '</table>' . "\n";

        $html .= wp_nonce_field('dkpdf_nonce_action', 'dkpdf_nonce', true, false);

        return $html;
    }

    /**
     * Return array with all fields in metabox.
     * @return array
     */
    private function getCustomFieldsSettings(): array
    {
        $fields['_hide_pdfbutton'] = [
            'name' => __('Disable DK PDF Button:', 'dkpdf'),
            'description' => '',
            'type' => 'checkbox',
            'default' => '',
            'section' => '',
        ];

        return $fields;
    }

    /**
     *
     * @return array
     */
    private function allowedHtml(): array
    {
        $allowedHtml = [
            'input' => [
                'type' => [],
                'name' => [],
                'id' => [],
                'value' => [],
                'checked' => [],
            ],
            'table' => [
                'class' => [],
            ],
            'tbody' => [],
            'tr' => [
                'valign' => [],
            ],
            'td' => [],
            'th' => [
                'scope' => [],
            ],
            'label' => [
                'for' => [],
            ],
            'span' => [
                'class' => [],
            ],
        ];
        return $allowedHtml;
    }
}
