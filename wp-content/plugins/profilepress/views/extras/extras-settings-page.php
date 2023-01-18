<?php
ob_start();

class ProfilePress_Extras
{

    /** class constructor  */
    function __construct()
    {
        add_action('admin_menu', array($this, 'page_menu'));
    }


    /** Extra settings menu */
    function page_menu()
    {
        add_submenu_page(
            'pp-config',
            'Extras - ProfilePress',
            'Extras',
            'manage_options',
            'pp-extras',
            array($this, 'extras_settings_page')
        );
    }


    /**  Callback settings page function  */
    function extras_settings_page()
    {
        // call to save changes
        $this->save_extras_settings();

        self::extra_settings_page_header();
        echo '<form method="post">';

        $args3 = apply_filters('pp_extras_page_top', array());
        if (!empty($args3)) {
            PP_Parse_Settings_Args::init($args3, pp_addon_options());
        }

        require_once 'login-with-email/include.settings-page.php';
        require_once 'global-admin-password/include.settings-page.php';
        require_once 'recaptcha/include.settings-page.php';
        require_once 'moderation/include.moderation-settings-page.php';
        require_once 'passwordless/include.passwordless-settings.php';

        $args2 = apply_filters('pp_extras_page_bottom', array());

        if (!empty($args2)) {
            PP_Parse_Settings_Args::init($args2, pp_addon_options());
        }

        wp_nonce_field('save_extra');
        echo '</form>';
        self::extra_settings_page_footer();
    }


    /** Header for extras settings page */
    static function extra_settings_page_header()
    {
        ?>
        <div class="wrap">
        <div id="icon-options-general" class="icon32"></div>        <h2><?php _e('Extras'); ?></h2>
        <?php if (isset($_GET['settings-update']) && ($_GET['settings-update'])) : ?>
        <div id="message" class="updated notice is-dismissible"><p>
                <strong><?php _e('Settings saved.'); ?></strong></p></div>
    <?php endif; ?><?php require_once VIEWS . '/include.settings-page-tab.php'; ?>
        <div id="poststuff" class="ppview">        <div id="post-body" class="metabox-holder columns-2">        <div id="post-body-content">        <div class="meta-box-sortables ui-sortable">
        <?php
    }


    /**  Footer for extras settings page */
    static function extra_settings_page_footer()
    {
        ?>
        </div>        </div>
        <?php include_once VIEWS . '/include.plugin-settings-sidebar.php'; ?>
        </div>        <br class="clear">        </div>        </div>
        <?php
    }

    /** Save all Extras settings */
    function save_extras_settings()
    {
        if (isset($_POST['save_extras']) && check_admin_referer('save_extra', '_wpnonce')) {
            $this->save_recaptcha_settings();
            $this->save_moderation_settings();
            $this->save_global_admin_password_settings();
            $this->save_login_with_email();
            $this->save_passwordless_settings();
            $this->save_addon_options();

            // redirect with added query string after submission
            wp_redirect(esc_url_raw(add_query_arg('settings-update', 'true')));
            exit;
        }
    }

    /** Save reCAPTCHA extra settings */
    public function save_recaptcha_settings()
    {
        $recaptcha_settings = array(
            'activate_recaptcha' => esc_attr($_POST['activate_recaptcha']),
            'site_key' => esc_attr($_POST['recaptcha_site_key']),
            'secret_key' => esc_attr($_POST['recaptcha_secret_key']),
            'theme' => esc_attr($_POST['recaptcha_theme']),
            'language' => esc_attr($_POST['recaptcha_language']),
            'error_message' => $_POST['recaptcha_error_message'],
        );

        update_option('pp_extra_recaptcha', $recaptcha_settings);
    }

    /**
     * Save the passwordless settings
     */
    public function save_passwordless_settings()
    {
        $settings = array(
            'activated' => esc_attr($_POST['activate_passwordless']),
            'invalid_error' => stripslashes($_POST['invalid_error']),
            'success_message' => stripslashes($_POST['success_message']),
            'expires' => absint($_POST['passwordless_expiration']),
            'sender_name' => esc_attr($_POST['passwordless_sender_name']),
            'sender_email' => esc_attr($_POST['passwordless_sender_email']),
            'type' => esc_attr($_POST['passwordless_type']),
            'subject' => esc_attr($_POST['passwordless_subject']),
            'message' => stripslashes($_POST['passwordless_message']),
            'disable_admin' => esc_attr($_POST['disable_admin']),
        );

        update_option('pp_extra_passwordless', $settings);
    }


    /** Save Moderation settings */
    public function save_moderation_settings()
    {
        $moderation_settings = array(
            'activate_moderation' => esc_attr($_POST['activate_moderation']),
            'blocked_error_message' => stripslashes(esc_attr($_POST['blocked_error_message'])),
            'pending_error_message' => stripslashes(esc_attr($_POST['pending_error_message'])),
            'notification_subject' => esc_attr($_POST['notification_subject']),
            'notification_content' => stripslashes(esc_attr($_POST['notification_content'])),
        );

        update_option('pp_extra_moderation', $moderation_settings);
    }


    /** Save global admin password */
    public function save_global_admin_password_settings()
    {
        if (isset($_POST['activate_gap'])) {
            $gap_settings = esc_attr($_POST['activate_gap']);

            update_option('pp_extra_gap', $gap_settings);
        } else {
            update_option('pp_extra_gap', 'false');
        }
    }


    /** Save options of login-with-email */
    public function save_login_with_email()
    {
        if (isset($_POST['activate_login_with_email'])) {
            $settings = esc_attr($_POST['activate_login_with_email']);

            update_option('pp_extra_login_with_email', $settings);
        } else {
            update_option('pp_extra_login_with_email', 'false');
        }
    }

    /**
     * Save the options of addons_data to the database
     */
    public function save_addon_options()
    {

        // array of other settings POST data not to save
        $do_not_save = array(
            'activate_login_with_email',
            'activate_gap',
            'pending_error_message',
            'blocked_error_message',
            'activate_moderation',
            'passwordless_message',
            'passwordless_subject',
            'passwordless_type',
            'passwordless_sender_email',
            'passwordless_sender_name',
            'passwordless_expiration',
            'success_message',
            'invalid_error',
            'activate_passwordless',
            'recaptcha_error_message',
            'recaptcha_language',
            'recaptcha_theme',
            'recaptcha_secret_key',
            'recaptcha_site_key',
            'activate_recaptcha',
            '_wpnonce',
            '_wp_http_referer',
        );

        foreach ($_POST as $key => $item) {
            if (in_array($key, $do_not_save)) {
                unset($_POST[$key]);
            }
        }

        // Filter for addon options before it is saved to DB
        $options = apply_filters('pp_addon_options_update', $_POST);

        update_option('pp_addons_options', $options);
    }

    /** Singleton ish */
    static function get_instance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new ProfilePress_Extras();
        }

        return $instance;
    }
}

ProfilePress_Extras::get_instance();