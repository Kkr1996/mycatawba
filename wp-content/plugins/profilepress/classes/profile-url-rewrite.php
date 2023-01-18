<?php

/**
 * Rewrite the profile page URL
 *
 * Rewrite the page URL to contain the "/profile" slug
 */
class ProfilePress_Profile_Rewrite
{

    /** @var  int page id containing the front-end profile shortcode */
    static private $page_id;

    /** @var array plugin settings (general) */
    private $plugin_db_settings;

    /** @type  string currently logged user's username */
    private $current_username;

    /** @type object instance */
    private static $instance;

    /** class constructor */
    function __construct()
    {
        $this->plugin_db_settings = get_option('pp_settings_data');

        // set $page_id to the WordPress page with the profile shortcode
        $page_with_profile_shortcode = $this->page_with_profile_shortcode();
        if ( ! empty($page_with_profile_shortcode)) {
            self::$page_id = absint($page_with_profile_shortcode);
        }

        add_action('init', array($this, 'current_user_username'));
        add_action('init', array($this, 'rewrite_function'), 10, 0);

        // if "convert author to profile slug" is active, a=hook the function
        if (isset($this->plugin_db_settings['author_slug_to_profile']) && $this->plugin_db_settings['author_slug_to_profile'] == 'on') {
            add_action('init', array($this, 'change_author_to_profile'), 10, 0);
        }

    }

    /**
     * Return the page ID that contains the profile shortcode as set in the plugin "General settings"
     *
     * @return int|null
     */
    public function page_with_profile_shortcode()
    {
        $page_with_profile_shortcode = null;
        // get db value
        $plugin_db_settings = $this->plugin_db_settings;

        if (isset($plugin_db_settings['set_user_profile_shortcode'])) {
            $page_with_profile_shortcode = $plugin_db_settings['set_user_profile_shortcode'];
        }

        return apply_filters('pp_profile_page_id', $page_with_profile_shortcode);
    }


    /**
     * Return currently logged in user or set user ID to 0 otherwise.
     */
    public function current_user_username()
    {
        if (is_user_logged_in()) {
            $current_user           = wp_get_current_user();
            $this->current_username = $current_user->user_login;
        } else {
            $this->current_username = '0';
        }
    }

    /** callback function */
    public function rewrite_function()
    {
        $profile_slug = pp_get_profile_slug();

        add_rewrite_tag('%who%', '([^&]+)');

        $regex_1 = apply_filters('pp_profile_rewrite_regex_1', "^{$profile_slug}/([^/]*)/?", $profile_slug);
        $regex_2 = apply_filters('pp_profile_rewrite_regex_2', "^{$profile_slug}/?$", $profile_slug);

        $query_1 = apply_filters('pp_profile_rewrite_query_1', 'index.php?page_id=' . self::$page_id . '&who=$matches[1]', self::$page_id);
        $query_2 = apply_filters('pp_profile_rewrite_query_2', 'index.php?page_id=' . self::$page_id, self::$page_id);

        add_rewrite_rule($regex_1, $query_1, 'top');
        add_rewrite_rule($regex_2, $query_2, 'top');

        do_action('pp_after_rewrite_hook_added', $profile_slug, self::$page_id);
    }


    public function change_author_to_profile()
    {
        global $wp_rewrite;
        $wp_rewrite->author_base = pp_get_profile_slug();
    }


    /**
     * Singleton instance
     *
     * @return mixed
     */
    public static function get_instance()
    {
        if ( ! self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}


ProfilePress_Profile_Rewrite::get_instance();