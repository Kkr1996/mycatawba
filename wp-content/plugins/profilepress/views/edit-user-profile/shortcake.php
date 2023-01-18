<?php

add_action('init', 'pp_edit_profile_shortcode_shortcake');

function pp_edit_profile_shortcode_shortcake()
{
    if (!function_exists('shortcode_ui_register_for_shortcode')) {
        return;
    }

    shortcode_ui_register_for_shortcode(
        'edit-profile-username',
        array(
            'label' => 'Edit Profile form: Username field',
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
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'edit-profile-password',
        array(
            'label' => 'Edit Profile form: Password field',
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
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'edit-profile-confirm-password',
        array(
            'label' => 'Edit Profile form: Confirm Password field',
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
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'edit-profile-email',
        array(
            'label' => 'Edit Profile form: Email field',
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
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'edit-profile-confirm-email',
        array(
            'label' => 'Edit Profile form: Confirm mail field',
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
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'edit-profile-website',
        array(
            'label' => 'Edit Profile form: Website field',
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
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'edit-profile-nickname',
        array(
            'label' => 'Edit Profile form: Nickname field',
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
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'edit-profile-display-name',
        array(
            'label' => 'Edit Profile form: Display name field',
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
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'edit-profile-first-name',
        array(
            'label' => 'Edit Profile form: First name field',
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
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'edit-profile-last-name',
        array(
            'label' => 'Edit Profile form: Last name field',
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
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'edit-profile-bio',
        array(
            'label' => 'Edit Profile form: Biography field',
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
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'edit-profile-avatar',
        array(
            'label' => 'Edit Profile form: Avatar field',
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
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'edit-profile-cpf',
        array(
            'label' => 'Edit Profile form: Custom field',
            'listItemImage' => 'dashicons-editor-code',
            'attrs' => array(
                array(
                    'label' => 'Field Key',
                    'attr' => 'key',
                    'type' => 'text',
                    'description' => 'Custom profile field\'s key.',
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
            ),
        )
    );

    shortcode_ui_register_for_shortcode(
        'edit-profile-submit',
        array(
            'label' => 'Edit Profile form: Submit Button',
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
        'remove-user-avatar',
        array(
            'label' => 'Edit Profile form: Remove Avatar Button',
            'listItemImage' => 'dashicons-trash',
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
                    'label' => 'Label',
                    'attr' => 'label',
                    'type' => 'text',
                    'description' => 'Submit button text.'
                ),
            ),
        )
    );
}