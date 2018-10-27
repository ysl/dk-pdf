<?php

namespace Dinamiko\DKPDF\Admin;

class Fields
{

    /**
     * Generate HTML for displaying fields
     *
     * @param array $args
     */
    public function displayField($args)
    {
        $field = $args['field'];
        $html = '';

        switch ($field['type']) {
            case 'text':
            case 'number':
            case 'email':
                $html = new Field\Text($field, $args['prefix']);
                break;
            case 'checkbox':
                $html = new Field\Checkbox($field, $args['prefix']);
                break;
            case 'checkbox_multi':
                $html = new Field\CheckboxMultiple($field, $args['prefix']);
                break;
            case 'radio':
                $html = new Field\Radio($field, $args['prefix']);
                break;
            case 'textarea':
                $html = new Field\Textarea($field, $args['prefix']);
                break;
            case 'textarea_code':
                $html = new Field\TextareaCode($field, $args['prefix']);
                break;
            case 'image':
                $html = new Field\Image($field, $args['prefix']);
                break;
        }

        echo wp_kses($html, $this->allowedHtml());
    }

    /**
     * Allowed HTML for fields.
     * @return array
     */
    private function allowedHtml()
    {
        $allowed = [
            'input' => [
                'id' => [],
                'class' => [],
                'type' => [],
                'name' => [],
                'placeholder' => [],
                'value' => [],
                'checked' => [],
                'data-uploader_button_text' => [],
            ],
            'label' => [
                'id' => [],
                'class' => [],
                'for' => [],
                'span' => [
                    'class' => [],
                ],
            ],
            'span' => [
                'id' => [],
                'class' => [],
            ],
            'div' => [
                'id' => [],
                'class' => [],
            ],
            'textarea' => [
                'name' => [],
                'id' => [],
                'class' => [],
                'placeholder' => [],
                'rows' => [],
                'cols' => [],
                'style' => [],
            ],
            'br' => [],
            'img' => [
                'id' => [],
                'class' => [],
                'src' => [],
            ],
        ];

        return apply_filters('dkpdf_allowed_html_fields', $allowed);
    }
}
