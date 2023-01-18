<?php

add_shortcode('social-button', 'pp_zocial_login_buttons');

/**
 * Callback for zocial login form
 *
 * @param array $att
 * @param string $content
 *
 * @return string
 */
function pp_zocial_login_buttons($att, $content)
{

    $sc = shortcode_atts(
        array(
            'type' => 'facebook',
            'size' => ''
        ),
        $att
    );

    // global variable
    $size      = $sc['size'];
    $size_attr = ! empty($size) ? "font-size:{$size}px" : null;

    switch ($sc['type']) {
        case 'facebook':
            $type = 'facebook';
            $text = ( ! empty($content)) ? $content : 'Sign in with Facebook';
            $url  = ProfilePress_Global_Shortcodes::facebook_login_url();
            break;

        case 'google':
            $type = 'googleplus';
            $text = ( ! empty($content)) ? $content : 'Sign in with Google';
            $url  = ProfilePress_Global_Shortcodes::google_login_url();
            break;

        case 'linkedin':
            $type = 'linkedin';
            $text = ( ! empty($content)) ? $content : 'Sign in with LinkedIn';
            $url  = ProfilePress_Global_Shortcodes::linkedin_login_url();
            break;

        case 'twitter':
            $type = 'twitter';
            $text = ( ! empty($content)) ? $content : 'Sign in with Twitter';
            $url  = ProfilePress_Global_Shortcodes::twitter_login_url();
            break;

        case 'github':
            $type = 'github';
            $text = ( ! empty($content)) ? $content : 'Sign in with Github';
            $url  = ProfilePress_Global_Shortcodes::github_login_url();
            break;

        case 'vk':
            $type = 'vk';
            $text = ( ! empty($content)) ? $content : 'Sign in with VK';
            $url  = ProfilePress_Global_Shortcodes::vk_login_url();
            break;
    }

    return "<a href=\"{$url}\" style=\"{$size_attr}\" class=\"zocial $type\">$text</a>";
}