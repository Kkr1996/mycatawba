<?php
/**
 * Theme name: Boson
 * Builder type: Edit user profile form
 */
namespace edit_user_profile\boson_theme;

/**
 * Class Boson_Edit_User_Profile
 * @package edit_user_profile\boson_theme
 */
class Boson_Edit_User_Profile
{

    public static function instance()
    {
        global $wpdb;

        $wpdb->insert(
            EDIT_PROFILE_TABLE,
            array(
                'title'                => 'Boson Edit Profile Theme',
                'structure'            => self::structure(),
                'css'                  => self::css(),
                'success_edit_profile' => '<div class="profilepress-edit-profile-status"><span class="fui-check"></span> Changes saved</div>',
                'date'                 => date('Y-m-d')
            ),
            array(
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
/* css class for the edit profile generated errors */

.profilepress-edit-profile-status {
	color: #555;
    font-size: 15px;
    font-weight: bold;
    margin: 10px auto;
    width: 90%;
    text-align: center;
}


/* Boson form CSS */

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
    width: 90%;
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
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
.boson-container > .login input[type=text]:focus, .boson-container > .login input[type=email]:focus, .boson-container > .login select:focus, .boson-container > .login input[type=password]:focus {
    border-color: #7dc9e2;
    outline-color: #dceefc;
    outline-offset: 0;
}

.boson-container > .login input[type=submit], .boson-container > .login button.removed {
    cursor: pointer;
    padding: 1px 18px;
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

.boson-container > .login button.removed {
    width: 40px;
}

.boson-container > .login input[type=submit] {
width: 80%;
}


.demo-download img {
    border-radius: 50%;
    display: block;
    height: 190px;
    margin: 0 auto 10px;
    padding: 2px;
    text-align: center;
    width: 190px;
}

CSS;
    }

    /** @return string form structure */
    public static function structure()
    {
        return <<<STRUCTURE
<div class="edit-profile">

 <div class="boson-container">
            <div class="login">
                 <h1>Edit Profile</h1>
<p>
<label for="id-username">Username</label>
[edit-profile-username id="id-username" placeholder="username" class="edit-profile-name"]
</p>

<p>
<label for="id-password">Password</label>
[edit-profile-password id="id-password" placeholder="password" class="edit-profile-passkey"]
</p>

<p>
<label for="id-email">Email Address</label>
[edit-profile-email id="id-email" placeholder="Email" class="reg-email"]
</p>

<p>
<label for="id-website">Website</label>
[edit-profile-website class="reg-website" placeholder="Website" id="id-website"]
</p>

<p>
<label for="id-nickname">Nickname</label>
[edit-profile-nickname class="remember-me" placeholder="Nickname" id="id-nickname"]
</p>

<p class="demo-download">
[user-avatar]
[remove-user-avatar label="Delete" class="removed"]
</p>

<p>
<label for="id-nickname">Profile Picture</label>
[edit-profile-avatar class="filestyle" id="avatar"]
</p>

<p>
<label for="display-name">Display Name</label>
[edit-profile-display-name class="display-name" placeholder="Display Name" id="display-name"]
</p>

<p>
<label for="id-firstname">First Name</label>
[edit-profile-first-name class="remember-me" id="id-firstname"  placeholder="First Name"]
</p>

<p>
<label for="id-lastname">Last Name</label>
[edit-profile-last-name class="remember-me" id="id-lastname" placeholder="Last Name"]
</p>

<p style="text-align:center">
[edit-profile-submit value="Save Profile"]
</p>

</div>
</div>

</div>
STRUCTURE;
    }
}