<?php # -*- coding: utf-8 -*-

namespace Dinamiko\DKPDF\PDF;

use Dinamiko\DKPDF\Common\TemplateLoader;

class Display {

	/**
	 * @var TemplateLoader
	 */
	private $template;

	/**
	 * @var \mPDF
	 */
	private $mpdf;

	/**
	 * Constructor. Sets up the properties.
	 *
	 * @param TemplateLoader $template
	 */
	public function __construct( TemplateLoader $template ) {

		$this->template = $template;
	}

	public function init() {

		add_filter( 'the_content', array( $this, 'display_pdf_button' ) );
		add_action( 'wp', array( $this, 'create_pdf' ) );
		add_filter( 'query_vars', array( $this, 'set_query_vars' ) );
	}

	/**
	 * Displays PDF button.
	 *
	 * @wp-hook the_content
	 *
	 * @param $content
	 *
	 * @return mixed|string
	 */
	public function display_pdf_button( $content ) {

		if ( $this->remove_button() ) {

			remove_shortcode( 'dkpdf-button' );
			$content = str_replace( '[dkpdf-button]', '', $content );

			return $content;
		}

		$button_position = get_option( 'dkpdf_pdfbutton_position', 'before' );
		$content         = $this->add_button_to_content( $button_position, $content );

		return $content;
	}

	/**
	 * Check if PDF button should be removed.
	 *
	 * @return boolean
	 */
	public function remove_button() {

		$pdf = (string) get_query_var( 'pdf' );
		if ( $pdf ) {
			return true;
		}

		global $post;
		$post_type = get_post_type( $post->ID );
		if ( ! in_array( $post_type, get_option( 'dkpdf_pdfbutton_post_types' ), true ) ) {
			return true;
		}

		if ( is_archive() || is_front_page() || is_home() ) {
			return true;
		}

		return false;
	}

	/**
	 * Add PDF button to content.
	 *
	 * @param string $button_position Button position.
	 * @param string $content         Post content.
	 *
	 * @return string
	 */
	public function add_button_to_content( $button_position, $content ) {

		if ( 'shortcode' === $button_position ) {
			return $content;
		}

		$output = $content;

		if ( 'before' === $button_position ) {

			ob_start();
			$this->template->get_template_part( 'dkpdf-button' );

			return ob_get_clean() . $output;

		} elseif ( 'after' === $button_position ) {

			ob_start();
			$this->template->get_template_part( 'dkpdf-button' );

			return $output . ob_get_clean();

		} else {
			return $content;
		}
	}

	/**
	 * Creates the PDF.
	 *
	 * @wp-hook wp
	 *
	 * @return void
	 */
	public function create_pdf() {

		$pdf = (string) get_query_var( 'pdf' );
		if ( $pdf ) {

			$config = [
				'tempDir'           => apply_filters( 'dkpdf_pdf_temp_dir', DKPDF_PLUGIN_DIR . '/tmp' ),
				'default_font_size' => get_option( 'dkpdf_font_size', '12' ),
				'format'            => get_option( 'dkpdf_page_orientation', 'vertical' ) === 'horizontal' ? 'A4-L' : 'A4',
				'margin_left'       => get_option( 'dkpdf_margin_left', '15' ),
				'margin_right'      => get_option( 'dkpdf_margin_right', '15' ),
				'margin_top'        => get_option( 'dkpdf_margin_top', '50' ),
				'margin_bottom'     => get_option( 'dkpdf_margin_bottom', '30' ),
				'margin_header'     => get_option( 'dkpdf_margin_header', '15' ),
			];

			$this->mpdf = new \Mpdf\Mpdf( apply_filters( 'dkpdf_pdf_config', $config ) );

			$this->pdf_setup();

			$this->pdf_display();

			$this->pdf_output();

			exit;
		}

	}

	public function pdf_setup() {

		if ( 'on' === get_option( 'dkpdf_enable_protection' ) ) {

			$grant_permissions = get_option( 'dkpdf_grant_permissions' );
			$this->mpdf->SetProtection( $grant_permissions );
		}

		if ( 'on' ===  get_option( 'dkpdf_keep_columns' ) ) {

			$this->mpdf->keepColumns = true;
		}

		// make chinese characters work in the pdf
		//$mpdf->useAdobeCJK = true;
		//$mpdf->autoScriptToLang = true;
		//$mpdf->autoLangToFont = true;
	}

	public function pdf_display() {

		$pdf_header_html = $this->dkpdf_get_template( 'dkpdf-header' );
		$this->mpdf->SetHTMLHeader( $pdf_header_html );

		$pdf_footer_html = $this->dkpdf_get_template( 'dkpdf-footer' );
		$this->mpdf->SetHTMLFooter( $pdf_footer_html );

		$this->mpdf->WriteHTML( apply_filters( 'dkpdf_before_content', '' ) );
		$this->mpdf->WriteHTML( $this->dkpdf_get_template( 'dkpdf-index' ) );
		$this->mpdf->WriteHTML( apply_filters( 'dkpdf_after_content', '' ) );
	}

	public function pdf_output() {

		global $post;
		$title = apply_filters( 'dkpdf_pdf_filename', get_the_title( $post->ID ) );
		$this->mpdf->SetTitle( $title );
		$this->mpdf->SetAuthor( apply_filters( 'dkpdf_pdf_author', get_bloginfo( 'name' ) ) );

		$pdfbutton_action = (string) get_option( 'dkpdf_pdfbutton_action', 'open' );
		if ( 'open' === $pdfbutton_action ) {
			$this->mpdf->Output( $title . '.pdf', 'I' );
		} else {
			$this->mpdf->Output( $title . '.pdf', 'D' );
		}
	}

	/**
	 * Returns a template
	 *
	 * @param $template_name
	 *
	 * @return string
	 */
	function dkpdf_get_template( $template_name ) {

		ob_start();
		$this->template->get_template_part( $template_name );

		return ob_get_clean();
	}

	/**
	 * Set query_vars
	 *
	 * @wp-hook query_vars
	 *
	 * @param array $query_vars
	 *
	 * @return array
	 */
	public function set_query_vars( $query_vars ) {

		$query_vars[] = 'pdf';

		return $query_vars;
	}
}
