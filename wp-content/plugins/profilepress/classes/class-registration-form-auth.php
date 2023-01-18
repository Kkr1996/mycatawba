<?php

/** @todo refactor this class to be modular and easy to @see moderation-notification.php */
class ProfilePress_Registration_Auth
{

    static protected $registration_form_status;

    // recaptcha db settings
    static protected $recaptcha_db_settings;


    /**
     * Is this an Ajax request?
     *
     * @return bool
     */
    public static function is_ajax()
    {
        return defined('DOING_AJAX') && DOING_AJAX;
    }

    /**
     * Called to validate registration form field
     *
     * @param string $id
     * @param string $redirect
     * @param bool $is_melange
     * @param string $no_login_redirect
     *
     * @return string
     */
    public static function validate_registration_form($id = '', $redirect = '', $is_melange = false, $no_login_redirect = '')
    {
        // if registration form have been submitted process it

        // filter to change registration submit button name to avoid validation for forms on same page
        $submit_name = apply_filters('pp_registration_submit_name', 'reg_submit', $id);
        if (isset($_POST[$submit_name])) {
            $register_the_user = self::register_new_user(
                $_POST,
                $id,
                $_FILES,
                $redirect,
                $is_melange,
                $no_login_redirect
            );
        }

        // display form generated messages
        if (!empty($register_the_user)) {
            $registration_status = html_entity_decode($register_the_user);
        } else {
            $registration_status = '';
        }

        return apply_filters('pp_registration_status', $registration_status);
    }

    /**
     * Wrapper function for call to the welcome email class
     *
     * @param int $form_id
     * @param string $email
     * @param string $username
     * @param string $password
     * @param string $first_name
     * @param string $last_name
     */
    public static function send_welcome_email($form_id, $email, $username, $password, $first_name, $last_name)
    {

        $db_settings_data = pp_db_data();

        // check if sending of welcome mail after registration is activated
        $send_welcome_mail_status = apply_filters('pp_activate_send_welcome_email', @$db_settings_data['set_welcome_message_after_reg']);

        if ($send_welcome_mail_status == 'on') {

            do_action('pp_before_send_welcome_mail', $form_id, $email, $username, $password, $first_name, $last_name);

            // send welcome email
            new Send_Email_After_Registration($form_id, $email, $username, $password, $first_name, $last_name);

            do_action('pp_after_send_welcome_mail', $form_id, $email, $username, $password, $first_name, $last_name);
        }
    }


    /**
     *
     * Wrapper function for call to the automatic login after reg function
     *
     * @param int $user_id
     * @param int $form_id
     * @param string $redirect redirect url after registration
     *
     * @return mixed
     */
    public static function auto_login_after_reg($user_id, $form_id, $redirect)
    {
        if (!empty($redirect)) {
            return PP_Auto_Login_After_Registration::initialize($user_id, $form_id, $redirect);
        } else {

            $db_settings_data = pp_db_data();

            $activate_auto_login = !empty($db_settings_data['set_auto_login_after_reg']) ? $db_settings_data['set_auto_login_after_reg'] : '';

            $auto_login_option = apply_filters('pp_activate_auto_login_after_signup', $activate_auto_login, $form_id);

            if ($auto_login_option == 'on') {
                return PP_Auto_Login_After_Registration::initialize($user_id, $form_id);
            }
        }
    }

    /**
     * Perform redirect after registration without logging the user in.
     *
     * @param int $form_id
     * @param string $no_login_redirect URL to redirect to.
     *
     * @return array
     */
    public static function no_login_redirect_after_reg($form_id, $no_login_redirect)
    {
        do_action('pp_before_no_login_redirect_after_reg', $no_login_redirect, $form_id);
        if (self::is_ajax()) {
            // we are returning array to uniquely identify redirect.
            return array($no_login_redirect);
        }

        wp_redirect($no_login_redirect);
        exit;
    }


