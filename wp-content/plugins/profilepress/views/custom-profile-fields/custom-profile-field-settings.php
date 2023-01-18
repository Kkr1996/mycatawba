<?php
ob_start();
require_once 'custom-profile-fields-wp-list-table.php';

/**
 *  custom profile fields settings page
 */
class Custom_Profile_Fields_Settings_Page
{
    private static $instance;
    private $profile_fields_form_errors;

    /** Constructor */
    function __construct()
    {
        add_action('admin_menu', array($this, 'register_cpf_settings_page'));
        add_filter('set-screen-option', array($this, 'save_screen_option'), 10, 3);
        add_action('admin_print_scripts', array($this, 'js_confirm_custom_fields'));
    }

    function register_cpf_settings_page()
    {

        $hook = add_submenu_page('pp-config',
            __('Custom Profile Fields', 'profilepress') . ' - ProfilePress',
            __('Custom Profile Fields', 'profilepress'),
            'manage_options',
            PROFILE_FIELDS_SETTINGS_PAGE_SLUG,
            array($this, 'cpf_settings_page_function'));

        add_action("load-$hook", array($this, 'add_options'));
    }


    public function add_options()
    {
        global $myListTable;
        $option = 'per_page';
        $args = array(
            'label' => 'Profile fields',
            'default' => 10,
            'option' => 'profile_fields_per_page'
        );
        add_screen_option($option, $args);

        $myListTable = new Profile_Fields_List_Table;

    }


    // save the screen option values
    public function save_screen_option($status, $option, $value)
    {
        return $value;
    }

    // argument @operation determine if the method should save added field or edited field
    public function save_add_edit_profile_fields($operation, $id = '')
    {
        if (isset($_POST['add_new_field']) || isset($_POST['edit_field'])) {
            $label_name = stripslashes(esc_attr($_POST['cpf_label_name']));
            $key = sanitize_text_field($_POST['cpf_key']);
            $description = stripslashes(sanitize_text_field($_POST['cpf_description']));
            $type = sanitize_text_field($_POST['cpf_type']);
            $options = stripslashes(esc_html($_POST['cpf_options']));
            $is_multi_selectable = isset($_POST['cpf_multi_select']) ? sanitize_text_field($_POST['cpf_multi_select']) : '';
            $is_multi_checkbox = isset($_POST['cpf_multi_checkbox']) ? sanitize_text_field($_POST['cpf_multi_checkbox']) : '';

            // catch and save form generated errors in property @profile_fields_form_errors
            if (empty($_POST['cpf_label_name'])) {
                $this->profile_fields_form_errors = __('Field label is empty', 'profilepress');
            } elseif (empty($_POST['cpf_key'])) {
                $this->profile_fields_form_errors = __('Field Key is empty', 'profilepress');
            } elseif (preg_match('/^[a-z0-9_]+$/', $_POST['cpf_key']) !== 1) {
                $this->profile_fields_form_errors = __('Field key appears to be of invalid format', 'profilepress');
            } elseif (empty($_POST['cpf_type'])) {
                $this->profile_fields_form_errors = __('Please choose a form Type', 'profilepress');
            } elseif ($_POST['cpf_type'] == 'select' && empty($_POST['cpf_options'])) {
                $this->profile_fields_form_errors = __('Options field is required', 'profilepress');
            } elseif ($_POST['cpf_type'] == 'radio' && empty($_POST['cpf_options'])) {
                $this->profile_fields_form_errors = __('Options field is required', 'profilepress');
            }
        }

        if (isset($this->profile_fields_form_errors)) {
            return;
        }

        if (isset($_POST['add_new_field']) && check_admin_referer('add_custom_profile_fields', '_wpnonce') && $operation == 'add') {
            global $wpdb;

            $result = PROFILEPRESS_sql::add_profile_field($label_name, $key, $description, $type, $options);

            if (is_int($result)) {
                $new_custom_field_id = $result;

                do_action('pp_insert_custom_field_db', $new_custom_field_id, $_POST);

                if ($type == 'select' && isset($is_multi_selectable) && $is_multi_selectable == 'yes') {
                    PROFILEPRESS_sql::add_multi_selectable($key, $wpdb->insert_id);
                } elseif ($type == 'checkbox' && isset($is_multi_checkbox) && $is_multi_checkbox == 'yes') {
                    PROFILEPRESS_sql::add_multi_checkbox($key, $wpdb->insert_id);
                }

                wp_redirect('?page=' . PROFILE_FIELDS_SETTINGS_PAGE_SLUG . '&field-added=true');
                exit;
            } else {
                echo __('Unexpected error. Please try again.', 'profilepress');
            }

        }

        // check if the edit buttin was clicked and if nonces are ok
        if (isset($_POST['edit_field']) && check_admin_referer('edit_custom_profile_fields', '_wpnonce')) {
            global $wpdb;

            // if field is being edited
            if ($operation == 'edit') {

                $update = PROFILEPRESS_sql::update_profile_field($id, $label_name, $key, $description, $type, $options);

                if ($update !== false) {
                    do_action('pp_update_custom_field_db', $id, $_POST);
                }

                if ($type == 'select' && isset($is_multi_selectable) && 'yes' == $is_multi_selectable) {
                    PROFILEPRESS_sql::add_multi_selectable($key, $id);
                } else {
                    PROFILEPRESS_sql::delete_multi_selectable($key);
                }

                if ($type == 'checkbox' && isset($is_multi_checkbox) && 'yes' == $is_multi_checkbox) {
                    PROFILEPRESS_sql::add_multi_checkbox($key, $id);
                } else {
                    PROFILEPRESS_sql::delete_multi_checkbox($key);
                }

                wp_redirect('?page=' . PROFILE_FIELDS_SETTINGS_PAGE_SLUG . '&field-edited=true');
                exit;


            }
        }


    }

