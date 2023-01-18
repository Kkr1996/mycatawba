<?php

class PP_ProfilePress_Recaptcha
{

    /** @var string captcha site key */
    static private $site_key;

    /** @var string captcha secrete key */
    static private $secret_key;

    static protected $theme;

    static protected $language;

    static protected $error_message;

    static protected $plugin_options;

    static protected $recaptcha_activated;

    /** initialize class functions */
    public static function initialize()
    {

        self::$plugin_options = get_option('pp_extra_recaptcha');

        self::$site_key = self::$plugin_options['site_key'];

        self::$secret_key = self::$plugin_options['secret_key'];

        self::$theme = self::$plugin_options['theme'];

        self::$language = self::$plugin_options['language'];

        self::$error_message = self::$plugin_options['error_message'];

        self::$recaptcha_activated = self::$plugin_options['activate_recaptcha'];

        if (self::$recaptcha_activated == 'active') {
            add_action('wp_head', array(__CLASS__, 'header_script'));
        }

    }


    /** reCAPTCHA header script */
    public static function header_script()
    {
        $lang_option = self::$plugin_options['language'];

        // if language is empty (auto detected chosen) do nothing otherwise add the lang query to the
        // reCAPTCHA script url
        if (isset($lang_option) && (!empty($lang_option))) {
            $lang = "?hl=$lang_option";
        } else {
            $lang = null;
        }

        echo apply_filters('pp_google_recaptcha_script', '<script src="https://www.google.com/recaptcha/api.js' . $lang . '" async defer></script>', $lang) . "\r\n";
    }


    /** Output the reCAPTCHA form field. */
    public static function display_captcha()
    {

        if (self::$recaptcha_activated == 'active') {

            return '<input type="hidden" name="pp-is-recaptcha-active" value="true"><div class="g-recaptcha" data-sitekey="' . self::$site_key . '" data-theme="' . self::$theme . '"></div>';
        } else {
            return __('reCAPTCHA is not activated.', 'profilepress');
        }
    }

    /**
     * Send a GET request to verify captcha challenge
     *
     * @return bool
     */
    public static function captcha_verification()
    {
        $response = isset($_POST['g-recaptcha-response']) ? esc_attr($_POST['g-recaptcha-response']) : '';

        $remote_ip = $_SERVER["REMOTE_ADDR"];

        // make a GET request to the Google reCAPTCHA Server
        $request = wp_remote_get(
            'https://www.google.com/recaptcha/api/siteverify?secret=' . self::$secret_key . '&response=' . $response . '&remoteip=' . $remote_ip
        );

        // get the request response body
        $response_body = wp_remote_retrieve_body($request);

        $result = json_decode($response_body, true);

        return $result['success'];
    }

}

PP_ProfilePress_Recaptcha::initialize();