<?php
/**
 * @package   ProfilePress_WordPress_Plugin
 * @author    ProfilePress Team <me@w3guy.com>
 * @license   GPL-2.0+
 * @link      https://profilepress.net
 * @copyright 2018 Proper Fraction Limited <me@w3guy.com>
 * @wordpress-plugin
 *
 * Plugin Name: ProfilePress
 * Plugin URI: https://profilepress.net
 * Description: Ultimate Custom Registration, Login, Profile and User Account Manager WordPress Plugin.
 * Version: 2.9.8
 * Author: ProfilePress Team
 * Contributors: collizo4sky
 * Author URI: https://profilepress.net
 * Text Domain: profilepress
 * Domain Path: /languages
 *
 */

defined('ABSPATH') or die("No script kiddies please!");

define('PROFILEPRESS_SYSTEM_FILE_PATH', __FILE__);
define('PROFILEPRESS_ROOT', plugin_dir_path(__FILE__));
define('PROFILEPRESS_ROOT_URL', plugin_dir_url(__FILE__));

define('CSS', PROFILEPRESS_ROOT . 'css');
define('WIDGETS', PROFILEPRESS_ROOT . 'widgets');
define('PASSWORD_RESET', PROFILEPRESS_ROOT . 'password-reset');
define('VIEWS', PROFILEPRESS_ROOT . 'views');
define('CLASSES', PROFILEPRESS_ROOT . 'classes');
define('RECAPTCHA', PROFILEPRESS_ROOT . 'recaptcha');
define('REGISTER_ACTIVATION', PROFILEPRESS_ROOT . 'register-activation');
define('SOCIAL_LOGIN', PROFILEPRESS_ROOT . 'social-login');
define('PP_BUGS', PROFILEPRESS_ROOT . 'bugs/');

global $wpdb;
define('EDIT_PROFILE_TABLE', $wpdb->base_prefix . 'pp_edit_profile_builder');
define('LOGIN_TABLE', $wpdb->base_prefix . 'pp_login_builder');
define('PASSWORD_RESET_TABLE', $wpdb->base_prefix . 'pp_password_reset_builder');
define('CUSTOM_PROFILE_FIELD_TABLE', $wpdb->base_prefix . 'pp_profile_fields');
define('PP_REGISTRATION_TABLE', $wpdb->base_prefix . 'pp_registration_builder');
define('USER_PROFILE_TABLE', $wpdb->base_prefix . 'pp_user_profile_builder');
define('MAKE_BUILDER_WIDGET_TABLE', $wpdb->base_prefix . 'pp_builder_widget');
define('PASSWORDLESS_TABLE', $wpdb->base_prefix . 'pp_passwordless');
define('PP_MELANGE_TABLE', $wpdb->base_prefix . 'pp_melange');

// profilePress themes folder constant
define('TEMPLATES_FOLDER', WP_CONTENT_DIR . '/uploads/pp-themes');
define('TEMPLATES_URL', WP_CONTENT_URL . '/uploads/pp-themes');

define('ASSETS_URL', PROFILEPRESS_ROOT_URL . 'assets');
define('VIEWS_URL', PROFILEPRESS_ROOT_URL . 'views');

// Directory for uploaded avatar
define("AVATAR_UPLOAD_DIR", apply_filters('pp_avatar_folder', WP_CONTENT_DIR . '/uploads/pp-avatar/'));
define("AVATAR_UPLOAD_URL", apply_filters('pp_avatar_url', WP_CONTENT_URL . '/uploads/pp-avatar/'));

// Directory for file custom fields
define("PP_FILE_UPLOAD_DIR", apply_filters('pp_files_folder', WP_CONTENT_DIR . '/uploads/pp-files/'));
define("PP_FILE_UPLOAD_URL", apply_filters('pp_files_url', WP_CONTENT_URL . '/uploads/pp-files/'));

define('PROFILE_FIELDS_SETTINGS_PAGE_SLUG', 'pp-cpf', true);
define('LOGIN_BUILDER_SETTINGS_PAGE_SLUG', 'pp-login', true);
define('REGISTRATION_BUILDER_SETTINGS_PAGE_SLUG', 'pp-registration', true);
define('PASSWORD_RESET_BUILDER_SETTINGS_PAGE_SLUG', 'pp-password-reset', true);
define('EDIT_PROFILE_BUILDER_SETTINGS_PAGE_SLUG', 'pp-edit-profile', true);
define('USER_PROFILE_BUILDER_SETTINGS_PAGE_SLUG', 'pp-user-profile', true);
define('MELANGE_SETTINGS_PAGE_SLUG', 'pp-melange', true);
define('LICENSE_SETTINGS_PAGE_SLUG', 'pp-license', true);

// EDD ish
define('PP_STORE_URL', 'https://profilepress.net', true);
define('PP_ITEM_NAME', 'ProfilePress WordPress Plugin', true);
define('PP_ITEM_ID', 3092, true);
define('PP_PLUGIN_DEVELOPER', 'ProfilePress Team', true);
define('PP_VERSION_NUMBER', '2.9.8', true);

add_action('plugins_loaded', 'pp_plugin_load_textdomain');
function pp_plugin_load_textdomain()
{
    load_plugin_textdomain('profilepress', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

require_once CLASSES . '/class.load-files.php';

register_activation_hook(__FILE__, array('ProfilePress_Plugin_On_Activate', 'instance'));
register_deactivation_hook(__FILE__, 'pp_flush_rewrites_deactivate');

// update DB when new new blog is created in multi site.
add_action('wpmu_new_blog', 'pp_activation_on_new_mu_blog', 10, 2);
/** register activation ish when a new multi-site blog is created */
function pp_activation_on_new_mu_blog($blog_id, $user_id)
{
    if (is_plugin_active_for_network('profilepress/profilepress.php')) {
        switch_to_blog($blog_id);
        ProfilePress_Plugin_On_Activate::plugin_settings_activation();
        restore_current_blog();
    }
}

/** Flush rewrite rule on plugin deactivation */
function pp_flush_rewrites_deactivate()
{
    flush_rewrite_rules();
}

// load plugin files
ProfilePress_Dir::load_files();

// call plugin update
add_action('plugins_loaded', 'pp_update_plugin');
function pp_update_plugin()
{
    if (!is_admin()) {
        return;
    }

    $instance = ProfilePress\Plugin_Update\PP_Update::get_instance();
    $instance->maybe_update();
}

update_site_option('pp_version', PP_VERSION_NUMBER);