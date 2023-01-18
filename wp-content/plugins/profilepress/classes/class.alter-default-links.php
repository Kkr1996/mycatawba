<?php

/**
 * Alter default login, registration, password_reset login and logout url
 */
class Modify_Redirect_Default_Links
{

    /** @type  object instance */
    private static $instance;

    /** @type  array plugin settings data */
    private $db_settings_data;


    /** Class constructor */
    public function __construct()
    {

        // initialize plugin settings data and save to its property
        $this->db_settings_data = pp_db_data();

        add_action('template_redirect', array($this, 'global_redirect'));

        // if the default password have been change i.e not empty add the filter and action
        if (!empty($this->db_settings_data['set_lost_password_url'])) {
            add_action('init', array($this, 'redirect_password_reset_page'));
            add_filter('lostpassword_url', array($this, 'lost_password_url_func'), 99);
        }

        // if the default login have been change i.e not empty add the filter and action
        if (!empty($this->db_settings_data['set_login_url'])) {
            add_action('init', array($this, 'redirect_login_page'));
            add_filter('login_url', array($this, 'set_login_url_func'), 99, 3);
        }


        // if the default registration have been change i.e not empty add the filter and action
        if (!empty($this->db_settings_data['set_registration_url'])) {
            add_action('init', array($this, 'redirect_reg_page'));
            add_filter('register_url', array($this, 'register_url_func'), 99);
        }

        // if the default logout have been change i.e not empty add the filter
        if (!empty($this->db_settings_data['set_log_out_url'])) {
            add_filter('logout_url', array($this, 'logout_url_func'), 99, 2);
        }


        // redirect default edit profile to custom edit profile page
        if (isset($this->db_settings_data['redirect_default_edit_profile_to_custom']) && !empty($this->db_settings_data['edit_user_profile_url']) && ($this->db_settings_data['redirect_default_edit_profile_to_custom'] == 'yes')) {
            add_action('init', array($this, 'redirect_default_edit_profile_to_custom'));
        }


        // redirect buddypress registration to PP custom registration page or default WP registration url if not set
        if (isset($this->db_settings_data['redirect_bp_registration_page']) && !empty($this->db_settings_data['redirect_bp_registration_page']) && ($this->db_settings_data['redirect_bp_registration_page'] == 'yes')) {
            add_action('template_redirect', array($this, 'redirect_bp_registration_page'));
            add_filter('bp_get_signup_page', array($this, 'rewrite_bp_registration_url'));
        }

        // redirect default logout page to blog homepage
        add_action('init', array($this, 'redirect_logout_page'));
    }


    /**
     * Modify the lost password url returned by wp_lostpassword_url() function.
     *
     * @return string
     */
    public function lost_password_url_func()
    {
        return pp_password_reset_url();
    }

    /** Force redirection of default password reset to the page with custom one. */
    public function redirect_password_reset_page()
    {
        if (apply_filters('pp_default_password_reset_redirect_enabled', true)) {

            $password_reset_url = get_permalink(absint($this->db_settings_data['set_lost_password_url']));

            $page_viewed = basename(esc_url($_SERVER['REQUEST_URI']));

            if ($page_viewed == "wp-login.php?action=lostpassword" && $_SERVER['REQUEST_METHOD'] == 'GET') {
                wp_redirect($password_reset_url);
                exit;
            }
        }
    }


    /**
     * Modify the login url returned by wp_login_url()
     *
     * @param string $redirect
     * @param bool $force_reauth
     *
     * @return string page with login shortcode
     */
    public function set_login_url_func($url, $redirect = '', $force_reauth = false)
    {
        // added check for <del>admin interface<del> is user logged in so heartbeat auth check return default wp login page.
        // fixes these issues https://wordpress.org/support/topic/wp-auth-check-uses-full-login-page-possible-to-switch-or-customize/
        // https://wordpress.org/support/topic/bug-in-dashboard-login-popup/
        // if (!is_user_logged_in()) {
        $url = pp_login_url();

        if (!empty($redirect)) {
            $url = add_query_arg('redirect_to', urlencode($redirect), $url);
        }

        if ($force_reauth) {
            $url = add_query_arg('reauth', '1', $url);
        }
        // }

        return $url;
    }


    /** Force redirect default login to page with login shortcode */
    function redirect_login_page()
    {
        if (apply_filters('pp_default_login_redirect_enabled', true)) {
            $login_url = pp_login_url();

            // retrieve the query stringif available.
            $query_string = !empty($_SERVER["QUERY_STRING"]) ? $_SERVER["QUERY_STRING"] : parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

            // if query string is available, append to login url.
            if (!empty($query_string)) {
                if (strpos($login_url, '?') !== false) {
                    $login_url .= "&$query_string";
                } else {
                    $login_url .= "?$query_string";
                }
            }

            $page_viewed = basename(esc_url($_SERVER['REQUEST_URI']));

            if ((strpos($page_viewed, 'wp-login.php') !== false) &&
                // loggedout GET param is used by default worpdress logout system
                !isset($_REQUEST['loggedout']) &&
                // action GET param is used by default wordpress register and lost password.
                !isset($_REQUEST['action']) &&
                $_SERVER['REQUEST_METHOD'] == 'GET'
            ) {
                wp_redirect($login_url);
                exit;
            }
        }
    }


