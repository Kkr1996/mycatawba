<?php

class PP_Edit_User_Profile_Captcha extends PP_ProfilePress_Recaptcha
{

    public static function initialize()
    {

        if (self::$recaptcha_activated == 'active') {
            add_filter('pp_edit_profile_validation', array(__CLASS__, 'recaptcha_edit_profile_form'));
        }

    }

    public static function recaptcha_edit_profile_form($validation_errors)
    {
        if (isset($_POST['pp-is-recaptcha-active']) && $_POST['pp-is-recaptcha-active'] == 'true') {
            if (isset($_POST['g-recaptcha-response']) && !self::captcha_verification()) {
                $validation_errors = new WP_Error('failed_captcha_verification', self::$error_message);
            }
        }

        return $validation_errors;
    }
}

PP_Edit_User_Profile_Captcha::initialize();