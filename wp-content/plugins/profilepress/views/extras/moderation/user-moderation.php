<?php
ob_start();

class ProfilePress_User_Moderation_Admin
{

    public static function initialize()
    {
        add_filter('user_row_actions', array(__CLASS__, 'moderation_links'), 10, 2);
        add_action('load-users.php', array(__CLASS__, 'act_on_moderation_orders'));
        add_action('authenticate', array(__CLASS__, 'login_authentication'), 30, 3);
        add_action('admin_notices', array(__CLASS__, 'admin_notices'));
        add_action('admin_notices', array(__CLASS__, 'pending_users_at_a_glance'));
        add_action('init', array(__CLASS__, 'act_on_approval_url_request'), 10, 0);
        add_action('init', array(__CLASS__, 'act_on_block_url_request'), 10, 0);
        add_action('restrict_manage_users', array(__CLASS__, 'bulk_moderation_button'));
        add_action('load-users.php', array(__CLASS__, 'act_on_bulk_moderation'));
        add_filter('dashboard_glance_items', array(__CLASS__, 'pending_users_at_a_glance'));
    }

    public static function moderation_is_active()
    {
        $extra_moderation_data = get_option('pp_extra_moderation');

        return isset($extra_moderation_data['activate_moderation']) && $extra_moderation_data['activate_moderation'] == 'active' ? true : false;
    }

    /**
     * Act on admin user approval request.
     */
    public static function act_on_approval_url_request()
    {
        if (isset($_GET['action']) && isset($_GET['id'])) {
            if (is_user_logged_in()) {
                $current_user_id = get_current_user_id();

                // user ID to approve.
                $user_id = absint($_GET['id']);
                if ($_GET['action'] == 'pp_approve_user' && is_super_admin($current_user_id)) {
                    self::approve_user($user_id);
                    wp_redirect(add_query_arg('update', 'approve', admin_url('users.php')));
                    exit;
                }
            }
        }
    }

    /**
     * Act on admin user block request.
     */
    public static function act_on_block_url_request()
    {
        if (isset($_GET['action']) && isset($_GET['id'])) {
            if (is_user_logged_in()) {
                $current_user_id = get_current_user_id();

                // user ID to block.
                $user_id = absint($_GET['id']);
                if ($_GET['action'] == 'pp_block_user' && is_super_admin($current_user_id)) {
                    self::block_user($user_id);
                    wp_redirect(add_query_arg('update', 'block', admin_url('users.php')));
                    exit;
                }
            }
        }
    }

    /**
     * Approve pending users
     *
     * @param $user_id
     *
     * @return void
     */
    public static function approve_user($user_id)
    {
        $user_data = get_userdata($user_id);
        $user_data->remove_role('pending_users');

        // send notification
        PP_User_Moderation_Notification::approve($user_id);

        do_action('pp_after_approve_user', $user_id);
    }

    /**
     * Add moderation links to user_row_actions
     *
     * @param $actions
     * @param $user_object
     *
     * @return mixed
     */
    public static function moderation_links($actions, $user_object)
    {

        $current_user = wp_get_current_user();

        // do not display button for admin
        if ($current_user->ID != $user_object->ID) {

            if (self::is_block($user_object->ID)) {

                // the unblock button
                $actions['unblock'] = sprintf('<a href="%1$s">%2$s</a>',
                    esc_url_raw(
                        add_query_arg(
                            array(
                                'action'   => 'unblock',
                                'user'     => $user_object->ID,
                                '_wpnonce' => wp_create_nonce('user-unblock')
                            ),
                            admin_url('users.php')
                        )
                    ),
                    __('unblock', 'profilepress')
                );
            } else {

                // the block button
                $actions['block'] = sprintf('<a href="%1$s">%2$s</a>',
                    esc_url(
                        add_query_arg(
                            array(
                                'action'   => 'block',
                                'user'     => $user_object->ID,
                                '_wpnonce' => wp_create_nonce('user-block')
                            ),
                            admin_url('users.php')
                        )
                    ),
                    __('block', 'profilepress')
                );

            }


            if (self::is_pending($user_object->ID)) {

                // the approve button
                $actions['user_approve'] = sprintf('<a href="%1$s">%2$s</a>',
                    esc_url(
                        add_query_arg(
                            array(
                                'action'   => 'user_approve',
                                'user'     => $user_object->ID,
                                '_wpnonce' => wp_create_nonce('user-approve')
                            ),
                            admin_url('users.php')
                        )
                    ),
                    __('approve', 'profilepress')
                );
            }
        }

        return $actions;
    }

