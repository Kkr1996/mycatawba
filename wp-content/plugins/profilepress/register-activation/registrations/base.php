<?php
namespace registrations;

// require the various registration theme instances

require_once 'boson-theme/boson.php';
require_once 'flatui-theme/flatui.php';
require_once 'fzbuk-theme/fzbuk.php';
require_once 'jakhu-theme/jakhu.php';
require_once 'smiley-theme/smiley.php';

/** Register all registration themes */
class Registrations_Base
{

    public static function instance()
    {
        // registration builder themes
        smiley_theme\Smiley_Registrations::instance();
        boson_theme\Boson_Registration::instance();
        flatui_theme\FlatUI_Registrations::instance();
        fzbuk_theme\Fzbuk_Registrations::instance();
        jakhu_theme\Jakhu_Registrations::instance();
    }
}