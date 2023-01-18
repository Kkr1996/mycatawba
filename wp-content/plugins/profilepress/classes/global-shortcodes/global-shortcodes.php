<?php

class ProfilePress_Global_Shortcodes
{
    /** @var WP_User */
    static private $current_user;

    public static function initialize()
    {
        add_action('init', array(__CLASS__, 'get_current_user'));
        add_shortcode('user-avatar', array(__CLASS__, 'user_avatar'));
        add_shortcode('user-avatar-url', array(__CLASS__, 'user_avatar_url'));
        add_shortcode('pp-recaptcha', array(__CLASS__, 'profilepress_recaptcha'));
        add_shortcode('link-registration', array(__CLASS__, 'link_registration'));
        add_shortcode('link-lost-password', array(__CLASS__, 'link_lost_password'));
        add_shortcode('link-login', array(__CLASS__, 'link_login'));
        add_shortcode('link-logout', array(__CLASS__, 'link_logout'));
        add_shortcode('link-edit-user-profile', array(__CLASS__, 'link_edit_profile'));
        add_shortcode('facebook-login-url', array(__CLASS__, 'facebook_login_url'));
        add_shortcode('twitter-login-url', array(__CLASS__, 'twitter_login_url'));
        add_shortcode('linkedin-login-url', array(__CLASS__, 'linkedin_login_url'));
        add_shortcode('github-login-url', array(__CLASS__, 'github_login_url'));
        add_shortcode('google-login-url', array(__CLASS__, 'google_login_url'));
        add_shortcode('vk-login-url', array(__CLASS__, 'vk_login_url'));
        add_shortcode('pp-login-form', array(__CLASS__, 'login_form_tag'));
        add_shortcode('pp-registration-form', array(__CLASS__, 'registration_form_tag'));
        add_shortcode('pp-password-reset-form', array(__CLASS__, 'password_reset_form_tag'));
        add_shortcode('pp-edit-profile-form', array(__CLASS__, 'edit_profile_form_tag'));
        add_shortcode('pp-redirect-non-logged-in-users', array(__CLASS__, 'redirect_non_logged_in_users'));
        add_shortcode('pp-redirect-logged-in-users', array(__CLASS__, 'redirect_logged_in_users'));
        add_shortcode('pp-logged-users', array(__CLASS__, 'pp_log_in_users'));
        add_shortcode('pp-non-logged-users', array(__CLASS__, 'pp_non_log_in_users'));

        add_shortcode('password-hint', 'wp_get_password_hint');

        // BbPress
        add_shortcode('bbp-topic-started-url', array(__CLASS__, 'bbp_topic_started_url'));
        add_shortcode('bbp-replies-created-url', array(__CLASS__, 'bbp_replies_created_url'));
        add_shortcode('bbp-favorites-url', array(__CLASS__, 'bbp_favorites_url'));
        add_shortcode('bbp-subscriptions-url', array(__CLASS__, 'bbp_subscriptions_url'));
    }

    /** Get the currently logged user */
    public static function get_current_user()
    {
        $current_user = wp_get_current_user();
        if ($current_user instanceof WP_User) {
            self::$current_user = $current_user;
        }
    }

    /**
     * Login form tag
     *
     * @param array $atts
     * @param string $content
     *
     * @return string
     */
    public static function login_form_tag($atts, $content)
    {
        $tag = '<form method="post" data-pp-form-submit="login">';
        $tag .= '<input type="hidden" name="is_melange" value="true">';
        $tag .= '<input type="hidden" name="pp_current_url" value="' . pp_get_current_url_raw() . '">';
        $tag .= do_shortcode($content);
        $tag .= '</form>';

        return $tag;
    }

    /**
     * Registration form tag
     *
     * @param array $atts
     * @param string $content
     *
     * @return string
     */
    public static function registration_form_tag($atts, $content)
    {
        $tag = '<form method="post" enctype="multipart/form-data" data-pp-form-submit="signup">';
        $tag .= '<input type="hidden" name="is_melange" value="true">';
        $tag .= do_shortcode($content);
        $tag .= '</form>';

        return $tag;

    }

    /**
     * Password reset form tag
     *
     * @param array $atts
     * @param string $content
     *
     * @return string
     */
    public static function password_reset_form_tag($atts, $content)
    {
        $tag = '<form method="post" data-pp-form-submit="passwordreset">';
        $tag .= '<input type="hidden" name="is_melange" value="true">';
        $tag .= do_shortcode($content);
        $tag .= '</form>';

        return $tag;

    }

    /**
     * Edit profile form tag
     *
     * @param array $atts
     * @param string $content
     *
     * @return string
     */
    public static function edit_profile_form_tag($atts, $content)
    {
        $tag = '<form method="post" enctype="multipart/form-data" data-pp-form-submit="editprofile">';
        $tag .= '<input type="hidden" name="is_melange" value="true">';
        $tag .= do_shortcode($content);
        $tag .= '</form>';

        return $tag;
    }


