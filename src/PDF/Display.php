<?php # -*- coding: utf-8 -*-

namespace Dinamiko\DKPDF\PDF;

use Dinamiko\DKPDF\Common\TemplateLoader;

class Display {

	/**
	 * @var TemplateLoader
	 */
	private $template;

	/**
	 * Constructor. Sets up the properties.
	 *
	 * @param TemplateLoader $template
	 */
	public function __construct( TemplateLoader $template ) {

		$this->template = $template;
	}

	public function init() {

		add_filter( 'the_content', array( $this, 'dkpdf_display_pdf_button' ) );
		add_action( 'wp', array( $this, 'dkpdf_output_pdf' ) );
		add_filter( 'query_vars', array( $this, 'dkpdf_set_query_vars' ) );
	}

	/**
	 * Displays pdf button
	 *
	 * @param $content
	 *
	 * @return mixed|string
	 */
	public function dkpdf_display_pdf_button( $content ) {

		// if is generated pdf don't show pdf button
		$pdf = get_query_var( 'pdf' );

		if ( apply_filters( 'dkpdf_hide_button_isset', isset( $_POST['dkpdfg_action_create'] ) ) ) {

			if ( $pdf
			     || apply_filters( 'dkpdf_hide_button_equal',
					$_POST['dkpdfg_action_create'] == 'dkpdfg_action_create' ) ) {

				remove_shortcode( 'dkpdf-button' );
				$content = str_replace( "[dkpdf-button]", "", $content );

				return $content;

			}

		} else {

			if ( $pdf ) {

				remove_shortcode( 'dkpdf-button' );
				$content = str_replace( "[dkpdf-button]", "", $content );

				return $content;

			}

		}

		global $post;
		$post_type = get_post_type( $post->ID );

		$option_post_types = sanitize_option( 'dkpdf_pdfbutton_post_types',
			get_option( 'dkpdf_pdfbutton_post_types', array() ) );

		if ( is_archive() || is_front_page() || is_home() ) {
			return $content;
		}

		// return content if not checked
		if ( $option_post_types ) {

			if ( ! in_array( get_post_type( $post ), $option_post_types ) ) {

				return $content;

			}

		}

		if ( $option_post_types ) {

			if ( in_array( get_post_type( $post ), $option_post_types ) ) {

				$c = $content;

				$pdfbutton_position = sanitize_option( 'dkpdf_pdfbutton_position',
					get_option( 'dkpdf_pdfbutton_position', 'before' ) );

				if ( $pdfbutton_position ) {

					if ( $pdfbutton_position == 'shortcode' ) {
						return $c;
					}

					if ( $pdfbutton_position == 'before' ) {

						ob_start();

						$content = $this->template->get_template_part( 'dkpdf-button' );

						return ob_get_clean() . $c;

					} else if ( $pdfbutton_position == 'after' ) {

						ob_start();

						$content = $this->template->get_template_part( 'dkpdf-button' );

						return $c . ob_get_clean();
					}
				}
			}
		} else {

			return $content;
		}
	}

