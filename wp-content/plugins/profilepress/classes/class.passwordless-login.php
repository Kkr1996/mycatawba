<?php

class PP_Passwordless_Login
{
    /** @var object singleton class instance */
    public static $instance;

    /** @var string user object */
    public static $user_obj;

    /** @var string user's username */
    public static $username;

    /** @var string user's email */
    public static $user_email;

    /** @var string user's ID */
    public static $user_id;

    /** @var array passwordless db data */
    private static $db_settings_data;

    /** Class constructor */
    public function __construct()
    {

        $db_settings_data       = get_option('pp_extra_passwordless');
        self::$db_settings_data = $db_settings_data;

        if ( ! empty($db_settings_data['activated']) && $db_settings_data['activated'] == 'active') {
            if ( ! apply_filters('pp_disable_login_passwordless', false)) {
                add_filter('authenticate', array($this, 'validate_user_login'), 80, 3);
            }
            add_action('init', array($this, 'validate_one_time_login_url'));
            add_filter('user_row_actions', array($this, 'resend_passwordless_login_link'), 10, 2);
            add_action('load-users.php', array($this, 'act_on_send_passworldess_request'));
            add_action('admin_notices', array($this, 'admin_notices'));
        }

        if (isset($db_settings_data['expires']) && ! empty($db_settings_data['expires'])) {
            // Private internal filter hook.
            add_filter('_pp_passwordless_expiration', array($this, 'modify_expiration'));
        }
    }

    /**
     * Callback function for authenticate hook.
     *
     * Validate the user supplied info.
     *
     * @param string $user
     * @param string $username
     * @param string $password
     *
     * @return null|WP_Error
     */
    public function validate_user_login($user = '', $username = '', $password = '')
    {
        do_action('pp_before_passwordless_login_validation', $user, $username, $password);

        $no_user_error_msg = apply_filters('pp_passwordless_no_user',
            __('Sorry, no user was found with that username or email.', 'profilepress'));

        if (is_email($username)) {

            $user = get_user_by('email', $username);

            if (false !== $user) {

                self::$user_obj = $user;

                // save the user's user ID
                self::$user_id = $user->ID;

                // save the user's email
                self::$user_email = $user->user_email;

                // save the user's username
                self::$username = $user->user_login;

                // if "pp_skip_passwordless_login" filter return true, passwordless login is skipped.
                if ((self::is_disable_for_admin() && is_super_admin(self::$user_id))
                    || apply_filters('pp_skip_passwordless_login', false, self::$username, $password)
                ) {
                    return wp_authenticate_username_password(null, self::$username, $password);
                }

                // send the one-time password login url
                $send_mail = $this->send_otp();

                if (is_wp_error($send_mail)) {
                    return $send_mail;
                } else {
                    return new WP_Error('otp_sent', self::_get_success_message());
                }
            } else {
                return new WP_Error('user_not_found', $no_user_error_msg);
            }

        } elseif (username_exists($username)) {
            $user           = get_user_by('login', $username);
            self::$user_obj = $user;

            // save the user's user ID
            self::$user_id = $user->ID;

            // save the user's email
            self::$user_email = $user->user_email;


            // save the user's username
            self::$username = $user->user_login;

            // if "pp_skip_passwordless_login" filter return true, passwordless login is skipped.
            if ((self::is_disable_for_admin() && is_super_admin(self::$user_id))
                || apply_filters('pp_skip_passwordless_login', false, $username, $password)
            ) {
                return wp_authenticate_username_password(null, $username, $password);
            }

            // send the one-time password login url
            $send_mail = $this->send_otp();

            if (is_wp_error($send_mail)) {
                return $send_mail;
            } else {
                return new WP_Error('otp_sent', self::_get_success_message());
            }
        } else {
            return new WP_Error('user_not_found', $no_user_error_msg);
        }

        return $user;
    }


