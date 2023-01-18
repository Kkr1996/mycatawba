<?php
namespace logins;

// require the various login theme instances

require_once 'boson-theme/boson.php';
require_once 'flatui-theme/flatui.php';
require_once 'fzbuk-theme/fzbuk.php';
require_once 'jakhu-theme/jakhu.php';
require_once 'sukan-theme/sukan.php';
require_once 'smiley-theme/smiley.php';
require_once 'social-login-signup/social-login-signup.php';


class Logins_Base
{

    public static function instance()
    {
        // Login themes
        smiley_theme\Smiley_Login::instance();
        boson_theme\Boson_Login::instance();
        flatui_theme\FlatUI_Login::instance();
        social_login_signup\Social_Login_Signup::instance();
        fzbuk_theme\Fzbuk_Login::instance();
        jakhu_theme\Jakhu_Login::instance();
        sukan_theme\Sukan_Login::instance();
    }
}