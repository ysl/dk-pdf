<?php

namespace Dinamiko\DKPDF;

class Utils
{

    /**
     * Returns an array of active post types.
     *
     * @return array
     */
    public static function getPostTypes()
    {
        $defaultPostTypes = get_post_types(['public' => true, '_builtin' => true]);
        $customPostTypes = get_post_types(['public' => true, '_builtin' => false]);

        return apply_filters(
            'dkpdf_posts_arr',
            array_merge($defaultPostTypes, $customPostTypes)
        );
    }
}
