<?php

class PP_Parent_Registration_Shortcode_Parser
{

    function __construct()
    {

        add_shortcode('profilepress-registration', array(__CLASS__, 'profilepress_registration_parser'));
    }

    public static function profilepress_registration_parser($atts)
    {

        $atts = shortcode_atts(
            array(
                'id' => '',
                'redirect' => '',
                'no-login-redirect' => '',
            ),
            $atts
        );

        // get registration builder id
        $id = absint($atts['id']);
        $redirect = esc_url_raw($atts['redirect']);
        $no_login_redirect = esc_url_raw($atts['no-login-redirect']);
        $registration_structure = self::get_registration_structure($id, $redirect, $no_login_redirect);

        $registration_status = ProfilePress_Registration_Auth::validate_registration_form($id, $redirect, false, $no_login_redirect);
        $registration_status = apply_filters('pp_registration_status', $registration_status, $id, $redirect);

        $attribution_start = apply_filters('pp_hide_attribution', '<!-- This registration form is built and powered by ProfilePress WordPress plugin - https://profilepress.net -->' . "\r\n");
        $attribution_end = apply_filters('pp_hide_attribution', "\r\n" . '<!-- / ProfilePress WordPress plugin. -->' . "\r\n");

        $css = self::get_registration_css($id);

        // call the registration structure/design
        return apply_filters('pp_registration_form',
            $attribution_start . $css . $registration_status . $registration_structure . $attribution_end,
            $id, $registration_structure);
    }


    /**
     * Get the registration structure from the database
     *
     * @param int $id
     *
     * @return string
     */
    public static function get_registration_structure($id, $redirect, $no_login_redirect)
    {
        $referrer_url    = isset($_SERVER['HTTP_REFERER']) ? esc_url_raw($_SERVER['HTTP_REFERER']) : '';

        if (!get_option('users_can_register')) {
            return apply_filters('pp_registration_disabled_text',
                __('Registration is disabled in this site.', 'profilepress'));
        } else {
            $registration_structure = PROFILEPRESS_sql::get_a_builder_structure('registration', $id);
            $registration_structure = do_shortcode($registration_structure);

            $form_tag = "<form data-pp-form-submit=\"signup\" id='pp_registration_$id' method=\"post\" enctype=\"multipart/form-data\"" . apply_filters('pp_registration_form_tag', '', $id) . ">";
            if (!empty($redirect)) {
                $registration_structure .= "<input type='hidden' name='signup_redirect' value='$redirect'>";
            }
            if (!empty($no_login_redirect)) {
                $registration_structure .= "<input type='hidden' name='signup_no_login_redirect' value='$no_login_redirect'>";
            }

            $registration_structure .= '<input type="hidden" name="pp_current_url" value="' . pp_get_current_url_query_string() . '">';
            $registration_structure .= "<input type='hidden' name='signup_form_id' value='$id'>";
            $registration_structure .= "<input type='hidden' name='signup_referrer_page' value='$referrer_url'>";

            $registration_structure = apply_filters('pp_form_field_structure', $registration_structure, $id);

            return $form_tag . $registration_structure . '</form>';
        }
    }


    /**
     * Get the CSS stylesheet for the ID registration
     *
     * @return mixed
     */

    public static function get_registration_css($registration_builder_id)
    {

        // if no id is set return
        if (!isset($registration_builder_id)) {
            return;
        }

        $registration_css = PROFILEPRESS_sql::get_a_builder_css('registration', $registration_builder_id);

        return "<style type=\"text/css\">\r\n $registration_css \r\n</style>";
    }


    /** Singleton poop */
    static function get_instance()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new self;
        }

        return $instance;
    }
}

PP_Parent_Registration_Shortcode_Parser::get_instance();