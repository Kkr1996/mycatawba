<?php

/**
 * Base class for sending notification when a user account is approved, blocked and unblocked.
 */
class PP_User_Moderation_Notification
{

    /** @var array General setting DB data */
    static protected $db_settings_data;

    /** @var  string email sender name */
    static protected $sender_name;

    /** @var string sender email */
    static protected $sender_email;

    /** @var  string email content-type */
    static protected $content_type;

    /** @var  string email subject */
    static protected $subject;

    /** @var  string pending approval notification email */
    static protected $pending_message;

    /** @var  string approval email message */
    static protected $approval_message;

    /** @var  string blocked notification message */
    static protected $block_message;

    /** @var  string unblock notification email */
    static protected $unblock_message;

    /** @var  string is notification disabled? */
    static protected $notification_disabled;

    public static function initialize()
    {
        $db_settings_data = get_option('pp_settings_data');

        self::$notification_disabled = @$db_settings_data['disable_account_status_notification'];

        self::$sender_name = @$db_settings_data['account_status_sender_name'];

        self::$sender_email = @$db_settings_data['account_status_sender_email'];

        self::$content_type = @$db_settings_data['account_status_type'];

        self::$subject = apply_filters('pp_moderation_notification_subject', @$db_settings_data['account_status_subject']);

        self::$pending_message = @$db_settings_data['account_status_pending_message'];

        self::$approval_message = apply_filters('pp_user_approved_notification', @$db_settings_data['account_status_approval_message']);

        self::$block_message = apply_filters('pp_user_blocked_notification', @$db_settings_data['account_status_block_message']);

        self::$unblock_message = apply_filters('pp_user_unblocked_notification', @$db_settings_data['account_status_unblock_message']);
    }


    public static function send_mail($user_id, $message)
    {
        $headers = 'From: ' . self::$sender_name . ' <' . self::$sender_email . '>' . "\r\n";

        // if content-type is HTML
        if (self::$content_type == 'text/html') {
            add_filter('wp_mail_content_type', 'pp_mail_content_type_html');

            wp_mail(self::user_id_email($user_id), self::$subject, htmlspecialchars_decode(self::format_message($user_id, $message)), $headers);

            remove_filter('wp_mail_content_type', 'pp_mail_content_type_html');
        } else {
            wp_mail(self::user_id_email($user_id), self::$subject, self::format_message($user_id, $message), $headers);
        }
    }


    /**
     * Return formatted email message by replacing placeholders with actual values
     *
     * @param int $user_id ID of user
     * @param string $message message to format
     *
     * @return mixed string
     */
    public static function format_message($user_id, $message)
    {
        $user_data = get_userdata($user_id);

        $search = array(
            '{{username}}',
            '{{email}}',
            '{{first_name}}',
            '{{last_name}}'
        );

        $replace = array(
            $user_data->user_login,
            $user_data->user_email,
            $user_data->first_name,
            $user_data->last_name
        );

        return str_replace($search, $replace, $message);
    }

    /**
     * Return the email address associated with a user ID
     *
     * @param int $user_id ID of user
     *
     * @return mixed string email address
     */
    public static function user_id_email($user_id)
    {
        $user_data = get_userdata($user_id);

        return $user_data->user_email;
    }

    /**
     * Send Approval notification
     *
     * @param $user_id
     */
    public static function approve($user_id)
    {
        if (!empty(self::$notification_disabled) && self::$notification_disabled == 'on') return;

        /**
         * Notification Action that is fires when user is approved
         *
         * @param int $user_id ID pf user that is being approved
         */
        do_action('pp_approval_notification', $user_id);

        $send_approval_mail = apply_filters('pp_disable_approval_notification', false, $user_id);
        if (!$send_approval_mail) {
            self::send_mail($user_id, self::$approval_message);
        }
    }

    /**
     * Send account blocked notification
     *
     * @param $user_id
     */
    public static function block($user_id)
    {
        if (!empty(self::$notification_disabled) && self::$notification_disabled == 'on') return;

        /**
         * Notification Action that is fires when user is blocked
         *
         * @param int $user_id ID pf user that is being blocked
         */
        do_action('pp_blocked_notification', $user_id);

        $send_pending_mail = apply_filters('pp_disable_blocked_notification', false, $user_id);
        if (!$send_pending_mail) {
            self::send_mail($user_id, self::$block_message);
        }
    }


