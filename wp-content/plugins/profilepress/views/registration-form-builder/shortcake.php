<?php

add_action('init', 'pp_registration_shortcode_shortcake');

function pp_registration_shortcode_shortcake()
{
    if (!function_exists('shortcode_ui_register_for_shortcode')) {
        return;
    }

    shortcode_ui_register_for_shortcode(
        'reg-username',
        array(
            'label' => 'Registration form: Username field',
            'listItemImage' => 'dashicons-admin-users',
            'attrs' => array(
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the field.',
                ),
                array(
                    'label' => 'CSS ID',
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => 'CSS id for the field.',
                ),
                array(
                    'label' => 'Title',
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => 'Title attribute for the input field.',
                ),
                array(
                    'label' => 'Placeholder',
                    'attr' => 'placeholder',
                    'type' => 'text',
                    'description' => 'Placeholder attribute for the input field.'
                ),
                array(
                    'label' => 'Value',
                    'attr' => 'value',
                    'type' => 'text',
                    'description' => 'Value attribute (default field text).'
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'reg-password',
        array(
            'label' => 'Registration form: Password field',
            'listItemImage' => 'dashicons-no-alt',
            'attrs' => array(
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the field.',
                ),
                array(
                    'label' => 'CSS ID',
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => 'CSS id for the field.',
                ),
                array(
                    'label' => 'Title',
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => 'Title attribute for the input field.',
                ),
                array(
                    'label' => 'Placeholder',
                    'attr' => 'placeholder',
                    'type' => 'text',
                    'description' => 'Placeholder attribute for the input field.'
                ),
                array(
                    'label' => 'Value',
                    'attr' => 'value',
                    'type' => 'text',
                    'description' => 'Value attribute (default field text).'
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'reg-confirm-password',
        array(
            'label' => 'Registration form: Confirm Password field',
            'listItemImage' => 'dashicons-no-alt',
            'attrs' => array(
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the field.',
                ),
                array(
                    'label' => 'CSS ID',
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => 'CSS id for the field.',
                ),
                array(
                    'label' => 'Title',
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => 'Title attribute for the input field.',
                ),
                array(
                    'label' => 'Placeholder',
                    'attr' => 'placeholder',
                    'type' => 'text',
                    'description' => 'Placeholder attribute for the input field.'
                ),
                array(
                    'label' => 'Value',
                    'attr' => 'value',
                    'type' => 'text',
                    'description' => 'Value attribute (default field text).'
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'reg-email',
        array(
            'label' => 'Registration form: Email field',
            'listItemImage' => 'dashicons-email',
            'attrs' => array(
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the field.',
                ),
                array(
                    'label' => 'CSS ID',
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => 'CSS id for the field.',
                ),
                array(
                    'label' => 'Title',
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => 'Title attribute for the input field.',
                ),
                array(
                    'label' => 'Placeholder',
                    'attr' => 'placeholder',
                    'type' => 'text',
                    'description' => 'Placeholder attribute for the input field.'
                ),
                array(
                    'label' => 'Value',
                    'attr' => 'value',
                    'type' => 'text',
                    'description' => 'Value attribute (default field text).'
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'reg-confirm-email',
        array(
            'label' => 'Registration form: Confirm Email field',
            'listItemImage' => 'dashicons-email',
            'attrs' => array(
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the field.',
                ),
                array(
                    'label' => 'CSS ID',
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => 'CSS id for the field.',
                ),
                array(
                    'label' => 'Title',
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => 'Title attribute for the input field.',
                ),
                array(
                    'label' => 'Placeholder',
                    'attr' => 'placeholder',
                    'type' => 'text',
                    'description' => 'Placeholder attribute for the input field.'
                ),
                array(
                    'label' => 'Value',
                    'attr' => 'value',
                    'type' => 'text',
                    'description' => 'Value attribute (default field text).'
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'reg-website',
        array(
            'label' => 'Registration form: Website field',
            'listItemImage' => 'dashicons-admin-links',
            'attrs' => array(
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the field.',
                ),
                array(
                    'label' => 'CSS ID',
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => 'CSS id for the field.',
                ),
                array(
                    'label' => 'Title',
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => 'Title attribute for the input field.',
                ),
                array(
                    'label' => 'Placeholder',
                    'attr' => 'placeholder',
                    'type' => 'text',
                    'description' => 'Placeholder attribute for the input field.'
                ),
                array(
                    'label' => 'Value',
                    'attr' => 'value',
                    'type' => 'text',
                    'description' => 'Value attribute (default field text).'
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'reg-nickname',
        array(
            'label' => 'Registration form: Nickname field',
            'listItemImage' => 'dashicons-admin-users',
            'attrs' => array(
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the field.',
                ),
                array(
                    'label' => 'CSS ID',
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => 'CSS id for the field.',
                ),
                array(
                    'label' => 'Title',
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => 'Title attribute for the input field.',
                ),
                array(
                    'label' => 'Placeholder',
                    'attr' => 'placeholder',
                    'type' => 'text',
                    'description' => 'Placeholder attribute for the input field.'
                ),
                array(
                    'label' => 'Value',
                    'attr' => 'value',
                    'type' => 'text',
                    'description' => 'Value attribute (default field text).'
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'reg-display-name',
        array(
            'label' => 'Registration form: Display name field',
            'listItemImage' => 'dashicons-admin-users',
            'attrs' => array(
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the field.',
                ),
                array(
                    'label' => 'CSS ID',
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => 'CSS id for the field.',
                ),
                array(
                    'label' => 'Title',
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => 'Title attribute for the input field.',
                ),
                array(
                    'label' => 'Placeholder',
                    'attr' => 'placeholder',
                    'type' => 'text',
                    'description' => 'Placeholder attribute for the input field.'
                ),
                array(
                    'label' => 'Value',
                    'attr' => 'value',
                    'type' => 'text',
                    'description' => 'Value attribute (default field text).'
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'reg-first-name',
        array(
            'label' => 'Registration form: First name field',
            'listItemImage' => 'dashicons-admin-users',
            'attrs' => array(
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the field.',
                ),
                array(
                    'label' => 'CSS ID',
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => 'CSS id for the field.',
                ),
                array(
                    'label' => 'Title',
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => 'Title attribute for the input field.',
                ),
                array(
                    'label' => 'Placeholder',
                    'attr' => 'placeholder',
                    'type' => 'text',
                    'description' => 'Placeholder attribute for the input field.'
                ),
                array(
                    'label' => 'Value',
                    'attr' => 'value',
                    'type' => 'text',
                    'description' => 'Value attribute (default field text).'
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'reg-last-name',
        array(
            'label' => 'Registration form: Last name field',
            'listItemImage' => 'dashicons-admin-users',
            'attrs' => array(
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the field.',
                ),
                array(
                    'label' => 'CSS ID',
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => 'CSS id for the field.',
                ),
                array(
                    'label' => 'Title',
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => 'Title attribute for the input field.',
                ),
                array(
                    'label' => 'Placeholder',
                    'attr' => 'placeholder',
                    'type' => 'text',
                    'description' => 'Placeholder attribute for the input field.'
                ),
                array(
                    'label' => 'Value',
                    'attr' => 'value',
                    'type' => 'text',
                    'description' => 'Value attribute (default field text).'
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'reg-bio',
        array(
            'label' => 'Registration form: Biography field',
            'listItemImage' => 'dashicons-info',
            'attrs' => array(
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the field.',
                ),
                array(
                    'label' => 'CSS ID',
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => 'CSS id for the field.',
                ),
                array(
                    'label' => 'Title',
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => 'Title attribute for the input field.',
                ),
                array(
                    'label' => 'Placeholder',
                    'attr' => 'placeholder',
                    'type' => 'text',
                    'description' => 'Placeholder attribute for the input field.'
                ),
                array(
                    'label' => 'Value',
                    'attr' => 'value',
                    'type' => 'text',
                    'description' => 'Value attribute (default field text).'
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'reg-avatar',
        array(
            'label' => 'Registration form: Avatar field',
            'listItemImage' => 'dashicons-admin-users',
            'attrs' => array(
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the field.',
                ),
                array(
                    'label' => 'CSS ID',
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => 'CSS id for the field.',
                ),
                array(
                    'label' => 'Title',
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => 'Title attribute for the input field.',
                ),
                array(
                    'label' => 'Placeholder',
                    'attr' => 'placeholder',
                    'type' => 'text',
                    'description' => 'Placeholder attribute for the input field.'
                ),
                array(
                    'label' => 'Value',
                    'attr' => 'value',
                    'type' => 'text',
                    'description' => 'Value attribute (default field text).'
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'reg-select-role',
        array(
            'label' => 'Registration form: Role Selection',
            'listItemImage' => 'dashicons-shield',
            'attrs' => array(
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the field.',
                ),
                array(
                    'label' => 'CSS ID',
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => 'CSS id for the field.',
                ),
                array(
                    'label' => 'Title',
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => __('Title attribute for the input field.', 'profilepress'),
                ),
                array(
                    'label' => 'Options',
                    'attr' => 'options',
                    'type' => 'text',
                    'description' => 'Comma separated list of roles for users to select on registration.'
                )
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'reg-cpf',
        array(
            'label' => 'Registration form: Custom field',
            'listItemImage' => 'dashicons-editor-code',
            'attrs' => array(
                array(
                    'label' => 'Field Key',
                    'attr' => 'key',
                    'type' => 'text',
                    'description' => 'Custom field key.',
                ),
                array(
                    'label' => 'Field Type',
                    'attr' => 'type',
                    'type' => 'select',
                    'options' => array(
                        '' => 'Choose...',
                        'text' => __('Text Field', 'profilepress'),
                        'number' => __('Number Field', 'profilepress'),
                        'date' => __('Date Field', 'profilepress'),
                        'textarea' => __('Textarea Field', 'profilepress'),
                        'select' => __('Select Dropdown', 'profilepress'),
                        'checkbox' => __('Check Box', 'profilepress'),
                        'radio' => __('Radio Button', 'profilepress'),
                        'country' => __('Countries Dropdown', 'profilepress'),
                        'file' => __('File Upload', 'profilepress'),
                        'agreeable' => __('Agreeable (Checkbox)', 'profilepress'),
                        'hidden' => __('Hidden Input Field)', 'profilepress'),
                    ),
                    'description' => 'Custom field type.',
                ),
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the field.',
                ),
                array(
                    'label' => 'CSS ID',
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => 'CSS id for the field.',
                ),
                array(
                    'label' => 'Title',
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => 'Title attribute for the input field.',
                ),
                array(
                    'label' => 'Placeholder',
                    'attr' => 'placeholder',
                    'type' => 'text',
                    'description' => 'Placeholder attribute for the input field.'
                ),
                array(
                    'label' => 'Value',
                    'attr' => 'value',
                    'type' => 'text',
                    'description' => 'Value attribute (default field text).'
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'reg-submit',
        array(
            'label' => 'Registration form: Submit Button',
            'listItemImage' => 'dashicons-cart',
            'attrs' => array(
                array(
                    'label' => 'CSS class',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => 'CSS class for the field.',
                ),
                array(
                    'label' => 'CSS ID',
                    'attr' => 'id',
                    'type' => 'text',
                    'description' => 'CSS id for the field.',
                ),
                array(
                    'label' => 'Title',
                    'attr' => 'title',
                    'type' => 'text',
                    'description' => 'Title attribute for the input field.',
                ),
                array(
                    'label' => 'Value',
                    'attr' => 'value',
                    'type' => 'text',
                    'description' => 'Submit button text.'
                ),
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'reg-password-meter',
        array(
            'label' => 'Registration form: Password Strength Meter',
            'listItemImage' => 'dashicons-clock',
            'attrs' => array(
                array(
                    'enforce' => 'Enforce strong password',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => __('Allowed values are "true" and "false" without quote. Default to "true" if not set.', 'profilepress'),
                )
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'reset-password-meter',
        array(
            'label' => 'Password reset form (handler): Password Strength Meter',
            'listItemImage' => 'dashicons-clock',
            'attrs' => array(
                array(
                    'enforce' => 'Enforce strong password',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => __('Allowed values are "true" and "false" without quote. Default to "true" if not set.', 'profilepress'),
                )
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'edit-profile-password-meter',
        array(
            'label' => 'Edit profile form: Password Strength Meter',
            'listItemImage' => 'dashicons-clock',
            'attrs' => array(
                array(
                    'enforce' => 'Enforce strong password',
                    'attr' => 'class',
                    'type' => 'text',
                    'description' => __('Allowed values are "true" and "false" without quote. Default to "true" if not set.', 'profilepress'),
                )
            ),
        )
    );
}