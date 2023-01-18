<?php
/** List of ProfilePress global helper functions */

/** Plugin DB settings data */
function pp_db_data()
{
    return get_option('pp_settings_data');
}


/** Addons options data */
function pp_addon_options()
{
    $addon_options = get_option('pp_addons_options', array());

    return ! empty($addon_options) ? $addon_options : array();
}

/**
 * Return the url to redirect to after login authentication
 *
 * @return bool|string
 */
function pp_login_redirect()
{
    if ( ! empty($_REQUEST['redirect_to'])) {
        $redirect = $_REQUEST['redirect_to'];
    } else {
        $data                      = pp_db_data();
        $login_redirect            = $data['set_login_redirect'];
        $custom_url_login_redirect = $data['custom_url_login_redirect'];

        if (isset($custom_url_login_redirect) && ! empty($custom_url_login_redirect)) {
            $redirect = $custom_url_login_redirect;
        } elseif ($login_redirect == 'dashboard') {
            $redirect = network_site_url('/wp-admin');
        } elseif ('current_page' == $login_redirect) {

            // in ajax mode, pp_current_url is set so we can do client-side redirection to current page after login.

            // no way to get current url in social login hence, look it up from $_GET['pp_current_url']
            if ( ! empty($_GET['pp_current_url'])) {
                $redirect = rawurldecode($_GET['pp_current_url']);
            } elseif (isset($_POST['pp_current_url'])) {
                $redirect = $_POST['pp_current_url'];
            } else {
                $redirect = pp_get_current_url_raw();
            }
        } elseif (isset($login_redirect) && ! empty($login_redirect)) {
            $redirect = get_permalink($login_redirect);
        } else {
            $redirect = network_site_url('/wp-admin');
        }
    }

    return apply_filters('pp_login_redirect', esc_url($redirect));
}

/**
 * Return the url to redirect to after successful reset / change of password.
 *
 * @return bool|string
 */
function pp_password_reset_redirect()
{
    $data                               = pp_db_data();
    $password_reset_redirect            = $data['set_password_reset_redirect'];
    $custom_url_password_reset_redirect = $data['custom_url_password_reset_redirect'];

    if ( ! empty($custom_url_password_reset_redirect)) {
        $redirect = $custom_url_password_reset_redirect;
    } elseif ( ! empty($password_reset_redirect)) {
        $redirect = get_permalink($password_reset_redirect);
        if ($password_reset_redirect == 'no_redirect') {
            $redirect = pp_password_reset_url() . '?password=changed';
        }
    } else {
        $redirect = pp_password_reset_url() . '?password=changed';
    }

    return apply_filters('pp_do_password_reset_redirect', esc_url($redirect));
}

/**
 * Return the url to redirect to after login authentication
 *
 * @return bool|string
 */
function pp_profile_url()
{
    $data   = pp_db_data();
    $db_url = $data['set_user_profile_shortcode'];

    if ( ! empty($db_url)) {
        $url = get_permalink($db_url);
    } else {
        $url = admin_url() . 'profile.php';
    }

    return apply_filters('pp_profile_url', $url);
}

/**
 * Return ProfilePress edit profile page URL or WP default profile URL as fallback
 *
 * @return bool|string
 */
function pp_edit_profile_url()
{
    $data   = pp_db_data();
    $db_url = $data['edit_user_profile_url'];

    if ( ! empty($db_url)) {
        $url = get_permalink($db_url);
    } else {
        $url = admin_url() . 'profile.php';
    }

    return apply_filters('pp_edit_profile_url', $url);
}

/**
 * Return ProfilePress password reset url.
 *
 * @return string
 */
function pp_password_reset_url()
{
    $data   = pp_db_data();
    $db_url = $data['set_lost_password_url'];

    if ( ! empty($db_url)) {
        $url = get_permalink($db_url);
    } else {
        $url = wp_lostpassword_url();
    }

    return apply_filters('pp_password_reset_url', $url);
}


