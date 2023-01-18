<?php
namespace pp_default_pages;

/**
 * Create an edit profile page powered by ProfilePress
 *
 * @package pp_default_pages
 */
class Edit_Profile
{

    /** insert the page to db */
    public static function instance()
    {
        // Create post object
        $post_args = array(
            'post_title'   => 'Edit Profile',
            'post_content' => '[profilepress-edit-profile id="3"]',
            'post_status'  => 'publish',
            'post_type'    => 'page',
        );

        // Insert the post into the database
        $insert = wp_insert_post($post_args, true);

        return $insert && ! is_wp_error($insert) ? $insert : null;
    }
}