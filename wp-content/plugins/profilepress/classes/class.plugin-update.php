<?php

namespace ProfilePress\Plugin_Update;

/**
 * Class PP_Update
 * @package ProfilePress\Plugin_Update
 */
class PP_Update
{
    public static $instance;

    const DB_VER = 11;

    public function init_options()
    {
        add_site_option('pp_db_ver', 0);
    }

    public function maybe_update()
    {
        $this->init_options();

        if (get_site_option('pp_db_ver') >= self::DB_VER) {
            return;
        }

        // update plugin
        $this->update();
    }

    public function update()
    {
        // no PHP timeout for running updates
        set_time_limit(0);

        // this is the current database schema version number
        $current_db_ver = get_site_option('pp_db_ver');

        // this is the target version that we need to reach
        $target_db_ver = self::DB_VER;

        // run update routines one by one until the current version number
        // reaches the target version number
        while ($current_db_ver < $target_db_ver) {
            // increment the current db_ver by one
            $current_db_ver++;

            // each db version will require a separate update function
            $update_method = "pp_update_routine_{$current_db_ver}";

            if (method_exists($this, $update_method)) {
                call_user_func(array($this, $update_method));
            }

            // update the option in the database, so that this process can always
            // pick up where it left off
            update_site_option('pp_db_ver', $current_db_ver);
        }
    }

    public function pp_update_routine_1()
    {
        // delete the old pp_db_version option. replaced by pp_db_ver
        delete_site_option('pp_db_version');

        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $table = $wpdb->base_prefix . 'pp_revisions';

        $sql = "CREATE TABLE IF NOT EXISTS $table (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				PRIMARY KEY  (id),
				structure longtext NOT NULL,
				css longtext NOT NULL,
				type varchar(20) NOT NULL,
				parent_id mediumint(9) NOT NULL,
				date datetime NOT NULL
				) $charset_collate;";

        // make dbDelta() available
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    public function pp_update_routine_2()
    {
        // delete the old pp_db_version option. replaced by pp_db_ver
        delete_site_option('pp_db_version');

        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $table = $wpdb->base_prefix . 'pp_melange';

        // applicable for the other builders
        $blog_id_and_date_column = is_multisite() ? 'date text NOT NULL, blog_id MEDIUMINT(9) NOT NULL' : 'date text NOT NULL';

        $sql = "CREATE TABLE IF NOT EXISTS $table (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				PRIMARY KEY  (id),
				title varchar(50) NOT NULL,
				structure longtext NOT NULL,
				css longtext NOT NULL,
				registration_msg text NOT NULL,
				edit_profile_msg text NOT NULL,
				password_reset_msg text NOT NULL,
				$blog_id_and_date_column
				) $charset_collate;";

        // make dbDelta() available
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        // Lucid tab widget
        $title = 'Lucid Account Tab Widget';
        $structure = <<<LUCID
<div class="lucidContainer">
	<div class='multitab-section'>
		<ul class='multitab-widget multitab-widget-content-tabs-id'>
			<li class='multitab-tab'><a href='#lucidLogin'>Log In</a></li>
			<li class='multitab-tab'><a href='#lucidRegistration'>Register</a></li>
			<li class='multitab-tab'><a href='#lucidReset'>Reset</a></li>
		</ul>
		<div class='multitab-widget-content multitab-widget-content-widget-id' id='lucidLogin'>
      <span class='sidebar' id='sidebartab1' preferred='yes'>
        [pp-login-form]
		[login-username placeholder="Username"]
		[login-password placeholder="Password"]
		[login-submit value="Log In"]
		[/pp-login-form]
      </span>
		</div>
		<div class='multitab-widget-content multitab-widget-content-widget-id' id='lucidRegistration'>
      <span class='sidebar' id='sidebartab2' preferred='yes'>
		[pp-registration-form]
		[reg-username placeholder="Username"]
		[reg-email placeholder="Email Address"]
		[reg-password placeholder="Password"]
		[reg-submit value="Register"]
		[/pp-registration-form]
      </span>
		</div>
		<div class='multitab-widget-content multitab-widget-content-widget-id' id='lucidReset'>
      <span class='sidebar' id='sidebartab3' preferred='yes'>
        [pp-password-reset-form]
		[user-login value="Username or Email"]
		[reset-submit value="Get New Password"]
		[/pp-password-reset-form]
      </span>
		</div>
	</div>
</div>

<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready(function ($) {
		$(".multitab-widget-content-widget-id").hide();
		$("ul.multitab-widget-content-tabs-id li:first a").addClass("multitab-widget-current").show();
		$(".multitab-widget-content-widget-id:first").show();
		$("ul.multitab-widget-content-tabs-id li a").click(function () {
			$("ul.multitab-widget-content-tabs-id li a").removeClass("multitab-widget-current a");
			$(this).addClass("multitab-widget-current");
			$(".multitab-widget-content-widget-id").hide();
			var activeTab = $(this).attr("href");
			$(activeTab).fadeIn();
			return false;
		});
	});
	//]]>
</script>
LUCID;

