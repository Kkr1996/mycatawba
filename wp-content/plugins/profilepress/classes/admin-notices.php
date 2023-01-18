<?php

if (class_exists('PAnD')) {
    // persist admin notice dismissal initialization
    add_action('admin_init', array('PAnD', 'init'));
}

/**
 * Notice when user registration is disabled.
 */
function pp_registration_disabled_notice()
{
    if (!is_super_admin(get_current_user_id())) {
        return;
    }
    if (get_option('users_can_register') || apply_filters('pp_remove_registration_disabled_notice', false)) {
        return;
    }

    $url = is_multisite() ? network_admin_url('settings.php') : admin_url('options-general.php');

    ?>
    <div id="message" class="updated notice is-dismissible">
        <p>
            <?php printf(__('User registration currently disabled. To enable, Go to <a href="%1$s">Settings -> General</a>, and under Membership, check "Anyone can register"', 'profilepress'), $url); ?>
            . </p>
    </div>
    <?php
}

function pp_starter_theme_install_success()
{
    if (isset($_GET['starter-theme-install']) && $_GET['starter-theme-install'] == 'success') {
        echo '<div id="message" class="updated notice is-dismissible"><p><strong>' . __('Starter themes successfully installed.', 'profilepress') . '</strong></p></div>';
    }
}


function pp_ajax_mode_activated()
{
    if (!class_exists('PAnD')) return;

    if (apply_filters('pp_disable_ajax_mode_admin_notice', false)) {
        return;
    }

    if (!PAnD::is_admin_notice_active('pp-disable-ajax-mode-forever')) {
        return;
    }

    $db_data = pp_db_data();

    if (!isset($db_data['disable_ajax_mode']) || @$db_data['disable_ajax_mode'] != 'yes') {
        $msg = sprintf(
            __('Ajax mode in login, registration, password reset & edit profile forms is currently enabled. %sClick here%s if you wish to deactivate it. %sLearn more%s', 'profilepress'),
            '<a href="' . admin_url('admin.php?page=pp-config#disable_ajax_mode') . '">', '</a>',
            '<a target="_blank" href="https://docs.profilepress.net/en/latest/configuration/ajax-mode/">', '</a>'
        );
        echo '<div data-dismissible="pp-disable-ajax-mode-forever" id="message" class="updated notice is-dismissible"><p>' . $msg . '</p></div>';
    }
}

add_action('admin_notices', 'pp_ajax_mode_activated');
add_action('admin_notices', 'pp_registration_disabled_notice');
add_action('admin_notices', 'pp_starter_theme_install_success');

add_filter('removable_query_args', 'pp_removable_query_args');

function pp_removable_query_args($args)
{
    $args[] = 'password-reset-edited';
    $args[] = 'password-reset-added';
    $args[] = 'registration-added';
    $args[] = 'registration-edited';
    $args[] = 'login-added';
    $args[] = 'login-edited';
    $args[] = 'settings-update';
    $args[] = 'edit-profile-edited';
    $args[] = 'edit-profile-added';
    $args[] = 'user-profile-added';
    $args[] = 'user-profile-edited';
    $args[] = 'field-edited';
    $args[] = 'field-added';
    $args[] = 'melange-edited';
    $args[] = 'melange-added';
    $args[] = 'new-contact-info';
    $args[] = 'starter-theme-install';

    return $args;
}