<?php

class PP_Login_Captcha extends PP_ProfilePress_Recaptcha
{

    public static function initialize()
    {

        if (self::$recaptcha_activated == 'active') {

            /* The built in "pp_login_validation" filter was used instead of core "authentication"
             * Because the latter affects auto login when recaptcha is activated in registration form
             * That is, authenticate will try and validate the captcha of the registraion form
             * Before the "auto login" class log in the user
             */
            add_filter('pp_login_validation', array(__CLASS__, 'add_recaptcha_login_form'), 30, 2);
        }

    }

    /**
     * Callback function to add the reCAPTCHA to the login form
     *
     * @param $login_errors
     * @param $login_form_id
     *
     * @return WP_Error
     */
    public static function add_recaptcha_login_form($login_errors, $login_form_id)
    {
        if (isset($_POST['pp-is-recaptcha-active']) && $_POST['pp-is-recaptcha-active'] == 'true') {

            if (isset($_POST['g-recaptcha-response']) && !self::captcha_verification()) {
                $login_errors = new WP_Error('failed_login_captcha_verification', self::$error_message);
            }
        }

        return $login_errors;
    }
}

PP_Login_Captcha::initialize();