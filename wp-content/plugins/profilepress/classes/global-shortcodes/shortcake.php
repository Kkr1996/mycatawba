<?php

add_action('init', 'pp_global_shortcode_shortcake');

function pp_global_shortcode_shortcake()
{
    if (is_singular()) {
        return;
    }
    if (!function_exists('shortcode_ui_register_for_shortcode')) {
        return;
    }

    shortcode_ui_register_for_shortcode(
        'user-avatar',
        array(
            'label' => 'User Avatar',
            'listItemImage' => 'dashicons-admin-users',
            'attrs' => array(
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the avatar image.',
                ),
                array(
                    'label' => 'CSS ID',
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => 'CSS id for the profile picture.',
                ),
                array(
                    'label' => 'Picture size',
                    'attr' => 'size',
                    'type' => 'text',
                    'description' => 'The size (without the "px" unit) of the profile picture.',
                ),
                array(
                    'label' => 'Default avatar URL',
                    'attr' => 'default',
                    'type' => 'text',
                    'description' => 'An image url to set as the default avatar if a user hasn\'t uploaded an avatar.',
                ),
                array(
                    'label' => 'Alternate text',
                    'attr' => 'alt',
                    'type' => 'text',
                    'description' => 'Alternate text for the profile picture (avatar)'
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'user-avatar-url',
        array(
            'label' => 'Avatar URL',
            'listItemImage' => 'dashicons-admin-users',
        )
    );

    shortcode_ui_register_for_shortcode(
        'pp-recaptcha',
        array(
            'label' => 'Google reCAPTCHA',
            'listItemImage' => '<img src="' . ASSETS_URL . '/images/tinymce/recaptcha.png">'
        )
    );

    shortcode_ui_register_for_shortcode(
        'link-registration',
        array(
            'label' => 'Registration Link',
            'listItemImage' => 'dashicons-admin-links',
            'attrs' => array(
                array(
                    'label' => 'Label',
                    'attr' => 'label',
                    'type' => 'text',
                    'description' => 'The anchor text for the link',
                ),
                array(
                    'label' => 'CSS ID',
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => 'CSS id for the HTML registration link.',
                ),
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the HTML registration link.',
                ),
                array(
                    'label' => 'Title',
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => 'Title attribute for the HTML link',
                )
            )
        )
    );

    shortcode_ui_register_for_shortcode(
        'link-lost-password',
        array(
            'label' => 'Password Reset Link',
            'listItemImage' => 'dashicons-admin-links',
            'attrs' => array(
                array(
                    'label' => 'Label',
                    'attr' => 'label',
                    'type' => 'text',
                    'description' => 'The anchor text for the link',
                ),
                array(
                    'label' => 'CSS ID',
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => 'CSS id for the password reset link.',
                ),
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the password reset link.',
                ),
                array(
                    'label' => 'Title',
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => 'Title attribute for the link',
                )
            )
        )
    );

    shortcode_ui_register_for_shortcode(
        'link-login',
        array(
            'label' => 'Login Link',
            'listItemImage' => 'dashicons-admin-links',
            'attrs' => array(
                array(
                    'label' => 'Label',
                    'attr' => 'label',
                    'type' => 'text',
                    'description' => 'The anchor text for the link',
                ),
                array(
                    'label' => 'CSS ID',
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => 'CSS id for the password reset link.',
                ),
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the password reset link.',
                ),
                array(
                    'label' => 'Title',
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => 'Title attribute for the link',
                )
            )
        )
    );

    shortcode_ui_register_for_shortcode(
        'link-logout',
        array(
            'label' => 'Logout Link',
            'listItemImage' => 'dashicons-admin-links',
            'attrs' => array(
                array(
                    'label' => 'Label',
                    'attr' => 'label',
                    'type' => 'text',
                    'description' => 'The anchor text for the link',
                ),
                array(
                    'label' => 'CSS ID',
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => 'CSS id for the password reset link.',
                ),
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the password reset link.',
                ),
                array(
                    'label' => 'Title',
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => 'Title attribute for the link',
                )
            )
        )
    );

    shortcode_ui_register_for_shortcode(
        'link-edit-user-profile',
        array(
            'label' => 'Edit Profile Link',
            'listItemImage' => 'dashicons-admin-links',
            'attrs' => array(
                array(
                    'label' => 'Label',
                    'attr' => 'label',
                    'type' => 'text',
                    'description' => 'The anchor text for the link',
                ),
                array(
                    'label' => 'CSS ID',
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => 'CSS id for the password reset link.',
                ),
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the password reset link.',
                ),
                array(
                    'label' => 'Title',
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => 'Title attribute for the link',
                )
            )
        )
    );

    shortcode_ui_register_for_shortcode(
        'facebook-login-url',
        array(
            'label' => 'Facebook Login URL',
            'listItemImage' => 'dashicons-admin-links',
        )
    );

    shortcode_ui_register_for_shortcode(
        'twitter-login-url',
        array(
            'label' => 'Twitter Login URL',
            'listItemImage' => 'dashicons-admin-links',
        )
    );

    shortcode_ui_register_for_shortcode(
        'linkedin-login-url',
        array(
            'label' => 'LinkedIn Login URL',
            'listItemImage' => 'dashicons-admin-links',
        )
    );

    shortcode_ui_register_for_shortcode(
        'github-login-url',
        array(
            'label' => 'Github Login URL',
            'listItemImage' => 'dashicons-admin-links',
        )
    );

    shortcode_ui_register_for_shortcode(
        'google-login-url',
        array(
            'label' => 'Google Login URL',
            'listItemImage' => 'dashicons-admin-links',
        )
    );

    shortcode_ui_register_for_shortcode(
        'vk-login-url',
        array(
            'label' => 'VK.com Login URL',
            'listItemImage' => 'dashicons-admin-links',
        )
    );

    shortcode_ui_register_for_shortcode(
        'bbp-topic-started-url',
        array(
            'label' => __('BbPress URL: Topics Started', 'profilepress'),
            'listItemImage' => 'dashicons-admin-links',
        )
    );


    shortcode_ui_register_for_shortcode(
        'bbp-replies-created-url',
        array(
            'label' => __('BbPress URL: Replies Created', 'profilepress'),
            'listItemImage' => 'dashicons-admin-links',
        )
    );

    shortcode_ui_register_for_shortcode(
        'bbp-favorites-url',
        array(
            'label' => __('BbPress URL: Favorites', 'profilepress'),
            'listItemImage' => 'dashicons-admin-links',
        )
    );

    shortcode_ui_register_for_shortcode(
        'bbp-subscriptions-url',
        array(
            'label' => __('BbPress URL: Subscriptions', 'profilepress'),
            'listItemImage' => 'dashicons-admin-links',
        )
    );

    shortcode_ui_register_for_shortcode(
        'vk-login-url',
        array(
            'label' => 'VK.com Login URL',
            'listItemImage' => 'dashicons-admin-links',
        )
    );

    shortcode_ui_register_for_shortcode(
        'social-button',
        array(
            'label' => 'Social Login Buttons',
            'listItemImage' => 'dashicons-networking',

            'inner_content' => array(
                'label' => 'Button Text',
                'description' => "The text shown in the social login button. E.g Facebook Login",
            ),
            'attrs' => array(
                array(
                    'label' => 'Type',
                    'attr' => 'type',
                    'type' => 'select',
                    'options' => array(
                        'facebook' => 'Facebook',
                        'twitter' => 'Twitter',
                        'google' => 'Google',
                        'linkedin' => 'Linkedin',
                        'github' => 'Github',
                        'vk' => 'VK.com'
                    ),
                    'description' => 'The social login button to generate.',
                ),
                array(
                    'label' => 'Font Size',
                    'attr' => 'size',
                    'type' => 'number',
                    'description' => "The font size of the button text. Leave empty for default size.",
                )
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'password-hint',
        array(
            'label' => 'Strong Password Hint',
            'listItemImage' => 'dashicons-book'
        )
    );
}

