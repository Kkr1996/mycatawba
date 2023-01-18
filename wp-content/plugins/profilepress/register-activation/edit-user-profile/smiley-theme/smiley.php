<?php
/**
 * Theme name: Smiley
 * Builder type: Edit user profile form
 */
namespace edit_user_profile\smiley_theme;

/**
 * Class Smiley_Edit_User_Profile
 * @package edit_user_profile\smiley_theme
 */
class Smiley_Edit_User_Profile
{

    public static function instance()
    {
        global $wpdb;

        $wpdb->insert(
            EDIT_PROFILE_TABLE,
            array(
                'title'                => 'Smiley Edit Profile Theme',
                'structure'            => self::structure(),
                'css'                  => self::css(),
                'success_edit_profile' => '<div class="pp-edit-success">Changes saved.</div>',
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
        $asset_url = PROFILEPRESS_ROOT_URL . 'assets/images/smiley';

        return <<<CSS
/* css class for the edit profile generated errors */
.profilepress-edit-profile-status {
    border-radius: 6px;
    font-size: 17px;
    line-height: 1.471;
    padding: 10px 19px;
    background-color: #e74c3c;
    color: #ffffff;
    font-weight: normal;
    display: block;
    text-align: center;
    vertical-align: middle;
    margin: 5px auto;
}

.pp-edit-success {
    border-radius: 6px;
    font-size: 17px;
    line-height: 1.471;
    padding: 10px 19px;
    background-color: #2ecc71;
    color: #ffffff;
    font-weight: normal;
    display: block;
    text-align: center;
    vertical-align: middle;
    margin: 5px auto;
}

.ppprofile1 {
    font-family: "Proxima Nova";
}

.ppprofile1 input[type="file"] {
    display: none;
}

#pp-file-upload-value {
    padding: 5px 10px 1px;
    text-shadow: 0 1px 1px rgba(0, 0, 0, 0);
}

.ppprofile1 .pp-top {
    border-top-left-radius:4px !important;
    border-top-right-radius:4px !important;
}

.ppprofile1 .pp-bottom {
    border-bottom-left-radius:4px !important;
    border-bottom-right-radius:4px !important;
    border-bottom:0;
}

.ppprofile1 .custom-file-upload {
    font-size: 15px;
}

.ppprofile1 .custom-file-upload {
    margin: 0 !important;
    background: #BABABA;
    border: 1px solid #BABABA;
    border-top: 1px solid #ccc;
    border-left: 1px solid #ccc;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
    color: #fff;
    display: inline-block;
    text-decoration: none;
    cursor: pointer;
    line-height: normal;
    padding: 5px;
}

.pp-del-pix {
    font-size: 10px !important;
    background-color: rgb(221, 51, 51) !important;
    border: 0 none !important;
}

.ppprofile1 .ppboxa {
    background: #fff;
    max-width: 350px;
    width: 100%;
    height: 80px;
    margin: 0 auto;
    text-align: center;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    position: relative;
}
.ppprofile1 .ppavatar {
    border-radius: 120px;
    border: 6px solid #fff;
    margin: auto;
    background: #f1f1f1;
    max-width: 120px;
    width: 100%;
    position: relative;
}
.ppprofile1 .ppboxb {
    background: url({$asset_url}/bg1.png);
    max-width: 350px;
    width: 100%;
    /* height: 400px; */
    margin: 0 auto;
    color: #fff;
    border-radius: 4px;
}

.ppprofile1 .ppuserdata {
    text-align: center;
    padding-top: 80px;
    text-shadow: 0 1px 1px rgba(0, 0, 0, .25);
}
.ppprofile1 span.ppname {
    font-size: 36px;
}
.ppprofile1 span.pptitle {
    text-transform: uppercase;
    font-size: 12px;
    color: rgba(255, 255, 255, .8);
}
.ppprofile1 .ppprofdata {
    padding: 30px;
    background: rgba(0, 0, 0, .1);
    margin-top: 50px;
    /* height: 90px; */
    margin: 30px;
    border-radius: 4px;
    text-shadow: 0 1px 1px rgba(0, 0, 0, .25);
    text-align: center;
    padding-bottom: 5px;
}

.ppprofile1 .ppprofdata div.heading {
    text-align:center;
}
.ppprofile1 .ppprofdata ul {
    list-style: none;
    margin: 0;
    padding: 0;
}
.ppprofile1 .ppprofdata ul li {
    padding-bottom: 10px;
}
.ppprofile1 .ppprofdata ul li a {
    color: #F1F1F1;
    text-decoration: none;
}
.ppprofile1 .ppprofdata ul li strong {
    padding-right: 6px;
    color: #D6EBFA;
}
.ppprofile1 .ppsocial {
    margin: 0;
    padding: 0;
    text-align: left;
    position: absolute;
    margin-top: -90px;
    margin-left: 20px;
    opacity: 0.2;
}
.ppprofile1 .ppsocial li {
    list-style: none;
    display: inline;
}
.pp-smiley-file, .ppprofile1 input[type=text], .ppprofile1 input[type=password], .ppprofile1 textarea {
    border: 0;
    margin: 0;
    border-radius: 0;
    box-sizing: border-box;
    color: #2b2b2b;
    font-weight: 500;
    background-color:#fff;
    font-family:"Proxima Nova";
    font-size: 14px;
    padding: 11px;
    max-width: 230px;
    width: 100%;
    border-bottom: 1px dashed rgba(0, 0, 0, .05);
    text-align: left;
    margin-bottom: 0px;
}

.ppprofdata textarea {
  height: 140px;
}

.pp-smiley-file, .ppprofile1 input[type=text]:focus, .ppprofile1 input[type=password]:focus, .ppprofile1 input[type=file]:focus, .ppprofile1 textarea:focus {
    outline: 0;
}

.ppprofile1 .ppsoclog {
    text-align: center;
    color: #F1F1F1;
    text-decoration: none;
    font-size: 12px;
}
.ppprofile1 .pploginbutton {
    text-align: center;
}
.ppprofile1 .pplogbutt {
    background: #f1f1f1;
    border: 1px solid rgba(0, 0, 0, .15);
    font-family:"Proxima Nova";
    font-size: 15px;
    color: rgba(31, 31, 31, 0.5);
    text-transform: uppercase;
    max-width: 290px;
    width: 100%;
    padding: 8px;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, .1);
    margin-bottom: 30px;
}
.ppprofile1 .ppsocialmedia {
    background: rgba(0, 0, 0, .2);
    height: 165px;
    margin-bottom: 30px;
    padding: 30px;
    text-align: center;
    display: none;
}
.ppprofile1 .ppsocialmedia .ppsochead {
    font-weight: 500;
    font-size: 16px;
}
.ppsocialmedia img {
    max-width: 220px;
    width: 100%;
    padding-top: 10px;
}

 .pp-gender, .pp-css {
    border: 0;
    background: #fff;
    max-width: 230px;
    width: 100%;
    font-size: 14px;
    font-family:"Proxima Nova";
    color: rgba(0, 0, 0, .5);
    font-weight: 500;
    border-radius: 0;
    border-bottom-left-radius: 4px;
    border-bottom-right-radius:4px;
    padding: 12px;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}

 .pp-gender, .pp-css {
    border: 0;
    background: #fff;
    max-width: 230px;
    width: 100%;
    font-size: 14px;
    font-family:"Proxima Nova";
    color: rgba(0, 0, 0, .5);
    font-weight: 500;
    border-radius: 0;
    border-bottom-left-radius: 4px !important;
    border-bottom-right-radius:4px !important;
    padding: 12px;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}

 .pp-no-radius {
    border: 0;
    background: #fff;
    max-width: 230px;
    width: 100%;
    font-size: 14px;
    font-family:"Proxima Nova";
    color: rgba(0, 0, 0, .5);
    font-weight: 500;
    border-radius: 0;
    padding: 12px;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}


.pp-gender:focus, .pp-css:focus {
    outline: none;
}
.pp-ep .pp-gender {
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
    border-bottom: 1px dashed rgba(0, 0, 0, .05);
}
.pp-epf label {
    float: left;
    margin-bottom: 10px;
}

.pp-edia {
	  position: absolute;
	  right: 11px;
	  top: -5px;
	  margin-top: 75px;
	  padding: 6px;
	  border-bottom-right-radius: 3px;
	  padding-left: 8px;
	  font-size: 12px;
}

.pp-edia a {
	text-decoration: none;
	color: #1f1f1f;
}
CSS;

    }

