<?php

//builder preview Ajax handler
add_action('wp_ajax_pp-builder-preview', 'pp_builder_preview_handler');

function pp_builder_preview_handler()
{

    if (current_user_can('administrator')) {
        // iframe preview url content
        if (isset($_GET['action']) && $_GET['action'] == 'pp-builder-preview') {
            include VIEWS . '/live-preview/builder-preview.php';
        } // if ajax post request is received return the parsed shortcode
        elseif (isset($_POST['builder_structure']) && !empty($_POST['builder_structure'])) {
            Front_End_Profile_Builder_Shortcode_Parser::get_instance();
            echo do_shortcode(stripslashes($_POST['builder_structure']));
        }
    }

    // IMPORTANT: don't forget to "exit"
    wp_die();

}

//builder preview Ajax handler
add_action('wp_ajax_pp_del_avatar', 'pp_ajax_delete_avatar');

function pp_ajax_delete_avatar()
{
    if (current_user_can('read')) {
        // check to see if the submitted nonce matches with the generated nonce we created earlier
        if (!wp_verify_nonce($_POST['nonce'], 'del-avatar-nonce')) {

            $response = json_encode(array('error' => 'nonce_failed'));

        } else {
            ProfilePress_Edit_Profile::remove_avatar_core();

            // generate the response
            $response = json_encode(array('success' => true, 'default' => pp_get_avatar_url('', '300')));
        }

        // response output
        header("Content-Type: application/json");
        echo $response;
    }

    // IMPORTANT: don't forget to "exit"
    wp_die();

}


add_action('wp_ajax_pp_profile_fields_sortable', 'pp_profile_fields_sortable_func');

function pp_profile_fields_sortable_func()
{
    if (current_user_can('administrator')) {
        global $wpdb;

        $posted_data = array_map('absint', $_POST['data']);
        $profile_field_ids = PROFILEPRESS_sql::get_profile_field_ids();
        $table_name = "{$wpdb->base_prefix}pp_profile_fields";

        /* Alter the IDs of the profile fields in DB incrementally starting from the last ID number of the record. */

        // set the index to the last profile field ID
        $index = array_pop($profile_field_ids) + 1;

        foreach ($posted_data as $id) {

            $wpdb->update(
                $table_name,
                array(
                    'id' => $index,
                ),
                array('id' => $id),
                array(
                    '%d',
                ),
                array('%d')
            );

            $index++;
        }


        /* Reorder the profile fields ID starting from 1 incrementally. */

        $index_2 = 1;

        // fetch the profile fields again
        $profile_field_ids_2 = PROFILEPRESS_sql::get_profile_field_ids();

        foreach ($profile_field_ids_2 as $id) {
            $wpdb->update(
                $table_name,
                array(
                    'id' => $index_2,
                ),
                array('id' => $id),
                array(
                    '%d',
                ),
                array('%d')
            );

            $index_2++;
        }
    }

    wp_die();
}

add_action('wp_ajax_pp_contact_info_sortable', 'pp_pp_contact_info_sortable_func');

function pp_pp_contact_info_sortable_func()
{

    if (current_user_can('administrator')) {

        $posted_data = array_map('esc_attr', $_POST['data']);
        $db_data = get_option('pp_contact_info', array());

        $newArray = array();

        foreach ($posted_data as $key) {
            $newArray[$key] = $db_data[$key];
        }

        update_option('pp_contact_info', $newArray);
    }

    wp_die();
}

//handle ajax login
add_action('wp_ajax_nopriv_pp_ajax_login', 'pp_ajax_login_func');

function pp_ajax_login_func()
{
    if (isset($_REQUEST['data'])) {
        parse_str($_REQUEST['data'], $data); //tabbed-login-name

        // populate global $_POST variable.
        $_POST = $data;

        $login_form_id = absint($data['login_form_id']);

        // $login_username, $login_password, $login_remember, $login_redirect, $ogin_form_id are all populated by parse_str()
        $login_status_css_class = apply_filters('pp_login_error_css_class', 'profilepress-login-status', $login_form_id);

        $login_username = !empty($data['tabbed-login-name']) ? $data['tabbed-login-name'] : $data['login_username'];
        $login_password = !empty($data['tabbed-login-password']) ? $data['tabbed-login-password'] : $data['login_password'];
        $login_remember = !empty($data['tabbed-login-remember-me']) ? $data['tabbed-login-remember-me'] : @$data['login_remember'];

        $login_username = sanitize_text_field($login_username);
        $login_password = sanitize_text_field($login_password);
        $login_remember = sanitize_text_field($login_remember);
        $login_redirect = sanitize_text_field(@$data['login_redirect']);

        // response is WP_Error on error or redirect url on success.
        $response = ProfilePress_Login_Auth::login_auth($login_username, $login_password, $login_remember, $login_form_id, $login_redirect);

        if (isset($response) && is_wp_error($response)) {
            $login_error = '<div class="' . $login_status_css_class . '">';
            $login_error .= $response->get_error_message();
            $login_error .= '</div>';

            $ajax_response = array('success' => false, 'message' => $login_error);
        } else {
            $ajax_response = array('success' => true, 'redirect' => $response);
        }

        wp_send_json($ajax_response);
    }

    wp_die();
}

