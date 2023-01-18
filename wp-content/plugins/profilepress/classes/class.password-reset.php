<?php
ob_start();

/**
 * Parent class that handles password reset
 *
 * Class ProfilePress_Password_Reset
 */
class ProfilePress_Password_Reset
{
    public static function is_ajax()
    {
        return defined('DOING_AJAX') && DOING_AJAX;
    }

    /**
     * Change the password reset title.
     *
     * @param $title string filter title
     *
     * @return string
     */
    public static function reset_email_title()
    {
        $db_settings = pp_db_data();

        $default_title = sprintf(__('[%s] Password Reset'), pp_site_title());
        $title = apply_filters(
            'pp_retrieve_password_title',
            isset($db_settings['password_reset_subject']) ? $db_settings['password_reset_subject'] : $default_title
        );

        return $title;
    }

    /** @return mixed Raw password reset message from plugin DB settings */
    public static function raw_reset_email_message()
    {
        $db_settings = pp_db_data();

        $default_message = <<<HTML
Someone requested to reset the password for the following account:

Username: {{username}}

If this was a mistake, just ignore this email and nothing will happen.

To reset your password, click the link below:

{{password_reset_link}}


Cheers.
HTML;
        return apply_filters(
            'pp_retrieve_password_message',
            isset($db_settings['password_reset_message']) ? $db_settings['password_reset_message'] : $default_message
        );
    }


    /**
     * Return the formatted password reset message.
     *
     * @param string $user_login username
     * @param string $key activation key
     * @param string $user_email user email address
     *
     * @return string
     */
    public static function formatted_reset_email_message($user_login, $key, $user_email)
    {
        $raw_message = self::raw_reset_email_message();

        $search = apply_filters('pp_password_reset_placeholder_search',
            array(
                '{{username}}',
                '{{password_reset_link}}',
                '{{email}}'
            ),
            $user_login, $key, $user_email
        );

        $replace = apply_filters('pp_password_reset_placeholder_replace',
            array(
                $user_login,
                pp_get_do_password_reset_url($user_login, $key),
                $user_email
            ),
            $user_login, $key, $user_email
        );

        $formatted_message = str_replace($search, $replace, $raw_message);

        return htmlspecialchars_decode($formatted_message);
    }

    /**
     * Callback function for filter
     *
     * @param $message string default reset message supply by password reset form
     * @param $key string activation key supplied by filter
     *
     * @return string formatted message for use by the password reset form
     */
    public static function reset_email_message($key)
    {
        // perform the rituals below to get the username/email entered into the password reset form
        if (strpos($_POST['user_login'], '@')) {
            $user_data = get_user_by('email', trim($_POST['user_login']));
        } else {
            $user_data = get_user_by('login', trim($_POST['user_login']));
        }

        $user_email = $user_data->user_email;

        // Alas! we got the damn user_login
        $user_login = $user_data->user_login;

        $message = self::formatted_reset_email_message($user_login, $key, $user_email);

        return $message;

    }

    /**
     * Validate password reset form
     *
     * @param int $id
     * @param null|int $is_melange is the validation being called by a melange?
     *
     * @return string
     */
    public static function validate_password_reset_form($id, $is_melange = null)
    {
        // if password reset form have been submitted process it

        // filter to change password reset submit button name to avoid validation for forms on same page
        $submit_name = apply_filters('pp_password_reset_submit_name', 'password_reset_submit', $id);
        if (isset($_POST[$submit_name])) {
            $password_reset = self::password_reset_status($_POST['user_login'], $id, $is_melange);
        }

        // display form generated messages
        if (!empty($password_reset)) {
            $password_reset_status = html_entity_decode($password_reset);
        } else {
            $password_reset_status = '';
        }

        return $password_reset_status;

    }

