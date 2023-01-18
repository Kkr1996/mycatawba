<?php

add_action('init', 'pp_frontend_profile_shortcode_shortcake');

/**
 *
 */
function pp_frontend_profile_shortcode_shortcake()
{
    if ( ! function_exists('shortcode_ui_register_for_shortcode')) {
        return;
    }

    shortcode_ui_register_for_shortcode(
        'profile-username',
        array(
            'label'         => 'Front-end Profile: Username',
            'listItemImage' => 'dashicons-admin-users',
        )
    );

    shortcode_ui_register_for_shortcode(
        'profile-email',
        array(
            'label'         => 'Front-end Profile: Email Address',
            'listItemImage' => 'dashicons-email',

        )
    );

    shortcode_ui_register_for_shortcode(
        'profile-website',
        array(
            'label'         => 'Front-end Profile: Website',
            'listItemImage' => 'dashicons-admin-links',

        )
    );

    shortcode_ui_register_for_shortcode(
        'profile-nickname',
        array(
            'label'         => 'Front-end Profile: Nickname',
            'listItemImage' => 'dashicons-admin-users',

        )
    );

    shortcode_ui_register_for_shortcode(
        'profile-display-name',
        array(
            'label'         => 'Front-end Profile: Display Name',
            'listItemImage' => 'dashicons-admin-users',

        )
    );

    shortcode_ui_register_for_shortcode(
        'profile-first-name',
        array(
            'label'         => 'Front-end Profile: First Name',
            'listItemImage' => 'dashicons-admin-users',

        )
    );

    shortcode_ui_register_for_shortcode(
        'profile-last-name',
        array(
            'label'         => 'Front-end Profile: Last name',
            'listItemImage' => 'dashicons-admin-users',

        )
    );

    shortcode_ui_register_for_shortcode(
        'profile-bio',
        array(
            'label'         => 'Front-end Profile: Biography',
            'listItemImage' => 'dashicons-info',

        )
    );

    shortcode_ui_register_for_shortcode(
        'profile-avatar-url',
        array(
            'label'         => 'Front-end Profile: Avatar Image URL',
            'listItemImage' => 'dashicons-admin-links',

        )
    );

    shortcode_ui_register_for_shortcode(
        'profile-cpf',
        array(
            'label'         => 'Front-end Profile: Custom Field Data',
            'listItemImage' => 'dashicons-editor-code',
            'attrs'         => array(
                array(
                    'label'       => 'Field Key',
                    'attr'        => 'key',
                    'type'        => 'text',
                    'description' => 'Custom profile field\'s key.',
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'profile-file',
        array(
            'label'         => 'Front-end Profile: Uploaded File',
            'listItemImage' => 'dashicons-upload',
            'attrs'         => array(
                array(
                    'label'       => 'File Key',
                    'attr'        => 'key',
                    'type'        => 'text',
                    'description' => 'Custom profile field\'s key for the file.',
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'post-count',
        array(
            'label'         => 'Front-end Profile: Post Count',
            'listItemImage' => 'dashicons-admin-post',
        )
    );

    shortcode_ui_register_for_shortcode(
        'comment-count',
        array(
            'label'         => 'Front-end Profile: Comment Count',
            'listItemImage' => 'dashicons-admin-comments',
        )
    );

    shortcode_ui_register_for_shortcode(
        'profile-post-list',
        array(
            'label'         => 'Front-end Profile: List of Post',
            'listItemImage' => 'dashicons-admin-post',
        )
    );

    shortcode_ui_register_for_shortcode(
        'profile-date-registered',
        array(
            'label'         => __('Front-end Profile: Date Registered', 'profilepress'),
            'listItemImage' => 'dashicons-calendar-alt',
        )
    );

    /**
     * Return an associative field key/field label name pairs.
     * @return array
     */
    $custom_profile_fields = array_reduce(PROFILEPRESS_sql::sql_user_admin_profile(), function ($haystack, $needle) {
        $haystack[$needle->field_key] = $needle->label_name;

        return $haystack;
    }, array());

    shortcode_ui_register_for_shortcode(
        'profile-hide-empty-field',
        array(
            'label'         => 'Front-end Profile: Hide Empty Data',
            'listItemImage' => 'dashicons-hidden',
            'attrs'         => array(
                array(
                    'label'       => __('Profile Field', 'profilepress'),
                    'attr'        => 'field',
                    'type'        => 'select',
                    'options'     => array_merge(array(
                        ''             => __('Choose...', 'profilepress'),
                        'username'     => __('Username', 'profilepress'),
                        'email'        => __('Email Address', 'profilepress'),
                        'website'      => __('Website URL', 'profilepress'),
                        'first_name'   => __('First Name', 'profilepress'),
                        'last_name'    => __('Last Name', 'profilepress'),
                        'nickname'     => __('Nickname', 'profilepress'),
                        'display_name' => __('Display Name', 'profilepress'),
                    ), $custom_profile_fields),
                    'description' => __('Select the profile field to make hidden when empty.', 'profilepress'),
                ),
            ),
            array(
                'label'       => __('Custom Profile Field', 'profilepress'),
                'attr'        => 'field',
                'type'        => 'text',
                'description' => __('For custom profile fields, enter the field key to make hidden when empty.', 'profilepress'),
            ),
        )
    );
}