    /**
     * Normalize unamed shortcode
     *
     * @param $atts
     *
     * @return mixed
     */
    public static function normalize_attributes($atts)
    {
        if (is_array($atts)) {
            foreach ($atts as $key => $value) {
                if (is_int($key)) {
                    $atts[$value] = true;
                    unset($atts[$key]);
                }
            }

            return $atts;
        }
    }

    /**
     * reCAPTCHA display
     *
     * @return string
     */
    public static function profilepress_recaptcha()
    {
        return PP_ProfilePress_Recaptcha::display_captcha();
    }

    /** registration url */
    public static function link_registration($atts)
    {
        $atts = self::normalize_attributes($atts);

        if ((!empty($atts['raw']) && ($atts['raw'] == true))) {
            return wp_registration_url();
        }

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'title' => '',
                'label' => __('Sign Up', 'profilepress'),
                'raw' => '',
            ),
            $atts
        );

        $class = 'class="' . $atts['class'] . '"';
        $id = 'id="' . $atts['id'] . '"';
        $label = $atts['label'];
        $title = 'title="' . $atts['title'] . '"';


        $html = '<a href="' . wp_registration_url() . "\" {$title} {$class} {$id}>$label</a>";

        return $html;
    }

    /** Lost password url */
    public static function link_lost_password($atts)
    {
        $atts = self::normalize_attributes($atts);


        if ((!empty($atts['raw']) && ($atts['raw'] == true))) {
            return wp_lostpassword_url();
        }

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'title' => '',
                'label' => __('Reset Password', 'profilepress'),
                'raw' => '',
            ),
            $atts
        );

        $class = 'class="' . $atts['class'] . '"';
        $id = 'id="' . $atts['id'] . '"';
        $label = $atts['label'];
        $title = 'title="' . $atts['title'] . '"';

        $html = "<a href=\"" . wp_lostpassword_url() . "\"{$title} {$class} {$id}>$label</a>";

        return $html;
    }


    /** Login url */
    public static function link_login($atts)
    {
        $atts = self::normalize_attributes($atts);

        if ((!empty($atts['raw']) && ($atts['raw'] == true))) {
            return wp_login_url();
        }

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'title' => '',
                'label' => __('Login', 'profilepress'),
                'raw' => '',
            ),
            $atts
        );

        $class = 'class="' . $atts['class'] . '"';
        $id = 'id="' . $atts['id'] . '"';
        $label = $atts['label'];
        $title = 'title="' . $atts['title'] . '"';

        $html = '<a href="' . wp_login_url() . '" ' . "$title $class $id" . '>' . $label . '</a>';

        return $html;
    }

    /** Logout URL */
    public static function link_logout($atts)
    {
        if (!is_user_logged_in()) {
            return;
        }

        $atts = self::normalize_attributes($atts);

        if ((!empty($atts['raw']) && ($atts['raw'] == true))) {
            return wp_logout_url();
        }

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'title' => '',
                'label' => __('Log Out', 'profilepress'),
                'raw' => '',
            ),
            $atts
        );

        $class = 'class="' . $atts['class'] . '"';
        $id = 'id="' . $atts['id'] . '"';
        $label = $atts['label'];
        $title = 'title="' . $atts['title'] . '"';

        $html = '<a href="' . wp_logout_url() . '" ' . "$title $class $id" . '>' . $label . '</a>';

        return $html;

    }

    /**
     * URL to user edit page
     * @return string|void
     */
    public static function link_edit_profile($atts)
    {
        if (!is_user_logged_in()) {
            return;
        }

        $atts = self::normalize_attributes($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'title' => '',
                'label' => __('Edit Profile', 'profilepress'),
                'raw' => '',
            ),
            $atts
        );

        $class = 'class="' . $atts['class'] . '"';
        $id = 'id="' . $atts['id'] . '"';
        $label = $atts['label'];
        $title = 'title="' . $atts['title'] . '"';


        $plugin_data = get_option('pp_settings_data');
        $edit_profile_page_id = $plugin_data['edit_user_profile_url'];

        // return the page of edit profile set by the plugin
        if (isset($edit_profile_page_id) && !empty($edit_profile_page_id)) {
            $edit_profile_page_url = get_permalink($edit_profile_page_id);
        } else {
            // else return the default WP page for user profile.
            $edit_profile_page_url = site_url('/wp-admin/profile.php');
        }

        if ((!empty($atts['raw']) && ($atts['raw'] == true))) {
            return $edit_profile_page_url;
        }

        $html = '<a href="' . $edit_profile_page_url . '" ' . "$title $class $id" . '>' . $label . '</a>';

        return $html;

    }

    /**
     * Display avatar of currently logged in user
     *
     * @param $atts
     *
     * @return string
     */
    public static function user_avatar($atts)
    {
        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'size' => 300,
                'default' => '',
                'alt' => '',
            ),
            $atts
        );

        $class = $atts['class'];
        $id = $atts['id'];
        $size = absint($atts['size']);
        $default = esc_url_raw($atts['default']);
        $alt = $atts['alt'];

        return get_profilepress_avatar(self::$current_user->ID, $size, $default, $alt, $class, $id);

    }


    /**
     * Redirect non logged users to login page.
     *
     * @param array $atts
     */
    public static function redirect_non_logged_in_users($atts)
    {
        if (is_user_logged_in()) {
            return;
        }

        $atts = shortcode_atts(
            array(
                'url' => '',
            ),
            $atts
        );

        $url = empty($atts['url']) ? pp_login_url() : $atts['url'];

        wp_redirect($url);
        exit;
    }


    /**
     * Redirect logged users to login page.
     *
     * @param array $atts
     */
    public static function redirect_logged_in_users($atts)
    {
        if (!is_user_logged_in()) {
            return;
        }

        $atts = shortcode_atts(
            array(
                'url' => '',
            ),
            $atts
        );

        $url = empty($atts['url']) ? pp_login_url() : $atts['url'];

        wp_redirect($url);
        exit;
    }


    /**
     * Returns avatar url
     *
     * @return string
     */
    public static function user_avatar_url()
    {
        return ProfilePress_Avatar_Url::get_avatar_url(self::$current_user->ID, 200);
    }

    /**
     * Facebook social login url
     * @return string
     */
    public static function facebook_login_url()
    {
        return add_query_arg(
            array(
                'pp_social_login' => 'facebook',
                'pp_current_url' => rawurlencode(pp_get_current_url_query_string()),
            ),
            wp_login_url()
        );
    }

    /**
     * Twitter social login url
     *
     * @return string
     */
    public static function twitter_login_url()
    {
        return add_query_arg(
            array(
                'pp_social_login' => 'twitter',
                'pp_current_url' => rawurlencode(pp_get_current_url_query_string()),
            ),
            wp_login_url()
        );
    }

    /**
     * LinkedIn social login url
     *
     * @return string
     */
    public static function linkedin_login_url()
    {
        return add_query_arg(
            array(
                'pp_social_login' => 'linkedin',
                'pp_current_url' => rawurlencode(pp_get_current_url_query_string()),
            ),
            wp_login_url()
        );
    }

    /**
     * Github social login url
     *
     * @return string
     */
    public static function github_login_url()
    {
        return add_query_arg(
            array(
                'pp_social_login' => 'github',
                'pp_current_url' => rawurlencode(pp_get_current_url_query_string()),
            ),
            wp_login_url()
        );
    }

    /**
     * Google social login url
     *
     * @return string
     */
    public static function google_login_url()
    {
        return add_query_arg(
            array(
                'pp_social_login' => 'google',
                'pp_current_url' => rawurlencode(pp_get_current_url_query_string()),
            ),
            wp_login_url()
        );
    }

    /**
     * VK.com social login url
     *
     * @return string
     */
    public static function vk_login_url()
    {
        return add_query_arg(
            array(
                'pp_social_login' => 'vk',
                'pp_current_url' => rawurlencode(pp_get_current_url_query_string()),
            ),
            wp_login_url()
        );
    }


    /**
     * Only logged user can view content.
     *
     * @param array $atts
     * @param mixed $content
     *
     * @return mixed
     */
    public static function pp_log_in_users($atts, $content)
    {
        if (is_user_logged_in()) {
            return do_shortcode($content);
        }
    }


    /**
     * Only non-logged user can view content.
     *
     * @param array $atts
     * @param mixed $content
     *
     * @return mixed
     */
    public static function pp_non_log_in_users($atts, $content)
    {
        if (!is_user_logged_in()) {
            return do_shortcode($content);
        }
    }


    /**
     * URL to topics started by users.
     *
     * @return string
     */
    public static function bbp_topic_started_url()
    {
        if (function_exists('bbp_get_user_topics_created_url')) {
            return esc_url(bbp_get_user_topics_created_url(self::$current_user->ID));
        }
    }


    /**
     * URL to topics started by users.
     *
     * @return string
     */
    public static function bbp_replies_created_url()
    {
        if (function_exists('bbp_user_replies_created_url')) {
            return esc_url_raw(bbp_get_user_replies_created_url(self::$current_user->ID));
        }
    }


    /**
     * URL to topics started by users.
     *
     * @return string|void
     */
    public static function bbp_favorites_url()
    {
        if (function_exists('bbp_get_favorites_permalink')) {
            return esc_url(bbp_get_favorites_permalink(self::$current_user->ID));
        }
    }


    /**
     * URL to topics started by users.
     *
     * @return string|void
     */
    public static function bbp_subscriptions_url()
    {
        if (function_exists('bbp_get_subscriptions_permalink')) {
            return esc_url(bbp_get_subscriptions_permalink(self::$current_user->ID));
        }
    }

}

ProfilePress_Global_Shortcodes::initialize();