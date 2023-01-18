<?php


if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Profile_Fields_List_Table extends WP_List_Table
{
    var $example_data;

    function __construct()
    {
        global $status, $page;

        $this->example_data = PROFILEPRESS_sql::sql_wp_list_table_profile_fields();
        parent::__construct(array(
            'singular' => __('field', 'profilepress'), //singular name of the listed records
            'plural' => __('fields', 'profilepress'), //plural name of the listed records
            'ajax' => false //does this table support ajax?

        ));

        add_action('admin_head', array(&$this, 'admin_header'));

    }

    function admin_header()
    {
        $page = (isset($_GET['page'])) ? esc_attr($_GET['page']) : false;
        if ('my_list_test' != $page) {
            return;
        }
        echo '<style type="text/css">';
        echo '.wp-list-table .column-id { width: 5%; }';
        echo '.wp-list-table .column-booktitle { width: 40%; }';
        echo '.wp-list-table .column-author { width: 35%; }';
        echo '.wp-list-table .column-isbn { width: 20%;}';
        echo '</style>';
    }

    function no_items()
    {
        _e('No profile field available.', 'profilepress');
    }

    function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'label_name':
            case 'description':
            case 'field_key':
            case 'type':
                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'label_name' => __('Field Name', 'profilepress'),
            'description' => __('Description', 'profilepress'),
            'field_key' => __('Field Key', 'profilepress'),
            'type' => __('Field Type', 'profilepress')
        );

        return $columns;
    }

    function column_label_name($item)
    {
        // Create an nonce, and add it as a query var in a link to perform an action.
        $nonce_edit = wp_create_nonce('pp_edit_field');
        $nonce_delete = wp_create_nonce('pp_delete_field');

        $edit_link = sprintf('admin.php?page=%s&action=%s&field=%s&_wpnonce=%s', esc_attr($_REQUEST['page']), 'edit', esc_attr($item['id']), $nonce_edit);

        $actions = array(
            'edit' => sprintf('<a href="?page=%s&action=%s&field=%s&_wpnonce=%s">Edit</a>', esc_attr($_REQUEST['page']), 'edit', esc_attr($item['id']), $nonce_edit),
            'delete' => sprintf('<a href="javascript:pp_del_custom_fields(\'%s\',\'%s\',\'%d\',\'%s\')">Delete</a>', $_REQUEST['page'], 'delete', absint($item['id']), $nonce_delete),
        );

        $a = '<a href="' . $edit_link . '">' . $item['label_name'] . '</a>';

        return '<strong>' . $a . '</strong>' . $this->row_actions($actions);
    }


    function get_bulk_actions()
    {
        $actions = array(
            'bulk-delete' => 'Delete'
        );

        return $actions;
    }

    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
        );
    }

    function prepare_items()
    {
        $this->_column_headers = $this->get_column_info();

        /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        $this->process_bulk_action();

        $per_page = $this->get_items_per_page('profile_fields_per_page', 20);
        $current_page = $this->get_pagenum();
        $total_items = count($this->example_data);

        // only ncessary because we have sample data
        $found_data = array_slice($this->example_data, (($current_page - 1) * $per_page), $per_page);

        $this->set_pagination_args(array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page' => $per_page //WE have to determine how many items to show on a page
        ));
        $this->items = $found_data;
    }

    function process_bulk_action()
    {
        //Detect when a bulk action is being triggered...
        if ('delete' === $this->current_action()) {

            // In our file that handles the request, verify the nonce.
            $nonce = @esc_attr($_REQUEST['_wpnonce']);
            if (!wp_verify_nonce($nonce, 'pp_delete_field')) {
                die('Weird: Plugin is resolving itself');
            }

            $field_id = absint($_GET['field']);
            // if delete is successful, redirect to base
            if (PROFILEPRESS_sql::sql_delete_profile_field($field_id)) {
                do_action('pp_delete_custom_field_db', $field_id);
                wp_redirect(esc_attr('?page=' . PROFILE_FIELDS_SETTINGS_PAGE_SLUG));
                exit;
            }

        }

        if ((isset($_POST['action']) || isset($_POST['action2'])) &&
            ($_POST['action'] == 'bulk-delete' || $_POST['action2'] == 'bulk-delete')
        ) {
            $delete_array = $_POST['bulk-delete'];

            foreach ($delete_array as $value) {
                PROFILEPRESS_sql::sql_delete_profile_field(absint($value));
            }

            wp_redirect(esc_attr('?page=' . PROFILE_FIELDS_SETTINGS_PAGE_SLUG));
            exit;
        }
    }

    /**
     * Extra controls to be displayed between bulk actions and pagination
     *
     * @param string $which
     */
    public function extra_tablenav($which)
    {
        do_action('pp_custom_fields_extra_tablenav', $which);
    }

    /**
     * Add ppview to a list of css classes included in the table
     *
     * THis method overrides that of the parent class
     *
     * @return array List of CSS classes for the table tag.
     */
    public function get_table_classes()
    {
        return array('widefat', 'fixed', 'custom_profile_fields', 'ppview');
    }

}
