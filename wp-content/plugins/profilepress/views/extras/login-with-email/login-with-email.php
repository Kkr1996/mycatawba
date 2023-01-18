<?php

$db_settings_data = get_option('pp_extra_login_with_email');

if (isset($db_settings_data) && $db_settings_data == 'active') {
    add_filter('authenticate', 'pp_login_with_email', 20, 3);
}

/**
 * Allow_email_login filter to the authenticate filter hook, to fetch a username based on entered email
 *
 * @param  object $user
 * @param  string $username [description]
 * @param  string $password [description]
 *
 * @return boolean
 */
function pp_login_with_email($user, $username, $password)
{
    // $username becomes email if if-condition is true.
    if (is_email($username)) {
        $user = get_user_by('email', $username);

        if (ProfilePress_User_Moderation_Admin::is_block($user->ID)) {
            return new WP_Error('user_blocked', ProfilePress_User_Moderation_Admin::blocked_error_notice());
        } elseif (ProfilePress_User_Moderation_Admin::is_pending($user->ID)) {
            return new WP_Error('user_pending', ProfilePress_User_Moderation_Admin::pending_error_notice());
        } elseif (class_exists('PP_User_Email_Confirmation') && !PP_User_Email_Confirmation::is_user_confirm($user->ID)) {
            $instance = new PP_User_Email_Confirmation();
            return new WP_Error('uec_error', $instance->pending_activation_error($user->ID));
        }

        if (function_exists('wp_authenticate_email_password')) {
            return wp_authenticate_email_password(null, $username, $password);
        } else {
            if (false !== $user) {
                $username = $user->user_login;
            }
            return wp_authenticate_username_password(null, $username, $password);
        }
    }

    return $user;
}