    /**
     * Does the heavy lifting of resetting password
     *
     * @param $user_login string username or email
     *
     * @return bool|WP_Error
     */
    public static function retrieve_password_func($user_login)
    {
        $_POST['user_login'] = $user_login;

        global $wpdb, $wp_hasher;

        $errors = new WP_Error();

        if (empty($_POST['user_login'])) {
            $errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or e-mail address.'));
        } elseif (strpos($_POST['user_login'], '@')) {
            $user_data = get_user_by('email', trim($_POST['user_login']));
            if (empty($user_data)) {
                $errors->add('invalid_email',
                    __('<strong>ERROR</strong>: There is no user registered with that email address.'));
            }
        } else {
            $login = trim($_POST['user_login']);
            $user_data = get_user_by('login', $login);
        }

        /**
         * Fires before errors are returned from a password reset request.
         *
         * @since 2.1.0
         */
        do_action('lostpassword_post');

        if ($errors->get_error_code()) {
            return $errors;
        }

        if (!$user_data) {
            $errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or e-mail.'));

            return $errors;
        }

        // Redefining user_login ensures we return the right case in the email.
        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;

        /**
         * Fires before a new password is retrieved.
         *
         * @since 1.5.0
         * @deprecated 1.5.1 Misspelled. Use 'retrieve_password' hook instead.
         *
         * @param string $user_login The user login name.
         */
        do_action('retreive_password', $user_login);

        /**
         * Fires before a new password is retrieved.
         *
         * @since 1.5.1
         *
         * @param string $user_login The user login name.
         */
        do_action('retrieve_password', $user_login);

        /**
         * Filter whether to allow a password to be reset.
         *
         * @since 2.7.0
         *
         * @param bool true           Whether to allow the password to be reset. Default true.
         * @param int $user_data ->ID The ID of the user attempting to reset a password.
         */
        $allow = apply_filters('allow_password_reset', true, $user_data->ID);

        if (!$allow) {
            return new WP_Error('no_password_reset', __('Password reset is not allowed for this user'));
        } elseif (is_wp_error($allow)) {
            return $allow;
        }

        // Generate something random for a password reset key.
        $key = wp_generate_password(20, false);

        /**
         * Fires when a password reset key is generated.
         *
         * @since 2.5.0
         *
         * @param string $user_login The username for the user.
         * @param string $key The generated password reset key.
         */
        do_action('retrieve_password_key', $user_login, $key);

        // Now insert the key, hashed, into the DB.
        if (empty($wp_hasher)) {
            require_once ABSPATH . WPINC . '/class-phpass.php';
            $wp_hasher = new PasswordHash(8, true);
        }

        // fix backward compatibility for WordPress prior to 4.3
        if (pp_get_wordpress_version() < '4.3') {
            $hashed = $wp_hasher->HashPassword($key);
        } else {
            $hashed = time() . ':' . $wp_hasher->HashPassword($key);
        }

        $wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $user_login));

        $title = self::reset_email_title();

        $message = self::reset_email_message($key);

        /** BEGIN custom added code to change the mail header name and email */

        $db_settings_data = get_option('pp_settings_data');

        // sender name
        if (empty($db_settings_data['password_reset_sender_name']) || !isset($db_settings_data['password_reset_sender_name'])) {
            $password_reset_sender_name = get_option('blogname');
        } else {
            $password_reset_sender_name = $db_settings_data['password_reset_sender_name'];
        }

        // sender email
        if (empty($db_settings_data['password_reset_sender_email']) || !isset($db_settings_data['password_reset_sender_email'])) {
            $password_reset_sender_email = pp_admin_email();
        } else {
            $password_reset_sender_email = $db_settings_data['password_reset_sender_email'];
        }

        if (!is_email($password_reset_sender_email)) {
            return new WP_Error('invalid_reset_sender_email', __('Sender email address is invalid. Contact website administrator.'));
        }

        // content type
        if (empty($db_settings_data['password_reset_type']) || !isset($db_settings_data['password_reset_type'])) {
            $password_reset_type = 'text/plain';
        } else {
            $password_reset_type = $db_settings_data['password_reset_type'];
        }

        $headers = array();
        $headers[] = "From: $password_reset_sender_name <$password_reset_sender_email>";
        $headers[] = "Content-type: $password_reset_type";

        /** END custom added code to change the mail header name and email */
        if ($message && !wp_mail($user_email, wp_specialchars_decode($title), $message, $headers)) {
            wp_die(__('The e-mail could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function.'));
        }

        return true;
    }

    /**
     * The error or success message received from the retrieve_password_func
     *
     * @param string $user_login username/email
     * @param int|null $form_id password_reset id
     * @param string $is_melange is this melange
     *
     * @return string|array
     */
    public static function password_reset_status($user_login, $form_id = null, $is_melange = '')
    {
        /**
         * Fires before password reset is processed
         *
         * @param string $user_login username/email
         * @param int $form_id password reset builder ID
         */
        do_action('pp_before_password_reset', $user_login, $form_id);

        /** filter to validate additional password field */
        $errors = '';
        $password_reset_validation = apply_filters('pp_password_reset_validation', $errors, $form_id);

        // if the action is contain WP_Error message, set the password response to the object
        // for reuse further down to return its WP_Error message
        if (is_wp_error($password_reset_validation) && $password_reset_validation->get_error_code() != '') {
            $password_reset_response = $password_reset_validation;
        } else {
            $password_reset_response = self::retrieve_password_func($user_login);
        }

        /**
         * Fires after password reset is processed
         *
         * @param string $user_login username/email
         * @param string $password_reset_response password reset response message
         */
        do_action('pp_after_password_reset', $form_id, $user_login, $password_reset_response);

        // filter for the css class of the error message
        $password_reset_status_css_class = apply_filters('pp_password_reset_error_css_class', 'profilepress-reset-status', $form_id);

        // return the response of the password reset process
        if (is_wp_error($password_reset_response)) {

            return '<div class="' . $password_reset_status_css_class . '">' . $password_reset_response->get_error_message() . '</div>';
        } else {

            do_action('pp_password_reset_success', $form_id, $user_login, $password_reset_response);

            if ($is_melange) {

                $message_on_password_reset = PROFILEPRESS_sql::get_db_success_melange($form_id, 'password_reset_msg');
            } else {
                $message_on_password_reset = PROFILEPRESS_sql::get_db_success_password_reset($form_id);
            }

            // remove the username/email address after password reset ish is successful.
            unset($_POST['user_login']);

            $success_msg = isset($message_on_password_reset) ? $message_on_password_reset : apply_filters('pp_default_password_reset_text', '<h4>' . __('Check your e-mail for further instruction', 'profilepress') . '</h4>');

            if (self::is_ajax()) {
                return array($success_msg);
            }

            return $success_msg;
        }
    }

    /**
     * Resets the user's password if the password reset form was submitted.
     */
    public static function do_password_reset()
    {
        if (isset($_REQUEST['reset_password']) && isset($_REQUEST['reset_key']) && isset($_REQUEST['reset_login'])) {
            $reset_key = $_REQUEST['reset_key'];
            $reset_login = $_REQUEST['reset_login'];

            $user = check_password_reset_key($reset_key, $reset_login);

            if (is_wp_error($user)) {
                if ($user->get_error_code() === 'expired_key') {
                    if (self::is_ajax()) {
                        return self::do_password_reset_status_messages('expired_key');
                    }
                    wp_redirect(pp_password_reset_url() . '?login=expiredkey');
                } else {
                    if (self::is_ajax()) {
                        return self::do_password_reset_status_messages('expired_key');
                    }
                    wp_redirect(pp_password_reset_url() . '?login=invalidkey');
                }
                exit;
            }

            if (isset($_POST['password1']) && isset($_POST['password2'])) {
                if ($_POST['password1'] != $_POST['password2']) {

                    if (self::is_ajax()) {
                        return self::do_password_reset_status_messages('password_mismatch');
                    }
                    // Passwords don't match
                    $redirect_url = add_query_arg(
                        array(
                            'key' => $reset_key,
                            'login' => $reset_login,
                            'error' => 'password_mismatch'
                        ),
                        pp_password_reset_url()
                    );

                    wp_redirect($redirect_url);
                    exit;
                }

                if (empty($_POST['password1'])) {

                    if (self::is_ajax()) {
                        return self::do_password_reset_status_messages('password_empty');
                    }
                    // Empty password
                    $redirect_url = add_query_arg(
                        array(
                            'key' => $reset_key,
                            'login' => $reset_login,
                            'error' => 'password_empty'
                        ),
                        pp_password_reset_url()
                    );

                    wp_redirect($redirect_url);
                    exit;
                }

                if (isset($_POST['pp_enforce_password_meter']) && ($_POST['pp_enforce_password_meter'] != '1')) {
                    if (self::is_ajax()) {
                        return self::do_password_reset_status_messages('weak_password');
                    }
                    // Empty password
                    $redirect_url = add_query_arg(
                        array(
                            'key' => $reset_key,
                            'login' => $reset_login,
                            'error' => 'weak_password'
                        ),
                        pp_password_reset_url()
                    );

                    wp_redirect($redirect_url);
                    exit;
                }

                // Everything is cool now.
                reset_password($user, $_POST['password1']);

                if (self::is_ajax()) {
                    return array(self::do_password_reset_status_messages('changed'));
                }

                wp_redirect(pp_password_reset_redirect());
                exit;
            } else {
                if (self::is_ajax()) {
                    return self::do_password_reset_status_messages('invalid');
                }

                $redirect_url = add_query_arg(
                    array(
                        'key' => $reset_key,
                        'login' => $reset_login,
                        'error' => 'invalid'
                    ),
                    pp_password_reset_url()
                );

                wp_redirect($redirect_url);
                exit;
            }

            // be double sure the function is exited :D
            exit;
        }
    }

    /**
     * Status messages for do password reset.
     *
     * @param string $type
     *
     * @return string
     */
    public static function do_password_reset_status_messages($type)
    {
        switch ($type) {
            case 'expiredkey':
            case 'invalidkey':
                $error = apply_filters('pp_password_reset_invalid',
                    __('Sorry, that key appears to be invalid.', 'profilepress'));
                break;
            case 'password_mismatch':
                $error = apply_filters('pp_password_mismatch', __('Passwords do not match.', 'profilepress'));
                break;
            case 'password_empty':
                $error = apply_filters('pp_password_empty', __('Please enter your password.', 'profilepress'));
                break;
            case 'invalid':
                $error = apply_filters('pp_password_reset_invalid', __('Sorry, that key does not appear to be valid.', 'profilepress'));
                break;
            case 'weak_password':
                $error = apply_filters('pp_weak_password', __('Password is not strong enough.', 'profilepress'));
                break;
            case 'changed':
                $error = apply_filters(
                    'pp_password_changed',
                    __(
                        'Your password has been reset.', 'profilepress') .
                    ' <a href="' . pp_login_url() . '">' .
                    __('Log in', 'profilepress') . '</a>'
                );
                break;
            default:
                $error = __('Unexpected error. Please try again.', 'profilepress');
        }

        return '<div class="profilepress-reset-status">' . $error . '</div>';
    }

    /**
     * Do password reset.
     *
     * @return string
     */
    public static function do_password_reset_status()
    {

        if (isset($_REQUEST['login']) && !empty($_REQUEST['login'])) {

            switch ($_REQUEST['login']) {
                case 'expiredkey':
                case 'invalidkey':
                    $error = self::do_password_reset_status_messages('invalidkey');
                    break;
            }
        }

        if (isset($_REQUEST['error']) && !empty($_REQUEST['error'])) {

            switch ($_REQUEST['error']) {
                case 'password_mismatch':
                    $error = self::do_password_reset_status_messages('password_mismatch');
                    break;
                case 'password_empty':
                    $error = self::do_password_reset_status_messages('password_empty');
                    break;
                case 'invalid':
                    $error = self::do_password_reset_status_messages('invalid');
                    break;
                case 'weak_password':
                    $error = self::do_password_reset_status_messages('weak_password');
                    break;
            }
        }

        if (isset($_REQUEST['password']) && 'changed' == $_REQUEST['password']) {
            $error = self::do_password_reset_status_messages('changed');
        }

        if (!empty($error)) {
            return $error;
        }

    }
}