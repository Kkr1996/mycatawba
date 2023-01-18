<?php

/**
 * Parse the individual profile shortcode of "Edit profile" builder
 */
class PP_Parent_Edit_Profile_Shortcode_Parser
{

    /** Constructor */
    public function __construct()
    {
        add_shortcode('profilepress-edit-profile', array($this, 'profilepress_edit_profile_parser'));
    }

    /**
     * Returns the page with user-edit shortcode
     * @return string PAGE ID
     */
    static public function page_with_edit_profile_shortcode()
    {
        $db_data = get_option('pp_settings_data');

        return absint($db_data['edit_user_profile_url']);
    }

    /** Get the current user id */
    public static function get_current_user_id()
    {
        $current_user = wp_get_current_user();

        return $current_user->ID;
    }

    /**
     * Shortcode callback function to parse the shortcode.
     *
     * @param $atts
     *
     * @return string
     */
    public function profilepress_edit_profile_parser($atts)
    {
        // get "edit user profile" builder id
        $id = absint($atts['id']);
        $redirect = isset($atts['redirect']) ? esc_url($atts['redirect']) : '';

        $response = ProfilePress_Edit_Profile::validate_form($id, false, $redirect);
        $response = apply_filters('pp_edit_profile_status', $response, $id);

        $attribution_start = apply_filters('pp_hide_attribution', '<!-- This edit profile form is built and powered by ProfilePress WordPress plugin - https://profilepress.net -->' . "\r\n");
        $attribution_end = apply_filters('pp_hide_attribution', "\r\n" . '<!-- / ProfilePress WordPress plugin. -->' . "\r\n");
        $css = self::get_edit_profile_css($id);

        // call the registration structure/design
        return apply_filters('pp_edit_profile_form', $attribution_start . $css . $response . self::get_edit_profile_structure($id, $redirect) . $attribution_end, $id);
    }


    /**
     * Get the registration structure from the database
     *
     * @param int $id
     * @param string $redirect URL to redirect to after edit profile.
     *
     * @return string
     */
    public static function get_edit_profile_structure($id, $redirect = '')
    {
        $edit_profile_structure = do_shortcode(PROFILEPRESS_sql::get_a_builder_structure('edit_user_profile', $id));
        $edit_profile_structure .= "<input type='hidden' name='editprofile_form_id' value='$id'>";
        if (!empty($redirect)) {
            $edit_profile_structure .= "<input type='hidden' name='editprofile_redirect' value='$redirect'>";
        }

        $form_tag = "<form data-pp-form-submit=\"editprofile\" id='pp_edit_profile_$id' method='post' enctype='multipart/form-data'" . apply_filters('pp_password_reset_form_tag', '', $id) . ">";

        return $form_tag . $edit_profile_structure . '</form>';

    }


    /**
     * Get the CSS stylesheet for the ID registration
     *
     * @return mixed
     */

    public static function get_edit_profile_css($edit_profile_builder_id)
    {
        // if no id is set return
        if (!isset($edit_profile_builder_id)) {
            return;
        }

        $edit_profile_css = PROFILEPRESS_sql::get_a_builder_css('edit_user_profile', $edit_profile_builder_id);

        return "<style type=\"text/css\">\r\n $edit_profile_css \r\n</style>";
    }


    /** Singleton instance */
    static public function get_instance()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new self;
        }

        return $instance;
    }
}

PP_Parent_Edit_Profile_Shortcode_Parser::get_instance();