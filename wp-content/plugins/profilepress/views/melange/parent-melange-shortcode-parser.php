<?php
ob_start();

class PP_Parent_Melange_Shortcode_Parser extends PP_Parent_Password_Reset_Shortcode_Parser
{

    public function __construct()
    {
        add_shortcode('profilepress-melange', array($this, 'profilepress_melange_parser'));
    }

    /** Melange shortcode parser */
    public function profilepress_melange_parser($atts)
    {
        $atts = shortcode_atts(
            array(
                'id' => '',
                'redirect' => '',
            ),
            $atts
        );

        $melange_id = absint($atts['id']);
        $redirect = isset($atts['redirect']) ? esc_url_raw($atts['redirect']) : '';

        // do password reset handler function.
        ProfilePress_Password_Reset::do_password_reset();

        $login_response = ProfilePress_Login_Auth::credentials_validation($melange_id, $redirect);
        $login_response = !empty($login_response) ? $login_response : apply_filters('pp_login_error_output', $login_response);

        $registration_response = ProfilePress_Registration_Auth::validate_registration_form($melange_id, $redirect, true);

        $password_reset_response = ProfilePress_Password_Reset::validate_password_reset_form($melange_id, true);
        $edit_profile_response = ProfilePress_Edit_Profile::validate_form($melange_id, true, $redirect);

        $do_password_Reset_response = ProfilePress_Password_Reset::do_password_reset_status();

        $response = '';
        if (!empty($login_response)) {
            $response = $login_response;
        } elseif (!empty($registration_response)) {
            $response = $registration_response;
        } elseif (!empty($password_reset_response)) {
            $response = $password_reset_response;
        } elseif (!empty($edit_profile_response)) {
            $response = $edit_profile_response;
        } elseif (!empty($do_password_Reset_response)) {
            $response = $do_password_Reset_response;
        }

        // get melange id
        $id = absint($atts['id']);
        $attribution = apply_filters('pp_hide_attribution', '<!-- (Melange) form built with the ProfilePress WordPress plugin - https://profilepress.net -->' . "\r\n");
        $css = self::get_melange_css($id);

        // call the melange structure/design
        return $attribution . $css . $response . $this->get_melange_structure($id);

    }


    /**
     * Get the melange structure from the database
     *
     * @param int $id
     *
     * @return string
     */
    public function get_melange_structure($id)
    {
        if ('GET' == $_SERVER['REQUEST_METHOD'] && isset($_REQUEST['key']) && isset($_REQUEST['login'])) {

            // since we are getting the structure directly, no <form>
            $first_password_reset_form_id = PROFILEPRESS_sql::get_first_password_reset_form();
            $structure = '<form method="post">' . $this->get_password_reset_handler_structure($first_password_reset_form_id) . '</form>';
        } else {
            $structure = PROFILEPRESS_sql::get_a_builder_structure('melange', $id);
        }

        $structure .= "<input type='hidden' id='pp_melange_id' value='$id'>";

        return do_shortcode($structure);
    }


    /**
     * Get the CSS stylesheet for the ID melange
     *
     * @return mixed
     */

    public static function get_melange_css($melange_id)
    {

        // if no id is set return
        if (!isset($melange_id)) {
            return;
        }

        $melange_css = PROFILEPRESS_sql::get_a_builder_css('melange', $melange_id);

        return "<style type=\"text/css\">\r\n $melange_css \r\n</style>";
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

PP_Parent_Melange_Shortcode_Parser::get_instance();