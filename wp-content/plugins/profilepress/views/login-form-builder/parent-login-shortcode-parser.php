<?php

class PP_Parent_Login_Shortcode_Parser
{

    /** login error status     */
    private $login_status;

    /** Constructor */
    public function __construct()
    {
        add_shortcode('profilepress-login', array($this, 'profilepress_login_parser'));
    }

    /** Parse login form */
    public function profilepress_login_parser($atts)
    {

        $atts = shortcode_atts(
            array(
                'id'       => '',
                'redirect' => '',
            ),
            $atts
        );

        // get login builder id
        $id       = absint($atts['id']);
        $redirect = ! empty($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : $atts['redirect'];
        $redirect = esc_url_raw($redirect);

        if (pp_is_login_passwordless($id)) {
            $instance = new PP_Passwordless_Login;
            // deactivate all authenticate hooks
            remove_all_filters('authenticate');
            $login_error = $instance->validate_passwordless_login_form(sanitize_text_field(@$_POST['login_username']));
        } else {
            $login_error = ProfilePress_Login_Auth::credentials_validation($id, $redirect);
        }

        // the filter pp_login_error_output is used to add custom error to login form.
        $login_error = ! empty($login_error) ? $login_error : apply_filters('pp_login_error_output', $login_error);
        $login_error = apply_filters('pp_login_error', $login_error);

        $attribution_start = apply_filters('pp_hide_attribution',
            '<!-- This login form is built and powered by ProfilePress WordPress plugin - https://profilepress.net -->' . "\r\n");
        $attribution_end   = apply_filters('pp_hide_attribution',
            "\r\n" . '<!-- / ProfilePress WordPress plugin. -->' . "\r\n");

        $css = self::get_login_css($id);

        return apply_filters(
            'pp_login_form',
            $attribution_start . $css . $login_error . $this->get_login_structure($id, $redirect) . $attribution_end,
            $id
        );
    }


    /**
     * Build the login structure
     *
     * @param int $id login builder ID
     * @param string $redirect url to redirect to. only used by ajax login form.
     *
     * @return string string login structure
     */
    public function get_login_structure($id, $redirect = '')
    {
        $login_structure = PROFILEPRESS_sql::get_a_builder_structure('login', $id);
        $login_structure = do_shortcode($login_structure);
        $referrer_url    = isset($_SERVER['HTTP_REFERER']) ? esc_url_raw($_SERVER['HTTP_REFERER']) : '';

        if ( ! empty($redirect)) {
            $login_structure .= "<input type='hidden' name='login_redirect' value='$redirect'>";
        }
        $login_structure .= "<input type='hidden' name='login_form_id' value='$id'>";
        $login_structure .= '<input type="hidden" name="pp_current_url" value="' . pp_get_current_url_query_string() . '">';
        $login_structure .= "<input type='hidden' name='login_referrer_page' value='$referrer_url'>";

        $form_tag = "<form data-pp-form-submit=\"login\" id='pp_login_$id' method=\"post\"" . apply_filters('pp_login_form_tag',
                '', $id) . ">";

        self::get_login_css($id);

        return $form_tag . $login_structure . '</form>';
    }


    /**
     * Get the CSS stylesheet for the ID login
     *
     * @return mixed
     */

    public static function get_login_css($login_builder_id)
    {

        // if no id is set return
        if ( ! isset($login_builder_id)) {
            return;
        }

        $login_css = PROFILEPRESS_sql::get_a_builder_css('login', $login_builder_id);

        // added a break-line to the style tag to keep it in a new line - viewed when viewing site source code
        return "\r\n <style type=\"text/css\">\r\n" . $login_css . "\r\n</style>\r\n
";
    }

    /** Singleton poop */
    static function get_instance()
    {
        static $instance = false;

        if ( ! $instance) {
            $instance = new self;
        }

        return $instance;
    }
}

PP_Parent_Login_Shortcode_Parser::get_instance();