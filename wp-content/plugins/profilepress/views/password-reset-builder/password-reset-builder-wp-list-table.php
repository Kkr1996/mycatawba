<?php


if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Password_Reset_Builder_List_Table extends WP_List_Table
{
    var $example_data;

    function __construct()
    {
        $this->example_data = PROFILEPRESS_sql::sql_wp_list_table_password_reset_builder();

        parent::__construct(array(
            'singular' => __('password_reset', 'profilepress'), //singular name of the listed records
            'plural' => __('password_resets', 'profilepress'), //plural name of the listed records
            'ajax' => false //does this table support ajax?

        ));

        add_action('admin_head', array(&$this, 'admin_header'));

    }

    function admin_header()
    {
        $page = (isset($_GET['page'])) ? esc_attr($_GET['page']) : false;
        if ('pp-password-reset' != $page) {
            return;
        }
    }

    function no_items()
    {
        _e('No Password_Reset Form Available.', 'profilepress');
    }

    function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'title':
            case 'shortcode':
            case 'date':
                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
            'title' => array('title', true),
            'date' => array('date', true),
        );

        return $sortable_columns;
    }

    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Title', 'profilepress'),
            'shortcode' => __('Shortcode', 'profilepress'),
            'widget' => __('Widget', 'profilepress'),
            'date' => __('Date', 'profilepress')
        );

        return $columns;
    }

    function usort_reorder($a, $b)
    {
// If no sort, default to title
        $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'title';
// If no order, default to asc
        $order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';
// Determine sort order
        $result = strcmp($a[$orderby], $b[$orderby]);

// Send final sort direction to usort
        return ($order === 'asc') ? $result : -$result;
    }

    function column_title($item)
    {
        // Create an nonce, and add it as a query var in a link to perform an action.
        $nonce_clone = wp_create_nonce('pp_clone_pass');
        $nonce_edit = wp_create_nonce('pp_edit_pass');
        $nonce_delete = wp_create_nonce('pp_delete_pass');

        $edit_link = admin_url(sprintf('admin.php?page=%s&action=%s&password-reset=%d&_wpnonce=%s', esc_attr($_REQUEST['page']), 'edit', absint($item['id']), $nonce_edit));

        $actions = array(
            'edit' => sprintf('<a href="%s">%s</a>', $edit_link, __('Edit', 'profilepress')),
            'clone' => sprintf('<a href="?page=%s&action=%s&password-reset=%s&_wpnonce=%s">%s</a>', esc_attr($_REQUEST['page']), 'clone', absint($item['id']), $nonce_clone, __('Clone', 'profilepress')),
            'delete' => sprintf('<a href="javascript:pp_del_password_reset(\'%s\',\'%s\',\'%d\',\'%s\')">%s</a>', $_REQUEST['page'], 'delete', esc_attr($item['id']), $nonce_delete, __('Delete', 'profilepress')),
        );

        $a = '<a href="' . $edit_link . '">' . $item['title'] . '</a>';

        return '<strong>' . $a . '</strong>' . $this->row_actions($actions);
    }

    function column_shortcode($item)
    {
        $shortcodes = array(
            sprintf('[profilepress-password-reset id="%1$d"]', $item['id'])
        );

        $output = '';

        foreach ($shortcodes as $shortcode) {
            $output .= "\n" . '<input type="text" onclick="focus();select();" readonly="readonly"
				value="' . esc_attr($shortcode) . '" class="shortcode-in-list-table" />';
        }

        return trim($output);
    }

    function column_widget($item)
    {
        $id = $item['id'];

        if (!is_null(PROFILEPRESS_sql::check_if_builder_is_widget($id, 'password_reset'))) {
            echo '<div class="pp_circle_green"></div>';
        } else {
            echo '<div class="pp_circle_red"></div>';
        }
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


        usort($this->example_data, array(&$this, 'usort_reorder'));
        $per_page = $this->get_items_per_page('password_reset_builder_per_page', 10);
        $current_page = $this->get_pagenum();
        $total_items = count($this->example_data);

        // only necessary because we have sample data
        $found_data = array_slice($this->example_data, (($current_page - 1) * $per_page), $per_page);

        $this->set_pagination_args(array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page' => $per_page //WE have to determine how many items to show on a page
        ));
        $this->items = $found_data;
    }


    function process_bulk_action()
    {
        if ('clone' === $this->current_action()) {
            // In our file that handles the request, verify the nonce.
            $nonce = @esc_attr($_REQUEST['_wpnonce']);
            if (!wp_verify_nonce($nonce, 'pp_clone_pass')) {
                die('Weird: Plugin is resolving itself');
            }

            $id = absint($_GET['password-reset']);

            $clone_data = PROFILEPRESS_sql::get_password_reset_builder_data($id);
            PROFILEPRESS_sql::sql_insert_password_reset_builder(
                $clone_data['title'] . " - Copy",
                $clone_data['structure'],
                $clone_data['handler_structure'],
                $clone_data['css'],
                $clone_data['success_password_reset'],
                date('Y-m-d')
            );

            wp_redirect(esc_attr('?page=' . PASSWORD_RESET_BUILDER_SETTINGS_PAGE_SLUG));
            exit;
        }

        if ('delete' === $this->current_action()) {

            // In our file that handles the request, verify the nonce.
            $nonce = @esc_attr($_REQUEST['_wpnonce']);
            if (!wp_verify_nonce($nonce, 'pp_delete_pass')) {
                die('Weird: Plugin is resolving itself');
            }

            $builder_id = absint($_GET['password-reset']);

            PROFILEPRESS_sql::sql_delete_password_reset_builder($builder_id);

            PROFILEPRESS_sql::sql_delete_pp_builder_widget($builder_id, 'password_reset');

            PROFILEPRESS_sql::delete_revisions_by_parent_id($builder_id);

            wp_redirect(esc_attr('?page=' . PASSWORD_RESET_BUILDER_SETTINGS_PAGE_SLUG));
            exit;
        }

        //Detect when a bulk action is being triggered...
        if ('bulk-delete' == $this->current_action()) {
            $delete_array = $_POST['bulk-delete'];

            foreach ($delete_array as $id) {
                $id = absint($id);
                PROFILEPRESS_sql::sql_delete_password_reset_builder($id);

                PROFILEPRESS_sql::sql_delete_pp_builder_widget($id, 'password_reset');

                PROFILEPRESS_sql::delete_revisions_by_parent_id($id);
            }

            wp_redirect(esc_attr('?page=' . PASSWORD_RESET_BUILDER_SETTINGS_PAGE_SLUG));
            exit;
        }

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
        return array('widefat', 'fixed', 'ppview');
    }

} //class
