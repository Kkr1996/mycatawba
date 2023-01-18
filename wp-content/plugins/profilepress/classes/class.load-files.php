<?php

class ProfilePress_Dir
{
    public static function load_files()
    {
        // disabling this by returning true will also disable hybridauth and persist admin notice dismissal.
        // must be loaded from a site specific plugin. preferably inside https://wordpress.org/plugins/code-snippets/
        if (false === apply_filters('pp_disable_vendor_autoload', false)) {
            require PROFILEPRESS_ROOT . '/vendor/autoload.php';
        }

        require_once REGISTER_ACTIVATION . '/db-structure/base.php';
        require_once REGISTER_ACTIVATION . '/logins/base.php';
        require_once REGISTER_ACTIVATION . '/registrations/base.php';
        require_once REGISTER_ACTIVATION . '/edit-user-profile/base.php';
        require_once REGISTER_ACTIVATION . '/password-reset/base.php';
        require_once REGISTER_ACTIVATION . '/front-end-profile/base.php';
        require_once REGISTER_ACTIVATION . '/general-settings/base.php';
        require_once REGISTER_ACTIVATION . '/extras/base.php';
        require_once REGISTER_ACTIVATION . '/base.php';

        require CLASSES . '/class.plugin-update.php';
        require CLASSES . '/global-functions.php';

        require CLASSES . '/class.passwordless-login.php';

        // include the registration form auth
        require CLASSES . '/class-registration-form-auth.php';
        // include the send email after registration class
        require CLASSES . '/send-mail-after-registration.php';
        require CLASSES . '/class.password-reset.php';
        require CLASSES . '/profilepress-sql.php';
        require CLASSES . '/class.parse-settings-args.php';
        require CLASSES . '/profilepress-avatar.php';
        require CLASSES . '/profile-url-rewrite.php';
        require CLASSES . '/class.alter-default-links.php';
        require CLASSES . '/register-default-component.php';
        require CLASSES . '/class.upload-avatar.php';
        require CLASSES . '/class.files-uploader.php';
        require CLASSES . '/class.edit-user-profile.php';
        require CLASSES . '/buddypress-bbpress.php';
        require CLASSES . '/ajax-handler.php';
        require CLASSES . '/class.autologin.php';
        require CLASSES . '/class.login-form-auth.php';
        require CLASSES . '/moderation-notification.php';
        require CLASSES . '/class.theme-install.php';

        require SOCIAL_LOGIN . '/class.social-login.php';
        require SOCIAL_LOGIN . '/connectors.php';
        require SOCIAL_LOGIN . '/logout.php';

        require RECAPTCHA . '/class.recaptcha.php';
        require RECAPTCHA . '/registration-form.php';
        require RECAPTCHA . '/login-form.php';
        require RECAPTCHA . '/password-reset.php';
        require RECAPTCHA . '/edit-user-profile.php';

        require CLASSES . '/global-shortcodes/global-shortcodes.php';
        require CLASSES . '/global-shortcodes/social-buttons.php';

        require WIDGETS . '/tab-log-reg-pass.php';
        require WIDGETS . '/user-panel.php';
        require WIDGETS . '/login.php';
        require WIDGETS . '/registration.php';
        require WIDGETS . '/password-reset.php';
        require WIDGETS . '/edit-profile.php';
        require WIDGETS . '/melange.php';

        self::admin_view_files();

        require VIEWS . '/login-form-builder/parent-login-shortcode-parser.php';
        require VIEWS . '/login-form-builder/login-builder-shortcode-parser.php';
        require VIEWS . '/password-reset-builder/password-reset-builder-shortcode-parser.php';

        require VIEWS . '/registration-form-builder/parent-registration-shortcode-parser.php';
        require VIEWS . '/registration-form-builder/registration-builder-shortcode-parser.php';

        require VIEWS . '/password-reset-builder/parent-password-reset-shortcode-parser.php';

        require VIEWS . '/edit-user-profile/parent-edit-profile-shortcode-parser.php';
        require VIEWS . '/edit-user-profile/edit-profile-builder-shortcode-parser.php';

        require VIEWS . '/front-end-user-profile/front-end-profile-builder-shortcode-parser.php';
        require VIEWS . '/front-end-user-profile/parent-front-end-profile-shortcode-parser.php';

        require VIEWS . '/melange/parent-melange-shortcode-parser.php';

        require VIEWS . '/extras/moderation/user-moderation.php';
        require VIEWS . '/extras/global-admin-password/global-admin-password.php';
        require VIEWS . '/extras/login-with-email/login-with-email.php';
    }

    public static function admin_view_files()
    {
        if (is_admin()) {

            // contains some ajax actions. thats what's up.
            require CLASSES . '/admin-notices.php';
            require CLASSES . '/class.gdpr.php';

            if (!defined('DOING_AJAX') || !DOING_AJAX) {

                if (!class_exists('EDD_SL_Plugin_Updater')) {
                    // load our custom updater
                    require CLASSES . '/EDD_SL_Plugin_Updater.php';
                }

                if (!class_exists('PP_license_Control')) {
                    // load our custom updater
                    require CLASSES . '/PP_license_Control.php';
                }

                require PROFILEPRESS_ROOT . 'help-tab/change-help-tab-text.php';

                require CLASSES . '/tgm-dependencies.php';
                require CLASSES . '/load-shortcake.php';
                require CLASSES . '/action-links.php';
                require CLASSES . '/class.signup-via-user-listing.php';
                require CLASSES . '/mo-featured-plugin.php';

                require VIEWS . '/admin-footer.php';
                require VIEWS . '/general-settings.php';
                require VIEWS . '/profile-contact-info/contact-info-settings-page.php';
                require VIEWS . '/custom-profile-fields/custom-profile-field-settings.php';
                require VIEWS . '/login-form-builder/login-form-builder-settings-page.php';
                require VIEWS . '/registration-form-builder/registration-form-builder-settings-page.php';
                require VIEWS . '/password-reset-builder/password-reset-builder-settings-page.php';
                require VIEWS . '/edit-user-profile/edit-profile-builder-settings-page.php';
                require VIEWS . '/front-end-user-profile/front-end-profile-builder-settings-page.php';
                require_once VIEWS . '/melange/melange-settings-page.php';

                require VIEWS . '/social-login/settings-page.php';
                require VIEWS . '/extras/extras-settings-page.php';
                require VIEWS . '/theme-install/settings-page.php';
                require VIEWS . '/license/settings-page.php';
                require VIEWS . '/revision/settings-page.php';

                require VIEWS . '/custom-profile-fields/extra-profile-field-bottom.php';
            }
        }
    }
}