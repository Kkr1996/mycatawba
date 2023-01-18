<?php

$db_settings_data = get_option('pp_social_login');

$social_login_base_url = PROFILEPRESS_ROOT_URL . 'social-login/';
return apply_filters('pp_social_login_config',
    array(
        "base_url" => $social_login_base_url,
        "providers" => array(
            "Google" => array(
                "enabled" => true,
                "scope" => apply_filters(
                    'pp_social_login_google_scope',
                    "profile " . // optional
                    "https://www.googleapis.com/auth/plus.profile.emails.read"
                ), // optional
                "keys" => array(
                    "id" => trim($db_settings_data['google_client_id']),
                    "secret" => trim($db_settings_data['google_client_secret']),
                ),
                "approval_prompt" => "auto",     // optional
                "access_type" => "online",     // optional
            ),
            "Facebook" => array(
                "enabled" => true,
                "keys" => array(
                    "id" => trim($db_settings_data['facebook_id']),
                    "secret" => trim($db_settings_data['facebook_secret']),
                ),
                "trustForwarded" => true,
                'photo_size' => 500,
                "scope" => apply_filters(
                    'pp_social_login_facebook_scope',
                    "public_profile, email"
                ), // optional
                "display" => "page" // optional
            ),
            "Twitter" => array(
                "enabled" => true,
                "keys" => array(
                    "key" => trim($db_settings_data['twitter_consumer_key']),
                    "secret" => trim($db_settings_data['twitter_consumer_secret']),
                ),
                'includeEmail' => true,
            ),
            "LinkedIn" => array(
                "enabled" => true,
                "keys" => array(
                    "id" => trim($db_settings_data['linkedin_consumer_key']),
                    "secret" => trim($db_settings_data['linkedin_consumer_secret']),
                ),
            ),
            "Github" => array(
                "enabled" => true,
                "keys" => array(
                    "id" => trim($db_settings_data['github_client_id']),
                    "secret" => trim($db_settings_data['github_client_secret']),
                ),
                "scope" => 'user:email',
                "wrapper" => array(
                    "path" => PROFILEPRESS_ROOT . "/vendor/hybridauth/hybridauth/additional-providers/hybridauth-github/Providers/GitHub.php",
                    "class" => "Hybrid_Providers_GitHub",
                ),
            ),
            "Vkontakte" => array(
                "enabled" => true,
                "keys" => array(
                    "id" => trim($db_settings_data['vk_application_id']),
                    "secret" => trim($db_settings_data['vk_secure_key']),
                ),
            ),
        ),
        // If you want to enable logging, set 'debug_mode' to true.
        // You can also set it to
        // - "error" To log only error messages. Useful in production
        // - "info" To log info and error messages (ignore debug messages)
        "debug_mode" => apply_filters('pp_social_login_debug', false),
        // Path to file writable by the web server. Required if 'debug_mode' is not false
        "debug_file" => dirname(__FILE__) . "/error.log",
    )
);
