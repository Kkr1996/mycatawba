<?php
add_action('init', 'pp_passreset_shortcode_shortcake');

function pp_passreset_shortcode_shortcake()
{
    if (!function_exists('shortcode_ui_register_for_shortcode')) {
        return;
    }

    shortcode_ui_register_for_shortcode(
        'user-login',
        array(
            'label' => __('Password Reset Form: Username / Email field', 'profilepress'),
            'listItemImage' => 'dashicons-admin-users',
            'attrs' => array(
                array(
                    'label' => __('CSS class', 'profilepress'),
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => __('CSS class for the field.', 'profilepress'),
                ),
                array(
                    'label' => __('CSS ID', 'profilepress'),
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => __('CSS id for the field.', 'profilepress'),
                ),
                array(
                    'label' => __('Title', 'profilepress'),
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => __('Title attribute for the input field.', 'profilepress'),
                ),
                array(
                    'label' => __('Placeholder', 'profilepress'),
                    'attr' => 'placeholder',
                    'type' => 'text',
                    'description' => __('Placeholder attribute for the input field.', 'profilepress')
                ),
                array(
                    'label' => __('Value', 'profilepress'),
                    'attr' => 'value',
                    'type' => 'text',
                    'description' => __('Value attribute (default field text).', 'profilepress')
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'reset-submit',
        array(
            'label' => 'Password Reset Form: Submit Button',
            'listItemImage' => 'dashicons-cart',
            'attrs' => array(
                array(
                    'label' => __('CSS class', 'profilepress'),
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => __('CSS class for the field.', 'profilepress'),
                ),
                array(
                    'label' => __('CSS ID', 'profilepress'),
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => __('CSS id for the field.', 'profilepress'),
                ),
                array(
                    'label' => __('Title', 'profilepress'),
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => __('Title attribute for the input field.', 'profilepress'),
                ),
                array(
                    'label' => __('Value', 'profilepress'),
                    'attr' => 'value',
                    'type' => 'text',
                    'description' => __('Submit button text.', 'profilepress')
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'enter-password',
        array(
            'label' => 'Password Reset Handler Form: Enter Password',
            'listItemImage' => 'dashicons-no-alt',
            'attrs' => array(
                array(
                    'label' => __('CSS class', 'profilepress'),
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => __('CSS class for the field.', 'profilepress'),
                ),
                array(
                    'label' => __('CSS ID', 'profilepress'),
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => __('CSS id for the field.', 'profilepress'),
                ),
                array(
                    'label' => __('Title', 'profilepress'),
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => __('Title attribute for the input field.', 'profilepress'),
                ),
                array(
                    'label' => __('Placeholder', 'profilepress'),
                    'attr' => 'placeholder',
                    'type' => 'text',
                    'description' => __('Placeholder attribute for the input field.', 'profilepress')
                ),
                array(
                    'label' => __('Value', 'profilepress'),
                    'attr' => 'value',
                    'type' => 'text',
                    'description' => __('Value attribute (default field text).', 'profilepress')
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        're-enter-password',
        array(
            'label' => 'Password Reset Handler Form: Re-enter Password',
            'listItemImage' => 'dashicons-no-alt',
            'attrs' => array(
                array(
                    'label' => __('CSS class', 'profilepress'),
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => __('CSS class for the field.', 'profilepress'),
                ),
                array(
                    'label' => __('CSS ID', 'profilepress'),
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => __('CSS id for the field.', 'profilepress'),
                ),
                array(
                    'label' => __('Title', 'profilepress'),
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => __('Title attribute for the input field.', 'profilepress'),
                ),
                array(
                    'label' => __('Placeholder', 'profilepress'),
                    'attr' => 'placeholder',
                    'type' => 'text',
                    'description' => __('Placeholder attribute for the input field.', 'profilepress')
                ),
                array(
                    'label' => __('Value', 'profilepress'),
                    'attr' => 'value',
                    'type' => 'text',
                    'description' => __('Value attribute (default field text).', 'profilepress')
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'reset-password-meter',
        array(
            'label' => 'Password Reset Handler Form: Password Strength Meter',
            'listItemImage' => 'dashicons-clock',
            'attrs' => array(
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the field.',
                ),
                array(
                    'label' => 'Value',
                    'attr' => 'enforce',
                    'type' => 'select',
                    'options' => array(
                        'true' => 'True',
                        'false' => 'False'
                    ),
                    'description' => __('Enforce strong password rule for users resetting their passwords.', 'profilepress'),
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'password-reset-submit',
        array(
            'label' => __('Password Reset Handler Form: Submit Button', 'profilepress'),
            'listItemImage' => 'dashicons-cart',
            'attrs' => array(
                array(
                    'label' => __('CSS class', 'profilepress'),
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => __('CSS class for the field.', 'profilepress'),
                ),
                array(
                    'label' => __('CSS ID', 'profilepress'),
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => __('CSS id for the field.', 'profilepress'),
                ),
                array(
                    'label' => __('Title', 'profilepress'),
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => __('Title attribute for the input field.', 'profilepress'),
                ),
                array(
                    'label' => __('Value', 'profilepress'),
                    'attr' => 'value',
                    'type' => 'text',
                    'description' => __('Submit button text.', 'profilepress')
                ),
            ),
        )
    );
}