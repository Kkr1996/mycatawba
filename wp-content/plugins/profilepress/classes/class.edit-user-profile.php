<?php

/** Handles editing profile of logged in user */
class ProfilePress_Edit_Profile
{

    public static function is_ajax()
    {
        return defined('DOING_AJAX') && DOING_AJAX;
    }

    /**
     * Called to validate the password reset form
     *
     * @param int $id builder ID
     * @param bool $is_melange is the validation being called from a melange?
     * @param string $redirect URL to redirect to after edit profile.
     *
     * @return string error or success message
     */
    public static function validate_form($id = null, $is_melange = false, $redirect = '')
    {
        // make $post global so the page/post ID can be retrieve for comparison below
        global $post;

        $response = self::process_func($_POST, $_FILES, $id, $is_melange, $redirect);

        // check if the page being viewed contains the "edit profile" shortcode. if true, redirect to login page
        if (has_shortcode($post->post_content, 'profilepress-edit-profile')) {
            if ( ! is_user_logged_in()) {
                wp_redirect(wp_login_url());
                exit;
            }
        }

        return $response;
    }

    /**
     * @param array $post_data $_POST data
     * @param array $file_data $_FILES submitted file via form
     * @param int $form_id builder ID
     * @param string $redirect URL to redirect to after profile edit.
     *
     * @return string the edit profile response be it error or success message
     */
    public static function process_func($post_data, $file_data, $form_id, $is_melange, $redirect = '')
    {
        // if edit-profile form have been submitted process it

        // filter to change edit-profile form submit button name to avoid validation for forms on same page
        $submit_name = apply_filters('pp_edit_profile_submit_name', 'eup_submit', $form_id);

        // process if this is an ajax call or edit profile form submit button was clicked.
        if (self::is_ajax() || isset($post_data[$submit_name])) {
            $edit_profile_response = self::update_user_profile($post_data, $file_data, $form_id, $redirect);
        }

        if (isset($post_data['eup_remove_avatar']) && $post_data['eup_remove_avatar'] == 'removed') {
            self::remove_user_avatar();
        }

        // get the success message on profile edit
        if ($is_melange) {
            $message_on_successful_update = PROFILEPRESS_sql::get_db_success_melange($form_id, 'edit_profile_msg');
        } else {
            $message_on_successful_update = PROFILEPRESS_sql::get_db_success_edit_profile($form_id);
        }
        $message_on_successful_update = isset($message_on_successful_update) ? $message_on_successful_update : '<h5>Profile Successfully Edited</h5>';

        // used by ajax mode.
        if ( ! empty($edit_profile_response) && self::is_ajax()) {
            $ajax_response = array();
            if (is_array($edit_profile_response) && $edit_profile_response['status'] == 'success') {
                $ajax_response['message'] = html_entity_decode($message_on_successful_update);
                if ( ! empty($edit_profile_response['avatar_url'])) {
                    $ajax_response['avatar_url'] = $edit_profile_response['avatar_url'];
                }
            } else {
                $ajax_response['message'] = '<div class="profilepress-edit-profile-status">' . $edit_profile_response . '</div>';
            }

            if ( ! empty($redirect)) {
                $ajax_response['redirect'] = $redirect;
            }

            return $ajax_response;
        }

        // display form generated messages else the success message
        if ( ! empty($edit_profile_response)) {
            return '<div class="profilepress-edit-profile-status">' . $edit_profile_response . '</div>';
        } elseif (isset($_GET['edit']) && ($_GET['edit'])) {
            return html_entity_decode($message_on_successful_update);
        }

    }


    /** Get the current user id */
    public static function get_current_user_id()
    {
        return get_current_user_id();
    }


