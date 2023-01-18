<?php
/**
 * Theme name: Boson
 * Builder type: Registration form
 */
namespace registrations\boson_theme;

/**
 * Class Boson_Registration
 * @package registrations\boson_theme
 */
class Boson_Registration
{

    public static function instance()
    {
        global $wpdb;

        $wpdb->insert(
            PP_REGISTRATION_TABLE,
            array(
                'title'                => 'Boson Registration Theme',
                'structure'            => self::structure(),
                'css'                  => self::css(),
                'success_registration' => '<div class="profilepress-reg-status">Registration Successful</div>',
                'date'                 => date('Y-m-d')
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

    public static function css()
    {
        return <<<CSS
/* css class for the registration form generated errors */
.profilepress-reg-status {
 	color: #555;
    font-size: 15px;
    font-weight: bold;
    margin: 10px 0;
    max-width: 310px;
    width: 100%;
    text-align: center;
}

/* Boson Registration form CSS */

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
    padding: 20px 20px 20px;
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
    font-size: 20px;
    color: #555;
}
.boson-container > .login input[type=text], .boson-container > .login input[type=email], .boson-container > .login input[type=password], .boson-container > .login select {
    width: 100%;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
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
    text-align: center;
}
:-moz-placeholder {
    color: #c9c9c9 !important;
    font-size: 13px;
}
::-webkit-input-placeholder {
    color: #ccc;
    font-size: 13px;
}
.boson-container > .login input[type=text], .boson-container > .login input[type=email], .boson-container > .login input[type=password], .boson-container > .login select {
    margin: 5px;
    padding: 0 10px;
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
.boson-container > .login input[type=text]:focus, .boson-container > .login input[type=email]:focus, .boson-container > .login select:focus, .boson-container > .login input[type=password]:focus {
    border-color: #7dc9e2;
    outline-color: #dceefc;
    outline-offset: 0;
}
.boson-container > .login input[type=submit] {
    cursor: pointer;
    padding: 1px 18px;
    height: 29px;
    width: 80%;
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
CSS;

    }


    /** Registration structure */
    public static function structure()
    {
        return <<<STRUCTURE
    <div class="boson-container">
        <div class="login">
             <h1>Sign Up</h1>

<p>
[reg-username placeholder="Username"]
</p>

<p>
[reg-password placeholder="password"]
</p>

<p>
[reg-email placeholder="Email"]
</p>

<p>
[reg-website placeholder="Website" required]
</p>

<p>
[reg-nickname placeholder="Nickname"]
</p>

<p>
[reg-first-name placeholder="First Name" required]
</p>

<p>
[reg-last-name placeholder="Last Name" required]
</p>

<p>
<label for="gender">Gender</label>
[reg-cpf type="select" key="gender" id="gender"]
</p>
<p class="submit">
[reg-submit value="Register"]
</p>
                    <p style="text-align:center">Have an account? [link-login label="Login"]

                    </p>
</div>
        </div>
STRUCTURE;
    }
}