/** Get ProfilePress login page URL or WP default login url if it isn't set. */
function pp_login_url()
{
    $data = pp_db_data();
    if ( ! empty($data['set_login_url'])) {
        $login_url = get_permalink($data['set_login_url']);
    } else {
        $login_url = wp_login_url();
    }

    return apply_filters('pp_login_url', $login_url);
}

/** Get ProfilePress login page URL or WP default login url if it isn't set. */
function pp_registration_url()
{
    $data = pp_db_data();
    if ( ! empty($data['set_registration_url'])) {
        $reg_url = get_permalink($data['set_registration_url']);
    } else {
        $reg_url = wp_registration_url();
    }

    return apply_filters('pp_registration_url', $reg_url);
}

/**
 * Return the URL of the currently view page.
 *
 * @return string
 */
function pp_get_current_url()
{
    global $wp;

    return home_url(add_query_arg(array(), $wp->request));
}


/**
 * Return currently viewed page url without query string.
 *
 * @return string
 */
function pp_get_current_url_raw()
{
    $protocol = 'http://';

    if ((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1))
        || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
    ) {
        $protocol = 'https://';
    }

    return esc_url($protocol . $_SERVER['HTTP_HOST'] . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
}

/**
 * Return currently viewed page url with query string.
 *
 * @return string
 */
function pp_get_current_url_query_string()
{
    $protocol = 'http://';

    if ((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1))
        || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
    ) {
        $protocol = 'https://';
    }

    $url = $protocol . $_SERVER['HTTP_HOST'];

    $url .= $_SERVER['REQUEST_URI'];

    return esc_url($url);
}


/** @return string blog URL without scheme */
function pp_site_url_without_scheme()
{
    $parsed_url = parse_url(home_url());

    return $parsed_url['host'];
}

/**
 * Append an option to a select dropdown
 *
 * @param string $option option to add
 * @param string $select select dropdown
 *
 * @return string
 */
function pp_append_option_to_select($option, $select)
{
    $regex = "/<select ([^<]*)>/";

    preg_match($regex, $select, $matches);
    $select_attr = $matches[1];

    $a = preg_split($regex, $select);

    $join = '<select ' . $select_attr . '>' . "\r\n";
    $join .= $option . $a[1];

    return $join;
}

/**
 * Blog name or domain name if name doesn't exist
 *
 * @return string
 */
function pp_site_title()
{
    if (is_multisite()) {
        $blog_name = get_blog_option(null, 'blogname');
        $blog_name = (empty($blog_name)) ? $GLOBALS['current_site']->site_name : $blog_name;
    } else {
        $blog_name = get_option('blogname');
    }

    return ! empty($blog_name) ? wp_specialchars_decode($blog_name, ENT_QUOTES) : str_replace(
        array(
            'http://',
            'https://',
        ),
        '',
        site_url()
    );
}


/**
 * Check if an admin settings page is ProfilePress'
 *
 * @return bool
 */
function is_pp_admin_page()
{
    $pp_builder_pages = array(
        REGISTRATION_BUILDER_SETTINGS_PAGE_SLUG,
        LOGIN_BUILDER_SETTINGS_PAGE_SLUG,
        PASSWORD_RESET_BUILDER_SETTINGS_PAGE_SLUG,
        EDIT_PROFILE_BUILDER_SETTINGS_PAGE_SLUG,
        USER_PROFILE_BUILDER_SETTINGS_PAGE_SLUG,
        MELANGE_SETTINGS_PAGE_SLUG,
        LICENSE_SETTINGS_PAGE_SLUG,
        PROFILE_FIELDS_SETTINGS_PAGE_SLUG,
        LICENSE_SETTINGS_PAGE_SLUG,
        'pp-config',
        'pp-contact-info',
        'pp-install-theme',
        'pp-extras',
        'pp-social-login',
    );

    return (isset($_GET['page']) && in_array($_GET['page'], $pp_builder_pages));
}


