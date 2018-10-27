<?php

namespace Dinamiko\DKPDF\Admin\Field;

class Checkbox
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
        $checked = $option && 'on' === $option ? 'checked="checked"' : '';

        return '<input id="'.esc_attr($this->field['id']).'" '
            . ' type="'.esc_attr($this->field['type']).'" '
            . ' name="' . esc_attr($optionName).'" '
            . $checked . '/>' . "\n"
            . '<label for="' . esc_attr($this->field['id']) . '">' . "\n"
            . '<span class="description">' . $this->field['description'] . '</span>' . "\n"
            . '</label>' . "\n";
    }
}