	/**
	 * output the pdf
	 */
	public function dkpdf_output_pdf( $query ) {

		$pdf = sanitize_text_field( get_query_var( 'pdf' ) );

		if ( $pdf ) {

			require_once DKPDF_PLUGIN_DIR . '/vendor/autoload.php';

			// font size
			$dkpdf_font_size = get_option( 'dkpdf_font_size', '12' );

			// page orientation
			$dkpdf_page_orientation = get_option( 'dkpdf_page_orientation', 'vertical' );
			if ( $dkpdf_page_orientation == 'horizontal' ) {
				$format = apply_filters( 'dkpdf_pdf_format', 'A4' ) . '-L';
			} else {
				$format = apply_filters( 'dkpdf_pdf_format', 'A4' );
			}

			// margins
			$dkpdf_margin_left   = get_option( 'dkpdf_margin_left', '15' );
			$dkpdf_margin_right  = get_option( 'dkpdf_margin_right', '15' );
			$dkpdf_margin_top    = get_option( 'dkpdf_margin_top', '50' );
			$dkpdf_margin_bottom = get_option( 'dkpdf_margin_bottom', '30' );
			$dkpdf_margin_header = get_option( 'dkpdf_margin_header', '15' );

			$mpdf = new \Mpdf\Mpdf( [
				'tempDir'           => DKPDF_PLUGIN_DIR . '/tmp',
				'default_font_size' => $dkpdf_font_size,
				'format'            => $format,
				'margin_left'       => $dkpdf_margin_left,
				'margin_right'      => $dkpdf_margin_right,
				'margin_top'        => $dkpdf_margin_top,
				'margin_bottom'     => $dkpdf_margin_bottom,
				'margin_header'     => $dkpdf_margin_header,
			] );

			// encrypts and sets the PDF document permissions
			// https://mpdf.github.io/reference/mpdf-functions/setprotection.html
			$enable_protection = get_option( 'dkpdf_enable_protection' );

			if ( $enable_protection == 'on' ) {
				$grant_permissions = get_option( 'dkpdf_grant_permissions' );
				$mpdf->SetProtection( $grant_permissions );
			}

			// keep columns
			$keep_columns = get_option( 'dkpdf_keep_columns' );

			if ( $keep_columns == 'on' ) {
				$mpdf->keepColumns = true;
			}

			/*
			// make chinese characters work in the pdf
			$mpdf->useAdobeCJK = true;
			$mpdf->autoScriptToLang = true;
			$mpdf->autoLangToFont = true;
			*/

			// header
			$pdf_header_html = $this->dkpdf_get_template( 'dkpdf-header' );
			$mpdf->SetHTMLHeader( $pdf_header_html );

			// footer
			$pdf_footer_html = $this->dkpdf_get_template( 'dkpdf-footer' );
			$mpdf->SetHTMLFooter( $pdf_footer_html );

			$mpdf->WriteHTML( apply_filters( 'dkpdf_before_content', '' ) );
			$mpdf->WriteHTML( $this->dkpdf_get_template( 'dkpdf-index' ) );
			$mpdf->WriteHTML( apply_filters( 'dkpdf_after_content', '' ) );

			// action to do (open or download)
			$pdfbutton_action = sanitize_option( 'dkpdf_pdfbutton_action',
				get_option( 'dkpdf_pdfbutton_action', 'open' ) );

			global $post;
			$title = apply_filters( 'dkpdf_pdf_filename', get_the_title( $post->ID ) );

			$mpdf->SetTitle( $title );
			$mpdf->SetAuthor( apply_filters( 'dkpdf_pdf_author', get_bloginfo( 'name' ) ) );

			if ( $pdfbutton_action == 'open' ) {

				$mpdf->Output( $title . '.pdf', 'I' );

			} else {

				$mpdf->Output( $title . '.pdf', 'D' );

			}

			exit;

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
	 * Returns an array of active post, page, attachment and custom post types.
	 *
	 * @return array
	 */
	function dkpdf_get_post_types() {

		$args = array(
			'public'   => true,
			'_builtin' => false,
		);

		$post_types = get_post_types( $args );
		$post_arr   = array( 'post' => 'post', 'page' => 'page', 'attachment' => 'attachment' );

		foreach ( $post_types as $post_type ) {

			$arr      = array( $post_type => $post_type );
			$post_arr += $arr;

		}

		$post_arr = apply_filters( 'dkpdf' . '_posts_arr', $post_arr );

		return $post_arr;
	}

	/**
	 * Set query_vars
	 *
	 * @param array $query_vars
	 *
	 * @return array
	 */
	public function dkpdf_set_query_vars( $query_vars ) {

		$query_vars[] = 'pdf';

		return $query_vars;
	}
}
