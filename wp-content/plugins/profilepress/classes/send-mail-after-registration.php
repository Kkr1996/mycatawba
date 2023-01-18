<?php

/**
 * send email containing registration details to user after successful registration
 *
 * @todo set the default value for the from email and name when plugin is updated
 * when building the settings: inform user that if the name field is left empty, they wont be addressed
 * with their name
 */
class Send_Email_After_Registration
{

    static $db_settings_data;

    // user registration details
    private $email, $username, $password, $first_name, $last_name;

    // email headers | sender name and email
    private $welcome_message_sender_name, $welcome_message_sender_email;

    /** constructor poop */
    function __construct($id, $email, $username, $password, $first_name, $last_name)
    {


        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->first_name = $first_name;
        $this->last_name = $last_name;

        // initialize methods
        $this->plugin_db_settings();

        $this->welcome_message();
        $this->formatted_message();
        $this->sender_name_email_header();

        if (self::$db_settings_data['welcome_message_type'] == 'html') {
            add_filter('wp_mail_content_type', 'pp_mail_content_type_html');

            $this->send_welcome_mail();

            // Reset content-type to avoid conflicts
            remove_filter('wp_mail_content_type', 'pp_mail_content_type_html');
        } else {
            $this->send_welcome_mail();
        }
    }

    /*
     * set the plugin_settings to property $db_settings_data
     */
    public function plugin_db_settings()
    {
        $db_settings_data = get_option('pp_settings_data');
        self::$db_settings_data = $db_settings_data;

    }

    public function welcome_message()
    {
        return apply_filters('pp_welcome_message_raw_content', self::$db_settings_data['pp_welcome_message_after_reg']);
    }


    /**
     * Format the email message and replace placeholders with real values
     */
    public function formatted_message()
    {
        $welcome_message = $this->welcome_message();
        $site_title = pp_site_title();

        $search = apply_filters('pp_welcome_message_placeholder_search', array(
            '{{username}}',
            '{{password}}',
            '{{email}}',
            '{{site_title}}',
            '{{first_name}}',
            '{{last_name}}',
            '{{password_reset_link}}'
        ));

        $replace = apply_filters('pp_welcome_message_placeholder_replace', array(
            $this->username,
            $this->password,
            $this->email,
            $site_title,
            $this->first_name,
            $this->last_name,
            pp_generate_password_reset_url($this->username)
        ));

        $formatted_welcome_message = str_replace($search, $replace, $welcome_message);

        return $formatted_welcome_message;

    }

    public function sender_name_email_header()
    {
        // sender name
        if (empty(self::$db_settings_data['welcome_message_sender_name']) || !isset(self::$db_settings_data['welcome_message_sender_name'])) {

            $welcome_message_sender_name = get_option('blogname');
        } else {
            $welcome_message_sender_name = self::$db_settings_data['welcome_message_sender_name'];
        }
        $this->welcome_message_sender_name = apply_filters('pp_welcome_message_sender_name', $welcome_message_sender_name);

        // sender email

        if (empty(self::$db_settings_data['welcome_message_sender_email']) || !isset(self::$db_settings_data['welcome_message_sender_email'])) {

            $welcome_message_sender_email = pp_admin_email();
        } else {
            $welcome_message_sender_email = self::$db_settings_data['welcome_message_sender_email'];
        }

        $this->welcome_message_sender_email = apply_filters('pp_welcome_message_sender_email', $welcome_message_sender_email);
    }


    public function send_welcome_mail()
    {
        // welcome email subject gotten from plugin db
        $welcome_message_subject = apply_filters('pp_welcome_message_subject', self::$db_settings_data['pp_welcome_message_subject']);

        // if email content-type set to "html", decode escaped html in the welcome message
        // else return the original message for "plain-text"
        if (self::$db_settings_data['welcome_message_type'] == 'html') {
            $welcome_message = htmlspecialchars_decode($this->formatted_message());
        } else {
            $welcome_message = $this->formatted_message();
        }

        $welcome_message = apply_filters('pp_welcome_message_content', $welcome_message);

        $headers = "From: $this->welcome_message_sender_name <$this->welcome_message_sender_email>" . "\r\n";

        wp_mail($this->email, $welcome_message_subject, $welcome_message, $headers);
    }

}
