<?php
ob_start();

class Contact_Info_Profile_Fields
{

    private static $instance;
    private $contact_info_errors;

    public function __construct()
    {
        add_action('admin_menu', array($this, 'register_settings_page'));

        // hook modify contact info to filter
        add_filter('user_contactmethods', array($this, 'modify_user_contact_methods'));

        add_action('admin_print_scripts', array($this, 'js_confirm_contact_info'));
    }

    public function register_settings_page()
    {

        add_submenu_page(
            'pp-config',
            'Profile Contact Information - ProfilePress',
            'Profile Contact Info',
            'manage_options',
            'pp-contact-info',
            array($this, 'settings_page_function'));
    }


    /**
     * save added contact info to db
     *
     * @return void
     */
    function save_contact_info()
    {
        if (isset($_POST['add_contact_info']) || isset($_POST['edit_contact_info'])) {
            // get form values
            $ci_name = esc_attr($_POST['ci_label_name']);
            $ci_key  = esc_attr($_POST['ci_key']);


            // catch and save form generated errors in property @contact_info_errors
            if (empty($_POST['ci_label_name'])) {
                $this->contact_info_errors = 'Field Label is empty';
            } elseif (empty($_POST['ci_key'])) {
                $this->contact_info_errors = 'Field Key is empty';
            }

            if (isset($this->contact_info_errors)) {
                return;
            }

            if (isset($_POST['add_contact_info']) && check_admin_referer('add_contact_info', '_wpnonce')) {

                // get previous option before merging and saving @array_merge
                $old_value = get_option('pp_contact_info');

                $new_value          = array();
                $new_value[$ci_key] = $ci_name;

                if ( ! empty($old_value)) {
                    // merge the new value to the ld contact-info data if only if the old value isn't empty
                    $merge_data = array_merge($old_value, $new_value);
                } else {
                    $merge_data = $new_value;
                }


                // update the db and redirect
                if (update_option('pp_contact_info', $merge_data)) {
                    wp_redirect('?page=pp-contact-info&new-contact-info=added');
                    exit;
                } else {
                    echo 'Unexpected error. Please try again.';
                }
            }
        }

    }


    /** Delete a contact info */
    function delete_contact_info($key_to_delete)
    {
        // get old value
        $old_value = get_option('pp_contact_info');

        // loop over the old value and unset/delete the key/value
        foreach ($old_value as $key => $value) {
            if ($key == $key_to_delete) {
                unset($old_value[$key_to_delete]);
            }
        }
        if (update_option('pp_contact_info', $old_value)) {
            wp_redirect('?page=pp-contact-info&contact-info=deleted');
            exit;
        } else {
            _e('Unexpected error. Please try again.', 'profilepress');
        }
    }


    /**
     * modify WordPress contact information in user profile.
     *
     * @param $user_contact
     *
     * @return mixed
     */
    function modify_user_contact_methods($user_contact)
    {
        // contact_info in db
        $db_contact_info = get_option('pp_contact_info', array());

        if ( ! empty($db_contact_info)) {
            if ( ! array_key_exists('aim', $db_contact_info)) {
                unset($user_contact['aim']);
            }

            if ( ! array_key_exists('yim', $db_contact_info)) {
                unset($user_contact['yim']);
            }

            if ( ! array_key_exists('jabber', $db_contact_info)) {
                unset($user_contact['jabber']);
            }

            foreach ($db_contact_info as $key => $label) {
                $user_contact[$key] = __($label);
            }
        }


        return $user_contact;
    }

    /** Add an alert before a contact_info builder is deleted */
    public function js_confirm_contact_info()
    {
        ?>
        <script type="text/javascript">
            function pp_del_contact_info(key) {
                if (confirm("Are you sure you want to delete this?")) {
                    window.location.href = '<?php echo admin_url('admin.php?page=pp-contact-info'); ?>&delete=' + key;
                }
            }
        </script>
        <?php
    }

