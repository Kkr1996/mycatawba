<?php

add_action('init', 'pp_social_shebang');
add_action('init', 'pp_delete_social_login_users');
set_exception_handler('pp_global_exception_handler');


/** Hook the whole social login shebang to 'wp_loaded' action. */
function pp_social_shebang()
{
    $config = SOCIAL_LOGIN . '/config.php';
    try {
        if (isset($_GET['pp_social_login'])) {

            // WPEngine host compact.
            if (defined('WPE_PLUGIN_BASE') || defined('WPE_PLUGIN_VERSION')) {
                if (!is_user_logged_in()) {
                    add_filter('auth_cookie_expiration', 'pp_reduce_cookie_time');

                    $username = 'ppsoc' . wp_generate_password(4, false, false);
                    $id = pp_wp_create_user($username, $username . '@dummy.com', 'pp_dummy');

                    // set a transient to be used to delete the temporary user after initiating the social login.
                    set_transient('pp_dummy_user_created', $id, 5);

                    // add flag
                    add_option('pp_dummy_user_created_flag', 'yes');

                    $secure_cookie = '';
                    if (defined('FORCE_SSL_ADMIN') && FORCE_SSL_ADMIN === true) {
                        $secure_cookie = true;
                    }

                    wp_set_auth_cookie($id, false, $secure_cookie);
                    wp_set_current_user($id);
                    remove_filter('auth_cookie_expiration', 'pp_reduce_cookie_time');
                    wp_redirect(pp_get_current_url_query_string());
                    exit;
                }
            }

            $hybridauth = new Hybrid_Auth($config);

            $adapter = apply_filters(null, $hybridauth);

            switch ($_GET['pp_social_login']) {
                case 'facebook':
                    $adapter = $hybridauth->authenticate("Facebook");
                    break;

                case 'twitter':
                    $adapter = $hybridauth->authenticate("Twitter");
                    break;

                case 'google':
                    $adapter = $hybridauth->authenticate("Google");
                    break;

                case 'linkedin':
                    $adapter = $hybridauth->authenticate("LinkedIn");
                    break;

                case 'github':
                    $adapter = $hybridauth->authenticate("Github");
                    break;

                case 'vk':
                    $adapter = $hybridauth->authenticate("Vkontakte");
                    break;
            }

            if (is_object($adapter)) {

                do_action('pp_before_social_login_process', $adapter->getUserProfile(), $_GET['pp_social_login']);

                $user_profile = $adapter->getUserProfile();

                $hybridauth::logoutAllProviders();

                // instantiate the social login processing class
                new PP_Social_Login_Process($user_profile);
            } else {
                throw new Exception('Error in retrieving profile data from authenticated social service.');
            }
        }

    } catch (Exception $e) {
        PP_File_Uploader::error_file_logger(
            array(),
            $e->getMessage(),
            'social-login'
        );

        // WPEngine host compact.
        if (defined('WPE_PLUGIN_BASE')) {
            pp_destroy_user_session(get_current_user_id());
        }

        $error_msg = __('Authentication failed. Please try again', 'profilepress');
        pp_login_wp_errors('idp_fail', $error_msg);
    }
}


/**
 * Reduce the login cookie time to 5 seconds.
 *
 * @param int $time
 *
 * @return int
 */
function pp_reduce_cookie_time($time)
{
    return 5;
}


/**
 * Global exception handler for uncaught HybridAuth exception errors.
 *
 * @param $exception
 */
function pp_global_exception_handler($exception)
{
    $error_msg = __('An unexpected error was encountered. Please try again.', 'profilepress');
    pp_login_wp_errors('idp_fail', $error_msg);

    if (defined('WPE_PLUGIN_BASE')) {
        pp_destroy_user_session(get_current_user_id());
        pp_delete_social_login_users();
    }
}


/**
 * Delete the temporary created user.
 */
function pp_delete_social_login_users()
{
    // WPEngine host compact.
    if (!defined('WPE_PLUGIN_BASE')) {
        return;
    }

    // if the flag has not been set, return and don't try to do the shebang below.
    if (false === get_option('pp_dummy_user_created_flag')) {
        return;
    }

    // if transient hasn't expire, return or better put, do not try to delete the dummy user.
    if (false !== get_transient('pp_dummy_user_created')) {
        return;
    }

    global $wpdb;
    $users = $wpdb->get_results('SELECT ID, user_login FROM wp_users WHERE user_login like "ppsoc%"');

    foreach ($users as $user) {
        if (substr($user->user_login, 0, 5) == 'ppsoc') {
            require_once(ABSPATH . 'wp-admin/includes/user.php');
            pp_destroy_user_session($user->ID);
            wp_delete_user($user->ID);
            // delete the flag
            delete_option('pp_dummy_user_created_flag');
        }
    }
}

/**
 * Create a user with subscriber role.
 *
 * @param string $username
 * @param string $password
 * @param string $email
 *
 * @return int|WP_Error
 */
function pp_wp_create_user($username, $password, $email = '')
{
    $user_login = wp_slash($username);
    $user_email = wp_slash($email);
    $user_pass = $password;
    $role = 'subscriber';

    $userdata = compact('user_login', 'user_email', 'user_pass', 'role');

    return wp_insert_user($userdata);
}

/**
 * Destroy the session of a user.
 *
 * @param int $user_id
 */
function pp_destroy_user_session($user_id)
{
    // get all sessions for user with ID $user_id
    $sessions = WP_Session_Tokens::get_instance($user_id);

    // we have got the sessions, destroy them all!
    $sessions->destroy_all();
}