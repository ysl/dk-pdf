<?php

namespace Dinamiko\DKPDF\Admin;

use Dinamiko\DKPDF\Utils;

class Config
{
    // phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
    public function settings()
    {
        $settings['pdfbtn'] = [
            'title' => __('PDF Button', 'dkpdf'),
            'description' => '',
            'fields' => [
                [
                    'id' => 'pdfbutton_text',
                    'label' => __('Button text', 'dkpdf'),
                    'description' => '',
                    'type' => 'text',
                    'default' => 'PDF Button',
                    'placeholder' => '',
                ],
                [
                    'id' => 'pdfbutton_post_types',
                    'label' => __('Post types to apply:', 'dkpdf'),
                    'description' => '',
                    'type' => 'checkbox_multi',
                    'options' => Utils::postTypes(),
                    'default' => [],
                ],
                [
                    'id' => 'pdfbutton_action',
                    'label' => __('Action', 'dkpdf'),
                    'description' => '',
                    'type' => 'radio',
                    'options' => [
                        'open' => 'Open PDF in new Window',
                        'download' => 'Download PDF directly',
                    ],
                    'default' => 'open',
                ],
                [
                    'id' => 'pdfbutton_position',
                    'label' => __('Position', 'dkpdf'),
                    'description' => '',
                    'type' => 'radio',
                    'options' => [
                        'shortcode' => 'Use shortcode',
                        'before' => 'Before content',
                        'after' => 'After content',
                    ],
                    'default' => 'before',
                ],
                [
                    'id' => 'pdfbutton_align',
                    'label' => __('Align', 'dkpdf'),
                    'description' => '',
                    'type' => 'radio',
                    'options' => ['left' => 'Left', 'center' => 'Center', 'right' => 'Right'],
                    'default' => 'right',
                ],
            ],
        ];

        $settings['dkpdf_setup'] = [
            'title' => __('PDF Setup', 'dkpdf'),
            'description' => '',
            'fields' => [
                [
                    'id' => 'page_orientation',
                    'label' => __('Page orientation', 'dkpdf'),
                    'description' => '',
                    'type' => 'radio',
                    'options' => ['vertical' => 'Vertical', 'horizontal' => 'Horizontal'],
                    'default' => 'vertical',
                ],
                [
                    'id' => 'font_size',
                    'label' => __('Font size', 'dkpdf'),
                    'description' => 'In points (pt)',
                    'type' => 'number',
                    'default' => '12',
                    'placeholder' => '12',
                ],
                [
                    'id' => 'margin_left',
                    'label' => __('Margin left', 'dkpdf'),
                    'description' => 'In points (pt)',
                    'type' => 'number',
                    'default' => '15',
                    'placeholder' => '15',
                ],
                [
                    'id' => 'margin_right',
                    'label' => __('Margin right', 'dkpdf'),
                    'description' => 'In points (pt)',
                    'type' => 'number',
                    'default' => '15',
                    'placeholder' => '15',
                ],
                [
                    'id' => 'margin_top',
                    'label' => __('Margin top', 'dkpdf'),
                    'description' => 'In points (pt)',
                    'type' => 'number',
                    'default' => '50',
                    'placeholder' => '50',
                ],
                [
                    'id' => 'margin_bottom',
                    'label' => __('Margin bottom', 'dkpdf'),
                    'description' => 'In points (pt)',
                    'type' => 'number',
                    'default' => '30',
                    'placeholder' => '30',
                ],
                [
                    'id' => 'margin_header',
                    'label' => __('Margin header', 'dkpdf'),
                    'description' => 'In points (pt)',
                    'type' => 'number',
                    'default' => '15',
                    'placeholder' => '15',
                ],
                [
                    'id' => 'margin_footer',
                    'label' => __('Margin footer', 'dkpdf'),
                    'description' => 'In points (pt)',
                    'type' => 'number',
                    'default' => '15',
                    'placeholder' => '15',
                ],
                [
                    'id' => 'enable_protection',
                    'label' => __('Enable PDF protection', 'dkpdf'),
                    'description' => __(
                        'Encrypts PDF file and respects permissions given below',
                        'dkpdf'
                    ),
                    'type' => 'checkbox',
                    'default' => '',
                ],
                [
                    'id' => 'grant_permissions',
                    'label' => __('Protected PDF permissions', 'dkpdf'),
                    'description' => '',
                    'type' => 'checkbox_multi',
                    'options' => [
                        'copy' => 'Copy',
                        'print' => 'Print',
                        'print-highres' => 'Print Highres',
                        'modify' => 'Modify',
                        'annot-forms' => 'Annot Forms',
                        'fill-forms' => 'Fill Forms',
                        'extract' => 'Extract',
                        'assemble' => 'Assemble',
                    ],
                    'default' => [],
                ],
                [
                    'id' => 'keep_columns',
                    'label' => __('Keep columns', 'dkpdf'),
                    'description' => __(
                        'Columns will be written successively (dkpdf-columns shortcode). i.e. there will be no balancing of the length of columns.',
                        'dkpdf'
                    ),
                    'type' => 'checkbox',
                    'default' => '',
                ],
            ],
        ];

        $settings['pdf_header_footer'] = [
            'title' => __('PDF Header & Footer', 'dkpdf'),
            'description' => '',
            'fields' => [
                [
                    'id' => 'pdf_header_image',
                    'label' => __('Header logo', 'dkpdf'),
                    'description' => '',
                    'type' => 'image',
                    'default' => '',
                    'placeholder' => '',
                ],
                [
                    'id' => 'pdf_header_show_title',
                    'label' => __('Header show title', 'dkpdf'),
                    'description' => '',
                    'type' => 'checkbox',
                    'default' => '',
                ],
                [
                    'id' => 'pdf_header_show_pagination',
                    'label' => __('Header show pagination', 'dkpdf'),
                    'description' => '',
                    'type' => 'checkbox',
                    'default' => '',
                ],
                [
                    'id' => 'pdf_footer_text',
                    'label' => __('Footer text', 'dkpdf'),
                    'description' => __('HTML tags: a, br, em, strong, hr, p, h1 to h4', 'dkpdf'),
                    'type' => 'textarea',
                    'default' => '',
                    'placeholder' => '',
                ],
                [
                    'id' => 'pdf_footer_show_title',
                    'label' => __('Footer show title', 'dkpdf'),
                    'description' => '',
                    'type' => 'checkbox',
                    'default' => '',
                ],
                [
                    'id' => 'pdf_footer_show_pagination',
                    'label' => __('Footer show pagination', 'dkpdf'),
                    'description' => '',
                    'type' => 'checkbox',
                    'default' => '',
                ],
            ],
        ];

        $settings['pdf_css'] = [
            'title' => __('PDF CSS', 'dkpdf'),
            'description' => '',
            'fields' => [
                [
                    'id' => 'pdf_custom_css',
                    'label' => __('PDF Custom CSS', 'dkpdf'),
                    'description' => '',
                    'type' => 'textarea_code',
                    'default' => '',
                    'placeholder' => '',
                ],
                [
                    'id' => 'print_wp_head',
                    'label' => __('Use current theme\'s CSS', 'dkpdf'),
                    'description' => __(
                        'Includes the stylesheet from current theme, but is overridden by PDF Custom CSS and plugins adding its own stylesheets.',
                        'dkpdf'
                    ),
                    'type' => 'checkbox',
                    'default' => '',
                ],
            ],
        ];
        // phpcs:enable

        return $settings;
    }
}