        $css = <<<LUCID_CSS
@import url(https://fonts.googleapis.com/css?family=Open+Sans:400,700);

/* css class for registration form generated errors */
.profilepress-login-status {
  border-radius: 6px;
  max-width: 350px;
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

.profilepress-login-status a {
  color: #333 !important;
}

/* css class for registration form generated errors */
.profilepress-reg-status {
  border-radius: 6px;
  max-width: 350px;
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

/* css class for password reset form generated errors */
.profilepress-reset-status {
  border-radius: 6px;
  max-width: 350px;
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

/* css class for the edit-profile form generated errors */
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

.lucidSuccess {
  border-radius: 6px;
  max-width: 350px;
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

.lucidContainer {
  max-width: 350px;
  margin: 30px auto;
  padding: 0;
  box-shadow: 0 10px 5px -5px rgba(0, 0, 0, 0.1);
  font-family: 'Open Sans', sans-serif;
}
/* Multi Tab Sidebar */

.multitab-section {
  display: inline-block;
  text-transform: uppercase;
  width: 100%;
}

.multitab-section p {
  display: inline-block;
  background: #fff;
  text-transform: lowercase;
  font-size: 14px;
  padding: 20px;
  margin: 0;
}

.multitab-widget {
  list-style: none;
  margin: 0 0 10px;
  padding: 0
}

.multitab-widget li {
  list-style: none;
  padding: 0;
  margin: 0;
  float: left
}

.multitab-widget li a {
  background: #22a1c4;
  color: #fff;
  display: block;
  padding: 15px;
  font-size: 13px;
  text-decoration: none
}

.multitab-tab {
  width: 33.3%;
  text-align: center
}

.multitab-section h2,
.multitab-section h3,
.multitab-section h4,
.multitab-section h5,
.multitab-section h6 {
  display: none;
}

.multitab-widget li a.multitab-widget-current {
  padding-bottom: 20px;
  margin-top: -10px;
  background: #fff;
  color: #444;
  text-decoration: none;
  border-top: 5px solid #22a1c4;
  font-size: 14px;
  text-transform: capitalize
}

.multitab-widget-content {
  padding: 0 20px;
  border-bottom: 1px solid #efefef;
  border-left: 1px solid #efefef;
  border-right: 1px solid #efefef;
}


div.lucidContainer input[type="email"],
div.lucidContainer input[type="text"],
div.lucidContainer input[type="password"], div.lucidContainer select, div.lucidContainer textarea {
  width: 100%;
  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.125);
  box-sizing: border-box;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  background: #fff;
  margin: 10px auto;
  border: 1px solid #ccc;
  padding: 10px;
  font-family: 'Open Sans', sans-serif;
  font-size: 95%;
  color: #555;
}


div.lucidContainer input[type="submit"] {
  width: 100%;
  box-sizing: border-box;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  margin: 10px auto;
  background: #3399cc;
  border: 0;
  padding: 4%;
  font-family: 'Open Sans', sans-serif;
  font-size: 100%;
  color: #fff;
  cursor: pointer;
  transition: background .3s;
  -webkit-transition: background .3s;
}

div.lucidContainer input[type="submit"]:hover {
  background: #2288bb;
}
LUCID_CSS;

        $reg_success = '<div class="lucidSuccess">Registration Successful.</div>';
        $reset_success = '<div class="lucidSuccess">Check your e-mail for further instruction2</div>';
        $edit_profile_success = '<div class="lucidSuccess">Profile Successfully Edited</div>';


        $wpdb->insert($table,

            array(
                'title' => $title,
                'structure' => $structure,
                'css' => $css,
                'registration_msg' => $reg_success,
                'edit_profile_msg' => $edit_profile_success,
                'password_reset_msg' => $reset_success,
                'date' => date('Y-m-d')
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            )

        );

        $wpdb->insert($wpdb->base_prefix . 'pp_builder_widget',
            array(
                'builder_type' => 'melange',
                'builder_id' => $wpdb->insert_id
            ),
            array(
                '%s',
                '%d',
            )
        );
    }

    public function pp_update_routine_3()
    {
        global $wpdb;

        $asset_url = ASSETS_URL;

        // monochrome front-end profile
        $css = <<<CSS
	.monochrome-profile {
		max-width: 580px;
		margin-left: auto;
		margin-right: auto;
		text-align: left;
		overflow: hidden;
		color: #888;
		font-family: 'Helvetica Neue', helvetica, arial, sans-serif;
		-webkit-font-smoothing: antialiased;
		-moz-font-smoothing: antialiased;
		font-smoothing: antialiased;
	}

	/* Cover */

	.monochrome-cover {
		background-color: #222;
		padding: 30px;
		text-align: center;
		overflow: hidden;
	}

	/* Avatar */

	.monochrome-avatar {
		display: block;
		float: left;
		padding: 5px;
		-webkit-border-radius: 50%;
		-moz-border-radius: 50%;
		border-radius: 50%;
		background: #fff;
	}

	.monochrome-avatar img {
		width: 70px;
		height: 70px;
		display: block;
		-webkit-border-radius: 50%;
		-moz-border-radius: 50%;
		border-radius: 50%;
		overflow: hidden;
	}

	/* Username */

	.monochrome-uname {
		display: block;
		text-align: center;
		color: #fff;
		font-size: 24px;
		line-height: 34px;
		font-weight: bold;
		margin-top: 23px;
		margin-left: 30px;
		float: left;
	}

	.monochrome-uname a {
		color: #fff;
		text-decoration: none;
	}

	/* Social Links */

	.monochrome-social {
		list-style: none;
		overflow: hidden;
		padding: 15px 25px;
		padding-bottom: 5px;
		border: 1px solid #ddd;
		border-top: 0;
		margin: -1px 0 0 !important;
	}

	.monochrome-social li {
		float: left;
		display: block;
		margin-right: 10px;
		margin-bottom: 10px;
	}

	.monochrome-social a {
		display: block;
		width: 32px;
		height: 32px;
		background-size: 32px 32px;
		opacity: 0.75;
	}

	.monochrome-social a:hover {
		opacity: 1;
	}

	a.monochrome-dribbble { background-image: url($asset_url/images/monochrome/dribbble.png); }
	a.monochrome-facebook { background-image: url($asset_url/images/monochrome/facebook.png); }
	a.monochrome-flickr { background-image: url($asset_url/images/monochrome/flickr.png); }
	a.monochrome-github { background-image: url($asset_url/images/monochrome/github.png); }
	a.monochrome-instagram { background-image: url($asset_url/images/monochrome/instagram.png); }
	a.monochrome-pinterest { background-image: url($asset_url/images/monochrome/pinterest.png); }
	a.monochrome-rss { background-image: url($asset_url/images/monochrome/rss.png); }
	a.monochrome-soundcloud { background-image: url($asset_url/images/monochrome/soundcloud.png); }
	a.monochrome-spotify { background-image: url($asset_url/images/monochrome/spotify.png); }
	a.monochrome-twitter { background-image: url($asset_url/images/monochrome/twitter.png); }
	a.monochrome-youtube { background-image: url($asset_url/images/monochrome/youtube.png); }

	/* Content */

	.monochrome-contentCont {
		border: 1px solid #ddd;
		border-top: 0;
	}

	/* Content */

	.monochrome-content {
		padding: 25px;
	}

	/* Section Titles */

	.monochrome-sectionTitle {
		display: block;
		color: #333;
		font-size: 14px;
		line-height: 24px;
		padding-bottom: 10px;
		margin-bottom: 10px;
		border-bottom: 1px solid #ddd;
		font-weight: bold;
	}

	/* Table */

	.monochrome-table {
		width: 100%;
		margin: 0;
		padding: 0;
		border: 0;
		border-collapse: collapse;
		font-size: 12px;
		line-height: 22px;
	}

	.monochrome-table td {
		padding: 8px 10px;
		width: 75%;
	}

	.monochrome-table td.monochrome-label {
		width: 25%;
	}

	.monochrome-label {
		font-weight: bold;
		color: #333;
	}
CSS;
        $structure = <<<STRUCTURE
<div class="monochrome-profile">
	<div class="monochrome-cover">
		<div class="monochrome-avatar"><img src="[profile-avatar-url]"/></div>
		<div class="monochrome-uname">[profile-username]</div>
	</div>
	<div class="monochrome-contentCont">
		<div class="monochrome-content">
			<div class="monochrome-sectionTitle">Profile Details</div>
			<table class="monochrome-table">
				<tr>
					<td class="monochrome-label">First name</td>
					<td>[profile-first-name]</td>
				</tr>
				<tr>
					<td class="monochrome-label">Last name</td>
					<td>[profile-last-name]</td>
				</tr>
				<tr>
					<td class="monochrome-label">Biography</td>
					<td>[profile-bio]</td>
				</tr>
				<tr>
					<td class="monochrome-label">Gender</td>
					<td>[profile-cpf key="gender"]</td>
				</tr>
				<tr>
					<td class="monochrome-label">Country</td>
					<td>[profile-cpf key="country"]</td>
				</tr>
			</table>
		</div>
	</div>
	<ul class="monochrome-social">
		<li><a class="monochrome-dribbble" href="[profile-cpf key=dribbble]"></a></li>
		<li><a class="monochrome-facebook" href="[profile-cpf key=facebook]"></a></li>
		<li><a class="monochrome-flickr" href="[profile-cpf key=flickr]"></a></li>
		<li><a class="monochrome-github" href="[profile-cpf key=github]"></a></li>
		<li><a class="monochrome-instagram" href="[profile-cpf key=instagram]"></a></li>
		<li><a class="monochrome-pinterest" href="[profile-cpf key=pinterest]"></a></li>
		<li><a class="monochrome-soundcloud" href="[profile-cpf key=soundcloud]"></a></li>
		<li><a class="monochrome-spotify" href="[profile-cpf key=spotify]"></a></li>
		<li><a class="monochrome-twitter" href="[profile-cpf key=twitter]"></a></li>
		<li><a class="monochrome-youtube" href="[profile-cpf key=youtube]"></a></li>
	</ul>
</div>
STRUCTURE;

        $wpdb->insert(
            USER_PROFILE_TABLE,
            array(
                'title' => 'Monochrome Profile Theme',
                'structure' => $structure,
                'css' => $css,
                'date' => date('Y-m-d')
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );
    }

    /**
     * Extended the number of characters in melange title to 50.
     */
    public function pp_update_routine_4()
    {
        global $wpdb;

        $melange_table = $wpdb->base_prefix . 'pp_melange';

        $wpdb->query("ALTER TABLE $melange_table MODIFY COLUMN title VARCHAR(50)");
    }


    /**
     * Reorder the profile fields ID starting from 1 incrementally.
     */
    public function pp_update_routine_5()
    {
        global $wpdb;

        $table_name = CUSTOM_PROFILE_FIELD_TABLE;

        $index = 1;

        // fetch the profile fields
        $profile_field_ids = \PROFILEPRESS_sql::get_profile_field_ids();

        foreach ($profile_field_ids as $id) {
            $wpdb->update(
                $table_name,
                array(
                    'id' => $index
                ),
                array('id' => $id),
                array(
                    '%d'
                ),
                array('%d')
            );

            $index++;
        }
    }


    /**
     * increase custom field option size
     */
    public function pp_update_routine_6()
    {
        global $wpdb;

        $profile_field_table = CUSTOM_PROFILE_FIELD_TABLE;

        $wpdb->query("ALTER TABLE $profile_field_table MODIFY COLUMN options VARCHAR(3000)");

    }


    /**
     * increase custom field option size
     */
    public function pp_update_routine_7()
    {
        global $wpdb;

        $table = PASSWORD_RESET_TABLE;
        $form = <<<FORM
<div class="pp-reset-password-form">
	<h3>Enter your new password below.</h3>
	<label for="password1">New password<span class="req">*</span></label>
	[enter-password id="password1" required autocomplete="off"]

	<label for="password2">Re-enter new password<span class="req">*</span></label>
	[re-enter-password id="password2" required autocomplete="off"]

	[password-reset-submit class="pp-reset-button pp-reset-button-block" value="Save"]
</div>
FORM;

        $wpdb->query("ALTER TABLE $table ADD handler_structure LONGTEXT NOT NULL AFTER structure");
        $wpdb->query("UPDATE $table SET handler_structure = '$form' WHERE id > 0");


        /** adding default values for admin notification when user is pending approval   */

        $blogname = pp_site_title();

        $data = array();
        $data['notification_subject'] = sprintf(__('[%s] New User Pending Moderation', 'profilepress'), $blogname);
        $data['notification_content'] = <<<CONTENT
A new user is is waiting for your approval on your site $blogname:

Username: {{username}}
E-mail: {{email}}

Click to approve: {{approval_url}}

Click to block: {{block_url}}
CONTENT;

        $existing_data = get_option('pp_extra_moderation', array());
        update_option('pp_extra_moderation', array_merge($existing_data, $data));

    }

    /**
     * Add user role column to registration table per profilepress registration form
     */
    public function pp_update_routine_8()
    {
        global $wpdb;

        $registration_table = PP_REGISTRATION_TABLE;

        if (is_multisite()) {
            $wpdb->query("ALTER TABLE $registration_table ADD user_role VARCHAR(50) NOT NULL AFTER blog_id;");
        } else {
            $wpdb->query("ALTER TABLE $registration_table ADD user_role VARCHAR(50) NOT NULL AFTER date;");
        }
    }

    /**
     * Add default values for new user signup admin email notification.
     */
    public function pp_update_routine_9()
    {
        if (is_multisite()) {
            $general_settings = get_blog_option(null, 'pp_settings_data', array());
        } else {
            $general_settings = get_option('pp_settings_data', array());
        }

        $site_title = pp_site_title();
        $site_url_without_scheme = pp_site_url_without_scheme();

        $signup_admin_email_content = <<<HTML
New user registration on your site {{site_title}}:

Username: {{username}}

Email: {{user_email}}
HTML;

        $general_settings['signup_admin_email_sender_name'] = "$site_title";
        $general_settings['signup_admin_email_sender_email'] = "noreply@$site_url_without_scheme";
        $general_settings['signup_admin_email_type'] = 'text/plain';
        $general_settings['signup_admin_email_subject'] = sprintf(__('[%s] New User Registration'), $site_title);
        $general_settings['signup_admin_email_message'] = $signup_admin_email_content;

        if (is_multisite()) {
            update_blog_option(null, 'pp_settings_data', $general_settings);
        } else {
            update_option('pp_settings_data', $general_settings);
        }
    }

    /**
     * Increment custom profile field key size limit from 20 to 100
     */
    public function pp_update_routine_10()
    {
        global $wpdb;
        $profile_fields_table = CUSTOM_PROFILE_FIELD_TABLE;
        $wpdb->query("ALTER TABLE $profile_fields_table CHANGE field_key field_key VARCHAR(100);");
    }

    /**
     * Increment custom profile field key size limit from 20 to 100
     */
    public function pp_update_routine_11()
    {
        global $wpdb;
        $profile_fields_table = CUSTOM_PROFILE_FIELD_TABLE;
        $login_table = LOGIN_TABLE;
        $registration_table = PP_REGISTRATION_TABLE;
        $password_reset_table = PASSWORD_RESET_TABLE;
        $edit_profile_table = EDIT_PROFILE_TABLE;
        $user_profile_table = USER_PROFILE_TABLE;
        $melange_table = PP_MELANGE_TABLE;
        $wpdb->query("ALTER TABLE $profile_fields_table CHANGE label_name label_name TINYTEXT;");

        $wpdb->query("ALTER TABLE $login_table CHANGE title title VARCHAR(100);");
        $wpdb->query("ALTER TABLE $registration_table CHANGE title title VARCHAR(100);");
        $wpdb->query("ALTER TABLE $password_reset_table CHANGE title title VARCHAR(100);");
        $wpdb->query("ALTER TABLE $edit_profile_table CHANGE title title VARCHAR(100);");
        $wpdb->query("ALTER TABLE $user_profile_table CHANGE title title VARCHAR(100);");
        $wpdb->query("ALTER TABLE $melange_table CHANGE title title VARCHAR(100);");
    }

    /** Singleton poop */
    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}