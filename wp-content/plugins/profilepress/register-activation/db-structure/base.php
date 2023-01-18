<?php

namespace db_structure;

class PP_Db_Schema
{
    public static function instance()
    {
        self::create_plugin_db_structure();
    }


    /** Create the plugin DB table structure */
    public static function create_plugin_db_structure()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $edit_profile_table          = EDIT_PROFILE_TABLE;
        $login_builder_table         = LOGIN_TABLE;
        $password_reset_table        = PASSWORD_RESET_TABLE;
        $custom_profile_fields_table = CUSTOM_PROFILE_FIELD_TABLE;
        $registration_table          = PP_REGISTRATION_TABLE;
        $user_profile_table          = USER_PROFILE_TABLE;
        $make_builder_widget_table   = MAKE_BUILDER_WIDGET_TABLE;

        $passwordless_table = PASSWORDLESS_TABLE;


        /** This whole ish was done for multisite sake.
         * If blog is multi-site, add the "blog_id" table else don't add it.
         */

        // applicable for the other builders
        $blog_id_and_date_column = is_multisite() ? 'date text NOT NULL, blog_id MEDIUMINT(9) NOT NULL' : 'date text NOT NULL';

        // custom profile field
        $blog_id_and_options = is_multisite() ? 'options varchar(200) NOT NULL, blog_id MEDIUMINT(9) NOT NULL' : 'options varchar(200) NOT NULL';

        // make builder widget
        $blog_id_and_builder_id = is_multisite() ? 'builder_id mediumint(9) NOT NULL, blog_id MEDIUMINT(9) NOT NULL' : 'builder_id mediumint(9) NOT NULL';


        $tables_to_create[] = "CREATE TABLE IF NOT EXISTS $edit_profile_table (
							  id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
							  PRIMARY KEY  (id),
							  title varchar(100) NOT NULL,
							  structure longtext NOT NULL,
							  css longtext NOT NULL,
							  success_edit_profile text NOT NULL,
							  $blog_id_and_date_column
							) $charset_collate;";


        $tables_to_create[] = "CREATE TABLE IF NOT EXISTS $login_builder_table (
							  id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
							  PRIMARY KEY  (id),
							  title varchar(100) NOT NULL,
							  structure longtext NOT NULL,
							  css longtext NOT NULL,
							  $blog_id_and_date_column
							) $charset_collate;";

        $tables_to_create[] = "CREATE TABLE IF NOT EXISTS $password_reset_table (
							  id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
							  PRIMARY KEY  (id),
							  title varchar(100) NOT NULL,
							  structure longtext NOT NULL,
							  css longtext NOT NULL,
							  success_password_reset text NOT NULL,
							  $blog_id_and_date_column
							) $charset_collate;";

        $tables_to_create[] = "CREATE TABLE IF NOT EXISTS $custom_profile_fields_table (
							  id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
							  PRIMARY KEY  (id),
							  label_name varchar(100) NOT NULL,
							  field_key varchar(100) NOT NULL,
							  UNIQUE KEY field_key  (field_key),
							  description varchar(500) NOT NULL,
							  type varchar(20) NOT NULL,
							  $blog_id_and_options
							) $charset_collate;";

        $tables_to_create[] = "CREATE TABLE IF NOT EXISTS $registration_table (
							  id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
							  PRIMARY KEY  (id),
							  title varchar(100) NOT NULL,
							  structure longtext NOT NULL,
							  css longtext NOT NULL,
							  success_registration text NOT NULL,
							  $blog_id_and_date_column
							) $charset_collate;";

        $tables_to_create[] = "CREATE TABLE IF NOT EXISTS $user_profile_table (
								id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
								PRIMARY KEY  (id),
								title varchar(100) NOT NULL,
								structure longtext NOT NULL,
								css longtext NOT NULL,
								$blog_id_and_date_column
								) $charset_collate;";

        $tables_to_create[] = "CREATE TABLE IF NOT EXISTS $make_builder_widget_table (
								id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
								PRIMARY KEY  (id),
								builder_type varchar(20) NOT NULL,
								$blog_id_and_builder_id
								) $charset_collate;";

        $tables_to_create[] = "CREATE TABLE IF NOT EXISTS $passwordless_table (
								id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
								PRIMARY KEY  (id),
								user_id mediumint(9) NOT NULL,
								token varchar(30) NOT NULL,
								expires int(10) NOT NULL
								) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        foreach ($tables_to_create as $sql) {
            dbDelta($sql);
        }

    }
}