    /**
     * Update user profile.
     *
     * @param array $post global for $_POST data
     * @param array $files global $_FILES for file upload
     * @param int $form_id ID of edit profile form
     * @param string $redirect URL to redirect to after edit profile.
     *
     * @return mixed
     */
    public static function update_user_profile($post, $files, $form_id, $redirect = '')
    {
        /* Validate and add custom validation to edit profile */
        $validation_errors = apply_filters('pp_edit_profile_validation', '', $form_id);

        if (is_wp_error($validation_errors)) {
            return $validation_errors->get_error_message();
        } /* End of filter */

        else {
            // create an array of acceptable userdata for use by wp_update_user
            $valid_userdata = array(
                'eup_username',
                'eup_password',
                'eup_email',
                'eup_email2',
                'eup_website',
                'eup_nickname',
                'eup_display_name',
                'eup_first_name',
                'eup_last_name',
                'eup_bio'
            );

            if (isset($post['eup_email']) && ! is_email($post['eup_email'])) {
                return __('Email address is invalid. Please try again', 'profilepress');
            }

            if (isset($post['eup_email2']) && ! is_email($post['eup_email2'])) {
                return __('Email address confirmation is invalid. Please try again', 'profilepress');
            }

            if (isset($post['eup_email2']) && ($post['eup_email'] != $post['eup_email2'])) {
                return __('Email addresses do not match. Please try again', 'profilepress');
            }

            if (isset($post['eup_password2'])) {

                // if set to true, empty password and empty confirm password field will cause password not to be changed.
                if (apply_filters('pp_allow_empty_password_unchanged', false)) {
                    if ( ! empty($post['eup_password']) && ! empty($post['eup_password2'])) {
                        if (($post['eup_password'] != $post['eup_password2'])) {
                            return __('Password do not match. Please try again.', 'profilepress');
                        }
                    }
                } else {
                    if (empty($post['eup_password']) || empty($post['eup_password2'])) {
                        return __('Password is empty or do not match. Please try again.', 'profilepress');
                    }
                    if (($post['eup_password'] != $post['eup_password2'])) {
                        return __('Password do not match. Please try again.', 'profilepress');
                    }
                }
            }

            // get the escaped data for userdata
            $escaped_post_data = self::escaped_post_data($post);

            // get the data for use by update_user_meta
            $custom_usermeta = apply_filters('pp_edit_profile_custom_usermeta', self::custom_usermeta_data($escaped_post_data, $valid_userdata), $form_id);

            // convert the form post data to userdata for use by wp_update_users
            $real_userdata = array();

            $real_userdata['ID'] = self::get_current_user_id();

            // only process password change if it is specified.
            if ( ! empty($post['eup_password'])) {
                $real_userdata['user_pass'] = $escaped_post_data['eup_password'];
            }

            if (isset($post['eup_email'])) {
                $real_userdata['user_email'] = $escaped_post_data['eup_email'];
            }

            if (isset($post['eup_website'])) {
                $real_userdata['user_url'] = $escaped_post_data['eup_website'];
            }

            if (isset($post['eup_nickname'])) {
                $real_userdata['nickname'] = $escaped_post_data['eup_nickname'];
            }

            if (isset($post['eup_display_name'])) {
                $real_userdata['display_name'] = $escaped_post_data['eup_display_name'];
            }

            if (isset($post['eup_first_name'])) {
                $real_userdata['first_name'] = $escaped_post_data['eup_first_name'];
            }

            if (isset($post['eup_last_name'])) {
                $real_userdata['last_name'] = $escaped_post_data['eup_last_name'];
            }

            if (isset($post['eup_bio'])) {
                $real_userdata['description'] = $escaped_post_data['eup_bio'];
            }

            // merge real data(for use by wp_insert_user()) and custom profile fields data
            $user_data = apply_filters('pp_edit_profile_user_data', array_merge($real_userdata, $custom_usermeta), $form_id);

            /**
             * Fires before profile is updated
             *
             * @param $user_data array user_data of user being updated
             * @param $form_id int builder ID
             */
            do_action('pp_before_profile_update', $user_data, $form_id);

            $ajax_response = array();

            if (isset($files['eup_avatar']['name']) && ! empty($files['eup_avatar']['name'])) {
                $upload_avatar = PP_User_Avatar_Upload::process($files['eup_avatar']);

                if (is_wp_error($upload_avatar)) {
                    return $upload_avatar->get_error_message();
                }

                // update custom profile field
                $custom_usermeta['pp_profile_avatar'] = $upload_avatar;

                if (self::is_ajax()) {
                    $ajax_response['avatar_url'] = AVATAR_UPLOAD_URL . $upload_avatar;
                }

            }

            // update file uploads
            $uploads       = PP_File_Uploader::init();
            $upload_errors = '';
            foreach ($uploads as $field_key => $uploaded_filename_or_wp_error) {
                if (is_wp_error($uploads[$field_key])) {
                    $upload_errors .= $uploads[$field_key]->get_error_message() . '<br/>';
                }
            }

            if (empty($upload_errors)) {
                // we get the old array of stored file for the user
                $old = get_user_meta(self::get_current_user_id(), 'pp_uploaded_files', true);
                $old = ! empty($old) ? $old : array();

                // we loop through the arra of newly uploaded files and remove any file (unsetting the file array key)
                // that isn't be updated i.e if the field is left empty, unsetting it prevent update_user_meta
                // fom overriding it.
                // we then merge the old and new uploads before saving the data to user meta table.
                foreach ($uploads as $key => $value) {
                    if (is_null($value) || empty($value)) {
                        unset($uploads[$key]);
                    }
                }

                update_user_meta(self::get_current_user_id(), 'pp_uploaded_files', array_merge($old, $uploads));
            } else {
                return $upload_errors;
            }


            if (is_array($custom_usermeta)) {

                $user_id = self::get_current_user_id();

                // loop over the custom user_meta and update data to DB
                foreach ($custom_usermeta as $key => $value) {
                    update_user_meta(
                        $user_id,
                        $key,
                        $value
                    );

                    // the 'edit_profile' parameter is used to distinguish it from same action hook in ProfilePress_Registration_Auth
                    do_action('pp_after_custom_field_update', $key, $value, $user_id, 'edit_profile');
                }
            }

            // proceed to profile edit using wp_update_user method which return the new user id
            $update_user = wp_update_user($real_userdata);

            if ( ! is_wp_error($update_user)) {

                /** Fires after profile is updated
                 *
                 * @param array $user_data
                 */
                do_action('pp_after_profile_update', $user_data, $form_id);

                // success flag is used by ajax mode. see self::process_func()
                if (self::is_ajax()) {
                    $ajax_response['status'] = 'success';

                    return $ajax_response;
                } else {
                    // ADD QUERY ARG WITH STATUS
                    $url = apply_filters('pp_redirect_after_profile_edit', esc_url_raw(add_query_arg('edit', 'true')));

                    if ( ! empty($redirect)) {
                        $url = $redirect;
                    }

                    wp_redirect($url);
                    exit;
                }
            } elseif (is_wp_error($update_user)) {
                return $update_user->get_error_message();
            } else {
                return __('Something unexpected happened. Please try again', 'profilepress');
            }
        }

    }


