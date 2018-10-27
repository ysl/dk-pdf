<?php

namespace Dinamiko\DKPDF\Admin\Field;

class Radio
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
        $option = get_option($optionName);
        if (!$option) {
            $option = $this->field['default'];
        }
        $output = '';

        foreach ($this->field['options'] as $key => $value) {
            $checked = $key === $option ? true : false;

            $output .= '<label for="' . esc_attr($this->field['id'] . '_' . $key) . '">';
            $output .= '<input type="radio" ' . checked($checked, true, false)
                . ' name="' . esc_attr($optionName)
                . '" value="' . esc_attr($key)
                . '" id="' . esc_attr($this->field['id'] . '_' . $key) . '" /> '
                . $value . '</label> ';
        }

        $output .= '<label class="full-width" for="' . esc_attr($this->field['id']) . '">' . "\n"
            . '<span class="description">' . $this->field['description'] . '</span>' . "\n"
            . '</label>' . "\n";

        return $output;
    }
}
