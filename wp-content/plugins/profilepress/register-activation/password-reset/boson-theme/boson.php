<?php
/**
 * Theme name: Boson
 * Builder type: Password Reset form
 */
namespace password_reset\boson_theme;

use password_reset\Password_Reset_Base;

/**
 * Class Boson_Password_Reset
 * @package password_reset\boson_theme
 */
class Boson_Password_Reset
{

    public static function instance()
    {
        global $wpdb;

        $wpdb->insert(
            PASSWORD_RESET_TABLE,
            array(
                'title'                  => 'Boson Password Reset',
                'structure'              => self::structure(),
                'css'                    => self::css(),
                'success_password_reset' => '<div class="profilepress-reset-status">Check your e-mail for further instruction.</div>',
                'date'                   => date('Y-m-d')
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );
    }


    /** CSS stylesheet */
    public static function css()
    {
        return <<<CSS
/* css class for the password reset form generated errors */
.profilepress-reset-status {
    max-width: 310px;
    width: 100%;
	color: #555;
	font-size: 15px;
	font-weight: bold;
	margin: 10px;
}


/* password reset stylesheet begins here */

.boson-container {
    margin: auto;
}

.boson-container a {
    color: #527881;
    text-decoration: underline;
    text-align: center;
}

.boson-container > .login {
    position: relative;
    padding: 20px 20px 60px;
    max-width: 310px;
    width: 100%;
    background: white;
    border-radius: 3px;
    -webkit-box-shadow: 0 0 200px rgba(255, 255, 255, 0.5), 0 1px 2px rgba(0, 0, 0, 0.3);
    box-shadow: 0 0 200px rgba(255, 255, 255, 0.5), 0 1px 2px rgba(0, 0, 0, 0.3);
}
.boson-container > .login h1 {
    margin: -20px -20px 21px;
    line-height: 40px;
    font-size: 15px;
    font-weight: bold;
    color: #555;
    text-align: center;
    text-shadow: 0 1px white;
    background: #f3f3f3;
    border-bottom: 1px solid #cfcfcf;
    border-radius: 3px 3px 0 0;
    background-image: -webkit-linear-gradient(top, whiteffd, #eef2f5);
    background-image: -moz-linear-gradient(top, whiteffd, #eef2f5);
    background-image: -o-linear-gradient(top, whiteffd, #eef2f5);
    background-image: linear-gradient(to bottom, whiteffd, #eef2f5);
    -webkit-box-shadow: 0 1px whitesmoke;
    box-shadow: 0 1px whitesmoke;
}
.boson-container > .login p {
    margin: 10px 0;
    color: #555;
    font-size: 14px;
}
.boson-container > .login input[type=text], .boson-container > .login input[type=password] {
    width: 278px;
    box-sizing: border-box;
}
.boson-container > .login p.remember_me {
    float: left;
    line-height: 31px;
}
.boson-container > .login p.remember_me label {
    font-size: 12px;
    color: #777;
    cursor: pointer;
}
.boson-container > .login p.remember_me input {
    position: relative;
    bottom: 1px;
    margin-right: 4px;
    vertical-align: middle;
}
.boson-container > .login p.submit {
    text-align: right;
}
:-moz-placeholder {
    color: #c9c9c9 !important;
    font-size: 13px;
}
::-webkit-input-placeholder {
    color: #ccc;
    font-size: 13px;
}
.boson-container > .login input[type=text], .boson-container > .login input[type=password] {
    margin: 5px;
    padding: 0 10px;
    width: 270px;
    height: 34px;
    color: #404040;
    background: white;
    border: 1px solid;
    border-color: #c4c4c4 #d1d1d1 #d4d4d4;
    border-radius: 2px;
    outline: 5px solid #eff4f7;
    -moz-outline-radius: 3px;
    -webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.12);
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.12);
}
.boson-container > .login input[type=text]:focus, .boson-container > .login input[type=password]:focus {
    border-color: #7dc9e2;
    outline-color: #dceefc;
    outline-offset: 0;
}
.boson-container > .login input[type=submit] {
    cursor: pointer;
    padding: 0 18px;
    height: 29px;
    font-size: 12px;
    font-weight: bold;
    color: #527881;
    text-shadow: 0 1px #e3f1f1;
    background: #cde5ef;
    border: 1px solid;
    border-color: #b4ccce #b3c0c8 #9eb9c2;
    border-radius: 16px;
    outline: 0;
    -webkit-box-sizing: content-box;
    -moz-box-sizing: content-box;
    box-sizing: content-box;
    background-image: -webkit-linear-gradient(top, #edf5f8, #cde5ef);
    background-image: -moz-linear-gradient(top, #edf5f8, #cde5ef);
    background-image: -o-linear-gradient(top, #edf5f8, #cde5ef);
    background-image: linear-gradient(to bottom, #edf5f8, #cde5ef);
    -webkit-box-shadow: inset 0 1px white, 0 1px 2px rgba(0, 0, 0, 0.15);
    box-shadow: inset 0 1px white, 0 1px 2px rgba(0, 0, 0, 0.15);
}
.boson-container > .login input[type=submit]:active {
    background: #cde5ef;
    border-color: #9eb9c2 #b3c0c8 #b4ccce;
    -webkit-box-shadow: inset 0 0 3px rgba(0, 0, 0, 0.2);
    box-shadow: inset 0 0 3px rgba(0, 0, 0, 0.2);
}

CSS;

    }


    /** Registration structure */
    public static function structure()
    {
        return <<<STRUCTURE
<div class="boson-container">
	<div class="login">
		<h1>Password Reset</h1>

		<p>
			Please enter your username or email address. You will receive a link to create a new password via email.

			[user-login placeholder="Username or E-mail"]
		</p>


		<p>
			[reset-submit value="Reset Password"]
		</p>

		<p class="remember_me">
			[link-login label="Back to Login"] | [link-registration label="Sign Up"]
		</p>

	</div>
</div>
STRUCTURE;
    }
}