    /**
     * Escaped the POST data
     *
     * @param $post_data array raw post data
     *
     * @return array
     */
    public static function escaped_post_data($post_data)
    {

        // get the escaped data for userdata
        $escaped_post_data = array();

        // loop over the $_POST data and create an array of the wp_insert_user userdata
        foreach ($post_data as $key => $value) {
            if ($key == 'eup_submit') {
                continue;
            }

            if (is_array($value)) {
                $escaped_post_data[$key] = array_map('sanitize_text_field', $value);
            } else {
                $escaped_post_data[$key] = sanitize_text_field($value);
            }
        }

        return $escaped_post_data;

    }


    /**
     * @param $post_data array escaped $_POST Data @see self::escaped_post_data
     *
     * @param $valid_userdata array userdata valid for wp_update_user
     *
     * @return array
     */
    public static function custom_usermeta_data($post_data, $valid_userdata)
    {

        // get the data for use by update_user_meta
        $custom_usermeta = array();

        // loop over the $_POST data and create an array of the invalid userdata/ custom usermeta
        foreach ($post_data as $key => $value) {
            if ($key == 'eup_submit') {
                continue;
            }

            if ( ! in_array($key, $valid_userdata)) {
                $custom_usermeta[$key] = $value;
            }
        }

        return $custom_usermeta;
    }


    /**
     * Remove user avatar and redirect. Triggered when JS is disabled.
     */
    public static function remove_user_avatar()
    {
        self::remove_avatar_core();

        // ADD QUERY ARG WITH STATUS
        wp_redirect(esc_url(add_query_arg('edit', 'true')));
        exit;
    }


    /**
     * Core function that removes/delete the user's avatar
     */
    public static function remove_avatar_core()
    {
        $avatar_upload_dir = AVATAR_UPLOAD_DIR;

        $avatar_slug = get_user_meta(self::get_current_user_id(), 'pp_profile_avatar', true);

        do_action('pp_before_avatar_removal', $avatar_slug);

        // delete the file profile image from server
        unlink($avatar_upload_dir . $avatar_slug);

        // delete the record from DB
        delete_user_meta(self::get_current_user_id(), 'pp_profile_avatar');

        do_action('pp_after_avatar_removal');
    }

}