/**
 * Return admin email
 *
 * @return string
 */
function pp_admin_email()
{
    return is_multisite() ? get_blog_option(null, 'admin_email') : get_option('admin_email');
}

/** Save all login generated WP_Error be it from normal login form or from social login shebang */
global $pp_login_wp_errors;
/**
 * Error handler for social login.
 *
 * @param string $error_key WP_Error key
 * @param string $error_value WP_Error value
 */
function pp_login_wp_errors($error_key, $error_value)
{
    global $pp_login_wp_errors;
    $pp_login_wp_errors = new WP_Error();
    $pp_login_wp_errors->add($error_key, $error_value);
}


/**
 * Checks whether the given user ID exists.
 *
 * @param string $user_id ID of user
 *
 * @return null|int The user's ID on success, and null on failure.
 */
function pp_user_id_exist($user_id)
{
    if ($user = get_user_by('id', $user_id)) {
        return $user->ID;
    } else {
        return null;
    }
}

/**
 * Get a user's username by their ID
 *
 * @param int $user_id
 *
 * @return bool|string
 */
function pp_get_username_by_id($user_id)
{
    if (empty($user_id)) {
        return false;
    }

    $user = get_user_by('id', $user_id);

    return $user->user_login;
}

/**
 * front-end profile slug.
 *
 * @return string
 */
function pp_get_profile_slug()
{
    $data = pp_db_data();

    $profile_slug = isset($data['set_user_profile_slug']) ? $data['set_user_profile_slug'] : 'profile';

    return apply_filters('pp_profile_slug', $profile_slug);
}

/**
 * Is plugin license valid?
 *
 * @return bool
 */
function pp_is_license_valid()
{
    $license = get_option('pp_license_status');
    if ($license == 'valid') {
        return true;
    } else {
        return false;
    }
}

/**
 * Is plugin license invalid?
 * @return bool
 */
function pp_is_license_invalid()
{
    $license = get_option('pp_license_status');
    if ($license == 'invalid') {
        return true;
    } else {
        return false;
    }
}

/**
 * Is license empty?
 *
 * @return bool
 */
