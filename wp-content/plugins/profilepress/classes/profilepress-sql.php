<?php

/**
 * Class Custom_Profile_Fields_Sql - sql query to get custom fields
 */
class PROFILEPRESS_sql
{

    /**
     * Query for profile placement if user can view the his profile
     *
     * @return mixed
     */
    public static function sql_user_admin_profile()
    {
        global $wpdb;

        $profile_user_fields = $wpdb->get_results("SELECT * FROM {$wpdb->base_prefix}pp_profile_fields ORDER BY id");

        return $profile_user_fields;
    }


    /**
     * sql query to list custom fields added to db in WP_List_Table
     * @return mixed
     */
    public static function sql_wp_list_table_profile_fields()
    {
        global $wpdb;

        $sql = $wpdb->get_results("SELECT * FROM {$wpdb->base_prefix}pp_profile_fields ORDER BY id", 'ARRAY_A');

        return $sql;
    }

    /**
     * retrieve the profile field row of an id for editing
     *
     * @param int $id
     *
     * @return array
     */
    public static function sql_profile_field_row_id($id)
    {
        global $wpdb;

        // get the profile fields row for the id and save as array
        $sql = $wpdb->get_row("SELECT * FROM {$wpdb->base_prefix}pp_profile_fields WHERE id = $id", 'ARRAY_A');

        return $sql;
    }

    public static function sql_delete_profile_field($id)
    {
        global $wpdb;

        $delete_sql = $wpdb->delete(
            "{$wpdb->base_prefix}pp_profile_fields",
            array(
                'id' => $id,
            ),
            array('%d')
        );

        return $delete_sql;

    }


    /**
     * Return a list of created custom profile IDs.
     *
     * @return array
     */
    public static function get_profile_field_ids()
    {
        global $wpdb;
        $table = "{$wpdb->base_prefix}pp_profile_fields";

        return $wpdb->get_col("SELECT id FROM $table ORDER BY id");
    }

    /**
     * Check if a profile field's key exist in the database.
     *
     * @param int $field_key
     *
     * @return bool
     */
    public static function is_profile_field_key_exist($field_key)
    {
        global $wpdb;
        $table = "{$wpdb->base_prefix}pp_profile_fields";

        $response = $wpdb->get_var("SELECT id FROM $table WHERE field_key = '$field_key'");

        return !is_null($response);
    }


    /**
     * Add custom profile field to DB
     *
     * @param string $label_name
     * @param string $key
     * @param string $description
     * @param string $type
     * @param string $options
     *
     * @return bool|int
     */
    public static function add_profile_field($label_name, $key, $description, $type, $options)
    {
        global $wpdb;
        $insert = $wpdb->insert(
            $wpdb->base_prefix . 'pp_profile_fields',
            array(
                'label_name' => $label_name,
                'field_key' => $key,
                'description' => $description,
                'type' => $type,
                'options' => $options,
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
            )
        );

        return !$insert ? false : $wpdb->insert_id;
    }


