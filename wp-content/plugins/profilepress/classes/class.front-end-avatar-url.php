<?php

class ProfilePress_Avatar_Url
{

    /**
     * Modified get_pp_avatar() to return just the image url
     *
     * @param $id_or_email
     * @param string $size
     * @param string $default
     *
     * @return mixed|string
     */
    public static function get_avatar_url($id_or_email, $size = '20', $default = '')
    {

        /** adapter for custom profile avatar */
        if (is_numeric($id_or_email)) {
            $avatar_url = get_user_meta($id_or_email, 'pp_profile_avatar', true);

            if ( ! empty($avatar_url)) {

                return AVATAR_UPLOAD_URL . $avatar_url;
            }
        }
        /** adapter ends here */


        $email = '';
        if (is_numeric($id_or_email)) {
            $id   = (int)$id_or_email;
            $user = get_userdata($id);
            if ($user) {
                $email = $user->user_email;
            }
        } else {
            $email = $id_or_email;
        }

        if (empty($default)) {
            $avatar_default = get_option('avatar_default');
            if (empty($avatar_default)) {
                $default = 'mystery';
            } else {
                $default = $avatar_default;
            }
        }

        if ( ! empty($email)) {
            $email_hash = md5(strtolower(trim($email)));
        }

        if (is_ssl()) {
            $host = 'https://secure.gravatar.com';
        } else {
            if ( ! empty($email)) {
                $host = sprintf('http://%d.gravatar.com', (hexdec($email_hash[0]) % 2));
            } else {
                $host = 'http://0.gravatar.com';
            }
        }

        if ('mystery' == $default) {
            $default = "$host/avatar/ad516503a11cd5ca435acc9bb6523536?s={$size}";
        } // ad516503a11cd5ca435acc9bb6523536 == md5('unknown@gravatar.com')
        elseif ('blank' == $default) {
            $default = $email ? 'blank' : includes_url('images/blank.gif');
        } elseif ( ! empty($email) && 'gravatar_default' == $default) {
            $default = '';
        } elseif ('gravatar_default' == $default) {
            $default = "$host/avatar/?s={$size}";
        } elseif (empty($email)) {
            $default = "$host/avatar/?d=$default&amp;s={$size}";
        } elseif (strpos($default, 'http://') === 0) {
            $default = add_query_arg('s', $size, $default);
        }

        if ( ! empty($email)) {
            $out = "$host/avatar/";
            $out .= $email_hash;
            $out .= '?s=' . $size;
            $out .= '&amp;d=' . urlencode($default);

            $rating = get_option('avatar_rating');
            if ( ! empty($rating)) {
                $out .= "&amp;r={$rating}";
            }

            $out    = str_replace('&#038;', '&amp;', esc_url($out));
            $avatar = $out;
        } else {
            $out    = esc_url_raw($default);
            $avatar = $out;
        }

        return $avatar;
    }
}