function pp_is_license_empty()
{
    $license = get_option('pp_license_key');
    if (false == $license || empty($license)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Was license once active?
 */
function pp_license_once_valid()
{
    $license = get_option('pp_license_once_active');
    if ($license == 'true') {
        return true;
    } else {
        return false;
    }
}


/**
 * Filter form field attributes for unofficial attributes.
 *
 * @param array $atts supplied shortcode attributes
 *
 * @return string
 */
function pp_other_field_atts($atts)
{
    if ( ! is_array($atts)) {
        return $atts;
    }
    $official_atts = array('name', 'class', 'id', 'value', 'title', 'required', 'placeholder', 'key');

    $other_atts = array();

    foreach ($atts as $key => $value) {
        if ( ! in_array($key, $official_atts)) {
            $other_atts[$key] = $value;
        }
    }


    $other_atts_html = '';
    foreach ($other_atts as $key => $value) {
        $other_atts_html .= "$key=\"$value\" ";
    }

    return $other_atts_html;
}


/**
 * Update option data in WordPress
 *
 * @param string $option
 * @param mixed $value
 *
 * changed to ppp to avoid conflict with press permit plugin.
 *
 * @return bool
 */
function ppp_update_option($option, $value)
{
    $current_blog_id = get_current_blog_id();

    $return = is_multisite() ? update_blog_option($current_blog_id, $option, $value) : update_option($option, $value);

    return $return;
}


/**
 * Add option data in WordPress.
 *
 * @param string $option
 * @param mixed $value
 *
 * @return bool
 */
function ppp_add_option($option, $value)
{
    $current_blog_id = get_current_blog_id();

    $return = is_multisite() ? add_blog_option($current_blog_id, $option, $value) : add_option($option, $value);

    return $return;
}


/**
 * Delete option data in WordPress
 *
 * @param string $option
 *
 * @return bool
 */
function ppp_delete_option($option)
{
    $current_blog_id = get_current_blog_id();

    $return = is_multisite() ? delete_blog_option($current_blog_id, $option) : delete_option($option);

    return $return;
}

/**
 * Get option data in WordPress
 *
 * @param string $option
 *
 * @return mixed|void
 */
function ppp_get_option($option)
{
    $current_blog_id = get_current_blog_id();

    $return = is_multisite() ? get_blog_option($current_blog_id, $option) : get_option($option);

    return $return;
}


/**
 * Create an index.php file to prevent directory browsing.
 *
 * @param string $location folder path to create the file in.
 */
function pp_create_index_file($location)
{
    $content = "You are not allowed here!";
    $fp      = fopen($location . "/index.php", "wb");
    fwrite($fp, $content);
    fclose($fp);
}

/**
 * Get front-end do password reset form url.
 *
 * @param string $user_login
 * @param string $key
 *
 * @return string
 */
function pp_get_do_password_reset_url($user_login, $key)
{
    $data = pp_db_data();
    // check to ensure custom password reset page is set
    $db_url = isset($data['set_lost_password_url']) ? $data['set_lost_password_url'] : '';

    if (apply_filters('pp_front_end_do_password_reset', true) && ! empty($db_url)) {

        $url = add_query_arg(
            array(
                'key'   => $key,
                'login' => rawurlencode($user_login)
            ),
            pp_password_reset_url()
        );
    } else {
        $url = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login');
    }

    return $url;
}


/**
 * Get the version number of WordPress.
 *
 * @return string
 */
function pp_get_wordpress_version()
{
    return get_bloginfo('version');
}

/**
 * Return true if a field key exist/is multi selectable dropdown.
 *
 * @param $field_key
 *
 * @return bool
 */
function pp_is_select_field_multi_selectable($field_key)
{

    $data = get_option('pp_cpf_select_multi_selectable', array());

    return array_key_exists($field_key, $data);
}

/**
 * Return true if a field key exist/is multi checkbox.
 *
 * @param $field_key
 *
 * @return bool
 */
function pp_is_checkbox_field_multi_selectable($field_key)
{

    $data = get_option('pp_cpf_checkbox_multi_selectable', array());

    return array_key_exists($field_key, $data);
}


/**
 * Return username/username of a user using the user's nicename to do the DB search.
 *
 * @param string $slug
 *
 * @return bool|null|string
 */
function pp_is_slug_nice_name($slug)
{
    global $wpdb;

    $response = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT user_login FROM {$wpdb->prefix}users WHERE user_nicename = '%s'",
            array($slug)
        )
    );

    // if response isn't null, the username/user_login is returned.
    return is_null($response) ? false : $response;
}


/**
 * Add login as passwordless login.
 *
 * @param $data
 *
 * @return bool
 */
function pp_add_passwordless_login($data)
{
    $old = get_option('pp_passwordless_login', array());
    // if value already exist in database, bail.
    if (in_array($data[0], $old)) {
        return;
    }

    $merge = array_merge($old, $data);

    return update_option('pp_passwordless_login', $merge);
}


/**
 * Remove login from list of passwordless login.
 *
 * @param int $login_id
 *
 * @return bool
 */
function pp_remove_passwordless_login($login_id)
{
    $data = get_option('pp_passwordless_login', array());
    if (($key = array_search($login_id, $data)) !== false) {
        unset($data[$key]);
    }

    return update_option('pp_passwordless_login', $data);
}


/**
 * Is a login form passwordless?
 *
 * @param int $login_id
 *
 * @return bool
 */
function pp_is_login_passwordless($login_id)
{
    $data = get_option('pp_passwordless_login', array());

    return in_array($login_id, $data);
}

/**
 * Helper function to output checked if a checkbox has been checked.
 *
 * @param string $key field key
 * @param string $value value of `value` attribute.
 * @param WP_User $wp_user
 *
 * @return string
 */