    /**
     * Check if a user is blocked
     *
     * @param $user_id
     *
     * @return bool
     */
    public static function is_block($user_id)
    {

        // get the meta value data
        $block_user_meta_data = get_user_meta($user_id, 'pp_user_block', true);

        if (isset($block_user_meta_data) && $block_user_meta_data == 'block') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if a user is pending approval
     *
     * @param $user_id
     *
     * @return bool
     */
    public static function is_pending($user_id)
    {

        $user_object = get_userdata($user_id);
        $user_roles  = $user_object->roles;

        if (is_array($user_roles) && in_array('pending_users', $user_roles)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Block and unblock user depending on command
     */
    public static function act_on_moderation_orders()
    {
        $user_id = isset($_GET['user']) ? absint($_GET['user']) : '';

        if (isset($_GET['action'])) {

            if ('block' == $_GET['action'] && check_admin_referer('user-block')) {

                self::block_user($user_id);

                wp_redirect(add_query_arg('update', 'block', admin_url('users.php')));
                exit;

            } elseif ('unblock' == $_GET['action'] && check_admin_referer('user-unblock')) {

                self::unblock_user($user_id);

                wp_redirect(add_query_arg('update', 'unblock', admin_url('users.php')));
                exit;
            } elseif ('user_approve' == $_GET['action'] && check_admin_referer('user-approve')) {

                self::approve_user($user_id);

                wp_redirect(add_query_arg('update', 'approve', admin_url('users.php')));
                exit;
            }

        }
    }

    /**
     * Block a user
     *
     * @param int $user_id
     *
     * @return bool
     */
    public static function block_user($user_id)
    {
        update_user_meta($user_id, 'pp_user_block', 'block');

        // send notification
        PP_User_Moderation_Notification::block($user_id);

        do_action('pp_after_block_user', $user_id);
    }

    /**
     * Unblock a user
     *
     * @param $user_id
     *
     * @return bool
     */
    public static function unblock_user($user_id)
    {
        delete_user_meta($user_id, 'pp_user_block');

        PP_User_Moderation_Notification::unblock($user_id);

        do_action('pp_after_unblock_user', $user_id);
    }

    /** Add admin notice */
    public static function admin_notices()
    {
        if (isset($_GET['update'])) {

            if ($_GET['update'] == 'block' || $_GET['update'] == 'unblock' || $_GET['update'] == 'approve') {

                echo '<div class="updated">';
                echo '<p>';

                if ($_GET['update'] == 'block') {
                    _e('User Blocked', 'profilepress');
                } elseif ($_GET['update'] == 'unblock') {
                    _e('User Unblocked', 'profilepress');
                } elseif ($_GET['update'] == 'approve') {
                    _e('User Approved', 'profilepress');
                }
                echo '</p>';
                echo '</div>';
            }
        }
    }

    /**
     * Authenticate if user is blocked
     *
     * @param $user
     * @param $username
     * @param $password
     *
     * @return WP_User|WP_Error
     */
    public static function login_authentication($user, $username, $password)
    {
        // block error message
        $block_error_message   = self::blocked_error_notice();
        $pending_error_message = self::pending_error_notice();

        $user_object = get_user_by('login', $username);

        if ($user_object) {

            if (self::is_block($user_object->ID)) {

                return new WP_Error('user_blocked', $block_error_message);
            } elseif (self::is_pending($user_object->ID)) {
                return new WP_Error('user_pending', $pending_error_message);
            }

        }

        return $user;
    }


    /**
     * Add bulk moderation select dropdown to top of user WP_List_Table class
     */
    public static function bulk_moderation_button()
    {
        echo '</div>'; ?>
        <div class="alignleft actions">
        <label class="screen-reader-text" for="new_role"><?php _e('Bulk User Moderation;') ?></label>
        <select name="bulk_moderation" id="new_role">
            <option value=""><?php _e('Bulk Moderation', 'profilepress') ?></option>
            <option value="pp_approve"><?php _e('Approve', 'profilepress') ?></option>
            <option value="pp_block"><?php _e('Block', 'profilepress') ?></option>
            <option value="pp_unblock"><?php _e('Unblock', 'profilepress') ?></option>
            <option value="pp_pending"><?php _e('Make Pending', 'profilepress') ?></option>
        </select>
        <?php
        submit_button(__('Apply'), 'button', 'pp_bulk_moderation_submit', false);
    }


    /**
     * Act on bulk moderation command.
     */
    public static function act_on_bulk_moderation()
    {
        if (isset($_GET['pp_bulk_moderation_submit'])) {

            $moderation_action = esc_attr($_GET['bulk_moderation']);
            $selected_users    = array_map('absint', $_GET['users']);

            foreach ($selected_users as $selected_user) {
                if ('pp_approve' == $moderation_action) {
                    self::approve_user($selected_user);
                } elseif ('pp_block' == $moderation_action) {
                    self::block_user($selected_user);
                } elseif ('pp_unblock' == $moderation_action) {
                    self::unblock_user($selected_user);
                } elseif ('pp_pending' == $moderation_action) {
                    self::make_user_pending($selected_user);
                }
            }
        }
    }

    /**
     * Make a registered user pending.
     *
     * @param int $user_id
     *
     * @return void
     */
    public static function make_user_pending($user_id)
    {
        if ( ! is_int($user_id)) {
            return;
        }
        $user_data = get_userdata($user_id);
        $user_data->add_role('pending_users');
    }


    /**
     * Add number of users pending activation.
     *
     * @param $items
     *
     * @return array
     */
    public static function pending_users_at_a_glance($items)
    {
        if ( ! is_super_admin(get_current_user_id())) {
            return;
        }

        $obj = new WP_User_Query(array(
            'role'        => 'pending_users',
            'count_total' => true
        ));

        $count = $obj->get_total();

        // we are checking if argument is array because "at a glance" dashboard has it set as an array.
        if ( ! is_array($items)) {
            if ($count > 0) {
                echo '<div class="updated notice is-dismissible"><p>';
                echo 'Hi admin, <a href="' . admin_url('users.php?role=pending_users') . '">' . sprintf(
                        _n(
                            __('1 user is pending approval', 'profilepress'),
                            __('%s users are pending approval', 'profilepress'),
                            $count,
                            'profilepress'
                        ),
                        $count
                    )
                     . '</a>';
                echo '.</p>';
                echo '</div>';
            }

            return;
        }

        if ($count > 0) {
            $items[] = '<a href="' . admin_url('users.php') . '">' . sprintf(
                    _n(
                        __('1 pending user', 'profilepress'),
                        __('%s pending users', 'profilepress'),
                        $count,
                        'profilepress'
                    ),
                    $count
                )
                       . '</a>';
        }


        return $items;
    }


    /** Pending user admin notice notice */
    public static function pending_user_notice()
    {

        if (isset($_GET['update'])) {

            if ($_GET['update'] == 'block' || $_GET['update'] == 'unblock' || $_GET['update'] == 'approve') {

                echo '<div class="updated">';
                echo '<p>';

                if ($_GET['update'] == 'block') {
                    _e('User Blocked', 'profilepress');
                } elseif ($_GET['update'] == 'unblock') {
                    _e('User Unblocked', 'profilepress');
                } elseif ($_GET['update'] == 'approve') {
                    _e('User Approved', 'profilepress');
                }
                echo '</p>';
                echo '</div>';
            }
        }
    }

    /**
     * block error message.
     *
     * @return string
     */
    public static function blocked_error_notice()
    {
        $extra_moderation_data = get_option('pp_extra_moderation');

        return isset($extra_moderation_data) && ! empty($extra_moderation_data['blocked_error_message']) ? htmlspecialchars_decode($extra_moderation_data['blocked_error_message']) : sprintf(__('%s ERROR %s: This account is blocked.', 'profilepress'), '<strong>', '</strong>');

    }


    /**
     *  pending error message.
     *
     * @return string
     */
    public static function pending_error_notice()
    {
        $extra_moderation_data = get_option('pp_extra_moderation');

        return isset($extra_moderation_data) && ! empty($extra_moderation_data['pending_error_message']) ? htmlspecialchars_decode($extra_moderation_data['pending_error_message']) : sprintf(__('%s ERROR %s: This account is pending approval.', 'profilepress'), '<strong>', '</strong>');
    }
}


if (ProfilePress_User_Moderation_Admin::moderation_is_active()) {
    ProfilePress_User_Moderation_Admin::initialize();
}