    public static function structure()
    {
        return <<<STRUCTURE
<div class="ppprofile1">
	<div class="ppboxa">
		[user-avatar class="ppavatar"]
		<div class="pp-edia">
			[remove-user-avatar class="pp-del-pix custom-file-upload" label="Delete Picture"]
		</div>
	</div>

	<div class="ppboxb">
		<div class="ppuserdata"><span class="ppname">Edit Profile</span></div>
		<div class="ppprofdata">
			<div class="heading">Personal Information</div>
			[edit-profile-username class="pp-top" placeholder="Username"]
			<br/> [edit-profile-email title="Email Address" placeholder="Email Address"]
			<br/> [edit-profile-password title="Password" placeholder="Password"]
			<br/> [edit-profile-website title="Website" placeholder="Website"]
			<br/> [edit-profile-nickname title="Nickname" placeholder="Nickname"]
			<br/> [edit-profile-first-name title="First Name" placeholder="First Name"]
			<br/> [edit-profile-last-name title="Last Name" placeholder="Last Name"]
			<br/> [edit-profile-cpf key="country" type="text" title="Country" placeholder="Country"]
			<br/>

			<div class="pp-smiley-file">
				<label for="pp-file-upload" class="custom-file-upload">Choose file</label> [edit-profile-avatar id="pp-file-upload"]
				<span id="pp-file-upload-value">upload avatar</span>
			</div>
			[edit-profile-bio title="Bio" placeholder="Bio"] [edit-profile-display-name title="Display Name" class="pp-no-radius" placeholder="Display Name"] [edit-profile-cpf key="gender" type="select" title="Gender" class="pp-gender"]
			<br style="clear:both"/> <br style="clear:both"/> <br/>

			<div class="heading">Social Media Profile URLs</div>
			[edit-profile-cpf key="facebook" type="text" class="pp-top" title="Facebook profile URL" placeholder="Facebook profile"]
			<br/> [edit-profile-cpf key="twitter" type="text" title="Twitter Profile URL" placeholder="Twitter URL"]
			<br/> [edit-profile-cpf key="linkedin" type="text" title="LinkedIn Profile URL" placeholder="LinkedIn Profile"]
			<br/> [edit-profile-cpf key="google" class="pp-bottom" title="Google+ Profile" type="text" placeholder="Google+ Profile"]
			<br style="clear:both"/> <br/> <br/>
		</div>
		<div class="pploginbutton">
			[edit-profile-submit class="pplogbutt"]
		</div>
	</div>
</div>
</div>

<script type="text/javascript">
	(function ($) {
		$("input:file[id=pp-file-upload]").change(function () {
			$("#pp-file-upload-value").html($(this).val());
		});
	})(jQuery);
</script>
STRUCTURE;
    }
}