    /**
     * Send unblock notification
     *
     * @param $user_id
     */
    public static function unblock($user_id)
    {
        if (!empty(self::$notification_disabled) && self::$notification_disabled == 'on') return;

        /**
         * Notification Action that is fires when user is unblocked
         *
         * @param int $user_id ID pf user that is being unblocked
         */
        do_action('pp_unblocked_notification', $user_id);

        $send_unblock_mail = apply_filters('pp_disable_unblocked_notification', false, $user_id);
        if (!$send_unblock_mail) {
            self::send_mail($user_id, self::$unblock_message);
        }
    }


    /**
     * Send notification to users pending approval after signup
     *
     * @param $user_id
     */
    public static function pending($user_id)
    {
        if (!empty(self::$notification_disabled) && self::$notification_disabled == 'on') return;

        /**
         * Notification Action that is fires when user is pending approval.
         *
         * @param int $user_id ID pf user that is pending approval.
         */
        do_action('pp_pending_notification', $user_id);

        $send_pending_mail = apply_filters('pp_disable_pending_notification', false, $user_id);
        if (!$send_pending_mail) {
            self::send_mail($user_id, self::$pending_message);
        }
    }


    /**
     * Send notification to admin when a user is pending approval.
     *
     * @param $user_id
     */
    public static function pending_admin_notification($user_id)
    {
        if (apply_filters('pp_disable_pending_admin_notification', false) === true) return;

        /** Replace placeholders with actual values in notification message to admin */
        function pp_parse_admin_notification($message, $username, $email, $first_name, $last_name, $approval_url, $block_url)
        {
            $search = apply_filters('pp_pending_admin_notification_placeholder_search',
                array('{{username}}', '{{email}}', '{{first_name}}', '{{last_name}}', '{{approval_url}}', '{{block_url}}')
            );

            $replace = apply_filters('pp_pending_admin_notification_placeholder_replace',
                array($username, $email, $first_name, $last_name, $approval_url, $block_url)
            );

            return str_replace($search, $replace, $message);
        }

        $db_settings_data = get_option('pp_extra_moderation');

        $user = get_userdata($user_id);

        // The blogname option is escaped with esc_html on the way into the database in sanitize_option
        // we want to reverse this for the plain text arena of emails.
        $blogname = pp_site_title();
        $username = $user->user_login;
        $email = $user->user_email;
        $first_name = $user->first_name;
        $last_name = $user->last_name;
        $approval_url = admin_url("users.php?action=pp_approve_user&id=$user_id");
        $block_url = admin_url("users.php?action=pp_block_user&id=$user_id");

        $subject = !empty($db_settings_data['notification_subject']) ? esc_attr($db_settings_data['notification_subject']) : sprintf(__('[%s] New User Pending Moderation', 'profilepress'), $blogname);

        // default notification message
        $df_message = sprintf(__('A new user is is waiting for your approval on your site %s:'), $blogname) . "\r\n\r\n";
        $df_message .= sprintf(__('Username: %s'), $user->user_login) . "\r\n";
        $df_message .= sprintf(__('E-mail: %s'), $user->user_email) . "\r\n\r\n";
        $df_message .= sprintf(__('Click to approve: %s'), $approval_url) . "\r\n";
        $df_message .= sprintf(__('Click to block: %s'), $block_url) . "\r\n";

        $message = empty($db_settings_data['notification_content']) ? $df_message : pp_parse_admin_notification($db_settings_data['notification_content'], $username, $email, $first_name, $last_name, $approval_url, $block_url);

        // if content-type is HTML
        if (apply_filters('pp_pending_user_admin_notification_content_type', 'text/plain') == 'text/html') {

            add_filter('wp_mail_content_type', 'pp_mail_content_type_html');

            wp_mail(apply_filters('pp_pending_user_admin_notification_email', pp_admin_email()), $subject, $message);

            remove_filter('wp_mail_content_type', 'pp_mail_content_type_html');
        } else {

            wp_mail(apply_filters('pp_pending_user_admin_notification_email', pp_admin_email()), $subject, $message);
        }
    }
}

PP_User_Moderation_Notification::initialize();