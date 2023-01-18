<?php

add_action('init', 'pp_melange_shortcode_shortcake');

function pp_melange_shortcode_shortcake()
{
    if ( ! function_exists('shortcode_ui_register_for_shortcode')) {
        return;
    }

    shortcode_ui_register_for_shortcode(
        'pp-login-form',
        array(
            'label'         => 'Login Form Tag',
            'listItemImage' => 'dashicons-tag',
        )
    );

    shortcode_ui_register_for_shortcode(
        'pp-registration-form',
        array(
            'label'         => 'Registration Form Tag',
            'listItemImage' => 'dashicons-tag',
        )
    );

    shortcode_ui_register_for_shortcode(
        'pp-password-reset-form',
        array(
            'label'         => 'Password Reset Form Tag',
            'listItemImage' => 'dashicons-tag',
        )
    );

    shortcode_ui_register_for_shortcode(
        'pp-edit-profile-form',
        array(
            'label'         => 'Edit Profile Form Tag',
            'listItemImage' => 'dashicons-tag',
        )
    );
}