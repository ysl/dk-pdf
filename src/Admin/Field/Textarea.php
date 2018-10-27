<?php

namespace Dinamiko\DKPDF\Admin\Field;

class Textarea
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

        return '<textarea id="' . esc_attr($this->field['id']) . '" rows="5" cols="50" '
            . 'name="' . esc_attr($optionName) . '" '
            . 'placeholder="' . esc_attr($this->field['placeholder']) . '">'
            . $option . '</textarea><br/>' . "\n"
            . '<label for="' . esc_attr($this->field['id']) . '">' . "\n"
            . '<span class="description">' . $this->field['description'] . '</span>' . "\n"
            . '</label>' . "\n";
    }
}