function pp_multicheckbox_checked($key, $value, $wp_user)
{

    if (isset($_POST[$key]) && is_array(@$_POST[$key]) && in_array($value, @$_POST[$key])) {
        $checked = 'checked="checked"';
    } elseif ( ! isset($_POST[$key]) && is_array($wp_user->$key) && in_array($value, $wp_user->$key)) {
        $checked = 'checked="checked"';
    } elseif ( ! isset($_POST[$key]) && ! is_array($wp_user->$key) && $value == $wp_user->$key) {
        $checked = 'checked="checked"';
    } else {
        $checked = null;
    }

    return $checked;
}


/**
 * Return array of editable roles.
 *
 * @return mixed|void
 */
function pp_get_editable_roles()
{
    $all_roles = wp_roles()->roles;

    if ( ! current_user_can('administrator')) {
        unset($all_roles['administrator']);
    }

    return $all_roles;
}

/**
 * make email content type html @callback function
 *
 * @return string
 */
function pp_mail_content_type_html()
{
    return 'text/html';
}

if ( ! function_exists('wp_new_user_notification')) :
    /**
     * Email login credentials to a newly-registered user.
     *
     * A new user registration notification is also sent to admin email.
     *
     * @since 2.0.0
     * @since 4.3.0 The `$plaintext_pass` parameter was changed to `$notify`.
     * @since 4.3.1 The `$plaintext_pass` parameter was deprecated. `$notify` added as a third parameter.
     * @since 4.6.0 The `$notify` parameter accepts 'user' for sending notification only to the user created.
     *
     * @global wpdb $wpdb WordPress database object for queries.
     * @global PasswordHash $wp_hasher Portable PHP password hashing framework instance.
     *
     * @param int $user_id User ID.
     * @param null $deprecated Not used (argument deprecated).
     * @param string $notify Optional. Type of notification that should happen. Accepts 'admin' or an empty
     *                           string (admin only), 'user', or 'both' (admin and user). Default empty.
     */
    function wp_new_user_notification($user_id, $deprecated = null, $notify = '')
    {
        if ($deprecated !== null) {
            _deprecated_argument(__FUNCTION__, '4.3.1');
        }

        $new_user_notification = apply_filters('pp_new_user_notification', 'enable');

        if ('enable' != $new_user_notification) return;

        global $wpdb, $wp_hasher;
        $user = get_userdata($user_id);

        // The blogname option is escaped with esc_html on the way into the database in sanitize_option
        // we want to reverse this for the plain text arena of emails.
        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

        if ('user' !== $notify) {

            $db_data = pp_db_data();
            $message = $db_data['signup_admin_email_message'];

            if (empty($message)) {
                $message = sprintf(__('New user registration on your site %s:'), $blogname) . "\r\n\r\n";
                $message .= sprintf(__('Username: %s'), $user->user_login) . "\r\n\r\n";
                $message .= sprintf(__('Email: %s'), $user->user_email) . "\r\n";
            } else {

                // handle support for custom fields placeholder.
                preg_match_all('#({{[a-z_-]+}})#', $message, $matches);

                if (isset($matches[1]) && ! empty($matches[1])) {

                    foreach ($matches[1] as $match) {
                        $key = str_replace(['{', '}'], '', $match);

                        if (isset($user->{$key})) {
                            $value = $user->{$key};

                            if (is_array($value)) {
                                $value = implode(', ', $value);
                            }

                            $message = str_replace($match, $value, $message);
                        }
                    }
                }

                $search = array(
                    '{{username}}',
                    '{{user_email}}',
                    '{{site_title}}',
                    '{{first_name}}',
                    '{{last_name}}'
                );

                $replace = array(
                    $user->user_login,
                    $user->user_email,
                    $blogname,
                    $user->first_name,
                    $user->last_name
                );

                $message = htmlspecialchars_decode(
                    apply_filters(
                        'pp_signup_admin_email_message',
                        str_replace($search, $replace, $message),
                        $user
                    )
                );
            }

            $title = sanitize_text_field($db_data['signup_admin_email_subject']);
            $title = empty($title) ? sprintf(__('[%s] New User Registration'), $blogname) : $title;

            $sender_name = sanitize_text_field($db_data['signup_admin_email_sender_name']);
            $sender_name = empty($sender_name) ? $blogname : $sender_name;

            $sender_email = sanitize_text_field($db_data['signup_admin_email_sender_email']);
            $sender_email = empty($sender_email) ? pp_admin_email() : $sender_email;

            // bail if sender email is not valid. Hoping this will reduce support tickets about ajax registration breaking.
            if ( ! is_email($sender_email)) return;

            $content_type = sanitize_text_field($db_data['signup_admin_email_type']);
            $content_type = empty($content_type) ? 'text/plain' : $content_type;

            $headers[]   = "From: $sender_name <$sender_email>";
            $admin_email = apply_filters('pp_signup_notification_admin_email', pp_admin_email());

            if ($content_type == 'text/html') {
                add_filter('wp_mail_content_type', 'pp_mail_content_type_html');

                @wp_mail($admin_email, $title, htmlspecialchars_decode($message), $headers);

                // Reset content-type to avoid conflicts
                remove_filter('wp_mail_content_type', 'pp_mail_content_type_html');
            } else {
                @wp_mail($admin_email, $title, $message, $headers);
            }
        }

        // `$deprecated was pre-4.3 `$plaintext_pass`. An empty `$plaintext_pass` didn't sent a user notifcation.
        if ('admin' === $notify || (empty($deprecated) && empty($notify))) {
            return;
        }

        // Generate something random for a password reset key.
        $key = wp_generate_password(20, false);

        /** This action is documented in wp-login.php */
        do_action('retrieve_password_key', $user->user_login, $key);

        // Now insert the key, hashed, into the DB.
        if (empty($wp_hasher)) {
            require_once ABSPATH . WPINC . '/class-phpass.php';
            $wp_hasher = new PasswordHash(8, true);
        }
        $hashed = time() . ':' . $wp_hasher->HashPassword($key);
        $wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $user->user_login));

        $message = sprintf(__('Username: %s'), $user->user_login) . "\r\n\r\n";
        $message .= __('To set your password, visit the following address:') . "\r\n\r\n";
        $message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . ">\r\n\r\n";

        $message .= wp_login_url() . "\r\n";

        wp_mail($user->user_email, sprintf(__('[%s] Your username and password info'), $blogname), $message);
    }
