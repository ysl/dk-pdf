<?php # -*- coding: utf-8 -*-
declare(strict_types=1);

namespace Dinamiko\DKPDF;

class Shortcode
{

    /**
     * @var TemplateLoader
     */
    private $template;

    /**
     * Constructor. Sets up the properties.
     *
     * @param TemplateLoader $template
     */
    public function __construct(TemplateLoader $template)
    {

        $this->template = $template;
    }

    public function init()
    {

        add_shortcode('dkpdf-button', array($this, 'dkpdf_button_shortcode'));
        add_shortcode('dkpdf-remove', array($this, 'dkpdf_remove_shortcode'));
        add_shortcode('dkpdf-pagebreak', array($this, 'dkpdf_pagebreak_shortcode'));
        add_shortcode('dkpdf-columns', array($this, 'dkpdf_columns_shortcode'));
        add_shortcode('dkpdf-columnbreak', array($this, 'dkpdf_columnbreak_shortcode'));
    }

    /**
     * [dkpdf-button]
     * This shortcode is used to display DK PDF Button
     * doesn't has attributes, uses settings from DK PDF Settings / PDF Button
     */
    public function dkpdf_button_shortcode($atts, $content = null)
    {

        ob_start();

        $this->template->templatePart('dkpdf-button');

        return ob_get_clean();
    }

    /**
     * [dkpdf-remove tag="gallery"]content to remove[/dkpdf-remove]
     * This shortcode is used remove pieces of content in the generated PDF
     *
     * @return string
     */
    public function dkpdf_remove_shortcode($atts, $content = null)
    {

        $atts = shortcode_atts(array(
            'tag' => '',
        ), $atts);

        $pdf = get_query_var('pdf');

        $tag = sanitize_text_field($atts['tag']);
        if ($tag !== '' && $pdf) {

            remove_shortcode($tag);
            add_shortcode($tag, '__return_false');

            return do_shortcode($content);
        } else {
            if ($pdf) {
                return '';
            }
        }

        return do_shortcode($content);
    }

    /**
     * [dkpdf-pagebreak]
     * Content after this shortcode goes to the next page.
     *
     * @return string
     */
    public function pagebreak()
    {

        $pdf = get_query_var('pdf');
        if ($pdf) {
            return '<pagebreak />';
        }
    }

    /**
     * [dkpdf-columns]text[/dkpdf-columns]
     * https://mpdf.github.io/what-else-can-i-do/columns.html
     *
     * <columns column-count=”n” vAlign=”justify” column-gap=”n” />
     * column-count = Number of columns. Anything less than 2 sets columns off. (Required)
     * vAlign = Automatically adjusts height of columns to be equal if set to J or justify. Default Off. (Optional)
     * gap = gap in mm between columns. Default 5. (Optional)
     *
     * <columnbreak /> <column_break /> or <newcolumn /> (synonymous) can be included to force a new column.
     * (This will automatically disable any justification or readjustment of column heights.)
     */
    public function dkpdf_columns_shortcode($atts, $content = null)
    {

        $atts = shortcode_atts(array(
            'columns' => '2',
            'equal-columns' => 'false',
            'gap' => '10',
        ), $atts);

        $pdf = get_query_var('pdf');

        if ($pdf) {
            $columns = sanitize_text_field($atts['columns']);
            $equal_columns = sanitize_text_field($atts['equal-columns']);
            $vAlign = $equal_columns == 'true' ? 'vAlign="justify"' : '';
            $gap = sanitize_text_field($atts['gap']);

            return '<columns column-count="' . $columns . '" ' . $vAlign . ' column-gap="' . $gap . '" />' . do_shortcode($content) . '<columns column-count="1">';
        } else {
            remove_shortcode('dkpdf-columnbreak');
            add_shortcode('dkpdf-columnbreak', '__return_false');

            return do_shortcode($content);
        }
    }

    /**
     * [dkpdf-columnbreak] forces a new column
     *
     * @uses <columnbreak />
     */
    public function dkpdf_columnbreak_shortcode($atts, $content = null)
    {

        $pdf = get_query_var('pdf');
        if ($pdf) {
            return '<columnbreak />';
        }
    }

}


