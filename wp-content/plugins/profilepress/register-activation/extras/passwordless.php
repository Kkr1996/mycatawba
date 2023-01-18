<?php

class PP_Passwordless
{

    public static function instance()
    {
        $title    = pp_site_title();
        $msg      = <<<MESSAGE
Hi {{username}}, below is your one-time login url to $title.

{{passwordless_link}}

Regards.
MESSAGE;
        $settings = array(
            'sender_name'     => pp_site_title(),
            'sender_email'    => pp_admin_email(),
            'type'            => 'text/plain',
            'subject'         => 'One time login to ' . pp_site_title(),
            'message'         => $msg,
            'expires'         => 10,
            'success_message' => 'One-time login URL sent successfully to your email.',
            'invalid_error'   => 'One-time login expired or invalid. <a href="' . site_url() . '">Return home</a>.',
        );

        update_option('pp_extra_passwordless', $settings);
    }
}