    /**
     * Register new users
     *
     * @param array $post $_POST data
     * @param int $form_id Registration builder ID
     * @param array $files Handle for global $_FILES
     * @param string $redirect URL to redirect to after registration.
     *
     * @return string
     */
    public static function register_new_user($post, $form_id, $files = array(), $redirect = '', $is_melange, $no_login_redirect = '')
    {
        // create an array of acceptable userdata for use by wp_insert_user
        $valid_userdata = array(
            'reg_username',
            'reg_password',
            'reg_password2',
            'reg_email2',
            'reg_password_present',
            'reg_email',
            'reg_website',
            'reg_nickname',
            'reg_display_name',
            'reg_first_name',
            'reg_last_name',
            'reg_bio',
            'reg_select_role',
        );

        // get the data for userdata
        $segregated_userdata = array();

        // loop over the $_POST data and create an array of the wp_insert_user userdata
        foreach ($post as $key => $value) {
            if ($key == 'reg_submit') {
                continue;
            }

            if (in_array($key, $valid_userdata)) {
                $segregated_userdata[$key] = esc_attr($value);
            }
        }

        $email = isset($segregated_userdata['reg_email']) ? $segregated_userdata['reg_email'] : '';

        $email2 = isset($segregated_userdata['reg_email2']) ? $segregated_userdata['reg_email2'] : null;

        // Handle username creation when username requirement is disabled.
        if (pp_is_signup_form_username_disabled($form_id, $is_melange)) {
            $username = sanitize_user(current(explode('@', $email)), true);
            // Ensure username is unique.
            $append = 1;
            $o_username = $username;
            while (username_exists($username)) {
                $username = $o_username . $append;
                $append++;
            }

        } else {
            // get convert the form post data to userdata for use by wp_insert_users
            $username = apply_filters('pp_registration_username_value',
                isset($segregated_userdata['reg_username']) ? $segregated_userdata['reg_username'] : ''
            );
        }

        $password = apply_filters('pp_registration_password_value', isset($segregated_userdata['reg_password']) ? $segregated_userdata['reg_password'] : '');

        // if the reg_password field isn't present in registration, generate a password for the user and set a flag to send a password reset message
        if (empty($password) && (empty($segregated_userdata['reg_password_present']) || $segregated_userdata['reg_password_present'] != 'true')) {
            $password = wp_generate_password(24);
            $flag_to_send_password_reset = apply_filters('pp_enable_auto_send_password_reset_flag', true);
        }

        $password2 = isset($segregated_userdata['reg_password2']) ? $segregated_userdata['reg_password2'] : null;
        $website = isset($segregated_userdata['reg_website']) ? $segregated_userdata['reg_website'] : '';
        $nickname = isset($segregated_userdata['reg_nickname']) ? $segregated_userdata['reg_nickname'] : '';
        $display_name = isset($segregated_userdata['reg_display_name']) ? $segregated_userdata['reg_display_name'] : '';
        $first_name = isset($segregated_userdata['reg_first_name']) ? $segregated_userdata['reg_first_name'] : '';
        $last_name = isset($segregated_userdata['reg_last_name']) ? $segregated_userdata['reg_last_name'] : '';
        $bio = isset($segregated_userdata['reg_bio']) ? $segregated_userdata['reg_bio'] : '';
        $role = isset($segregated_userdata['reg_select_role']) ? $segregated_userdata['reg_select_role'] : '';

        // real uer data
        $real_userdata = array(
            'user_login' => $username,
            'user_pass' => $password,
            'user_email' => apply_filters('pp_registration_email_value', $email),
            'user_url' => apply_filters('pp_registration_website_value', $website),
            'nickname' => apply_filters('pp_registration_nickname_value', $nickname),
            'display_name' => apply_filters('pp_registration_display_name_value', $display_name),
            'first_name' => apply_filters('pp_registration_first_name_value', $first_name),
            'last_name' => apply_filters('pp_registration_last_name_value', $last_name),
            'description' => apply_filters('pp_registration_bio_value', $bio),
        );

        if (!empty($role)) {
            // acceptable defined roles in reg-select-role shortcode.
            $accepted_role = (array)self::acceptable_defined_roles($form_id);

            if ($role != 'administrator' && in_array($role, $accepted_role)) {
                $real_userdata['role'] = $role;
            }
        } else {
            $builder_role = PROFILEPRESS_sql::get_registration_user_role($form_id);
            // only set user role if the registration form has one set
            // otherwise no role is set for the user thus wp_insert_user will use the default user role set in Settings > General
            if (!empty($builder_role)) {
                $real_userdata['role'] = $builder_role;
            }
        }

        // filter for the css class of the error message
        $reg_status_css_class = apply_filters('pp_registration_error_css_class', 'profilepress-reg-status', $form_id);

        /* start filter Hook */
        $reg_errors = new WP_Error();

        if (!is_email($real_userdata['user_email'])) {
            $reg_errors->add('invalid_email', __('Email address is not valid', 'profilepress'));
        }

        if (isset($password2) && ($password != $password2)) {
            $reg_errors->add('password_mismatch', __('Passwords do not match', 'profilepress'));
        }

        if (isset($email2) && ($email != $email2)) {
            $reg_errors->add('email_mismatch', __('Email addresses do not match', 'profilepress'));
        }

        if (isset($post['pp_enforce_password_meter']) && ($post['pp_enforce_password_meter'] != '1')) {
            $reg_errors->add('password_weak', __('Password is not strong', 'profilepress'));
        }


        // --------START ---------   validation for required fields ----------------------//
        // loop through required fields and throw error if any is empty
        if (!empty($_POST['required-fields']) && is_array($_POST['required-fields'])) {
            foreach ($_POST['required-fields'] as $key => $value) {
                if (empty($_POST[$key])) {
                    $reg_errors->add('required_field_empty', sprintf(__('%s field is required', 'profilepress'), $value));
                    // stop looping if a required field is found empty.
                    break;
                }
            }
        }
        // --------END ---------   validation for required fields ----------------------//

        // get the data for use by update_meta
        $custom_usermeta = array();
        // loop over the $_POST data and create an array of the invalid userdata/ custom usermeta
        foreach ($post as $key => $value) {
            if ($key == 'reg_submit') {
                continue;
            }

            if (!in_array($key, $valid_userdata)) {
                $custom_usermeta[$key] = is_array($value) ? array_map('sanitize_text_field', $value) : sanitize_text_field($value);
            }
        }

        // merge real data(for use by wp_insert_user()) and custom profile fields data
        $user_data = array_merge($real_userdata, $custom_usermeta);

        /* Begin Filter Hook */
        // call validate reg from function
        $reg_form_errors = apply_filters('pp_registration_validation', $reg_errors, $form_id, $user_data);
        if (is_wp_error($reg_form_errors) && $reg_form_errors->get_error_code() != '') {
            return '<div class="' . $reg_status_css_class . '">' . $reg_form_errors->get_error_message() . '</div>';
        }
        /* End Filter Hook */

        // --------START ---------   validation for file upload ----------------------//
        $uploads = PP_File_Uploader::init();
        $upload_errors = '';
        if (!empty($uploads)) {
            foreach ($uploads as $field_key => $uploaded_filename_or_wp_error) {
                if (is_wp_error($uploads[$field_key])) {
                    $upload_errors .= $uploads[$field_key]->get_error_message() . '<br/>';
                }
            }
            if (!empty($upload_errors)) {
                return "<div class='$reg_status_css_class'>$upload_errors</div>";
            }
        }
        // --------END ---------   validation for file upload ----------------------//


        // --------START ---------   validation for avatar upload ----------------------//
        // if user selected a photo for upload, process it.
        if (isset($files['reg_avatar']['name']) && !empty($files['reg_avatar']['name'])) {
            $upload_avatar = PP_User_Avatar_Upload::process($files['reg_avatar']);

            if (is_wp_error($upload_avatar)) {
                $error = $upload_avatar->get_error_message();

                return "<div class='$reg_status_css_class'>$error</div>";
            }
        }

        // --------END ---------   validation for avatar upload ----------------------//

        /* Start Action Hook */
        do_action('pp_before_registration', $form_id, $user_data);
        /* End Action Hook */

        // proceed to registration using wp_insert_user method which return the new user id
        $user_id = wp_insert_user($real_userdata);

        // if moderation is active, set new registered users as pending
        if (apply_filters('pp_is_moderation_Action', self::moderation_is_active(), $form_id, $real_userdata, $user_id)) {
            if (is_null(get_role('pending_users'))) {
                add_role('pending_users', 'Pending');
            }

            // make registered user pending.
            ProfilePress_User_Moderation_Admin::make_user_pending($user_id);
        }

        if (is_int($user_id) && isset($flag_to_send_password_reset) && $flag_to_send_password_reset === true) {
            ProfilePress_Password_Reset::retrieve_password_func($username);
        }


        // register custom profile field
        if (!is_wp_error($user_id)) {
            // record signup via
            if ($is_melange) {
                add_user_meta($user_id, '_pp_signup_melange_via', $form_id);
            } else {
                add_user_meta($user_id, '_pp_signup_via', $form_id);
            }

            // update custom profile field
            $custom_usermeta['pp_profile_avatar'] = isset($upload_avatar) ? $upload_avatar : null;

            // if we get to this point, it means the files pass validation defined above.
            // array of files uploaded. Array key is the "custom field key" and the filename as the array value.
            $custom_usermeta['pp_uploaded_files'] = $uploads;

            // if @$user_id is no WP_Error, add the extra user profile field
            if (is_array($custom_usermeta)) {

                foreach ($custom_usermeta as $key => $value) {
                    update_user_meta($user_id, $key, $value);

                    // the 'edit_profile' parameter is used to distinguish it from same action hook in ProfilePress_Registration_Auth
                    do_action('pp_after_custom_field_update', $key, $value, $user_id, 'registration');
                }
            }

            if (is_int($user_id)) {
                wp_new_user_notification($user_id, null, 'admin');
            }

            // if user moderation is active, send pending notification.
            if (self::moderation_is_active()) {
                PP_User_Moderation_Notification::pending($user_id);
                PP_User_Moderation_Notification::pending_admin_notification($user_id);
            }

            // send welcome message
            self::send_welcome_email($form_id, $email, $username, $password, $first_name, $last_name);

            /**
             * Fires after a user registration is completed.
             *
             * @param int $form_id ID of the registration form.
             * @param mixed $user_data array of registered user info.
             * @param int $user_id ID of the registered user.
             */
            do_action('pp_after_registration', $form_id, $user_data, $user_id);
            /* End Action Hook */

            if (!empty($no_login_redirect)) {
                $response = self::no_login_redirect_after_reg($form_id, $no_login_redirect);
            } else {
                /**
                 * call auto-login
                 *
                 * @param int $user_id registered user ID
                 * @param int $form_id registration form ID
                 * @param string $redirect redirect url after login
                 */
                $response = self::auto_login_after_reg($user_id, $form_id, $redirect);
            }

            if (self::is_ajax() && isset($response) && !empty($response) && is_array($response)) {
                // $response should be an array containing the url to redirect to.
                return $response;
            }

            // get the "registration successful message" for the registration page
            if ($is_melange) {
                $message_on_successful_registration = PROFILEPRESS_sql::get_db_success_melange($form_id, 'registration_msg');
            } else {
                $message_on_successful_registration = PROFILEPRESS_sql::get_db_success_registration($form_id);
            }

            $status = !empty($message_on_successful_registration) ? $message_on_successful_registration : '<div class="profilepress-reg-status">' . __('Registration successful.', 'profilepress') . '</div>';
            $status = apply_filters('pp_registration_success_message', $status);

        } else {
            $status = '<div class="' . $reg_status_css_class . '">' . $user_id->get_error_message() . '</div>';
            $status = apply_filters('pp_registration_error_message', $status);

        }

        return $status;

    }

    /**
     * Check if the user moderation module is active.
     *
     * @return bool
     */
    public static function moderation_is_active()
    {
        $extra_moderation_data = get_option('pp_extra_moderation');

        return isset($extra_moderation_data['activate_moderation']) && $extra_moderation_data['activate_moderation'] == 'active' ? true : false;
    }


    /**
     * Array list of acceptable defined roles.
     *
     * @param int $form_id ID of registration form
     *
     * @return array
     */
    public static function acceptable_defined_roles($form_id)
    {
        $registration_structure = PROFILEPRESS_sql::get_a_builder_structure('registration', $form_id);

        // find the first occurrence of reg-select-role shortcode.
        preg_match('/\[reg-select-role .+\]/', $registration_structure, $matches);

        if (empty($matches) || !isset($matches[0])) {
            return;
        } else {
            $result = shortcode_parse_atts($matches[0]);

            //if no options attribute was found in the shortcode, default to all list of editable roles
            if (empty($result['options'])) {
                $acceptable_user_role = array_keys(pp_get_editable_roles());
            } else {
                $acceptable_user_role = array_map('trim', explode(',', $result['options']));
            }

            return apply_filters('pp_acceptable_user_role', $acceptable_user_role, $form_id);
        }
    }
}