    /**
     * Modify the url returned by wp_registration_url().
     *
     * @return string page url with registration shortcode.
     */
    function register_url_func()
    {
        return pp_registration_url();
    }


    /** force redirection of default registration to custom one */
    function redirect_reg_page()
    {
        if (apply_filters('pp_default_registration_redirect_enabled', true)) {

            $reg_url = pp_registration_url();

            $page_viewed = basename(esc_url($_SERVER['REQUEST_URI']));

            if ($page_viewed == "wp-login.php?action=register" && $_SERVER['REQUEST_METHOD'] == 'GET') {
                wp_redirect($reg_url);
                exit;
            }
        }
    }


    /**
     * Add query string (url) to logout url which is url to redirect to after logout
     *
     * @param $logout_url string filter default login url to be modified
     * @param $redirect string where to redirect to after logout
     *
     * @return string
     */
    public function logout_url_func($logout_url, $redirect)
    {
        if (isset($this->db_settings_data['custom_url_log_out']) && !empty($this->db_settings_data['custom_url_log_out'])) {
            $set_redirect = $this->db_settings_data['custom_url_log_out'];
        } elseif (!empty($this->db_settings_data['set_log_out_url'])) {
            $db_logout_url = get_permalink(absint($this->db_settings_data['set_log_out_url']));

            if (empty($db_logout_url) || $this->db_settings_data['set_log_out_url'] == 'current_view_page') {

                // make redirect currently viewed page
                $set_redirect = get_permalink();
            } else {
                $set_redirect = $db_logout_url;
            }
        }

        $set_redirect = apply_filters('pp_logout_redirect', esc_url_raw($set_redirect));

        return add_query_arg('redirect_to', $set_redirect, $logout_url);
    }


    /** Redirect user edit profile (/wp-admin/profile.php) to "custom edit profile" page. */
    function redirect_default_edit_profile_to_custom()
    {
        // Filter to disable edit profile redirect for administrator.
        $disable = apply_filters('pp_disable_admin_edit_profile_redirect', false);
        if ($disable && current_user_can('delete_users')) {
            return;
        }

        if (!empty($this->db_settings_data['edit_user_profile_url'])) {
            $edit_user_profile_url = pp_edit_profile_url();

            $page_viewed = esc_url($_SERVER['REQUEST_URI']);


            if (isset($page_viewed) && substr($page_viewed, -20) == 'wp-admin/profile.php') {
                wp_redirect($edit_user_profile_url);
                exit;
            }
        }
    }


    /** Redirect the default logout page (/wp-login.php?loggedout=true) to blog homepage */
    public function redirect_logout_page()
    {
        $page_viewed = basename(esc_url($_SERVER['REQUEST_URI']));

        if ($page_viewed == "wp-login.php?loggedout=true" && $_SERVER['REQUEST_METHOD'] == 'GET') {
            wp_redirect(home_url());
            exit;
        }
    }


    public function redirect_bp_registration_page()
    {
        if (!class_exists('BuddyPress') && !function_exists('bp_has_custom_signup_page')) {
            return;
        }

        if (!bp_has_custom_signup_page()) {
            return;
        }

        // ! bp_has_custom_signup_page()
        $page = bp_get_root_domain() . '/' . bp_get_signup_slug();

        if ($page == pp_get_current_url()) {
            wp_redirect(pp_registration_url());
            exit;
        }
    }


    /**
     * Rewrite buddypress registration url to PP custom url or WP's if not set.
     *
     * @param string $page
     *
     * @return string
     */
    function rewrite_bp_registration_url($page)
    {
        $rewrite = apply_filters('pp_bp_registration_url', true);
        if ($rewrite) {
            $page = pp_registration_url();
        }

        return $page;
    }


    /**
     * Global redirect function
     */
    public function global_redirect()
    {
        global $post;

        $global_redirect = absint(@$this->db_settings_data['global_redirect']);
        $login_page = absint(@$this->db_settings_data['set_login_url']);
        $registration_page = absint(@$this->db_settings_data['set_registration_url']);
        $password_reset_page = absint(@$this->db_settings_data['set_lost_password_url']);

        $allowed_pages = apply_filters('pp_global_redirect_page_ids', array(
            $login_page,
            $registration_page,
            $password_reset_page
        ));

        if (!empty($global_redirect) && $global_redirect != 'deactivate') {
            if (!is_user_logged_in() && !is_page($global_redirect)) {
                if (!in_array($post->ID, $allowed_pages)) {
                    wp_redirect(get_permalink($global_redirect));
                    exit;
                }
            }
        }
    }


    /** Singleton poop */
    public static function get_instance()
    {

        if (!isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

}

Modify_Redirect_Default_Links::get_instance();