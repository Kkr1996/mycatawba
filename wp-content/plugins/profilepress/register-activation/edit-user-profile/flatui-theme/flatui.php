<?php
/**
 * Theme name: FlatUI
 * Builder type: Edit user profile form
 */
namespace edit_user_profile\flatui_theme;

/**
 * Class FlatUI_Edit_User_Profile
 * @package edit_user_profile\flatui_theme
 */
class FlatUI_Edit_User_Profile
{

    public static function instance()
    {
        global $wpdb;

        $wpdb->insert(
            EDIT_PROFILE_TABLE,
            array(
                'title'                => 'FlatUI Edit Profile Theme',
                'structure'            => self::structure(),
                'css'                  => self::css(),
                'success_edit_profile' => '<div class="profilepress-edit-profile-status"><h4>Changes Saved Successfully</h4></div>',
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

    /** CSS Stylesheet */
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

/*
This form uses the FlatUI css stylesheet that ships with the plugin hence this look.
It's actually very pretty when implemented.
*/
CSS;

    }

    public static function structure()
    {
        return <<<STRUCTURE
<div class="login-form">

<div class="form-group">
[edit-profile-username id="edit-username" placeholder="Username" class="form-control login-field"]
<label class="login-field-icon fui-user" for="edit-username"></label>
</div>

<div class="form-group">
[edit-profile-password id="edit-password" placeholder="Password" class="form-control login-field"]
<label class="login-field-icon fui-lock" for="edit-password"></label>
</div>

<div class="form-group">
[edit-profile-email id="edit-email" placeholder="Email" class="form-control login-field"]
<label class="login-field-icon fui-mail" for="edit-email"></label>
</div>

<div class="form-group">
[edit-profile-website class="form-control login-field" placeholder="Website" id="edit-website"]
<label class="login-field-icon fui-chat" for="edit-website"></label>
</div>

<div class="form-group">
[edit-profile-nickname class="form-control login-field" placeholder="Nickname" id="nickname"]
<label class="login-field-icon fui-user" for="nickname"></label>
</div>

<div class="form-group">
<span class="demo-download">
[user-avatar]  [remove-user-avatar label="Remove" class="btn btn-danger"]
</span>
</div>

<div class="form-group">
<label for="avatar">Profile Picture</label>
[edit-profile-avatar placeholder="avatar" id="avatar" class="filestyle"]
</div>

<div class="form-group">
<label for="display-name">Display Name</label>
[edit-profile-display-name class="flat-select" placeholder="Display Name" id="display-name"]
</div>

<div class="form-group">
[edit-profile-first-name class="form-control login-field" id="firstname"  placeholder="First Name"]
<label class="login-field-icon fui-user" for="firstname"></label>
</div>

<div class="form-group">
[edit-profile-last-name class="form-control login-field" id="lastname" placeholder="Last Name"]
<label class="login-field-icon fui-user" for="lastname"></label>
</div>

<div class="form-group">
[edit-profile-cpf key="country" type="text" class="form-control login-field" id="country" placeholder="Country"]
</div>

<div class="form-group">
[edit-profile-bio class="form-control login-field" id="edit-bio" placeholder="About / Bio"]
</div>

<div class="form-group">
<label for="gender">Gender</label><br/>
[edit-profile-cpf key="gender" type="radio" class="flat-radio" id="gender"]
</div>


<p>
[edit-profile-submit value="Edit Profile" class="btn btn-block btn-lg btn-primary" id="submit-button"]
</p>

</div>
STRUCTURE;
    }
}