<?php
namespace password_reset;

// require the various password reset theme instances

require_once 'boson-theme/boson.php';
require_once 'flatui-theme/flatui.php';
require_once 'fzbuk-theme/fzbuk.php';
require_once 'jakhu-theme/jakhu.php';
require_once 'smiley-theme/smiley.php';


class Password_Reset_Base
{

    public static function instance()
    {
        smiley_theme\Smiley_Password_Reset::instance();
        boson_theme\Boson_Password_Reset::instance();
        flatui_theme\Flatui_Password_Reset::instance();
        fzbuk_theme\Fzbuk_Password_Reset::instance();
        jakhu_theme\Jakhu_Password_Reset::instance();
    }

    public static function default_handler_structure()
    {
        return <<<FORM
<div class="pp-reset-password-form">
	<h3>Enter your new password below.</h3>
	<label for="password1">New password<span class="req">*</span></label>
	[enter-password id="password1" required autocomplete="off"]

	<label for="password2">Re-enter new password<span class="req">*</span></label>
	[re-enter-password id="password2" required autocomplete="off"]

	[password-reset-submit class="pp-reset-button pp-reset-button-block" value="Save"]
</div>
FORM;
    }
}