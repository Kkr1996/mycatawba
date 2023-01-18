<?php

class PP_Password_Reset_Captcha extends PP_ProfilePress_Recaptcha
{

    public static function initialize()
    {

        if (self::$recaptcha_activated == 'active') {
            add_filter('pp_password_reset_validation', array(__CLASS__, 'recaptcha_password_reset_form'), 10, 2);
        }

    }

    public static function recaptcha_password_reset_form($errors, $form_id)
    {
        if (isset($_POST['pp-is-recaptcha-active']) && $_POST['pp-is-recaptcha-active'] == 'true') {
            if (isset($_POST['g-recaptcha-response']) && !self::captcha_verification()) {
                $errors = new WP_Error('failed_captcha_verification', self::$error_message);
            }
        }

        return $errors;
    }
}


PP_Password_Reset_Captcha::initialize();