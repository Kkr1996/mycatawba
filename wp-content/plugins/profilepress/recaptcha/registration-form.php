<?php

class PP_Registration_Captcha extends PP_ProfilePress_Recaptcha
{

    public static function initialize()
    {

        if (self::$recaptcha_activated == 'active') {
            add_filter('pp_registration_validation', array(__CLASS__, 'add_recaptcha_registration_form'), 10, 2);
        }

    }

    public static function add_recaptcha_registration_form($reg_errors, $form_id)
    {
        if (isset($_POST['pp-is-recaptcha-active']) && $_POST['pp-is-recaptcha-active'] == 'true') {
            if (!isset($_POST['g-recaptcha-response']) || !self::captcha_verification()) {
                $reg_errors = new WP_Error('failed_registration_captcha_verification', self::$error_message);
            }
        }

        return $reg_errors;
    }
}


PP_Registration_Captcha::initialize();