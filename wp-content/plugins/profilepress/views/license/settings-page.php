<?php
ob_start();

class PP_Plugin_License_Page
{
    const slug = 'pp-license';

    private static $license_key;

    public static function initialize()
    {
        self::$license_key = trim(ppp_get_option('pp_license_key'));

        add_action('admin_menu', array(__CLASS__, 'register_settings_page'));

        // unavailablity of this class could potentially break all ajax requests.
        if (class_exists('PP_license_Control')) {
            add_action('admin_init', array(__CLASS__, 'plugin_updater'), 0);
            add_action('admin_init', array(__CLASS__, 'pp_plugin_check_license'), 0);
        }

        add_action('admin_notices', array(__CLASS__, 'license_not_active_notice'));
    }

    /**
     * @return PP_license_Control
     */
    public static function license_control_instance()
    {
        return new PP_license_Control(
            self::$license_key,
            PROFILEPRESS_SYSTEM_FILE_PATH,
            PP_VERSION_NUMBER,
            PP_ITEM_NAME,
            PP_ITEM_ID
        );
    }

    /**
     * Check if the plugin license is active
     */
    public static function pp_plugin_check_license()
    {
        // only check license if transient doesn't exist
        if (false === get_transient('pp_license_check')) {

            $response = self::license_control_instance()->check_license();

            if (is_wp_error($response)) {
                return false;
            }

            if (!empty($response->license)) {
                if ($response->license == 'valid') {
                    update_option('pp_license_status', 'valid');
                } else {
                    update_option('pp_license_status', 'invalid');
                }
            }

            set_transient('pp_license_check', 'active', 24 * HOUR_IN_SECONDS);
        }
    }


    public static function register_settings_page()
    {

        add_submenu_page(
            'pp-config',
            __('License', 'profilepress') . ' - ProfilePress',
            __('License', 'profilepress'),
            'manage_options',
            self::slug,
            array(__CLASS__, 'license_page')
        );
    }

    /**
     * License settings page
     */
    public static function license_page()
    {
        $license = ppp_get_option('pp_license_key');

        // listen for our activate button to be clicked
        if (isset($_POST['pp_activate_license'])) {
            self::activate_license();
        } else if (isset($_POST['pp_deactivate_license'])) {
            self::deactivate_license();
        } // listen for our activate button to be clicked
        else if (isset($_POST['save_license'])) {
            self::save_license_key();
        }

        if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
            add_settings_error(self::slug, 'changes_saved', __('License key updated successfully', 'profilepress'), 'updated');
        } elseif (isset($_GET['license']) && $_GET['license'] == 'activated') {
            add_settings_error(self::slug, 'valid_license', __('License key activation successful.', 'profilepress'), 'updated');
        } elseif (isset($_GET['license']) && $_GET['license'] == 'deactivated') {
            add_settings_error(self::slug, 'invalid_license', __('License key deactivation successful.', 'profilepress'), 'updated');
        }
        ?>