    /** Singleton poop */
    public static function get_instance()
    {
        if ( ! isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }


    public function settings_page_function()
    {
        // if the 'add new' button is clicked call the @function add_new_contact_info_page
        if (isset($_GET['contact-info']) && $_GET['contact-info'] == 'new') {
            $this->add_new_contact_info_page();

        } else {
            // display index contact info page
            $contact_info_in_db = get_option('pp_contact_info', array());
            ?>
            <div class="wrap">
                <div id="icon-options-general" class="icon32"></div>
                <h2>Contact Information
                    <a class="add-new-h2" href="<?php echo esc_url(add_query_arg('contact-info', 'new')); ?>"><?php _e('Add New', 'profilepress'); ?></a>
                </h2>
                <?php

                // status displayed when new field is added
                if (isset($_GET['new-contact-info']) && $_GET['new-contact-info'] == 'added') {
                    ?>
                    <div id="message" class="updated notice is-dismissible"><p><strong>Profile Field Edited. </strong>
                        </p></div>
                    <?php
                } elseif (isset($_GET['contact-info']) && $_GET['contact-info'] == 'deleted') {
                    ?>
                    <div id="message" class="updated notice is-dismissible"><p>
                            <strong>Contact Information Deleted. </strong></p></div>
                    <?php
                }

                // if a field is to be deleted call the delete method
                if (isset($_GET['delete']) && is_string($_GET['delete'])) {
                    $this->delete_contact_info(esc_attr($_GET['delete']));
                }

                require_once VIEWS . '/include.settings-page-tab.php'; ?>

                <div id="poststuff" class="ppview">
                    <div id="post-body" class="metabox-holder columns-2">
                        <div id="post-body-content">
                            <div class="meta-box-sortables ui-sortable">
                                <form method="post">

                                    <div class="inside">
                                        <table class="widefat" id="pp_contact_info">
                                            <thead>
                                            <tr>
                                                <th> Field Label</th>
                                                <th> Field Key</th>
                                                <th> Action</th>
                                            </tr>
                                            </thead>

                                            <tfoot>
                                            <tr>
                                                <th> Field Label</th>
                                                <th> Field Key</th>
                                                <th> Action</th>
                                            </tr>
                                            </tfoot>

                                            <tbody>
                                            <?php if (empty($contact_info_in_db)) {
                                                echo '<tr><td>' . __('No contact information was found', 'profilepress') . '</td></tr>';
                                            } else {

                                                foreach ($contact_info_in_db as $key => $value) {
                                                    echo "<tr id='$key'>";
                                                    echo "<td>$value</td>";
                                                    echo "<td>$key</td>";
                                                    echo '<td><a href="javascript:pp_del_contact_info(\'' . $key . '\')">Delete</a></td>';
                                                    echo '</tr>';

                                                }

                                            }
                                            ?>
                                            </tbody>
                                        </table>

                                    </div>
                                </form>
                            </div>

                        </div>
                        <?php include_once VIEWS . '/include.plugin-settings-sidebar.php'; ?>

                    </div>
                    <br class="clear">
                </div>
            </div>
            <?php
        }
    }


    function add_new_contact_info_page() {
        ?>
        <div class="wrap">
        <h2>Contact Information</h2>
        <?php
        // call function to insert field to db
        $this->save_contact_info();

        // display form generated error notice
        if ( isset( $this->contact_info_errors ) ) : ?>
            <div id="message" class="error notice is-dismissible"><p><strong><?php echo $this->contact_info_errors; ?>. </strong></p></div>
        <?php
        endif;

        // status displayed when new field is added
        if ( isset( $_GET['field-edited'] ) && $_GET['field-edited'] ) : ?>
            <div id="message" class="updated notice is-dismissible"><p><strong>Profile Field Edited. </strong></p></div>
        <?php
        endif;

        // include the add new contact info page
        require_once 'include.add-new-contact-info.php';
    }
}

Contact_Info_Profile_Fields::get_instance();