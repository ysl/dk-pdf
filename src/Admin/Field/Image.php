<?php

namespace Dinamiko\DKPDF\Admin\Field;

class Image
{
    /**
     * @var array
     */
    private $field;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @param $field
     * @param $prefix
     */
    public function __construct($field, $prefix)
    {
        $this->field = $field;
        $this->prefix = $prefix;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $optionName = $this->prefix . $this->field['id'];
        $option = get_option($optionName, $this->field['default']);
        $output = '';

        $imageThumb = '';
        if ($option) {
            $imageThumb = wp_get_attachment_thumb_url($option);
        }

        $output .= '<img id="' . $optionName . '_preview" class="image_preview" ';
        $output .= 'src="' . $imageThumb . '" /><br/>' . "\n";
        $output .= '<input id="' . $optionName . '_button" type="button" data-uploader_title="'
            . __('Upload an image', 'dkpdf') . '" data-uploader_button_text="'
            . __('Use image', 'dkpdf') . '" class="image_upload_button button" value="'
            . __('Upload new image', 'dkpdf') . '" />' . "\n";
        $output .= '<input id="' . $optionName
            . '_delete" type="button" class="image_delete_button button" value="'
            . __('Remove image', 'dkpdf') . '" />' . "\n";
        $output .= '<input id="' . $optionName
            . '" class="image_data_field" type="hidden" name="' . $optionName
            . '" value="' . esc_attr($option) . '"/><br/>' . "\n";
        $output .= '<label for="' . esc_attr($this->field['id']) . '">' . "\n"
            . '<span class="description">' . $this->field['description'] . '</span>' . "\n"
            . '</label>' . "\n";

        return $output;
    }
}
