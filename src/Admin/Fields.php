<?php

namespace Dinamiko\DKPDF\Admin;

class Fields
{

    /**
     * Generate HTML for displaying fields
     *
     * @param array $args
     *
     * @return void
     */
    public function displayField($args)
    {

        $field = $args['field'];
        $optionName = $args['prefix'] . $field['id'];
        $option = get_option($optionName, $field['default']);
        $html = '';

        switch ($field['type']) {
            case 'text':
            case 'url':
            case 'email':
                $html .= '<input id="'
                    . esc_attr($field['id'])
                    . '" type="text" name="'
                    . esc_attr($optionName)
                    . '" placeholder="'
                    . esc_attr($field['placeholder'])
                    . '" value="'
                    . esc_attr($option) . '" />' . "\n";
                break;

            case 'password':
            case 'number':
            case 'hidden':
                $min = '';
                if (isset($field['min'])) {
                    $min = ' min="' . esc_attr($field['min']) . '"';
                }

                $max = '';
                if (isset($field['max'])) {
                    $max = ' max="' . esc_attr($field['max']) . '"';
                }
                $html .= '<input id="'
                    . esc_attr($field['id'])
                    . '" type="'
                    . esc_attr($field['type'])
                    . '" name="'
                    . esc_attr($optionName)
                    . '" placeholder="'
                    . esc_attr($field['placeholder'])
                    . '" value="' . esc_attr($option)
                    . '"' . $min . '' . $max . '/>' . "\n";
                break;

            case 'text_secret':
                $html .= '<input id="'
                    . esc_attr($field['id'])
                    . '" type="text" name="'
                    . esc_attr($optionName) . '" placeholder="'
                    . esc_attr($field['placeholder'])
                    . '" value="" />' . "\n";
                break;

            case 'textarea_code':
                $html .= '<div id="' . 'editor' . '">' . $option . '</div>' . "\n";
                $html .= '<textarea id="'
                    . esc_attr($optionName) . '" rows="5" cols="50" name="'
                    . esc_attr($optionName) . '" placeholder="'
                    . esc_attr($field['placeholder']) . '">' . $option . '</textarea>' . "\n";
                break;

            case 'textarea':
                $html .= '<textarea id="'
                    . esc_attr($field['id']) . '" rows="5" cols="50" name="'
                    . esc_attr($optionName) . '" placeholder="'
                    . esc_attr($field['placeholder']) . '">' . $option . '</textarea><br/>' . "\n";
                break;

            case 'checkbox':
                $checked = '';
                if ($option && 'on' === $option) {
                    $checked = 'checked="checked"';
                }
                $html .= '<input id="'
                    . esc_attr($field['id']) . '" type="'
                    . esc_attr($field['type']) . '" name="'
                    . esc_attr($optionName) . '" ' . $checked . '/>' . "\n";
                break;

            case 'checkbox_multi':
                foreach ($field['options'] as $key => $value) {
                    $checked = false;
                    if ($option === false) {
                        $option = [];
                    }
                    if (in_array($key, $option, true)) {
                        $checked = true;
                    }
                    $html .= '<label for="'
                        . esc_attr($field['id'] . '_' . $key)
                        . '" class="checkbox_multi"><input type="checkbox" '
                        . checked($checked, true, false)
                        . ' name="' . esc_attr($optionName) . '[]" value="'
                        . esc_attr($key) . '" id="'
                        . esc_attr($field['id'] . '_' . $key) . '" /> ' . $value . '</label> ';
                }
                break;

            case 'radio':
                foreach ($field['options'] as $key => $value) {
                    $checked = false;
                    if ($key === $option) {
                        $checked = true;
                    }
                    $html .= '<label for="'
                        . esc_attr($field['id'] . '_' . $key) . '"><input type="radio" '
                        . checked($checked, true, false). ' name="'
                        . esc_attr($optionName) . '" value="'
                        . esc_attr($key) . '" id="'
                        . esc_attr($field['id'] . '_' . $key) . '" /> ' . $value . '</label> ';
                }
                break;

            case 'select':
                $html .= '<select name="' . esc_attr($optionName) . '" id="' . esc_attr($field['id']) . '">';
                foreach ($field['options'] as $key => $value) {
                    $selected = false;
                    if ($key === $option) {
                        $selected = true;
                    }
                    $html .= '<option ' . selected($selected, true, false) . ' value="'
                        . esc_attr($key) . '">' . $value . '</option>';
                }
                $html .= '</select> ';
                break;

            case 'select_multi':
                $html .= '<select name="' . esc_attr($optionName) . '[]" id="'
                    . esc_attr($field['id']) . '" multiple="multiple">';
                foreach ($field['options'] as $key => $value) {
                    $selected = false;
                    if (in_array($key, $option, true)) {
                        $selected = true;
                    }
                    $html .= '<option ' . selected($selected, true, false) . ' value="'
                        . esc_attr($key) . '">' . $value . '</option>';
                }
                $html .= '</select> ';
                break;

            case 'image':
                $imageThumb = '';
                if ($option) {
                    $imageThumb = wp_get_attachment_thumb_url($option);
                }
                $html .= '<img id="' . $optionName . '_preview" class="image_preview" src="'
                    . $imageThumb . '" /><br/>' . "\n";
                $html .= '<input id="' . $optionName. '_button" type="button" data-uploader_title="'
                    . __('Upload an image', 'dkpdf')
                    . '" data-uploader_button_text="'
                    . __('Use image', 'dkpdf')
                    . '" class="image_upload_button button" value="'
                    . __('Upload new image', 'dkpdf') . '" />' . "\n";
                $html .= '<input id="' . $optionName
                    . '_delete" type="button" class="image_delete_button button" value="'
                    . __('Remove image', 'dkpdf') . '" />' . "\n";
                $html .= '<input id="' . $optionName
                    . '" class="image_data_field" type="hidden" name="' . $optionName
                    . '" value="' . $option . '"/><br/>' . "\n";
                break;

            case 'color':
                ?>
                <div class="color-picker" style="position:relative;">
                    <input type="text" name="<?php esc_attr_e($optionName); ?>" class="color"
                           value="<?php esc_attr_e($option); ?>"/>
                    <div style="position:absolute;background:#FFF;z-index:99;border-radius:100%;"
                         class="colorpicker"></div>
                </div>
                <?php
                break;

            case 'checkbox_multi':
            case 'radio':
            case 'select_multi':
                $html .= '<br/><span class="description">' . $field['description'] . '</span>';
                break;

            default:
                global $post;
                if (!$post) {
                    $html .= '<label for="' . esc_attr($field['id']) . '">' . "\n";
                }

                $html .= '<span class="description">' . $field['description'] . '</span>' . "\n";

                if (!$post) {
                    $html .= '</label>' . "\n";
                }
                break;
        }

        echo $html;
    }
}