    /**
     * Update custom profile field in DB
     *
     * @param string $label_name
     * @param string $key
     * @param string $description
     * @param string $type
     * @param string $options
     *
     * @return bool|int
     */
    public static function update_profile_field($id, $label_name, $key, $description, $type, $options)
    {
        global $wpdb;

        return $wpdb->update(
            $wpdb->base_prefix . 'pp_profile_fields',
            array(
                'label_name' => $label_name,
                'field_key' => $key,
                'description' => $description,
                'type' => $type,
                'options' => $options,
            ),
            array('id' => $id),
            array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',

            ),
            array('%d')
        );
    }


    /***
     * Mark a select field as multi selectable.
     *
     * @param string $key
     *
     * @param int $id must have a value.
     *
     * @return bool
     */
    public static function add_multi_selectable($key, $id = 0)
    {
        $old_data = get_option('pp_cpf_select_multi_selectable', array());
        $new_data = array($key => $id);

        return update_option(
            'pp_cpf_select_multi_selectable',
            array_unique(array_merge($old_data, $new_data))
        );
    }

    /***
     * Remove a select field as multi selectable.
     *
     * @param string $key
     *
     * @return bool
     */
    public static function delete_multi_selectable($key)
    {
        $old_data = get_option('pp_cpf_select_multi_selectable', array());
        unset($old_data[$key]);
        return update_option('pp_cpf_select_multi_selectable', array_unique($old_data));
    }

    /***
     * Mark a checkbox field as multi check-able.
     *
     * @param string $key
     * @param int $id
     *
     * @return bool
     */
    public static function add_multi_checkbox($key, $id = 0)
    {
        $old_data = get_option('pp_cpf_checkbox_multi_selectable', array());
        $new_data = array($key => $id);
        return update_option(
            'pp_cpf_checkbox_multi_selectable',
            array_unique(array_merge($old_data, $new_data))
        );
    }

    /***
     * Remove a checkbox field as multi check-able.
     *
     * @param string $key
     *
     * @return bool
     */
    public static function delete_multi_checkbox($key)
    {
        $old_data = get_option('pp_cpf_checkbox_multi_selectable', array());
        unset($old_data[$key]);
        return update_option('pp_cpf_checkbox_multi_selectable', array_unique($old_data));
    }


    /** Retrieve login builder record from DB */
    static function sql_wp_list_table_login_builder()
    {
        global $wpdb;
        $current_blog_id = get_current_blog_id();

        if (is_multisite()) {
            $sql = "SELECT id, title, structure, css, date FROM {$wpdb->base_prefix}pp_login_builder WHERE blog_id = 0 OR blog_id = $current_blog_id";
        } else {
            $sql = "SELECT id, title, structure, css, date FROM {$wpdb->base_prefix}pp_login_builder";
        }
        $result = $wpdb->get_results(
            $sql,
            'ARRAY_A'
        );

        return $result;
    }


    /**
     * Delete a login builder reference with ID
     *
     * @param  int $id login builder ID
     *
     * @return mixed
     */
    static function sql_delete_login_builder($id)
    {
        global $wpdb;

        $delete_sql = $wpdb->delete(
            "{$wpdb->base_prefix}pp_login_builder",
            array(
                'id' => $id,
            ),
            array('%d')
        );

        return $delete_sql;
    }

    /**
     * Retrieve list of login builder for editing
     *
     * @param int $id
     *
     * @return array
     */
    static function sql_edit_login_builder($id)
    {
        global $wpdb;

        // get the profile fields row for the id and save as array
        $sql = $wpdb->get_row("SELECT * FROM {$wpdb->base_prefix}pp_login_builder WHERE id = $id", 'ARRAY_A');

        return $sql;
    }


    /**
     * Update login builder
     *
     * @param $id
     * @param $title
     * @param $structure
     * @param $css
     * @param $date
     */
    static function sql_update_login_builder($id, $title, $structure, $css, $date)
    {
        global $wpdb;

        // only update the blog_id with the current multisite ID if the previous blog_id isn't 0.
        $blog_id = self::get_builder_mutisite_blog_id('login', $id) == 0 ? 0 : get_current_blog_id();

        if (is_multisite()) {
            $wpdb->update(
                "{$wpdb->base_prefix}pp_login_builder",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'css' => $css,
                    'date' => $date,
                    'blog_id' => $blog_id,
                ),
                array('id' => $id),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                )
            );
        } else {
            $wpdb->update(
                "{$wpdb->base_prefix}pp_login_builder",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'css' => $css,
                    'date' => $date,
                ),
                array('id' => $id),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                )
            );
        }

    }

    /**
     * Insert data to login builder
     *
     * @param $title
     * @param $structure
     * @param $css
     * @param $date
     *
     * @return bool|int
     */
    public static function sql_insert_login_builder($title, $structure, $css, $date = '')
    {
        global $wpdb;

        if (is_multisite()) {
            $insert = $wpdb->insert(
                "{$wpdb->base_prefix}pp_login_builder",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'css' => $css,
                    'date' => $date,
                    'blog_id' => get_current_blog_id(),
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                )
            );
        } else {

            $insert = $wpdb->insert(
                "{$wpdb->base_prefix}pp_login_builder",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'css' => $css,
                    'date' => $date,
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                )
            );
        }

        // if insert is false (fail to insert to DB), return ID number
        return !$insert ? false : $wpdb->insert_id;

    }

    /**
     * Returns the login CSS of a builder
     *
     * @param  int $id login builder ID
     *
     * @return string login builder css
     */
    public static function get_login_builder_css($id)
    {
        global $wpdb;
        $current_blog = get_current_blog_id();

        if (is_multisite()) {
            $sql = "SELECT css FROM {$wpdb->base_prefix}pp_login_builder WHERE id = $id AND (blog_id = 0 OR blog_id = $current_blog)";
        } else {
            $sql = "SELECT css FROM {$wpdb->base_prefix}pp_login_builder WHERE id = $id";
        }

        return $wpdb->get_var($sql);
    }

    /**
     * Returns all columns of a login builder ID
     *
     * @param int $id login builder ID
     *
     * @return mixed
     */
    public static function get_login_builder_data($id)
    {
        return self::sql_edit_login_builder($id);
    }


    /**
     * Return the login structure of a builder
     *
     * @param  int $id builder ID
     *
     * @return string login structure
     */
    public static function get_login_structure($id)
    {
        global $wpdb;

        $sql = $wpdb->get_var("SELECT structure FROM {$wpdb->base_prefix}pp_login_builder WHERE id = $id");

        return $sql;

    }

    /**
     * Retrieve the registration DB data
     *
     * @return mixed
     */
    static function sql_wp_list_table_registration_builder()
    {
        global $wpdb;

        if (is_multisite()) {
            $current_blog_id = get_current_blog_id();
            $sql = "SELECT id, title, structure, css, date FROM {$wpdb->base_prefix}pp_registration_builder WHERE blog_id = 0 OR blog_id = $current_blog_id";
        } else {
            $sql = "SELECT id, title, structure, css, date FROM {$wpdb->base_prefix}pp_registration_builder";
        }

        return $wpdb->get_results($sql, 'ARRAY_A');
    }

    /**
     * Delete an entry from registration builder
     *
     * @param $id
     *
     * @return mixed
     */
    static function sql_delete_registration_builder($id)
    {
        global $wpdb;

        $delete_sql = $wpdb->delete(
            "{$wpdb->base_prefix}pp_registration_builder",
            array(
                'id' => $id,
            ),
            array('%d')
        );

        return $delete_sql;
    }

    /**
     * retrieve the profile field row for an id for editing
     *
     * @param int $id
     *
     * @return array
     */
    static function sql_edit_registration_builder($id)
    {
        global $wpdb;

        // get the profile fields row for the id and save as array
        $sql = $wpdb->get_row("SELECT * FROM {$wpdb->base_prefix}pp_registration_builder WHERE id = $id", 'ARRAY_A');

        return $sql;
    }


    /**
     * Update login builder
     *
     * @param $id
     * @param $title
     * @param $structure
     * @param $css
     * @param $date
     * @param $user_role
     */
    static function sql_update_registration_builder($id, $title, $structure, $css, $success_registration, $date, $user_role = '')
    {
        global $wpdb;

        if (is_multisite()) {

            // only update the blog_id with the current multisite ID if the previous blog_id isn't 0.
            $blog_id = self::get_builder_mutisite_blog_id('registration', $id) == 0 ? 0 : get_current_blog_id();

            $wpdb->update(
                "{$wpdb->base_prefix}pp_registration_builder",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'css' => $css,
                    'success_registration' => $success_registration,
                    'date' => $date,
                    'blog_id' => $blog_id,
                    'user_role' => $user_role,
                ),
                array('id' => $id),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                    '%s',
                )
            );
        } else {
            $wpdb->update(
                "{$wpdb->base_prefix}pp_registration_builder",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'css' => $css,
                    'success_registration' => $success_registration,
                    'date' => $date,
                    'user_role' => $user_role,
                ),
                array('id' => $id),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                )
            );
        }

    }


    /**
     * Insert new registration builder to DB
     *
     * @param $title
     * @param $structure
     * @param $css
     * @param $success_registration
     * @param $date
     * @param $user_role
     *
     * @return int
     */
    static function sql_insert_registration_builder($title, $structure, $css, $success_registration, $date, $user_role = '')
    {
        global $wpdb;

        if (is_multisite()) {

            $wpdb->insert(
                "{$wpdb->base_prefix}pp_registration_builder",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'css' => $css,
                    'success_registration' => $success_registration,
                    'date' => $date,
                    'blog_id' => get_current_blog_id(),
                    'user_role' => $user_role,
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                    '%s',
                )
            );
        } else {

            $wpdb->insert(
                "{$wpdb->base_prefix}pp_registration_builder",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'css' => $css,
                    'success_registration' => $success_registration,
                    'date' => $date,
                    'user_role' => $user_role,
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                )
            );
        }

        // return inserted form ID number
        return $wpdb->insert_id;
    }

    /** get radio buttons options of an added custom profile field */
    static function get_field_option_values($field_key)
    {
        global $wpdb;
        if (is_multisite()) {
            $current_blog = get_current_blog_id();
            $sql = "SELECT options FROM {$wpdb->base_prefix}pp_profile_fields WHERE field_key = '$field_key' AND (blog_id = 0 OR blog_id = $current_blog)";
        } else {
            $sql = "SELECT options FROM {$wpdb->base_prefix}pp_profile_fields WHERE field_key = '$field_key'";
        }

        return $wpdb->get_col($sql);
    }

    /** get radio buttons options of an added custom profile field */
    static function get_field_label($field_key)
    {
        global $wpdb;
        if (is_multisite()) {
            $current_blog = get_current_blog_id();
            $sql = "SELECT label_name FROM {$wpdb->base_prefix}pp_profile_fields WHERE field_key = '$field_key' AND (blog_id = 0 OR blog_id = $current_blog)";
        } else {
            $sql = "SELECT label_name FROM {$wpdb->base_prefix}pp_profile_fields WHERE field_key = '$field_key'";
        }

        return $wpdb->get_var($sql);
    }

    /**
     * Returns all columns of a registration builder ID
     *
     * @param int $id login builder ID
     *
     * @return mixed
     */
    public static function get_registration_builder_data($id)
    {
        return self::sql_edit_registration_builder($id);
    }


    /**
     * Get successful message on registration completion
     *
     * @param $id
     *
     * @return mixed
     */
    public static function get_db_success_registration($id)
    {
        global $wpdb;
        $sql = $wpdb->get_var("SELECT success_registration FROM {$wpdb->base_prefix}pp_registration_builder WHERE id = $id");

        return $sql;
    }

    public static function get_registration_user_role($id)
    {
        global $wpdb;
        $sql = $wpdb->get_var("SELECT user_role FROM {$wpdb->base_prefix}pp_registration_builder WHERE id = $id");

        return $sql;
    }

    /**
     * Retrieve the password reset DB builder data
     *
     * @return mixed
     */
    static function sql_wp_list_table_password_reset_builder()
    {
        global $wpdb;

        if (is_multisite()) {
            $current_blog = get_current_blog_id();
            $sql = "SELECT id, title, structure, css, date FROM {$wpdb->base_prefix}pp_password_reset_builder WHERE blog_id = 0 OR blog_id = $current_blog";
        } else {
            $sql = "SELECT id, title, structure, css, date FROM {$wpdb->base_prefix}pp_password_reset_builder";
        }

        return $wpdb->get_results($sql, 'ARRAY_A');
    }

    /**
     * Delete an entry from password-reset builder
     *
     * @param $id
     *
     * @return mixed
     */
    static function sql_delete_password_reset_builder($id)
    {
        global $wpdb;

        $delete_sql = $wpdb->delete(
            "{$wpdb->base_prefix}pp_password_reset_builder",
            array(
                'id' => $id,
            ),
            array('%d')
        );

        return $delete_sql;
    }


    /**
     * Retrieve the password reset field row for an id for editing
     *
     * @param int $id
     *
     * @return array
     */
    static function sql_edit_password_reset_builder($id)
    {
        global $wpdb;

        // get the profile fields row for the id and save as array
        $sql = $wpdb->get_row("SELECT * FROM {$wpdb->base_prefix}pp_password_reset_builder WHERE id = $id", 'ARRAY_A');

        return $sql;
    }


    /**
     * Insert new registration builder to DB
     *
     * @param $title
     * @param $structure
     * @param $handler_structure
     * @param $css
     * @param $success_password_reset
     * @param $date
     *
     * @return int
     */
    static function sql_insert_password_reset_builder($title, $structure, $handler_structure, $css, $success_password_reset, $date)
    {
        global $wpdb;

        if (is_multisite()) {

            $insert = $wpdb->insert(
                "{$wpdb->base_prefix}pp_password_reset_builder",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'handler_structure' => $handler_structure,
                    'css' => $css,
                    'success_password_reset' => $success_password_reset,
                    'date' => $date,
                    'blog_id' => get_current_blog_id(),
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                )
            );
        } else {
            $wpdb->insert(
                "{$wpdb->base_prefix}pp_password_reset_builder",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'handler_structure' => $handler_structure,
                    'css' => $css,
                    'success_password_reset' => $success_password_reset,
                    'date' => $date,
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                )
            );
        }

        // return ID number
        return $wpdb->insert_id;
    }


    /**
     * Update password reset builder.
     *
     * @param int $id
     * @param string $title
     * @param string $structure
     * @param string $handler_structure
     * @param string $css
     * @param string $success_password_reset
     * @param string $date
     */
    public static function sql_update_password_reset_builder(
        $id,
        $title,
        $structure,
        $handler_structure,
        $css,
        $success_password_reset,
        $date
    )
    {
        global $wpdb;

        if (is_multisite()) {

            // only update the blog_id with the current multisite ID if the previous blog_id isn't 0.
            $blog_id = self::get_builder_mutisite_blog_id('password_reset', $id) == 0 ? 0 : get_current_blog_id();

            $wpdb->update(
                "{$wpdb->base_prefix}pp_password_reset_builder",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'handler_structure' => $handler_structure,
                    'css' => $css,
                    'success_password_reset' => $success_password_reset,
                    'date' => $date,
                    'blog_id' => $blog_id,
                ),
                array('id' => $id),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                )
            );
        } else {
            $wpdb->update(
                "{$wpdb->base_prefix}pp_password_reset_builder",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'handler_structure' => $handler_structure,
                    'css' => $css,
                    'success_password_reset' => $success_password_reset,
                    'date' => $date,
                ),
                array('id' => $id),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                )
            );
        }

    }

    /**
     * Get successful message on password reset
     *
     * @param $id
     *
     * @return mixed
     */
    static function get_db_success_password_reset($id)
    {
        global $wpdb;

        $sql = $wpdb->get_var("SELECT success_password_reset FROM {$wpdb->base_prefix}pp_password_reset_builder WHERE id = $id");

        return $sql;
    }


    /** Edit Profile Builder */


    /**
     * Retrieve the "edit user profile" builder DB data
     *
     * @return mixed
     */
    static function sql_wp_list_table_edit_profile_builder()
    {
        global $wpdb;

        if (is_multisite()) {
            $current_blog = get_current_blog_id();
            $sql = "SELECT id, title, structure, css, date FROM {$wpdb->base_prefix}pp_edit_profile_builder WHERE blog_id = 0 OR blog_id = $current_blog";
        } else {
            $sql = "SELECT id, title, structure, css, date FROM {$wpdb->base_prefix}pp_edit_profile_builder";
        }

        return $wpdb->get_results($sql, 'ARRAY_A');
    }

    /**
     * Delete an entry from password-reset builder
     *
     * @param $id
     *
     * @return mixed
     */
    static function sql_delete_edit_profile_builder($id)
    {
        global $wpdb;

        $delete_sql = $wpdb->delete(
            "{$wpdb->base_prefix}pp_edit_profile_builder",
            array(
                'id' => $id,
            ),
            array('%d')
        );

        return $delete_sql;
    }


    /**
     * Retrieve the "edit user profile" field row of an id for editing
     *
     * @param int $id
     *
     * @return array
     */
    static function sql_edit_profile_builder($id)
    {
        global $wpdb;

        // get the profile fields row for the id and save as array
        $sql = $wpdb->get_row("SELECT * FROM {$wpdb->base_prefix}pp_edit_profile_builder WHERE id = $id", 'ARRAY_A');

        return $sql;
    }

    /**
     * Returns all columns of a edit profile builder ID
     *
     * @param int $id login builder ID
     *
     * @return mixed
     */
    public static function get_edit_profile_builder_data($id)
    {
        return self::sql_edit_profile_builder($id);
    }


    /**
     * Insert new "edit user profile" builder to DB
     *
     * @param string $title
     * @param string $structure
     * @param string $css
     * @param string $success_edit_profile
     * @param string $date
     *
     * @return false|int
     */
    static function sql_insert_edit_profile_builder($title, $structure, $css, $success_edit_profile, $date)
    {
        global $wpdb;

        if (is_multisite()) {

            $insert = $wpdb->insert(
                "{$wpdb->base_prefix}pp_edit_profile_builder",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'css' => $css,
                    'success_edit_profile' => $success_edit_profile,
                    'date' => $date,
                    'blog_id' => get_current_blog_id(),
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                )
            );
        } else {
            $insert = $wpdb->insert(
                "{$wpdb->base_prefix}pp_edit_profile_builder",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'css' => $css,
                    'success_edit_profile' => $success_edit_profile,
                    'date' => $date,
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                )
            );
        }

        // return ID number
        return $wpdb->insert_id;

    }


    /**
     * Update "edit user profile" builder
     *
     * @param $id
     * @param $title
     * @param $structure
     * @param $css
     * @param $success_edit_profile
     * @param $date
     */
    static function sql_update_edit_profile_builder($id, $title, $structure, $css, $success_edit_profile, $date)
    {
        global $wpdb;

        if (is_multisite()) {

            // only update the blog_id with the current multisite ID if the previous blog_id isn't 0.
            $blog_id = self::get_builder_mutisite_blog_id('edit_user_profile', $id) == 0 ? 0 : get_current_blog_id();

            $wpdb->update(
                "{$wpdb->base_prefix}pp_edit_profile_builder",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'css' => $css,
                    'success_edit_profile' => $success_edit_profile,
                    'date' => $date,
                    'blog_id' => $blog_id,
                ),
                array('id' => $id),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                )
            );
        } else {
            $wpdb->update(
                "{$wpdb->base_prefix}pp_edit_profile_builder",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'css' => $css,
                    'success_edit_profile' => $success_edit_profile,
                    'date' => $date,
                ),
                array('id' => $id),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                )
            );
        }

    }

    /**
     * Get successful message on profile edit
     *
     * @param $id
     *
     * @return mixed
     */
    static function get_db_success_edit_profile($id)
    {
        global $wpdb;
        $sql = $wpdb->get_var("SELECT success_edit_profile FROM {$wpdb->base_prefix}pp_edit_profile_builder WHERE id = $id");

        return $sql;
    }



    /** Front-end user profile */


    /**
     * Retrieve the "front-end user profile" DB data
     *
     * @return mixed
     */
    static function sql_wp_list_table_user_profile_builder()
    {
        global $wpdb;


        if (is_multisite()) {
            $current_blog = get_current_blog_id();
            $sql = "SELECT id, title, structure, css, date FROM {$wpdb->base_prefix}pp_user_profile_builder WHERE blog_id = 0 OR blog_id = $current_blog";
        } else {
            $sql = "SELECT id, title, structure, css, date FROM {$wpdb->base_prefix}pp_user_profile_builder";
        }

        return $wpdb->get_results($sql, 'ARRAY_A');
    }

    /**
     * Delete an entry from "front-end user profile" builder
     *
     * @param $id
     *
     * @return mixed
     */
    static function sql_delete_user_profile_builder($id)
    {
        global $wpdb;

        $delete_sql = $wpdb->delete(
            "{$wpdb->base_prefix}pp_user_profile_builder",
            array(
                'id' => $id,
            ),
            array('%d')
        );

        return $delete_sql;
    }


    /**
     * Retrieve the "front-end user profile" field row of an id for editing
     *
     * @param int $id
     *
     * @return array
     */
    static function sql_edit_user_profile_builder($id)
    {
        global $wpdb;

        // get the profile fields row for the id and save as array
        $sql = $wpdb->get_row("SELECT * FROM {$wpdb->base_prefix}pp_user_profile_builder WHERE id = $id", 'ARRAY_A');

        return $sql;
    }

    /**
     * Returns all columns of a user/frontend profile builder ID
     *
     * @param int $id login builder ID
     *
     * @return mixed
     */
    public static function get_user_profile_builder_data($id)
    {
        return self::sql_edit_user_profile_builder($id);
    }


    /**
     * Insert new record to "front-end user profile" builder
     *
     * @param $title
     * @param $structure
     * @param $css
     * @param $date
     *
     * @return int/bool
     */
    static function sql_insert_user_profile_builder($title, $structure, $css, $date)
    {
        global $wpdb;

        if (is_multisite()) {
            $wpdb->insert(
                "{$wpdb->base_prefix}pp_user_profile_builder",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'css' => $css,
                    'date' => $date,
                    'blog_id' => get_current_blog_id(),
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                )
            );
        } else {
            $wpdb->insert(
                "{$wpdb->base_prefix}pp_user_profile_builder",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'css' => $css,
                    'date' => $date,
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                )
            );
        }

        // return inserted form ID number
        return $wpdb->insert_id;


    }


    /**
     * Update "front-end user profile" builder
     *
     * @param $id
     * @param $title
     * @param $structure
     * @param $css
     * @param $date
     */
    static function sql_update_user_profile_builder($id, $title, $structure, $css, $date)
    {
        global $wpdb;

        if (is_multisite()) {

            // only update the blog_id with the current multisite ID if the previous blog_id isn't 0.
            $blog_id = self::get_builder_mutisite_blog_id('front_end_profile', $id) == 0 ? 0 : get_current_blog_id();

            $wpdb->update(
                "{$wpdb->base_prefix}pp_user_profile_builder",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'css' => $css,
                    'date' => $date,
                    'blog_id' => $blog_id,
                ),
                array('id' => $id),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                )
            );
        } else {
            $wpdb->update(
                "{$wpdb->base_prefix}pp_user_profile_builder",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'css' => $css,
                    'date' => $date,
                ),
                array('id' => $id),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                )
            );
        }

    }


    /** Builder widget SQLs */


    /**
     * Insert record to make builder available as a widget
     *
     * @param $builder_id int ID of the builder
     * @param $builder_type string builder type
     */
    public static function sql_add_pp_builder_widget($builder_id, $builder_type)
    {
        global $wpdb;

        if (is_multisite()) {
            $wpdb->insert(
                "{$wpdb->base_prefix}pp_builder_widget",
                array(
                    'builder_type' => $builder_type,
                    'builder_id' => $builder_id,
                    'blog_id' => get_current_blog_id(),
                ),
                array(
                    '%s',
                    '%d',
                    '%d',
                )
            );
        } else {
            $wpdb->insert(
                "{$wpdb->base_prefix}pp_builder_widget",
                array(
                    'builder_type' => $builder_type,
                    'builder_id' => $builder_id,
                ),
                array(
                    '%s',
                    '%d',
                )
            );
        }
    }


    /**
     * unset/delete a widget that was made available as a widget
     *
     * @param $builder_id int ID of the builder
     * @param $builder_type string builder type
     */
    public static function sql_delete_pp_builder_widget($builder_id, $builder_type)
    {
        global $wpdb;

        $wpdb->delete(
            "{$wpdb->base_prefix}pp_builder_widget",
            array(
                'builder_type' => $builder_type,
                'builder_id' => $builder_id,
            ),
            array(
                '%s',
                '%d',
            )
        );
    }


    /**
     * Check if a builder is available as a widget.
     *
     * Used in every builder settings page to power the "make widget" checkbox.
     *
     * @return mixed
     */
    public static function check_if_builder_is_widget($id, $builder_type)
    {
        global $wpdb;

        // get the profile fields row for the id and save as array
        $sql = $wpdb->get_row(
            "SELECT * FROM {$wpdb->base_prefix}pp_builder_widget WHERE builder_type = '$builder_type' AND builder_id = $id",
            'ARRAY_A'
        );

        return $sql;
    }


    /**
     * Get the title of a builder
     *
     * @param $builder_type string builder type
     * @param  int $id builder_type row ID
     *
     * @return string title of builder
     */
    public static function get_a_builder_title($builder_type, $id)
    {
        global $wpdb;

        $id = absint($id);
        $title = '';

        if ($builder_type == 'login') {
            $title = $wpdb->get_var("SELECT title FROM {$wpdb->base_prefix}pp_login_builder WHERE id = $id");
        } elseif ($builder_type == 'registration') {
            $title = $wpdb->get_var("SELECT title FROM {$wpdb->base_prefix}pp_registration_builder WHERE id = $id");
        } elseif ($builder_type == 'front_end_profile') {
            $title = $wpdb->get_var("SELECT title FROM {$wpdb->base_prefix}pp_user_profile_builder WHERE id = $id");
        } elseif ($builder_type == 'password_reset') {
            $title = $wpdb->get_var("SELECT title FROM {$wpdb->base_prefix}pp_password_reset_builder WHERE id = $id");
        } elseif ($builder_type == 'edit_user_profile') {
            $title = $wpdb->get_var("SELECT title FROM {$wpdb->base_prefix}pp_edit_profile_builder WHERE id = $id");
        } elseif ($builder_type == 'melange') {
            $title = $wpdb->get_var("SELECT title FROM {$wpdb->base_prefix}pp_melange WHERE id = $id");
        }

        return $title;
    }

    /**
     * Returns all columns of a password reset builder ID
     *
     * @param int $id login builder ID
     *
     * @return mixed login builder css
     */
    public static function get_password_reset_builder_data($id)
    {
        return self::sql_edit_password_reset_builder($id);
    }


    /**
     * Get the structure of a password reset handler form.
     *
     * @param int $id
     *
     * @return null|string
     */
    public static function get_password_reset_handler_structure($id)
    {
        $current_blog_id = get_current_blog_id();
        global $wpdb;
        if (is_multisite()) {
            $sql = "SELECT handler_structure FROM {$wpdb->base_prefix}pp_password_reset_builder WHERE id = $id AND (blog_id = 0 OR blog_id = $current_blog_id)";
        } else {
            $sql = "SELECT handler_structure FROM {$wpdb->base_prefix}pp_password_reset_builder WHERE id = $id";
        }

        return $wpdb->get_var($sql);
    }


    /**
     * Get the ID of the very first password reset form.
     * to be used by melange in retrieving the password reset handler form to use.
     *
     * @return int
     */
    public static function get_first_password_reset_form()
    {
        global $wpdb;

        $sql = "SELECT id FROM {$wpdb->base_prefix}pp_password_reset_builder LIMIT 1";

        return $wpdb->get_var($sql);
    }


    /**
     * Get the structure of a builder
     *
     * @param $builder_type string builder type
     * @param int $id row ID of a builder_type
     *
     * @return string
     */
    public static function get_a_builder_structure($builder_type, $id)
    {

        global $wpdb;
        $current_blog_id = get_current_blog_id();
        if ($builder_type == 'login') {
            if (is_multisite()) {
                $sql = "SELECT structure FROM {$wpdb->base_prefix}pp_login_builder WHERE id = $id AND (blog_id = 0 OR blog_id = $current_blog_id)";
            } else {
                $sql = "SELECT structure FROM {$wpdb->base_prefix}pp_login_builder WHERE id = $id";
            }
        } elseif ($builder_type == 'registration') {
            if (is_multisite()) {
                $sql = "SELECT structure FROM {$wpdb->base_prefix}pp_registration_builder WHERE id = $id AND (blog_id = 0 OR blog_id = $current_blog_id)";
            } else {
                $sql = "SELECT structure FROM {$wpdb->base_prefix}pp_registration_builder WHERE id = $id";
            }
        } elseif ($builder_type == 'front_end_profile') {
            if (is_multisite()) {
                $sql = "SELECT structure FROM {$wpdb->base_prefix}pp_user_profile_builder WHERE id = $id AND (blog_id = 0 OR blog_id = $current_blog_id)";
            } else {
                $sql = "SELECT structure FROM {$wpdb->base_prefix}pp_user_profile_builder WHERE id = $id";
            }
        } elseif ($builder_type == 'password_reset') {
            if (is_multisite()) {
                $sql = "SELECT structure FROM {$wpdb->base_prefix}pp_password_reset_builder WHERE id = $id AND (blog_id = 0 OR blog_id = $current_blog_id)";
            } else {
                $sql = "SELECT structure FROM {$wpdb->base_prefix}pp_password_reset_builder WHERE id = $id";
            }
        } elseif ($builder_type == 'edit_user_profile') {
            if (is_multisite()) {
                $sql = "SELECT structure FROM {$wpdb->base_prefix}pp_edit_profile_builder WHERE id = $id AND (blog_id = 0 OR blog_id = $current_blog_id)";
            } else {
                $sql = "SELECT structure FROM {$wpdb->base_prefix}pp_edit_profile_builder WHERE id = $id";
            }
        } elseif ($builder_type == 'melange') {
            if (is_multisite()) {
                $sql = "SELECT structure FROM {$wpdb->base_prefix}pp_melange WHERE id = $id AND (blog_id = 0 OR blog_id = $current_blog_id)";
            } else {
                $sql = "SELECT structure FROM {$wpdb->base_prefix}pp_melange WHERE id = $id";
            }
        }

        return $wpdb->get_var($sql);
    }


    /**
     * Return the CSS stylesheet of a builder
     *
     * @param $builder_type string builder type
     * @param  int $id row ID of a builder_type
     *
     * @return mixed
     */
    public static function get_a_builder_css($builder_type, $id)
    {

        global $wpdb;
        $current_blog_id = get_current_blog_id();

        if ($builder_type == 'login') {
            if (is_multisite()) {
                $sql = "SELECT css FROM {$wpdb->base_prefix}pp_login_builder WHERE id = $id AND (blog_id = 0 OR blog_id = $current_blog_id)";
            } else {
                $sql = "SELECT css FROM {$wpdb->base_prefix}pp_login_builder WHERE id = $id";
            }
        } elseif ($builder_type == 'registration') {
            if (is_multisite()) {
                $sql = "SELECT css FROM {$wpdb->base_prefix}pp_registration_builder WHERE id = $id AND (blog_id = 0 OR blog_id = $current_blog_id)";
            } else {
                $sql = "SELECT css FROM {$wpdb->base_prefix}pp_registration_builder WHERE id = $id";
            }
        } elseif ($builder_type == 'front_end_profile') {
            if (is_multisite()) {
                $sql = "SELECT css FROM {$wpdb->base_prefix}pp_user_profile_builder WHERE id = $id AND (blog_id = 0 OR blog_id = $current_blog_id)";
            } else {
                $sql = "SELECT css FROM {$wpdb->base_prefix}pp_user_profile_builder WHERE id = $id";
            }
        } elseif ($builder_type == 'password_reset') {
            if (is_multisite()) {
                $sql = "SELECT css FROM {$wpdb->base_prefix}pp_password_reset_builder WHERE id = $id AND (blog_id = 0 OR blog_id = $current_blog_id)";
            } else {
                $sql = "SELECT css FROM {$wpdb->base_prefix}pp_password_reset_builder WHERE id = $id";
            }
        } elseif ($builder_type == 'edit_user_profile') {
            if (is_multisite()) {
                $sql = "SELECT css FROM {$wpdb->base_prefix}pp_edit_profile_builder WHERE id = $id AND (blog_id = 0 OR blog_id = $current_blog_id)";
            } else {
                $sql = "SELECT css FROM {$wpdb->base_prefix}pp_edit_profile_builder WHERE id = $id";
            }
        } elseif ($builder_type == 'melange') {
            if (is_multisite()) {
                $sql = "SELECT css FROM {$wpdb->base_prefix}pp_melange WHERE id = $id AND (blog_id = 0 OR blog_id = $current_blog_id)";
            } else {
                $sql = "SELECT css FROM {$wpdb->base_prefix}pp_melange WHERE id = $id";
            }
        }

        return $wpdb->get_var($sql);
    }


    /**
     * Return the message/test displayed when a builder action is done.
     *
     *  eg, when a user successfully register an account
     * when a user successfully edited his profile.
     *
     * @param $builder_type
     * @param $id
     *
     * @return mixed
     */
    public static function get_a_builder_message_after_action($builder_type, $id)
    {
        global $wpdb;

        if ($builder_type == 'registration') {
            $sql = "SELECT success_registration FROM {$wpdb->base_prefix}pp_registration_builder WHERE id = $id";
        } elseif ($builder_type == 'password_reset') {
            $sql = "SELECT success_password_reset FROM {$wpdb->base_prefix}pp_password_reset_builder WHERE id = $id";
        } elseif ($builder_type == 'edit_user_profile') {
            $sql = "SELECT success_edit_profile FROM {$wpdb->base_prefix}pp_edit_profile_builder WHERE id = $id";
        }

        return $wpdb->get_var($sql);
    }


    /**
     * Return an array of a list of IDs of a builder.
     *
     * @param $builder_type string the builder type
     *
     * @return array list of a builder id
     */
    public static function get_a_builder_ids($builder_type)
    {
        global $wpdb;
        $current_blog_id = get_current_blog_id();
        if ($builder_type == 'login') {
            if (is_multisite()) {
                $sql = "SELECT id FROM {$wpdb->base_prefix}pp_login_builder WHERE blog_id = 0 OR blog_id = $current_blog_id";
            } else {
                $sql = "SELECT id FROM {$wpdb->base_prefix}pp_login_builder";
            }
        } elseif ($builder_type == 'registration') {
            if (is_multisite()) {
                $sql = "SELECT id FROM {$wpdb->base_prefix}pp_registration_builder WHERE blog_id = 0 OR blog_id = $current_blog_id";
            } else {
                $sql = "SELECT id FROM {$wpdb->base_prefix}pp_registration_builder";
            }
        } elseif ($builder_type == 'front_end_profile') {
            if (is_multisite()) {
                $sql = "SELECT id FROM {$wpdb->base_prefix}pp_user_profile_builder WHERE blog_id = 0 OR blog_id = $current_blog_id";
            } else {
                $sql = "SELECT id FROM {$wpdb->base_prefix}pp_user_profile_builder";
            }
        } elseif ($builder_type == 'password_reset') {
            if (is_multisite()) {
                $sql = "SELECT id FROM {$wpdb->base_prefix}pp_password_reset_builder WHERE blog_id = 0 OR blog_id = $current_blog_id";
            } else {
                $sql = "SELECT id FROM {$wpdb->base_prefix}pp_password_reset_builder";
            }
        } elseif ($builder_type == 'edit_user_profile') {
            if (is_multisite()) {
                $sql = "SELECT id FROM {$wpdb->base_prefix}pp_edit_profile_builder WHERE blog_id = 0 OR blog_id = $current_blog_id";
            } else {
                $sql = "SELECT id FROM {$wpdb->base_prefix}pp_edit_profile_builder";
            }
        } elseif ($builder_type == 'melange') {
            if (is_multisite()) {
                $sql = "SELECT id FROM {$wpdb->base_prefix}pp_melange WHERE blog_id = 0 OR blog_id = $current_blog_id";
            } else {
                $sql = "SELECT id FROM {$wpdb->base_prefix}pp_melange";
            }
        }

        return $wpdb->get_col($sql);
    }

    public static function get_builder_mutisite_blog_id($builder_type, $form_id)
    {
        // bail if it is not a multi-site install.
        if (!is_multisite()) {
            return;
        }

        global $wpdb;

        $form_id = absint($form_id);

        if ($builder_type == 'login') {
            $sql = "SELECT blog_id FROM {$wpdb->base_prefix}pp_login_builder WHERE id = $form_id";
        } elseif ($builder_type == 'registration') {
            $sql = "SELECT blog_id FROM {$wpdb->base_prefix}pp_registration_builder WHERE id = $form_id";
        } elseif ($builder_type == 'front_end_profile') {
            $sql = "SELECT blog_id FROM {$wpdb->base_prefix}pp_user_profile_builder WHERE id = $form_id";
        } elseif ($builder_type == 'password_reset') {
            $sql = "SELECT blog_id FROM {$wpdb->base_prefix}pp_password_reset_builder WHERE id = $form_id";
        } elseif ($builder_type == 'edit_user_profile') {
            $sql = "SELECT blog_id FROM {$wpdb->base_prefix}pp_edit_profile_builder WHERE id = $form_id";
        } elseif ($builder_type == 'melange') {
            $sql = "SELECT blog_id FROM {$wpdb->base_prefix}pp_melange WHERE id = $form_id";
        }

        return $wpdb->get_var($sql);
    }

    /** One time Passwordless login */
    public static function passwordless_insert_record($user_id, $token, $expiration)
    {
        global $wpdb;

        $table = "{$wpdb->base_prefix}pp_passwordless";

        // check if a passwordless record already exist for the user
        // if true update the row else add a new row record.
        $id = $wpdb->get_var("SELECT user_id FROM $table WHERE user_id = $user_id");

        if (is_null($id)) {
            $prepared_statement = $wpdb->prepare(
                "
		INSERT INTO $table
		( user_id, token, expires )
		VALUES ( %d, %s, %d )
	",
                array(
                    $user_id,
                    $token,
                    $expiration,
                )
            );
        } else {
            $prepared_statement = $wpdb->prepare(
                "
		UPDATE $table
		SET token = %s, expires = %d
		WHERE user_id = %d
	",
                array(
                    $token,
                    $expiration,
                    $user_id,
                )
            );
        }

        return $wpdb->query($prepared_statement);
    }

    /**
     * Delete OTP record for a user.
     *
     * @param int $user_id
     *
     * @return false|int
     */
    public static function passwordless_delete_record($user_id)
    {
        global $wpdb;

        return $wpdb->delete("{$wpdb->base_prefix}pp_passwordless", array('user_id' => $user_id), array('%d'));
    }

    /**
     * Get the passwordless token of a user by ID
     *
     * @param int $user_id ID of user
     *
     * @return null|string
     */
    public static function passwordless_get_user_token($user_id)
    {
        global $wpdb;
        $sql = "SELECT token FROM {$wpdb->base_prefix}pp_passwordless WHERE user_id = $user_id";

        return $wpdb->get_var($sql);
    }

    /**
     * Get the expiration time
     *
     * @param int $user_id
     *
     * @return null|string
     */
    public static function passwordless_get_expiration($user_id)
    {
        global $wpdb;
        $sql = "SELECT expires FROM {$wpdb->base_prefix}pp_passwordless WHERE user_id = $user_id";

        return $wpdb->get_var($sql);
    }


    /**
     * Insert builder/form revision
     *
     * @param int $parent_id builder/form ID
     * @param string $type
     */
    public static function insert_revision($parent_id, $type)
    {
        global $wpdb;
        $structure = self::get_a_builder_structure($type, $parent_id);
        $css = self::get_a_builder_css($type, $parent_id);
        $date = current_time('mysql');

        $wpdb->insert(
            "{$wpdb->base_prefix}pp_revisions",
            array(
                'structure' => $structure,
                'css' => $css,
                'type' => $type,
                'parent_id' => $parent_id,
                'date' => $date,
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%d',
                '%s',
            )
        );

    }


    /**
     * Delete older record of a builder/form type revision save for record the latest 5.
     *
     * @param string $type
     */
    public static function delete_older_revisions($type)
    {
        global $wpdb;

        // only run this delete once every 12hrs
        if (!get_transient('pp_delete_revisions')) {
            // table name
            $table = "{$wpdb->base_prefix}pp_revisions";

            // get total count of record in db
            $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->base_prefix}pp_revisions WHERE type = '$type'");

            // if total record is greater than 5, delete the older record save for the latest 5

            /**@see self::get_builder_revisions for documentation. */
            if ($count > apply_filters('pp_revision_count', 5)) {

                /** @see http://stackoverflow.com/questions/12382250/mysql-delete-order-by */
                $sql = "DELETE FROM $table WHERE type = %s AND id IN (
						select * FROM (
							SELECT id FROM $table WHERE type = %s ORDER BY date ASC limit %d
							)
						AS t
			)";

                $wpdb->query($wpdb->prepare($sql, $type, $type, $count - 5));
            }

            set_transient('pp_delete_revisions', 'active', 12 * HOUR_IN_SECONDS);
        }
    }


    /**
     * Delete revisions belonging to a builder ID.
     *
     * @param string $parent_id
     */
    public static function delete_revisions_by_parent_id($parent_id)
    {
        global $wpdb;

        $table = "{$wpdb->base_prefix}pp_revisions";

        $sql = "DELETE FROM $table WHERE parent_id = %d";

        $wpdb->query($wpdb->prepare($sql, $parent_id));
    }


    /**
     * Get the parent builder/form ID of a revision.
     *
     * @param int $id
     *
     * @return int
     */
    public static function get_revision_parent_id($id)
    {
        global $wpdb;
        $sql = "SELECT parent_id FROM {$wpdb->base_prefix}pp_revisions WHERE id = $id";

        return $wpdb->get_var($sql);
    }


    /**
     * Get the revision builder/form type.
     *
     * @param int $id
     *
     * @return string
     */
    public static function get_revision_type($id)
    {
        global $wpdb;
        $sql = "SELECT type FROM {$wpdb->base_prefix}pp_revisions WHERE id = $id";

        return $wpdb->get_var($sql);
    }


    /**
     * Return a revision structure.
     *
     * @param int $revision_id
     *
     * @return null|string
     */
    public static function get_revision_structure($revision_id)
    {
        global $wpdb;

        return $wpdb->get_var("SELECT structure FROM {$wpdb->base_prefix}pp_revisions WHERE id = $revision_id");

    }


    /**
     * Return a revision css stylesheet
     *
     * @param int $revision_id
     *
     * @return null|string
     */
    public static function get_revision_css($revision_id)
    {
        global $wpdb;

        return $wpdb->get_var("SELECT css FROM {$wpdb->base_prefix}pp_revisions WHERE id = $revision_id");


    }


    /**
     * Update a form/builder with a revision
     *
     * @param int $id
     * @param string $builder_type
     * @param string $structure
     * @param string $css
     */
    public static function update_builder_with_revision($id, $builder_type, $structure, $css)
    {
        global $wpdb;

        $table_name = '';

        switch ($builder_type) {
            case 'login':
                $table_name = "{$wpdb->base_prefix}pp_login_builder";
                break;
            case 'registration':
                $table_name = "{$wpdb->base_prefix}pp_registration_builder";
                break;
            case 'front_end_profile':
                $table_name = "{$wpdb->base_prefix}pp_user_profile_builder";
                break;
            case 'password_reset':
                $table_name = "{$wpdb->base_prefix}pp_password_reset_builder";
                break;

            case 'edit_user_profile':
                $table_name = "{$wpdb->base_prefix}pp_edit_profile_builder";
                break;

            case 'melange':
                $table_name = "{$wpdb->base_prefix}pp_melange";
                break;
        }
        $wpdb->update(
            $table_name,
            array(
                'structure' => $structure,
                'css' => $css,
                'date' => date('Y-m-d'),
            ),
            array('id' => $id),
            array(
                '%s',
                '%s',
                '%s',
            )
        );

    }

    /**
     * A list of revisions available to a builder
     *
     * @param string $type
     * @param int $id
     *
     * @return mixed
     */
    public static function get_builder_revisions($type, $id)
    {
        global $wpdb;
        /** @var int $pp_revision_count number of revision to display */
        $pp_revision_count = apply_filters('pp_revision_count', 5);
        $sql = "SELECT id, date FROM {$wpdb->base_prefix}pp_revisions WHERE type = '$type' AND parent_id = '$id'  ORDER BY date DESC LIMIT $pp_revision_count";

        return $wpdb->get_results($sql, 'ARRAY_A');
    }


    /**
     * Insert melange form / design to Database
     *
     * @param string $title
     * @param string $structure
     * @param string $css
     * @param string $registration_msg
     * @param  string $edit_profile_msg
     * @param string $password_reset_msg
     * @param string $date
     *
     * @return bool|int
     */
    public static function sql_insert_melange_builder(
        $title,
        $structure,
        $css,
        $registration_msg,
        $edit_profile_msg,
        $password_reset_msg,
        $date
    )
    {
        global $wpdb;

        if (is_multisite()) {
            $insert = $wpdb->insert(
                "{$wpdb->base_prefix}pp_melange",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'css' => $css,
                    'registration_msg' => $registration_msg,
                    'edit_profile_msg' => $edit_profile_msg,
                    'password_reset_msg' => $password_reset_msg,
                    'date' => $date,
                    'blog_id' => get_current_blog_id(),
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                )
            );
        } else {

            $insert = $wpdb->insert(
                "{$wpdb->base_prefix}pp_melange",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'css' => $css,
                    'registration_msg' => $registration_msg,
                    'edit_profile_msg' => $edit_profile_msg,
                    'password_reset_msg' => $password_reset_msg,
                    'date' => $date,
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                )
            );
        }

        // if insert is false (fail to insert to DB), return ID number
        return !$insert ? false : $wpdb->insert_id;
    }


    /**
     * Update melange in Database.
     *
     * @param int $id
     * @param string $title
     * @param string $structure
     * @param string $css
     * @param string $registration_msg
     * @param string $edit_profile_msg
     * @param string $password_reset_msg
     * @param string $date
     */
    public static function sql_update_melange_builder(
        $id,
        $title,
        $structure,
        $css,
        $registration_msg,
        $edit_profile_msg,
        $password_reset_msg,
        $date
    )
    {
        global $wpdb;

        if (is_multisite()) {

            // only update the blog_id with the current multisite ID if the previous blog_id isn't 0.
            $blog_id = self::get_builder_mutisite_blog_id('melange', $id) == 0 ? 0 : get_current_blog_id();

            $wpdb->update(
                "{$wpdb->base_prefix}pp_melange",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'css' => $css,
                    'registration_msg' => $registration_msg,
                    'edit_profile_msg' => $edit_profile_msg,
                    'password_reset_msg' => $password_reset_msg,
                    'date' => $date,
                    'blog_id' => $blog_id,
                ),
                array('id' => $id),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                )
            );
        } else {
            $wpdb->update(
                "{$wpdb->base_prefix}pp_melange",
                array(
                    'title' => $title,
                    'structure' => $structure,
                    'css' => $css,
                    'registration_msg' => $registration_msg,
                    'edit_profile_msg' => $edit_profile_msg,
                    'password_reset_msg' => $password_reset_msg,
                    'date' => $date,
                ),
                array('id' => $id),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                )
            );
        }
    }


    /**
     * Delete a melange in database.
     *
     * @param int $id
     *
     * @return false|int
     */
    public static function sql_delete_melange_builder($id)
    {
        global $wpdb;

        return $wpdb->delete(
            "{$wpdb->base_prefix}pp_melange",
            array(
                'id' => $id,
            ),
            array('%d')
        );
    }


    /**
     * Retrieve the row of a melange id for editing
     *
     * @param int $id
     *
     * @return mixed
     */
    public static function sql_edit_melange_builder($id)
    {
        global $wpdb;

        // get the profile fields row for the id and save as array
        $sql = $wpdb->get_row("SELECT * FROM {$wpdb->base_prefix}pp_melange WHERE id = $id", 'ARRAY_A');

        return $sql;
    }

    /**
     * Returns all columns of a melange builder ID
     *
     * @param int $id melange builder ID
     *
     * @return mixed
     */
    public static function get_melange_builder_data($id)
    {
        return self::sql_edit_melange_builder($id);
    }

    /** Melange WP_List_Table data. */
    static function sql_wp_list_table_melange()
    {
        // ALTER TABLE `wp_pp_login_builder` CHANGE `date` `date` DATE NOT NULL;
        global $wpdb;

        if (is_multisite()) {
            $current_blog_id = get_current_blog_id();
            $sql = "SELECT id, title, structure, css, date FROM {$wpdb->base_prefix}pp_melange WHERE blog_id = 0 OR blog_id = $current_blog_id";
        } else {
            $sql = "SELECT id, title, structure, css, date FROM {$wpdb->base_prefix}pp_melange";
        }

        return $wpdb->get_results($sql, 'ARRAY_A');
    }


    /**
     * Get melange successful messages
     *
     * @param int $id
     *
     * @param string $type what success message to return?
     *
     * @return string
     */
    static function get_db_success_melange($id, $type)
    {
        global $wpdb;

        $sql = $wpdb->get_var("SELECT $type FROM {$wpdb->base_prefix}pp_melange WHERE id = $id");

        return $sql;
    }
}
