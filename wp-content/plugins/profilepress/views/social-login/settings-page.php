<?php

class ProfilePress_Social_Login_Settings
{

    private static $instance;

    /** class constructor  */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'page_menu'));
    }


    /** Extra settings menu */
    public function page_menu()
    {
        add_submenu_page(
            'pp-config',
            __('Social Login Settings', 'profilepress') . ' - ProfilePress',
            __('Social Login', 'profilepress'),
            'manage_options',
            'pp-social-login',
            array($this, 'social_login_settings')
        );
    }

    public function social_login_settings()
    {
        $this->save_social_login_settings();
        self::page_header();
        echo '<form method="post">';
        require 'include.application-settings-page.php';
        wp_nonce_field('social_login');
        echo '</form>';
        self::page_footer();
    }

    /** Save all Extras settings */
    public function save_social_login_settings()
    {

        if (isset($_POST['save_social_login']) && check_admin_referer('social_login', '_wpnonce')) {
            $social_login_settings = array(
                'facebook_id'              => sanitize_text_field($_POST['facebook_id']),
                'facebook_secret'          => sanitize_text_field($_POST['facebook_secret']),
                'twitter_consumer_key'     => sanitize_text_field($_POST['twitter_consumer_key']),
                'twitter_consumer_secret'  => sanitize_text_field($_POST['twitter_consumer_secret']),
                'google_client_id'         => sanitize_text_field($_POST['google_client_id']),
                'google_client_secret'     => sanitize_text_field($_POST['google_client_secret']),
                'linkedin_consumer_key'    => sanitize_text_field($_POST['linkedin_consumer_key']),
                'linkedin_consumer_secret' => sanitize_text_field($_POST['linkedin_consumer_secret']),
                'github_client_id'         => sanitize_text_field($_POST['github_client_id']),
                'github_client_secret'     => sanitize_text_field($_POST['github_client_secret']),
                'vk_application_id'        => sanitize_text_field($_POST['vk_application_id']),
                'vk_secure_key'            => sanitize_text_field($_POST['vk_secure_key']),

            );

            update_option('pp_social_login', $social_login_settings);
            wp_redirect(esc_url_raw(add_query_arg('settings-update', 'true')));
            exit;
        }
    }

    /** Header for settings page */
    public static function page_header()
    {
        ?>
        <div class="wrap">
        <div id="icon-options-general" class="icon32"></div>
        <h2><?php _e('Social Login Configuration', 'profilepress'); ?></h2>

        <?php if (isset($_GET['settings-update']) && ($_GET['settings-update'])) : ?>
        <div id="message" class="updated notice is-dismissible"><p>
                <strong><?php _e('Settings saved.', 'profilepress'); ?></strong></p>
        </div>
    <?php endif; ?>

        <?php require_once VIEWS . '/include.settings-page-tab.php'; ?>
        <div id="poststuff" class="ppview">        <div id="post-body" class="metabox-holder columns-2">        <div id="post-body-content">        <div class="meta-box-sortables ui-sortable">

        <?php
    }

    /**  Footer for settings page */
    public static function page_footer()
    {
        ?>
        </div>        </div>
        <?php include_once VIEWS . '/include.plugin-settings-sidebar.php'; ?>
        </div>        <br class="clear">        </div>        </div>
        <?php
    }

    /**
     * Singleton poop
     *
     * @return ProfilePress_Social_Login_Settings
     */
    public static function get_instance()
    {
        if ( ! isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}

ProfilePress_Social_Login_Settings::get_instance();