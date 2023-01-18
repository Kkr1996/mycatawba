<?php

global $wpdb;

/**
 * Class ProfilePress_Plugin_Options_On_Activate
 */
class ProfilePress_Plugin_On_Activate
{
    /** Class instance */
    public static function instance()
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }

        $is_plugin_activated = is_multisite() ? get_site_option('pp_plugin_activated') : get_option('pp_plugin_activated');

        if (!$is_plugin_activated) {
            self::plugin_settings_activation();
            self::db_activation();
        }

        flush_rewrite_rules();
    }

    /** Store the whole database activation code in this function for easy reuse by the @self::instance method */
    public static function db_activation()
    {
        db_structure\PP_Db_Schema::instance();

        self::insert_custom_profile_field();
        logins\Logins_Base::instance();
        registrations\Registrations_Base::instance();
        password_reset\Password_Reset_Base::instance();
        edit_user_profile\Edit_User_Profile_Base::instance();
        front_end_profile\Front_End_Profile_Base::instance();
        Extras\PP_Extras::instance();


        /**
         * Save the plugin state i.e if its been install and activated at first.
         * It is done to avoid duplicate data insertion on plugin activation
         * @see http://wordpress.stackexchange.com/q/168448/59917
         */

        if (is_multisite()) {
            add_site_option('pp_plugin_activated', 'true');
        } else {
            add_option('pp_plugin_activated', 'true');
        }
    }

    /** Activation for non-database settings */
    public static function plugin_settings_activation()
    {
        self::profile_contact_info();
        self::user_moderation_add_pending_role();
        self::moderation_extra();
        general_settings\General_Settings::instance();
    }


    /** Create a usermeta info containing the default WordPress contact information */
    public static function profile_contact_info()
    {
        // default contact information
        $default_contact_info = array(
            'facebook' => __('Facebook profile URL', 'profilepress'),
            'twitter' => __('Twitter profile URL', 'profilepress'),
            'linkedin' => __('LinkedIn profile URL', 'profilepress'),
            'google' => __('Google+ profile URL', 'profilepress'),
        );

        if (is_multisite()) {
            add_blog_option(null, 'pp_contact_info', $default_contact_info);
        } else {
            add_option('pp_contact_info', $default_contact_info);
        }
    }


    /** Add "pending_user" role for use by the moderation extra */
    public static function user_moderation_add_pending_role()
    {
        add_role('pending_users', 'Pending');
    }

    /** Default error message for the moderation extra */
    public static function moderation_extra()
    {

        $moderation_settings = array(
            'blocked_error_message' => '<strong>ERROR</strong>: This account is blocked.',
            'pending_error_message' => '<strong>ERROR</strong>: This account is pending approval.',
        );

        if (is_multisite()) {
            add_blog_option(null, 'pp_extra_moderation', $moderation_settings);
        } else {
            add_option('pp_extra_moderation', $moderation_settings);
        }

    }


    /** Add a gender custom profile field */
    public static function insert_custom_profile_field()
    {
        global $wpdb;

        $wpdb->insert(
            $wpdb->base_prefix . 'pp_profile_fields',
            array(
                'label_name' => 'Gender',
                'field_key' => 'gender',
                'description' => 'Gender of a user',
                'type' => 'select',
                'options' => 'Male, Female',
            )
        );
        $wpdb->insert(
            $wpdb->base_prefix . 'pp_profile_fields',
            array(
                'label_name' => 'Country',
                'field_key' => 'country',
                'description' => 'The country you are from.',
                'type' => 'text'
            )
        );

    }
}