<?php
ob_start();

/**
 * Parse the individual profile shortcode of "Edit profile" builder
 */
class PP_Parent_Front_End_Profile_Shortcode_Parser
{
    /** @var int shortcode ID of edit profile builder */
    private $user_profile_builder_id;

    /** @var int current user id */
    static private $current_user_id;

    /** @type  object user profile object */
    private $plugin_db_settings;


    /** Constructor */
    public function __construct()
    {
        $this->plugin_db_settings = get_option('pp_settings_data');

        add_shortcode('profilepress-user-profile', array($this, 'pp_user_profile_parser'));

        add_action('init', array($this, 'get_current_user_id'));

        add_filter('pre_get_document_title', array($this, 'rewrite_profile_title'), 99, 1);
        add_filter('wp_title', array($this, 'rewrite_profile_title'), 99, 1);
    }

    /** Get the current user id */
    public function get_current_user_id()
    {

        $current_user = wp_get_current_user();
        if ($current_user instanceof WP_User) {
            self::$current_user_id = $current_user->ID;
        }
    }

    /**
     * Get currently logged in user object_data
     * @return WP_User
     */
    function get_current_user_data()
    {
        $current_user = wp_get_current_user();

        return $current_user;
    }

    /**
     * Shortcode callback function to parse the shortcode.
     *
     * @param $atts
     *
     * @return string
     */
    public function pp_user_profile_parser($atts)
    {
        $who = get_query_var('who');

        // initialise variable
        $user = '';

        if (empty($who)) {
            if (is_user_logged_in()) {
                $user = $this->get_current_user_data();
            } elseif (!is_user_logged_in()) {
                $profile_slug = pp_get_profile_slug();
                $profile_slug_with_slash = pp_get_profile_slug() . '/';
                $profile_slug_count = strlen($profile_slug);
                $profile_slug_with_slash_count = strlen($profile_slug_with_slash);

                if (substr(esc_url($_SERVER['REQUEST_URI']), -$profile_slug_with_slash_count) == $profile_slug_with_slash ||
                    substr(esc_url($_SERVER['REQUEST_URI']), -$profile_slug_count) == $profile_slug
                ) {
                    wp_redirect(wp_login_url());
                    exit;
                }
            }
        } else {
            $username_or_nicename = apply_filters('pp_frontend_user_profile_username', rawurldecode($who), $atts);
            // attempt to check if the slug is a nice-name and then retrieve the username of the user.
            $check = pp_is_slug_nice_name($username_or_nicename);
            if (is_string($check)) {
                $username_or_nicename = $check;
            }

            $user = get_user_by('login', $username_or_nicename);
        }

        if (!empty($atts['user-id'])) {
            $user = get_user_by('ID', absint($atts['user-id']));
        }

        $user = apply_filters('pp_frontend_profile_wp_user_object', $user, $atts);

        Front_End_Profile_Builder_Shortcode_Parser::get_instance($user);

        // get "edit user profile" builder id
        $id = absint($atts['id']);

        do_action('pp_frond_end_profile_id', $id);

        // set $user_profile_builder_id to the currently processed id
        $this->user_profile_builder_id = absint($atts['id']);
        $attribution_start = apply_filters('pp_hide_attribution', '<!-- This WordPress front-end profile is built and powered by ProfilePress WordPress plugin - https://profilepress.net -->' . "\r\n");
        $attribution_end = apply_filters('pp_hide_attribution', "\r\n" . '<!-- / ProfilePress WordPress plugin. -->' . "\r\n");
        $css = self::get_user_profile_css($id);

        // call the registration structure/design
        return apply_filters('pp_front_end_profile', $attribution_start . $css . $this->get_user_profile_structure($id) . $attribution_end, $user);
    }


    /**
     * Get the registration structure from the database
     *
     * @param int $id
     *
     * @return string
     */
    public static function get_user_profile_structure($id)
    {
        $user_profile_structure = PROFILEPRESS_sql::get_a_builder_structure('front_end_profile', $id);

        return do_shortcode($user_profile_structure);

    }


    /**
     * Get the CSS stylesheet for the ID registration
     *
     * @return mixed
     */

    public static function get_user_profile_css($user_profile_builder_id)
    {
        // if no id is set return
        if (!isset($user_profile_builder_id)) {
            return;
        }

        $user_profile_css = PROFILEPRESS_sql::get_a_builder_css('front_end_profile', $user_profile_builder_id);

        return "<style type=\"text/css\">\r\n $user_profile_css \r\n</style>";
    }


    /** Rewrite the title of the profile */
    public function rewrite_profile_title($title)
    {
        global $post;

        $who = get_query_var('who');

        if (empty($who)) {
            if (is_user_logged_in()) {
                $user_object = $this->get_current_user_data();
            }
        } else {
            $user_object = get_user_by('login', $who);
        }


        // if currently viewed page is the page with the front-end profile, rewrite the title accordingly.
        if ((@$post->ID == $this->plugin_db_settings['set_user_profile_shortcode'])
            || has_shortcode('profilepress-user-profile', @$post->post_content)
        ) {

            if (isset($user_object) && is_object($user_object)) {

                // if first and last name is set, use the combo as title
                if (!empty($user_object->first_name) && !empty($user_object->last_name)) {
                    $title = "$user_object->first_name {$user_object->last_name}";
                } // if either first or last name is set, use either as title
                elseif (!empty($user_object->first_name) || !empty($user_object->last_name)) {
                    $title = "$user_object->first_name {$user_object->last_name}";
                } // else use their username
                else {
                    $title = $user_object->user_login;
                }

                $title = apply_filters('pp_profile_username_title', self::title_possessiveness($title), $title);
            }
        }

        return $title;
    }

    public static function title_possessiveness($string)
    {
        $string = trim($string);
        $lastchar = substr($string, -1);

        $profile_string = __('Profile', 'profilepress');

        if ('s' == $lastchar) {
            $title = $string . "' $profile_string";
        } else {
            $title = ucwords($string) . "'s $profile_string";
        }

        return $title;
    }


    /** Singleton instance */
    static public function get_instance()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new self;
        }

        return $instance;
    }
}

PP_Parent_Front_End_Profile_Shortcode_Parser::get_instance();