endif;

/**
 * Does registration form has username requirement disabled?
 *
 * @param int $form_id
 * @param bool $is_melange
 *
 * @return bool
 */
function pp_is_signup_form_username_disabled($form_id, $is_melange = false)
{
    $option_name = "pp_disable_username_requirement_$form_id";

    if ($is_melange === true) {
        $option_name = "pp_disable_username_requirement_melange_$form_id";
    }

    return get_option($option_name, 'no') == 'yes';
}

/**
 * Generate url to reset user's password.
 *
 * @param string $user_login
 *
 * @return string
 */
function pp_generate_password_reset_url($user_login)
{
    global $wpdb, $wp_hasher;

    // Generate something random for a password reset key.
    $key = wp_generate_password(20, false);

    /** This action is documented in wp-login.php */
    do_action('retrieve_password_key', $user_login, $key);

    // Now insert the key, hashed, into the DB.
    if (empty($wp_hasher)) {
        $wp_hasher = new PasswordHash(8, true);
    }

    $hashed = time() . ':' . $wp_hasher->HashPassword($key);
    $wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $user_login));

    return pp_get_do_password_reset_url($user_login, $key);
}

/**
 * Return array of countries.
 *
 * @return mixed
 */
function pp_array_of_world_countries()
{
    return apply_filters('pp_countries_custom_field_data', include(VIEWS . '/data/countries.php'));
}