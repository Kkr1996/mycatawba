<?php
/**
 * HTML image for the user profile
 *
 * @param $id_or_email
 * @param string $size
 * @param string $default
 * @param bool $alt
 * @param string $class
 * @param string $id
 *
 * @return mixed
 */
function get_profilepress_avatar($id_or_email, $size = '20', $default = '', $alt = false, $class = '', $css_id = '')
{

    if (!get_option('show_avatars')) {
        return false;
    }

    if (false === $alt) {
        $safe_alt = '';
    } else {
        $safe_alt = esc_attr($alt);
    }

    if (!is_numeric($size)) {
        $size = '96';
    }


    $email = '';
    if (is_numeric($id_or_email)) {
        $id = (int)$id_or_email;
        $user = get_userdata($id);
        if ($user) {
            $email = $user->user_email;
        }
    } elseif (is_object($id_or_email)) {
        // No avatar for pingbacks or trackbacks

        /**
         * Filter the list of allowed comment types for retrieving avatars.
         *
         * @since 3.0.0
         *
         * @param array $types An array of content types. Default only contains 'comment'.
         */
        $allowed_comment_types = apply_filters('get_avatar_comment_types', array('comment'));
        if (!empty($id_or_email->comment_type) && !in_array($id_or_email->comment_type,
                (array)$allowed_comment_types)
        ) {
            return false;
        }


        /** adapter for custom profile avatar */
        // overwrite user avatar who commented and uploaded a custom profile image
        if (is_numeric($id_or_email->user_id)) {
            $avatar_url = get_user_meta($id_or_email->user_id, 'pp_profile_avatar', true);

            if (!empty($avatar_url)) {
                $avatar = "<img data-del=\"avatar\" alt='{$safe_alt}' src='" . AVATAR_UPLOAD_URL . "{$avatar_url}' class='{$class} avatar avatar-{$size} photo' height='{$size}' id='$css_id' width='{$size}' />";

                return apply_filters('get_avatar', $avatar, $id_or_email, $size, $default, $alt);
            }
        }
        /* adapter ends here */


        if (!empty($id_or_email->user_id)) {
            $id = (int)$id_or_email->user_id;
            $user = get_userdata($id);
            if ($user) {
                $email = $user->user_email;
            }
        }

        if (!$email && !empty($id_or_email->comment_author_email)) {
            $email = $id_or_email->comment_author_email;
        }
    } else {
        $email = $id_or_email;
    }


    /** adapter for custom profile avatar */
    if (is_email($email)) {
        $userdata = get_user_by('email', $email);
        $user_id = @$userdata->ID;
        $avatar_url = get_user_meta($user_id, 'pp_profile_avatar', true);
    }

    if (!empty($avatar_url)) {
        $avatar = "<img data-del=\"avatar\" alt='{$safe_alt}' src='" . AVATAR_UPLOAD_URL . "{$avatar_url}' class='{$class} avatar avatar-{$size} photo' height='{$size}' id='{$css_id}' width='{$size}' />";

        return apply_filters('get_avatar', $avatar, $id_or_email, $size, $default, $alt);
    } else {
        /** adapter ends here */

        if (empty($default)) {
            $avatar_default = get_option('avatar_default');
            if (empty($avatar_default)) {
                $default = 'mystery';
            } else {
                $default = $avatar_default;
            }
        }

        if (!empty($email)) {
            $email_hash = md5(strtolower(trim($email)));
        }

        if (is_ssl()) {
            $host = 'https://secure.gravatar.com';
        } else {
            if (!empty($email)) {
                $host = sprintf("http://%d.gravatar.com", (hexdec($email_hash[0]) % 2));
            } else {
                $host = 'http://0.gravatar.com';
            }
        }

        if ('mystery' == $default) {
            $default = "$host/avatar/ad516503a11cd5ca435acc9bb6523536?s={$size}";
        } // ad516503a11cd5ca435acc9bb6523536 == md5('unknown@gravatar.com')
        elseif ('blank' == $default) {
            $default = $email ? 'blank' : includes_url('images/blank.gif');
        } elseif (!empty($email) && 'gravatar_default' == $default) {
            $default = '';
        } elseif ('gravatar_default' == $default) {
            $default = "$host/avatar/?s={$size}";
        } elseif (empty($email)) {
            $default = "$host/avatar/?d=$default&amp;s={$size}";
        } elseif (strpos($default, 'http://') === 0) {
            $default = add_query_arg('s', $size, $default);
        }

        if (!empty($email)) {
            $out = "$host/avatar/";
            $out .= $email_hash;
            $out .= '?s=' . $size;
            $out .= '&amp;d=' . urlencode($default);

            $rating = get_option('avatar_rating');
            if (!empty($rating)) {
                $out .= "&amp;r={$rating}";
            }

            if (is_array($class)) {
                $class = implode(',', $class);
            }

            $out = str_replace('&#038;', '&amp;', esc_url($out));
            $avatar = "<img data-del=\"avatar\" alt='{$safe_alt}' src='{$out}' class='avatar avatar-{$size} photo {$class}' height='{$size}' width='{$size}' id='{$css_id}' />";
        } else {
            $out = esc_url_raw($default);
            $avatar = "<img data-del=\"avatar\" alt='{$safe_alt}' src='{$out}' class='avatar avatar-{$size} photo avatar-default {$class}' height='{$size}' width='{$size}' id='{$css_id}'/>";
        }
    }

    /**
     * Filter the avatar to retrieve.
     *
     * @since 2.5.0
     *
     * @param string $avatar Image tag for the user's avatar.
     * @param int|object|string $id_or_email A user ID, email address, or comment object.
     * @param int $size Square avatar width and height in pixels to retrieve.
     * @param string $alt Alternative text to use in the avatar image tag.
     *                                       Default empty.
     */

    return apply_filters('get_avatar', $avatar, $id_or_email, $size, $default, $alt);
}

