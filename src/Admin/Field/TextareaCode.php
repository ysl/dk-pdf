<?php

namespace Dinamiko\DKPDF\Admin\Field;

class TextareaCode
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

        $output .= '<div id="' . 'editor' . '">' . $option . '</div>' . "\n";
        $output .= '<textarea name="' . esc_attr($optionName) . '"';
        $output .= ' id="' . esc_attr($optionName) . '" rows="5" cols="50" ';
        $output .= 'placeholder="' . esc_attr($this->field['placeholder']) . '">'
            . $option . '</textarea>' . "\n";

        return $output;
    }
}
