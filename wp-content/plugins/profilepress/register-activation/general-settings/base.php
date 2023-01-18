<?php
// Global namespace
namespace {

    require_once REGISTER_ACTIVATION . '/pp-default-pages/edit-profile.php';
    require_once REGISTER_ACTIVATION . '/pp-default-pages/login.php';
    require_once REGISTER_ACTIVATION . '/pp-default-pages/registration.php';
    require_once REGISTER_ACTIVATION . '/pp-default-pages/front-end-profile.php';
    require_once REGISTER_ACTIVATION . '/pp-default-pages/password-reset.php';
}


namespace general_settings {

    /** Default plugin data for general settings */
    class General_Settings
    {

        /** Save default plugin settings on activation */
        public static function instance()
        {

            $site_title = pp_site_title();
            $site_url_without_scheme = pp_site_url_without_scheme();

            $welcome_message = <<<HTML
Welcome {{first_name}} {{last_name}} to $site_title

Below is your Login details:

Username: {{username}}
Password: the password you registered with.

Cheers.
HTML;

            $password_reset_reset = <<<HTML
Someone requested to reset the password for the following account:

Username: {{username}}

If this was a mistake, just ignore this email and nothing will happen.

To reset your password, click the link below:

{{password_reset_link}}


Cheers.
HTML;

            $signup_admin_email_content = <<<HTML
New user registration on your site New user registration on your site {{site_title}}:

Username: {{username}}

Email: {{user_email}}
HTML;

            $account_status_pending_message = <<<MESSAGE
Hi {{first_name}} {{last_name}}, your account is pending approval. You will receive a mail once your account is approved.

Regards.
MESSAGE;

            $account_status_approval_message = <<<MESSAGE
Hi {{first_name}} {{last_name}}, your account with username "{{username}}" has been approved.

Cheers.
MESSAGE;

            $account_status_block_message = <<<MESSAGE
Hi {{first_name}} {{last_name}}, your account with username "{{username}}" has been blocked.

Regards.
MESSAGE;

            $account_status_unblock_message = <<<MESSAGE
Hi {{first_name}} {{last_name}}, your account with username "{{username}}" has been unblocked.

Regards.
MESSAGE;

            $general_settings = array();

            $general_settings['set_log_out_url'] = 'current_view_page';
            $general_settings['set_login_redirect'] = 'dashboard';

            $general_settings['welcome_message_sender_name'] = $site_title;
            $general_settings['welcome_message_sender_email'] = pp_admin_email();
            $general_settings['welcome_message_type'] = 'plain-text';
            $general_settings['pp_welcome_message_subject'] = "Welcome To $site_title";
            $general_settings['pp_welcome_message_after_reg'] = $welcome_message;

            $general_settings['set_user_profile_slug'] = 'profile';

            $general_settings['password_reset_sender_name'] = "User Protection Dept. - $site_title";
            $general_settings['password_reset_sender_email'] = "noreply@$site_url_without_scheme";
            $general_settings['password_reset_type'] = 'text/plain';
            $general_settings['password_reset_subject'] = "[$site_title] Password Reset.";
            $general_settings['password_reset_message'] = $password_reset_reset;

            $general_settings['signup_admin_email_sender_name'] = "$site_title";
            $general_settings['signup_admin_email_sender_email'] = "noreply@$site_url_without_scheme";
            $general_settings['signup_admin_email_type'] = 'text/plain';
            $general_settings['signup_admin_email_subject'] = sprintf(__('[%s] New User Registration'), $site_title);
            $general_settings['signup_admin_email_message'] = $signup_admin_email_content;

            $general_settings['account_status_sender_name'] = $site_title;
            $general_settings['account_status_sender_email'] = "noreply@$site_url_without_scheme";
            $general_settings['account_status_type'] = 'text/plain';
            $general_settings['account_status_subject'] = "Account Status Notification";
            $general_settings['account_status_approval_message'] = $account_status_approval_message;
            $general_settings['account_status_pending_message'] = $account_status_pending_message;
            $general_settings['account_status_block_message'] = $account_status_block_message;
            $general_settings['account_status_unblock_message'] = $account_status_unblock_message;

            $general_settings['set_login_url'] = \pp_default_pages\Login::instance();
            $general_settings['set_registration_url'] = \pp_default_pages\Registration::instance();
            $general_settings['edit_user_profile_url'] = \pp_default_pages\Edit_Profile::instance();
            $general_settings['set_lost_password_url'] = \pp_default_pages\Password_Reset::instance();
            $general_settings['set_user_profile_shortcode'] = \pp_default_pages\Front_End_Profile::instance();
            $general_settings['redirect_default_edit_profile_to_custom'] = 'yes';
            $general_settings['set_welcome_message_after_reg'] = 'on';

            if (is_multisite()) {
                add_blog_option(null, 'pp_settings_data', $general_settings);
            } else {
                add_option('pp_settings_data', $general_settings);
            }

        }
    }

}