    /**
     * This function is used by login form explicitly marked as passwordless login.
     *
     * Validate the user supplied info.
     *
     * @param string $username username or email. whatever is supplied.
     *
     * @return WP_Error
     */
    public function validate_passwordless_login_form($username)
    {
        do_action('pp_before_single_passwordless_login_validation', $username);

        if ( ! isset($username) || empty($username)) {
            return;
        }
        $no_user_error_msg = apply_filters('pp_passwordless_no_user',
            __('Sorry, no user was found with that username or email.', 'profilepress'));

        if (is_email($username)) {

            $user = get_user_by('email', $username);

            if (false !== $user) {

                self::$user_obj = $user;

                // save the user's user ID
                self::$user_id = $user->ID;

                // save the user's email
                self::$user_email = $user->user_email;

                // save the user's username
                self::$username = $user->user_login;

                // send the one-time password login url
                $send_mail = $this->send_otp();

                if (is_wp_error($send_mail)) {
                    $status = $send_mail;
                } else {
                    $status = new WP_Error('otp_sent', self::_get_success_message());
                }
            } else {
                $status = new WP_Error('user_not_found', $no_user_error_msg);
            }

        } elseif (username_exists($username)) {
            $user           = get_user_by('login', $username);
            self::$user_obj = $user;

            // save the user's user ID
            self::$user_id = $user->ID;

            // save the user's email
            self::$user_email = $user->user_email;


            // save the user's username
            self::$username = $user->user_login;

            // send the one-time password login url
            $send_mail = $this->send_otp();

            if (is_wp_error($send_mail)) {
                $status = $send_mail;
            } else {
                $status = new WP_Error('otp_sent', self::_get_success_message());
            }
        } else {
            $status = new WP_Error('user_not_found', $no_user_error_msg);
        }

        return $status->get_error_message();
    }

    /**
     * Generate the one-time login url
     *
     * @return string|WP_Error
     */
    private function _generate_ot_url()
    {

        $filter = (int)apply_filters('_pp_passwordless_expiration', '10');

        $expiration = time() + 60 * $filter;

        $token_length = apply_filters('pp_passwordless_token_length', 20);
        $token        = wp_generate_password($token_length, false);

        $insert_data = PROFILEPRESS_sql::passwordless_insert_record(self::$user_id, $token, $expiration);

        if ($insert_data === false) {
            return new WP_Error('db_insert_failed', __('Unexpected error. Please try again', 'profilepress'));
        } else {
            return add_query_arg(
                array(
                    'uid'   => self::$user_id,
                    'token' => $token,
                ),
                wp_login_url()
            );
        }
    }


    /**
     * Send the one-time login email
     *
     * @return WP_Error|mixed
     */
    public function send_otp()
    {
        if ( ! apply_filters('pp_passwordless_should_send_otp', true, self::$user_obj)) {
            return;
        }

        $ot_url = $this->_generate_ot_url();
        if (is_wp_error($ot_url)) {
            return $ot_url;
        }

        $headers = 'From: ' . self::_get_sender_name() . ' <' . self::_get_sender_email() . '>' . "\r\n";

        // get the blog title or the url if no title exist.
        $blog_title = pp_site_title();

        $mail_subject = apply_filters('pp_passwordless_subject', self::_get_email_subject());

        $mail_content = apply_filters('pp_passwordless_message', self::message_content(self::$username, $blog_title, $ot_url));

        // if content-type is HTML
        if (self::_get_content_type() == 'text/html') {
            add_filter('wp_mail_content_type', 'pp_mail_content_type_html');

            $send_mail = wp_mail(self::$user_email, $mail_subject, $mail_content, $headers);

            remove_filter('wp_mail_content_type', 'pp_mail_content_type_html');
        } else {
            $send_mail = wp_mail(self::$user_email, $mail_subject, $mail_content, $headers);
        }

        if ( ! $send_mail) {
            return new WP_Error('mail_sending_failed',
                apply_filters('pp_passwordless_email_fail', 'Error sending the email, Please try again.'));
        }
    }


    /**
     * Passwordless email content/message.
     *
     * @param string $blog_title
     * @param string $ot_url one-time password
     *
     * @return string
     */
    public static function message_content($username, $blog_title, $ot_url)
    {

        if ( ! empty(self::$db_settings_data['message'])) {
            $search = apply_filters('pp_passwordless_login_placeholder_search', array(
                '{{username}}',
                '{{passwordless_link}}',
                '{{first_name}}',
                '{{last_name}}',
            ));

            $replace = apply_filters('pp_passwordless_login_placeholder_replace', array(
                $username,
                $ot_url,
                self::$user_obj->first_name,
                self::$user_obj->last_name,
            ));

            return apply_filters(
                'pp_passwordless_login_message',
                str_replace($search, $replace, self::$db_settings_data['message']),
                self::$user_obj
            );
        } else {
            return <<<MESSAGE
Hi $username, below is your one-time login url to $blog_title;

$ot_url

Regards.
MESSAGE;

        }
    }

    /** make email content type html  - a callback function */
    public static function mail_content_type_html()
    {
        return 'text/html';
    }


    /**
     * Callback function to modify expiration time.
     *
     * @param int $int
     *
     * @return mixed
     */
    public function modify_expiration($int)
    {
        return self::$db_settings_data['expires'];
    }

    /** Get sender name from DB */
    private static function _get_sender_name()
    {
        return ! empty(self::$db_settings_data['sender_name']) ? self::$db_settings_data['sender_name'] : pp_site_title();
    }

