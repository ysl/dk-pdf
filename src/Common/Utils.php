<?php # -*- coding: utf-8 -*-

namespace Dinamiko\DKPDF\Common;

class Utils {

	/**
	 * Returns an array of active post types.
	 *
	 * @return array
	 */
	public static function get_post_types() {

		$default_post_types = get_post_types( [ 'public' => true, '_builtin' => true ] );
		$custom_post_types = get_post_types( [ 'public' => true, '_builtin' => false ] );

		return apply_filters(
			'dkpdf_posts_arr',
			array_merge( $default_post_types, $custom_post_types )
		);
	}
}
