<?php

/**
 * Default css and
 */

function pp_public_css()
{
    if (!apply_filters('pp_disable_flatui_bootstrap', false)) {
        wp_register_style('pp-bootstrap', ASSETS_URL . '/css/flat-ui/bs/css/bs.css');
        wp_enqueue_style('pp-flat-ui', ASSETS_URL . '/css/flat-ui/css/flat-ui.css', array('pp-bootstrap'));
    }

    wp_enqueue_style('pp-social-button', ASSETS_URL . '/css/zocial/zocial.css', false, PP_VERSION_NUMBER);
    wp_enqueue_style('ppcore', ASSETS_URL . '/css/ppcore.min.css', false, PP_VERSION_NUMBER);
    wp_enqueue_style('font-awesome', ASSETS_URL . '/css/font-awesome/css/font-awesome.min.css');
    wp_enqueue_style('pp-chosen', ASSETS_URL . '/chosen/chosen.min.css');
}


function pp_admin_css()
{
    wp_enqueue_style('pp-chosen', ASSETS_URL . '/chosen/chosen.min.css');

    wp_enqueue_style('pp-admin', ASSETS_URL . '/css/admin-style.css');

    // only load in profilepress settings pages.
    if (!is_pp_admin_page()) return;
    wp_enqueue_style('pp-codemirror', ASSETS_URL . '/codemirror/codemirror.css');
}


function pp_public_js()
{
    $db_data = pp_db_data();
    $is_ajax_mode_disabled = isset($db_data['disable_ajax_mode']) && $db_data['disable_ajax_mode'] == 'yes' ? 'true' : 'false';
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('password-strength-meter');
    wp_enqueue_script('pp-bootstrap-filestyle', ASSETS_URL . '/js/bootstrap-filestyle.js', array('jquery'));
    wp_enqueue_script('pp-jcarousel', ASSETS_URL . '/js/jcarousel.js', array('jquery'));
    wp_enqueue_script('pp-sweetalert2', ASSETS_URL . '/js/sweetalert2.min.js', array('jquery'));
    wp_enqueue_script('pp-frontend-script', ASSETS_URL . '/js/frontend.js', array('jquery'), PP_VERSION_NUMBER, true);
    wp_localize_script('pp-frontend-script', 'pp_ajax_form',
        array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pp-ajax-form-submit'),
            'disable_ajax_form' => apply_filters('pp_disable_ajax_form', (string)$is_ajax_mode_disabled)
        )
    );
    wp_localize_script('pp-frontend-script', 'pp_del_avatar_obj',
        array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'preloader' => ASSETS_URL . '/images/preload.gif',
            'nonce' => wp_create_nonce('del-avatar-nonce')
        )
    );
    wp_enqueue_script('pp-chosen', ASSETS_URL . '/chosen/chosen.jquery.min.js', array('jquery'));
}

function pp_admin_js()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-datepicker', array('jquery'));
    wp_enqueue_script('jquery-ui-sortable');

    wp_enqueue_script('pp-chosen', ASSETS_URL . '/chosen/chosen.jquery.min.js', array('jquery'));

    // only load in profilepress settings pages.
    if (!is_pp_admin_page()) return;

    wp_enqueue_script('pp-admin-scripts', ASSETS_URL . '/js/admin.js', array('jquery', 'jquery-ui-sortable'));

    wp_enqueue_script('pp-codemirror', ASSETS_URL . '/codemirror/codemirror.js');
    wp_enqueue_script('pp-codemirror-css', ASSETS_URL . '/codemirror/css.js');
}


add_action('wp_enqueue_scripts', 'pp_public_css');
add_action('admin_enqueue_scripts', 'pp_admin_css');
add_action('wp_enqueue_scripts', 'pp_public_js');
add_action('admin_enqueue_scripts', 'pp_admin_js');