//handle ajax signup
add_action('wp_ajax_nopriv_pp_ajax_signup', 'pp_ajax_signup_func');

function pp_ajax_signup_func()
{
    if (isset($_REQUEST)) {

        $is_melange = (!empty($_POST['is_melange']) && $_POST['is_melange'] == 'true');

        $form_id = !empty($_POST['melange_id']) ? $_POST['melange_id'] : @$_POST['signup_form_id'];
        $form_id = absint($form_id);

        $redirect = esc_url_raw(@$_POST['signup_redirect']);
        $no_login_redirect = esc_url_raw(@$_POST['signup_no_login_redirect']);

        // filter for the css class of the error message
        $reg_status_css_class = apply_filters('pp_registration_error_css_class', 'profilepress-reg-status', $form_id);

        // if this is tab widget.
        if ($_POST['is-pp-tab-widget'] && $_POST['is-pp-tab-widget'] == 'true') {
            $widget_status = @Tabbed_widget_dependency::registration(
                $_POST['tabbed-reg-username'],
                $_POST['tabbed-reg-password'],
                $_POST['tabbed-reg-email'],
                $_POST['auto-login-after-reg']
            );

            if (!empty($widget_status)) {
                $response = '<div class="pp-tab-status">' . $widget_status . '</div>';
            }

        } else {
            $response = ProfilePress_Registration_Auth::register_new_user($_POST, $form_id, $_FILES, $redirect, $is_melange, $no_login_redirect);
        }

        // display form generated messages
        if (!empty($response)) {
            if (is_array($response)) {
                $ajax_response = array('redirect' => $response[0]);
            } else {
                $ajax_response = array('message' => html_entity_decode($response));
            }

            wp_send_json($ajax_response);
        }
    }

    wp_die();
}

//handle ajax password reset
add_action('wp_ajax_nopriv_pp_ajax_passwordreset', 'pp_ajax_passwordreset_func');
add_action('wp_ajax_pp_ajax_passwordreset', 'pp_ajax_passwordreset_func');

function pp_ajax_passwordreset_func()
{

    if (isset($_REQUEST['data'])) {
        parse_str($_REQUEST['data'], $data);

        // populate global $_POST and $_REQUEST variable.
        $_POST = $_REQUEST = $data;


        // variable is populated by parse_str()
        $user_login = !empty($data['tabbed-user-login']) ? $data['tabbed-user-login'] : $data['user_login'];
        $user_login = sanitize_text_field($user_login);

        $is_melange = (!empty($_POST['is_melange']) && $_POST['is_melange'] == 'true');

        $form_id = !empty($data['melange_id']) ? $data['melange_id'] : $data['passwordreset_form_id'];
        $form_id = absint($form_id);

        // do password reset
        if (!empty($data['reset_key']) && !empty($data['reset_login'])) {
            // needed for checking if this is for do password reset.
            $_REQUEST['reset_password'] = true;
            $response = ProfilePress_Password_Reset::do_password_reset();
        } else {
            // response is WP_Error on error or redirect url on success.
            $response = ProfilePress_Password_Reset::password_reset_status($user_login, $form_id, $is_melange);
        }

        $ajax_response = array();
        $ajax_response['status'] = is_array($response) ? true : false;
        $ajax_response['message'] = is_array($response) ? html_entity_decode($response[0]) : html_entity_decode($response);

        wp_send_json($ajax_response);
    }

    wp_die();
}


//handle ajax edit profile
add_action('wp_ajax_pp_ajax_editprofile', 'pp_ajax_editprofile_func');

function pp_ajax_editprofile_func()
{
    if (isset($_REQUEST)) {

        $is_melange = (!empty($_POST['is_melange']) && $_POST['is_melange'] == 'true');

        $form_id = !empty($_POST['melange_id']) ? $_POST['melange_id'] : $_POST['editprofile_form_id'];
        $form_id = absint($form_id);
        $redirect = esc_url_raw(@$_POST['editprofile_redirect']);

        // check to see if the submitted nonce matches with the generated nonce we created earlier
        if (!wp_verify_nonce($_REQUEST['nonce'], 'pp-ajax-form-submit')) {
            wp_send_json(array(
                'success' => false,
                'message' => '<div class="profilepress-edit-profile-status">' . __('Security validation failed. Try again', 'profilepress') . '</div>'
            ));
        }

        $response = ProfilePress_Edit_Profile::process_func($_POST, $_FILES, $form_id, $is_melange, $redirect);

        // display form generated messages
        if (isset($response) && is_array($response)) {
            wp_send_json($response);
        }
    }

    wp_die();
}