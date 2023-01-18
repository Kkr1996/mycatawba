<?php
namespace front_end_profile;

// require the various login theme instances

require_once 'smiley/smiley.php';
require_once 'smiley2/smiley2.php';
require_once 'dixon-theme/dixon.php';


class Front_End_Profile_Base
{

    public static function instance()
    {
        smiley_theme\Smiley_Profile::instance();
        smiley2_theme\Smiley2_Profile::instance();
        dixon_theme\Dixon_Profile::instance();
    }
}