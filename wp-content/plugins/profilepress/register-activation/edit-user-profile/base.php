<?php
namespace edit_user_profile;

// require the various login theme instances

require_once 'boson-theme/boson.php';
require_once 'flatui-theme/flatui.php';
require_once 'smiley-theme/smiley.php';


class Edit_User_Profile_Base
{

    public static function instance()
    {

        smiley_theme\Smiley_Edit_User_Profile::instance();
        boson_theme\Boson_Edit_User_Profile::instance();
        flatui_theme\FlatUI_Edit_User_Profile::instance();
    }
}