function pp_get_avatar_url($id_or_email = '', $size = null)
{
    $avatar = false;
    if (!get_option('show_avatars')) {
        return false;
    }

    $id_or_email = !empty($id_or_email) ? $id_or_email : wp_get_current_user()->user_email;

    $default = '';

    $size = '96';

    $email = '';
    if (is_numeric($id_or_email)) {
        $id = (int)$id_or_email;
        $user = get_userdata($id);
        if ($user) {
            $email = $user->user_email;
        }
    } elseif (is_object($id_or_email)) {
        // No avatar for pingbacks or trackbacks

        /** adapter for custom profile avatar */
        // overwrite user avatar who commented and uploaded a custom profile image
        if (is_numeric($id_or_email->user_id)) {
            $avatar_url = get_user_meta($id_or_email->user_id, 'pp_profile_avatar', true);

            if (!empty($avatar_url) && is_string($avatar_url)) {
                $avatar = AVATAR_UPLOAD_URL . "$avatar_url";

                return $avatar;
            }
        }
        /* adapter ends here */


        if (!empty($id_or_email->user_id)) {
            $id = (int)$id_or_email->user_id;
            $user = get_userdata($id);
            if ($user) {
                $email = $user->user_email;
            }
        }

        if (!$email && !empty($id_or_email->comment_author_email)) {
            $email = $id_or_email->comment_author_email;
        }
    } else {
        $email = $id_or_email;
    }


    /** adapter for custom profile avatar */
    if (is_email($email)) {
        $userdata = get_user_by('email', $email);
        $user_id = @$userdata->ID;
        $avatar_url = get_user_meta($user_id, 'pp_profile_avatar', true);
    }

    if (!empty($avatar_url) && is_string($avatar_url)) {
        $avatar = AVATAR_UPLOAD_URL . $avatar_url;

        return $avatar;
    } else {
        /** adapter ends here */

        if (empty($default)) {
            $avatar_default = get_option('avatar_default');
            if (empty($avatar_default)) {
                $default = 'mystery';
            } else {
                $default = $avatar_default;
            }
        }

        if (!empty($email)) {
            $email_hash = md5(strtolower(trim($email)));
        }

        if (is_ssl()) {
            $host = 'https://secure.gravatar.com';
        } else {
            if (!empty($email)) {
                $host = sprintf("http://%d.gravatar.com", (hexdec($email_hash[0]) % 2));
            } else {
                $host = 'http://0.gravatar.com';
            }
        }

        if ('mystery' == $default) {
            $default = "$host/avatar/ad516503a11cd5ca435acc9bb6523536?s={$size}";
        } // ad516503a11cd5ca435acc9bb6523536 == md5('unknown@gravatar.com')
        elseif ('blank' == $default) {
            $default = $email ? 'blank' : includes_url('images/blank.gif');
        } elseif (!empty($email) && 'gravatar_default' == $default) {
            $default = '';
        } elseif ('gravatar_default' == $default) {
            $default = "$host/avatar/?s={$size}";
        } elseif (empty($email)) {
            $default = "$host/avatar/?d=$default&amp;s={$size}";
        } elseif (strpos($default, 'http://') === 0) {
            $default = add_query_arg('s', $size, $default);
        }

        if (!empty($email)) {
            $out = "$host/avatar/";
            $out .= $email_hash;
            $out .= '?s=' . $size;
            $out .= '&amp;d=' . urlencode($default);

            $rating = get_option('avatar_rating');
            if (!empty($rating)) {
                $out .= "&amp;r={$rating}";
            }

            $out = str_replace('&#038;', '&amp;', esc_url($out));
            $avatar = $out;
        } else {
            $out = esc_url($default);
            $avatar = $out;
        }
    }

    return $avatar;
}

