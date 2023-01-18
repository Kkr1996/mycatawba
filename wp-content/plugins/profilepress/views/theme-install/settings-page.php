<?php
ob_start();

/** Theme installation settings page */
class PP_Theme_Installer_Page
{
    /** class constructor  */

    private static $instance;

    /** @var  string Theme name or title */
    private $theme_title;

    /** CLass constructor */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'page_menu'));
    }


    /** Extra settings menu */
    public function page_menu()
    {
        add_submenu_page(
            'pp-config',
            __('Install Theme', 'profilepress') . ' - ProfilePress',
            __('Install Theme', 'profilepress'),
            'manage_options',
            'pp-install-theme',
            array($this, 'theme_install_settings_page'));
    }

    /**
     * Callback function to output the settings page
     */
    public function theme_install_settings_page()
    {
        $install_theme = $this->process_theme_install();

        if (isset($install_theme) && ! is_wp_error($install_theme)) {
            wp_redirect(esc_url_raw(add_query_arg(
                array(
                    'install'     => 'true',
                    'theme_title' => urlencode($this->theme_title),
                    '_wpnonce'    => wp_create_nonce('theme_install_redirect'),
                )
            )));
            exit;
        }
        ?>
        <?php if (isset($_GET['install']) && $_GET['install'] && check_admin_referer('theme_install_redirect')) : ?>
        <h2><?php _e('Theme installation successful.', 'profilepress'); ?></h2>
        <p><?php _e('Theme file:', 'profilepress'); ?><?php echo strip_tags($_GET['theme_title']); ?></p>
        <a title="Return to Theme installer" href="?page=pp-install-theme"><?php _e('Return to Theme installer', 'profilepress'); ?></a>

        <?php
    else :
        self::installer_html_form($install_theme);
    endif;
    }

    /**
     * Instantiate the theme installer class and return the installation status of the theme operation.
     * @return bool|WP_Error
     */
    public function process_theme_install()
    {
        if ( ! isset($_POST['pp_theme_submit'])) {
            return;
        }

        if ( ! check_admin_referer('pp_theme_upload_nonce', '_wpnonce')) {
            return;
        }

        if (is_uploaded_file($_FILES['pp_theme_file']['tmp_name'])) {
            // instantiate the Theme_installer class
            $installer_instance        = new PP_Theme_Installer_Class();
            $theme_installation_result = $installer_instance->initialize($_FILES['pp_theme_file']);
            // grab the title of the theme for reuse
            $this->theme_title = $installer_instance->theme_file_name;

            return $theme_installation_result;
        }
    }

    /**
     * Upload form
     *
     * @param string $install_result
     */
    public static function installer_html_form($install_result)
    {
        ?>
        <div class="upload-theme" style="display: block !important;">
            <p class="install-help">
                <?php if (is_wp_error($install_result)) : ?>
                    <?php echo $install_result->get_error_message(); ?>
                <?php else : ?>
                    <?php _e('If you have a ProfilePress theme in a .zip format, you may install it by uploading it here.', 'profilepress'); ?>
                <?php endif; ?>
            </p>

            <form class="wp-upload-form" method="post" enctype="multipart/form-data">
                <input type="file" name="pp_theme_file">
                <?php wp_nonce_field('pp_theme_upload_nonce'); ?>
                <input class="button" type="submit" name="pp_theme_submit" value="Install Now">
            </form>
        </div>
        <div style="margin: 10px auto; text-align: center;">
            <p class="install-help" style="font-size: 17px">
                <?php printf(
                    __('Get beautiful login, registration, password reset, edit profile forms and front-end profile themes from the %s', 'profilepress'),
                    '<strong><a href="https://profilepress.net/themes" target="_blank">' . __('theme shop', 'profilepress') . '</a>.</strong>');
                ?>
            </p>
        </div>
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
}

PP_Theme_Installer_Page::get_instance();