        <div class="wrap">
        <h2><?php _e('ProfilePress License', 'profilepress'); ?></h2>
        <!--	Output Settings error	-->
        <?php settings_errors(); ?>
        <?php self::license_banner(); ?>
        <form method="post">
            <table class="form-table">
                <tbody>
                <tr valign="top">
                    <th scope="row" valign="top">
                        <?php _e('License Key'); ?>
                    </th>
                    <td>
                        <input id="pp_license_key" name="pp_license_key" type="text" class="regular-text" value="<?php esc_attr_e($license); ?>"/>
                        <label class="description" for="pp_plugin_license_key"><?php _e('Enter your license key', 'profilepress'); ?></label>
                    </td>
                </tr>
                <?php if (false !== $license) { ?>
                    <tr valign="top" id="license_Activate_th">
                        <th scope="row" valign="top">
                            <?php _e('Activate License', 'profilepress'); ?>
                        </th>
                        <td>
                            <?php if (pp_is_license_valid()) { ?>
                                <span style="color:green;"><?php _e('active'); ?></span>
                                <input type="submit" class="button-secondary" name="pp_deactivate_license" value="<?php _e('Deactivate License'); ?>"/>
                                <?php
                            } else {
                                ?>
                                <input type="submit" class="button-secondary" name="pp_activate_license" value="<?php _e('Activate License'); ?>"/>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <?php wp_nonce_field('pp_plugin_nonce', 'pp_plugin_nonce'); ?>
            <?php submit_button(null, 'primary', 'save_license'); ?>
        </form>
        <script type="text/javascript">
            (function ($) {
                field = $('input#pp_license_key');
                var initial_value = field.val();
                field.change(function () {
                    $(this).val() != initial_value ? $('tr#license_Activate_th').hide() : $('tr#license_Activate_th').show();
                });
            })(jQuery);

        </script>
        <?php
    }

    /**
     * Save License key to DB
     */
    public static function save_license_key()
    {
        // run a quick security check
        if (!check_admin_referer('pp_plugin_nonce', 'pp_plugin_nonce')) {
            return;
        }

        $old = self::$license_key;
        $new = esc_html($_POST['pp_license_key']);

        if ($old && $old != $new) {
            ppp_delete_option('pp_license_status'); // new license has been entered, so must reactivate
        }

        ppp_update_option('pp_license_key', $new);

        wp_redirect(esc_url_raw(add_query_arg('settings-updated', 'true')));
        exit;
    }


    /**
     * Acivate License key
     */
    public static function activate_license()
    {
        // run a quick security check
        if (!check_admin_referer('pp_plugin_nonce', 'pp_plugin_nonce')) {
            return;
        }

        $response = self::license_control_instance()->activate_license();

        if (is_wp_error($response)) {
            add_settings_error(self::slug, 'activation_error', $response->get_error_message());
            return;
        }

        // $response->license will be either "valid" or "invalid"
        ppp_update_option('pp_license_status', $response->license);

        if ($response->license == 'invalid') {
            add_settings_error(self::slug, 'invalid_license', 'License key entered is invalid.');
        } elseif ($response->license == 'valid') {
            //first time activation
            ppp_add_option('pp_license_once_active', 'true');
            wp_redirect(add_query_arg('license', 'activated'));
            exit;
        }

    }

    /**
     * Plugin update method
     */
    public static function plugin_updater()
    {
        self::license_control_instance()->plugin_updater();
    }

    /**
     * Deactivate license
     */
    public static function deactivate_license()
    {
        // run a quick security check
        if (!check_admin_referer('pp_plugin_nonce', 'pp_plugin_nonce')) {
            return;
        } // get out if we didn't click the Activate button

        $response = self::license_control_instance()->deactivate_license();

        if (is_wp_error($response)) {
            add_settings_error(self::slug, 'deactivation_error', $response->get_error_message());
            return;
        }

        // $response->license will be either "deactivated" or "failed"
        if ($response->license == 'deactivated') {
            ppp_delete_option('pp_license_status');
        }
        wp_redirect(add_query_arg('license', 'deactivated'));
        exit;
    }

    /**
     * License Banner
     */
    public static function license_banner()
    {
        if (pp_is_license_empty()) {
            echo '<div class="banner">' . __('Enter a License Key', 'profilepress') . '</div><br/><br/><br/><br/>';
        } elseif (pp_is_license_valid()) {
            echo '<div class="banner">' . __('You have an active License', 'profilepress') . '</div><br/><br/><br/><br/>';
        } elseif (pp_is_license_invalid()) {
            echo '<div class="banner">' . __('License key is invalid or expired', 'profilepress') . '</div><br/><br/><br/><br/>';
        }
    }

    public static function license_not_active_notice()
    {
        if (!is_super_admin(get_current_user_id())) {
            return;
        }
        if (pp_is_license_valid()) {
            return;
        }
        echo '<div id="message" class="error notice is-dismissible"><p>' . sprintf(__('ProfilePress license is not active or has expired. %s or %s to ensure plugin updates are continually received.', 'profilepress'),
                '<a href="' . admin_url('admin.php?page=pp-license') . '">' . __('Activate', 'profilepress') . '</a>',
                '<a target="_blank" href="https://profilepress.net/account/">' . __('Renew your license', 'profilepress') . '</a>') . '</p></div>';
    }

}

PP_Plugin_License_Page::initialize();