/**
 * Culled and then modified from get_avatar_data()
 *
 * @param $url
 * @param $id_or_email
 * @param $args
 *
 * @return string
 */
function pp_override_avatar_url($url, $id_or_email, $args)
{
    if (is_object($id_or_email) && isset($id_or_email->comment_ID)) {
        $id_or_email = get_comment($id_or_email);
    }

    // Process the user identifier.
    if (is_numeric($id_or_email)) {
        $user_id = absint($id_or_email);
    } elseif (is_string($id_or_email)) {
        // if this is not gravatar hash, then string apparently is email.
        if (!strpos($id_or_email, '@md5.gravatar.com')) {
            // email address
            $email = $id_or_email;
            $userdata = get_user_by('email', $email);
            $user_id = @$userdata->ID;
        }
    } elseif ($id_or_email instanceof WP_User) {
        // User Object
        $user_id = $id_or_email->ID;
    } elseif ($id_or_email instanceof WP_Post) {
        // Post Object
        $user_id = (int)$id_or_email->post_author;
    } elseif ($id_or_email instanceof WP_Comment) {
        /**
         * Filters the list of allowed comment types for retrieving avatars.
         *
         * @since 3.0.0
         *
         * @param array $types An array of content types. Default only contains 'comment'.
         */
        $allowed_comment_types = apply_filters('get_avatar_comment_types', array('comment'));
        if (!empty($id_or_email->comment_type) && !in_array($id_or_email->comment_type,
                (array)$allowed_comment_types)
        ) {
            $args['url'] = false;

            /** This filter is documented in wp-includes/link-template.php */
            return apply_filters('get_avatar_data', $args, $id_or_email);
        }

        if (!empty($id_or_email->user_id)) {
            $user_id = (int)$id_or_email->user_id;
        }
        if (!empty($id_or_email->comment_author_email)) {
            $email = $id_or_email->comment_author_email;
            $userdata = get_user_by('email', $email);
            if (is_object($userdata)) {
                $user_id = $userdata->ID;
            }
        }
    }


    /** adapter for custom profile avatar */
    if (!empty($user_id)) {
        $avatar_url = get_user_meta($user_id, 'pp_profile_avatar', true);
    }

    if (!empty($avatar_url) && is_string($avatar_url)) {
        $url = AVATAR_UPLOAD_URL . $avatar_url;
    }

    return $url;
}

if (pp_get_wordpress_version() < '4.2' && !function_exists('get_avatar')) {

    /**
     * Modify default avatar.
     *
     * @param $id_or_email
     * @param string $size
     * @param string $default
     * @param bool $alt
     * @param string $class
     *
     * @return mixed|void
     */
    function get_avatar($id_or_email, $size = '20', $default = '', $alt = false, $class = '')
    {
        return get_profilepress_avatar($id_or_email, $size, $default, $alt, $class);
    }
} else {
    add_filter('get_avatar_url', 'pp_override_avatar_url', 10, 3);
}