    /** Singleton instance */
    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /** Add an alert before a custom_profile_field builder is deleted */
    public function js_confirm_custom_fields()
    {
        ?>
        <script type="text/javascript">
            function pp_del_custom_fields(page, action, custom_fields, _wpnonce) {
                if (confirm("Are you sure you want to delete this?")) {
                    window.location.href = '?page=' + page + '&action=' + action + '&field=' + custom_fields + '&_wpnonce=' + _wpnonce;
                }
            }
        </script>
        <?php
    }

    public function cpf_settings_page_function()
    {
        // if we are in addition of field page
        if (isset($_GET['post']) && $_GET['post'] == 'new') {
            ?>
            <div class="wrap">

                <h2><?php _e('Custom Profile Fields', 'profilepress'); ?></h2>
                <?php
                // call function to insert field to db
                $this->save_add_edit_profile_fields('add');

                // include the add new custom field file
                if (isset($this->profile_fields_form_errors)) : ?>
                    <div id="message" class="error notice is-dismissible">
                        <p><strong><?php echo $this->profile_fields_form_errors; ?>. </strong></p>
                    </div>

                <?php endif;
                require_once 'include.add-profile-fields-settings-page.php'; ?>
            </div>
            <?php
        } // if we are in field editing field
        elseif (isset($_GET['action']) && $_GET['action'] == 'edit') { ?>
            <div class="wrap"><h2><?php _e('Custom Profile Fields', 'profilepress'); ?></h2>

            <?php
            // @GET field id to edit
            $field_id = (int)$_GET['field'];

            // call function to edit field in db
            $this->save_add_edit_profile_fields('edit', $field_id);

            if (isset($this->profile_fields_form_errors)) {
                ?>
                <div id="message" class="error notice is-dismissible"><p>
                        <strong><?php echo $this->profile_fields_form_errors; ?>. </strong>
                    </p></div>
                <?php
            }

            // include the edit custom field file
            require_once 'include.edit-profile-fields-settings-page.php';
        } else {
            global $myListTable;

            echo '<div class="wrap"><h2>' . __('Custom Profile Fields', 'profilepress') . ' <a class="add-new-h2" href="' . esc_attr(esc_url_raw(add_query_arg('post', 'new'))) . '">' . __('Add New', 'profilepress') . '</a></h2>';

            // status displayed when new field is added
            if (isset($_GET['field-edited']) && $_GET['field-edited']) {
                echo '<div id="message" class="updated notice is-dismissible"><p><strong>' . __('Profile Field Edited.', 'profilepress') . ' </strong></p></div>';
            }

            // status displayed when new field is edited
            if (isset($_GET['field-added']) && $_GET['field-added']) {
                ?>
                <div id="message" class="updated notice is-dismissible"><p>
                        <strong><?php _e('New Profile Field Added.', 'profilepress'); ?></strong>
                    </p></div>
                <?php
            }

            // include settings tab
            require_once VIEWS . '/include.settings-page-tab.php';

            $myListTable->prepare_items();
            ?>

            <form method="post">
                <input type="hidden" name="page" value="ttest_list_table">
                <?php $myListTable->display(); ?>
            </form>
            </div>
            <?php

            do_action('pp_custom_field_wp_list_table_bottom');
        }

    }
}


Custom_Profile_Fields_Settings_Page::get_instance();