<?php

/**
 * Class PP_BuddyPress_Avatar
 * Override BuddyPress avatar display with ProfilePress
 */
class PP_BuddyPress_Avatar
{

    public static function init()
    {
        $db_data = pp_db_data();

        if ( ! empty($db_data['override_bp_avatar']) && $db_data['override_bp_avatar'] == 'yes') {
            add_filter('bp_core_fetch_avatar', array(__CLASS__, 'override_html_avatar'), 99, 3);
            add_filter('bp_core_fetch_avatar_url', array(__CLASS__, 'override_avatar_url'), 99, 2);
        }
        if ( ! empty($db_data['override_bp_profile_url']) && $db_data['override_bp_profile_url'] == 'yes') {
            add_filter('bp_core_get_user_domain', array(__CLASS__, 'override_bp_profile_url'), 99, 4);
        }

        if ( ! empty($db_data['override_bbp_profile_url']) && $db_data['override_bbp_profile_url'] == 'yes') {
            add_filter('bbp_pre_get_user_profile_url', array(__CLASS__, 'override_bbp_profile_url'), 99);
        }

    }

    public static function override_bp_profile_url($domain, $user_id, $user_nicename, $user_login)
    {
        if ( ! $user_login) {
            $user_login = pp_get_username_by_id($user_id);
        }

        return home_url(pp_get_profile_slug() . '/') . $user_login;
    }


    public static function override_bbp_profile_url($user_id)
    {
        if (is_int($user_id)) {
            $user_info = get_userdata($user_id);
            $username  = $user_info->user_login;

            return home_url(pp_get_profile_slug() . '/') . $username;
        }

        return $user_id;
    }


    /**
     * Override HTML BP avatar output.
     *
     * @param string $image_in_html
     * @param array $params
     * @param int $item_id
     *
     * @return mixed
     */
    public static function override_html_avatar($image_in_html, $params, $item_id)
    {
        if (isset($params['object']) && 'user' == $params['object']) {
            $user_id    = $item_id;
            $avatar_url = pp_get_avatar_url($user_id);

            if ( ! empty($avatar_url)) {
                return preg_replace('/src=".+?"/', 'src="' . $avatar_url . '"', $image_in_html);
            }
        }

        return $image_in_html;
    }


    /**
     * Override BP avatar url.
     *
     * @param string $image_url
     * @param array $params
     *
     * @return bool|mixed|string
     */
    public static function override_avatar_url($image_url, $params)
    {
        if (isset($params['object']) && 'user' == $params['object']) {
            $user_id   = $params['item_id'];
            $image_url = pp_get_avatar_url($user_id);
        }

        return $image_url;
    }
}

PP_BuddyPress_Avatar::init();