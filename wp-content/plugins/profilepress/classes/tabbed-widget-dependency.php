<?php

/**
 * tabbed widget with login registration password reset functions
 */

require_once CLASSES . '/class.password-reset.php';

/** Tabbed widget dependency */
class Tabbed_widget_dependency
{

    /**
     * Wrapper function for login authentication
     *
     * @param $username         string      login username
     * @param $password         string      login password
     * @param $remember_login   bool        remember login
     *
     * @return string
     */
    static function login($username, $password, $remember_login)
    {
        $login_status = ProfilePress_Login_Auth::login_auth($username, $password, $remember_login);

        if (isset($login_status)) {
            return $login_status->get_error_message();
        } else {
            return __('Fail to login. Please try again', 'profilepress');
        }

    }


    /**
     * Process password reset
     *
     * @param $user_login
     *
     * @return bool|string
     */
    static function retrieve_password_process($user_login)
    {
        if (isset($_POST['tabbed_reset_passkey'])) {
            $password_reset = ProfilePress_Password_Reset::retrieve_password_func($user_login);

            if ( ! is_wp_error($password_reset)) {
                $success_msg = apply_filters('pp_password_reset_success_text', 'Check your e-mail for further instructions.');

                return __($success_msg, 'profilepress');
            } elseif (is_wp_error($password_reset)) {
                return $password_reset->get_error_message();

            } else {
                return __('Unexpected error, please try again', 'profilepress');
            }
        }
    }

    /**
     * Register the user -tabbed widget
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $auto_login_after_reg
     *
     * @return WP_Error|string
     */
    static function registration($username, $password, $email, $auto_login_after_reg)
    {

        $reg_errors = self::validate_tab_registration($username, $password, $email);

        //if there is an error, return the error message
        if (is_wp_error($reg_errors) && ($reg_errors->get_error_code())) {
            return $reg_errors->get_error_message();
        }


        // if no error was generated, proceed to sanitizing and registering the user
        $reg_errors_code = $reg_errors->get_error_code();
        if (empty($reg_errors_code)) {

            $user_data = array(
                'user_login' => esc_attr($username),
                'user_email' => esc_attr($email),
                'user_pass'  => esc_attr($password)
            );

            // register the user
            $register = wp_insert_user($user_data);

            /*
             * check if the wp_insert_user return a WP_Error object.
             * if true, return the error message else return a success message
             */
            if (is_wp_error($register)) {

                return $register->get_error_message();
            } else {

                add_user_meta($register, '_pp_signup_via', 'tab_widget');
                
                wp_new_user_notification($register, null, 'admin');

                // enable autologin
                //  if tabbed widget has auto-login on, enable it
                if ($auto_login_after_reg == 'on') {
                    PP_Auto_Login_After_Registration::initialize($username, $password);
                }

                return 'Registration complete. <a href="' . wp_login_url() . '">Log In</a>';
            }
        }
    }

    /**
     * @param $username
     * @param $password
     * @param $email
     *
     * @return mixed|void
     */
    public static function validate_tab_registration($username, $password, $email)
    {

        $reg_errors = new WP_Error;

        if (empty($username) || empty($password) || empty($email)) {
            $reg_errors->add('field', __('Required form field is missing', 'profilepress'));
        }

        /*
        if ( !validate_username( $username ) ) {
            $reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
        }

        if ( strlen( $password ) < 3 ) {
            $reg_errors->add( 'password', 'Password length must be greater than 3' );
        }
        */
        if ( ! is_email($email)) {
            $reg_errors->add('email_invalid', 'Email is not valid');
        }

        return apply_filters('validate_profilepress_tab_widget', $reg_errors, $username, $password, $email);
    }


}