    /** Return the error message when OTP url is invalid */
    private function _get_invalid_error()
    {
        return ! empty(self::$db_settings_data['invalid_error']) ? self::$db_settings_data['invalid_error'] : 'One-time login expired or invalid. <a href="' . site_url() . '">Return home</a>.';
    }

    /** Return the error message when OTP url is invalid */
    private static function _get_success_message()
    {
        return ! empty(self::$db_settings_data['success_message']) ? self::$db_settings_data['success_message'] : 'One-time login URL sent successfully to your email.';
    }


    /** Get sender email from DB */
    private static function _get_sender_email()
    {
        return ! empty(self::$db_settings_data['sender_email']) ? self::$db_settings_data['sender_email'] : pp_admin_email();
    }


    /** Get sender email from DB */
    private static function _get_email_subject()
    {
        $blog_title = pp_site_title();

        return ! empty(self::$db_settings_data['subject'])
            ? self::$db_settings_data['subject']
            : __("One time login to $blog_title",
                'profilepress');
    }

    /** Get email content-type */
    private static function _get_content_type()
    {
        return self::$db_settings_data['type'];
    }


    /**
     * Validate one-time login url
     */
    public function validate_one_time_login_url()
    {
        if (isset($_GET['token']) && isset($_GET['uid'])) {

            $uid   = sanitize_key($_GET['uid']);
            $token = esc_attr($_GET['token']);

            $time       = time();
            $db_token   = PROFILEPRESS_sql::passwordless_get_user_token($uid);
            $db_expires = (int)PROFILEPRESS_sql::passwordless_get_expiration($uid);

            if (pp_user_id_exist($uid) && $token == $db_token && $time < $db_expires) {

                $secure_cookie = '';
                // If the user wants ssl but the session is not ssl, force a secure cookie.
                if ( ! force_ssl_admin()) {
                    if (get_user_option('use_ssl', $uid)) {
                        $secure_cookie = true;
                        force_ssl_admin(true);
                    }
                }

                if (defined('FORCE_SSL_ADMIN') && FORCE_SSL_ADMIN === true) {
                    $secure_cookie = true;
                }

                /**
                 * Filter to enable remember me for passwordless login.
                 */
                $remember_me = apply_filters('pp_passwordless_login_remember', false);

                wp_set_auth_cookie($uid, $remember_me, $secure_cookie);
                $is_delete = apply_filters('pp_delete_passwordless_record', true, $uid);
                if ($is_delete) {
                    PROFILEPRESS_sql::passwordless_delete_record($uid);
                }
                wp_redirect(pp_login_redirect());
                exit;
            } else {
                wp_die(apply_filters('pp_passwordless_invalid', self::_get_invalid_error()));
            }
        }
    }


    /**
     * User action link to resend passwordless login.
     *
     * @param array $actions
     * @param object $user_object
     *
     * @return mixed
     */
    public function resend_passwordless_login_link($actions, $user_object)
    {
        $current_user = wp_get_current_user();

        // do not display button for admin
        if ($current_user->ID != $user_object->ID) {

            // the unblock button
            $actions['send_passwordless'] = sprintf('<a href="%1$s">%2$s</a>',
                esc_url(
                    add_query_arg(
                        array(
                            'action'   => 'send_passwordless',
                            'user'     => $user_object->ID,
                            '_wpnonce' => wp_create_nonce('send-passwordless'),
                        ),
                        admin_url('users.php')
                    )
                ),
                __('Send passwordless login', 'profilepress')
            );
        }

        return $actions;
    }


    /**
     * Check if passwordless login has been disabled for administrators.
     *
     * @return bool
     */
    public static function is_disable_for_admin()
    {
        return isset(self::$db_settings_data['disable_admin']) && self::$db_settings_data['disable_admin'] == 'active';
    }

    public function act_on_send_passworldess_request()
    {
        $user_id = isset($_GET['user']) ? absint($_GET['user']) : '';

        if (isset($_GET['action'])) {

            if ('send_passwordless' == $_GET['action'] && check_admin_referer('send-passwordless')) {

                $username = pp_get_username_by_id($user_id);

                $this->validate_user_login(null, $username, null);

                wp_redirect(add_query_arg('update', 'send_passwordless', admin_url('users.php')));
                exit;
            }

        }
    }

    /** Add admin notice */
    public function admin_notices()
    {

        if (isset($_GET['update'])) {

            if ($_GET['update'] == 'send_passwordless') {

                echo '<div class="updated">';
                echo '<p>';
                _e('One-time passwordless login sent to user.', 'profilepress');
                echo '</p>';
                echo '</div>';
            }
        }
    }

    /**
     * Singleton poop
     *
     * @return PP_Passwordless_Login
     */
    public static function get_instance()
    {
        if ( ! isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

}

PP_Passwordless_Login::get_instance();