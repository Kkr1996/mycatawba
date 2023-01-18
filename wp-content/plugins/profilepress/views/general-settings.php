<?php

/** General settings page **/
class PP_General_settings_page
{

    static $instance;
    private $db_settings_data;

    /** class constructor */
    public function __construct()
    {
        $this->db_settings_data = pp_db_data();
        add_action('admin_menu', array($this, 'register_general_settings_page'), 1);
    }

    public function register_general_settings_page()
    {
        add_menu_page(
            __('ProfilePress - Ultimate WordPress Account Manager', 'profilepress'),
            'ProfilePress',
            'manage_options',
            'pp-config',
            array(
                $this,
                'general_settings_page_function',
            ),
            ASSETS_URL . '/images/dashicon.png',
            '80.0015'
        );

        add_submenu_page(
            'pp-config',
            __('General Settings - ProfilePress', 'profilepress'),
            __('Settings', 'profilepress'),
            'manage_options',
            'pp-config',
            array($this, 'general_settings_page_function')
        );

    }

    public function general_settings_page_function()
    { ?>
        <div class="wrap">
            <div id="icon-options-general" class="icon32"></div>
            <h2><?php _e('General Settings - ProfilePress', 'profilepress'); ?></h2>
            <?php if (isset($_GET['settings-update']) && $_GET['settings-update']) { ?>
                <div id="message" class="updated notice is-dismissible"><p>
                        <strong><?php _e('Settings saved', 'profilepress'); ?>.</strong>
                    </p></div>
                <?php
            }

            $db_settings_data = $this->db_settings_data;

            $this->save_settings_data($_POST);
            ?>

            <?php require_once 'include.settings-page-tab.php'; ?>

            <div id="poststuff" class="ppview">

                <div style="margin: 10px"><a href="#" id="pp-general-expand" class="button"><?php _e('Expand All', 'profilepress');?></a> <a href="#" id="pp-general-collapse" class="button"><?php _e('Collapse All', 'profilepress');?></a></div>

                <div id="post-body" class="metabox-holder columns-2">

                    <!-- main content -->
                    <div id="post-body-content">

                        <div class="meta-box-sortables ui-sortable pp-general">
                            <form method="post">
                                <div class="postbox">
                                    <button type="button" class="handlediv button-link" aria-expanded="true">
                                        <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
                                        <span class="toggle-indicator" aria-hidden="true"></span>
                                    </button>
                                    <h3 class="hndle ui-sortable-handle">
                                        <span><?php _e('Global Settings', 'profilepress'); ?></span></h3>

                                    <div class="inside">
                                        <table class="form-table">
                                            <tr>
                                                <th scope="row"><?php _e('Password-reset Page', 'profilepress'); ?></th>
                                                <td><?php
                                                    $lostp_args = array(
                                                        'name' => 'set_lost_password_url',
                                                        'show_option_none' => 'Select...',
                                                        'selected' => isset($db_settings_data['set_lost_password_url']) ? $db_settings_data['set_lost_password_url'] : ''
                                                    );

                                                    wp_dropdown_pages($lostp_args); ?>

                                                    <p class="description">
                                                        <?php echo sprintf(__('Select the page you wish to make WordPress default "Lost Password page". %s This should be the page that contains the %s', 'profilepress'),
                                                            '<br/><strong>' . __('Note:', 'profilepress') . '</strong>',
                                                            '<a href="?page=pp-password-reset"><strong>' . __('password reset  shortcode', 'profilepress') . '</strong></a>');
                                                        ?>
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row"><?php _e('Login Page', 'profilepress'); ?></th>
                                                <td><?php
                                                    $login_args = array(
                                                        'name' => 'set_login_url',
                                                        'show_option_none' => 'Select...',
                                                        'selected' => isset($db_settings_data['set_login_url']) ? $db_settings_data['set_login_url'] : ''
                                                    );

                                                    wp_dropdown_pages($login_args); ?>
                                                    <p class="description">
                                                        <?php echo sprintf(__('Select the page you wish to make WordPress default Login page. %s This should be the page that contains the %s', 'profilepress'),
                                                            '<br/><strong>' . __('Note:', 'profilepress') . '</strong>',
                                                            '<a href="?page=pp-login"><strong>' . __('login form  shortcode', 'profilepress') . '</strong></a>');
                                                        ?>
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr id="registration_page">
                                                <th scope="row"><?php _e('Registration Page', 'profilepress'); ?></th>
                                                <td><?php
                                                    $registration_args = array(
                                                        'name' => 'set_registration_url',
                                                        'show_option_none' => 'Select...',
                                                        'selected' => isset($db_settings_data['set_registration_url']) ? $db_settings_data['set_registration_url'] : ''
                                                    );

                                                    wp_dropdown_pages($registration_args); ?>
                                                    <p class="description">
                                                        <?php echo sprintf(__('Select the page you wish to make WordPress default Registration page. %s This should be the page that contains the %s', 'profilepress'),
                                                            '<br/><strong>' . __('Note:', 'profilepress') . '</strong>',
                                                            '<a href="?page=pp-registration"><strong>' . __('registration form  shortcode', 'profilepress') . '</strong></a>');
                                                        ?>
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr id="edit_user_profile_page">
                                                <th scope="row"><?php _e('Edit User Profile Page', 'profilepress'); ?></th>
                                                <td><?php
                                                    $edit_profile_args = array(
                                                        'name' => 'edit_user_profile_url',
                                                        'show_option_none' => 'Select...',
                                                        'selected' => isset($db_settings_data['edit_user_profile_url']) ? $db_settings_data['edit_user_profile_url'] : ''
                                                    );

                                                    wp_dropdown_pages($edit_profile_args); ?>
                                                    <p class="description">
                                                        <?php echo sprintf(__('Select the page you wish to make WordPress default Edit profile page. %s This should be the page that contains the %s', 'profilepress'),
                                                            '<br/><strong>' . __('Note:', 'profilepress') . '</strong>',
                                                            '<a href="?page=pp-edit-profile"><strong>' . __('edit user profile  shortcode', 'profilepress') . '</strong></a>');
                                                        ?>
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr id="disable_ajax_mode">
                                                <th scope="row"><?php _e('Disable Ajax Mode', 'profilepress'); ?></th>
                                                <td>
                                                    <label for="disable_ajax_mode"><strong><?php _e('Disable', 'profilepress'); ?></strong></label>
                                                    <input type="checkbox" id="disable_ajax_mode" name="disable_ajax_mode" value="yes" <?php isset($db_settings_data['disable_ajax_mode']) ? checked($db_settings_data['disable_ajax_mode'], 'yes') : ''; ?>>

                                                    <p class="description"><?php _e('Check this box to disable ajax behaviour(whereby forms do not require page reload when submitted) in forms.', 'profilepress'); ?></p>
                                                </td>
                                            </tr>

                                            <tr id="edit_user_profile_page">
                                                <th scope="row"><?php _e('Remove Data on Uninstall?', 'profilepress'); ?></th>
                                                <td>
                                                    <label for="remove_plugin_data"><strong><?php _e('Delete', 'profilepress'); ?></strong></label>
                                                    <input type="checkbox" id="remove_plugin_data" name="remove_plugin_data" value="yes" <?php isset($db_settings_data['remove_plugin_data']) ? checked($db_settings_data['remove_plugin_data'], 'yes') : ''; ?>>

                                                    <p class="description"><?php _e('Check this box if you would like ProfilePress to completely remove all of its data when the plugin is deleted.', 'profilepress'); ?></p>
                                                </td>
                                            </tr>
                                        </table>
                                        <p>
                                            <?php wp_nonce_field('general_settings_nonce'); ?>
                                            <input class="button-primary" type="submit" name="general_settings_submit"
                                                   value="<?php _e('Save All Changes', 'profilepress'); ?>">
                                        </p>
                                    </div>
                                </div>


                                <div class="postbox">
                                    <button type="button" class="handlediv button-link" aria-expanded="true">
                                        <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
                                        <span class="toggle-indicator" aria-hidden="true"></span>
                                    </button>
                                    <h3 class="hndle ui-sortable-handle">
                                        <span><?php _e('Front-end Profile Settings', 'profilepress'); ?></span></h3>

                                    <div class="inside">
                                        <table class="form-table">
                                            <tr>
                                                <th scope="row"><?php _e('Page with User Profile Shortcode', 'profilepress'); ?></th>
                                                <td>
                                                    <?php
                                                    $set_user_profile_shortcode_args = array(
                                                        'name' => 'set_user_profile_shortcode',
                                                        'show_option_none' => 'Select...',
                                                        'selected' => isset($db_settings_data['set_user_profile_shortcode']) ? $db_settings_data['set_user_profile_shortcode'] : ''
                                                    );

                                                    wp_dropdown_pages($set_user_profile_shortcode_args); ?>

                                                    <p class="description">
                                                        <?php echo sprintf(__('Select the page that contains your %s', 'profilepress'), '<a href="?page=pp-user-profile">' . __('Front-end user profile shortcode', 'profilepress') . '</a>'); ?>
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?php _e('Profile Slug', 'profilepress'); ?></th>
                                                <td>
                                                    <input type="text" name="set_user_profile_slug" class="regular-text code" value="<?php echo isset($db_settings_data['set_user_profile_slug']) ? $db_settings_data['set_user_profile_slug'] : 'profile' ?>"/>

                                                    <p class="description">
                                                        <?php printf(__('Enter your preferred profile URL slug. Default to "profile" if empty. If slug is "profile", URL becomes %s where "john" is a user\'s username', 'profilepress'), '<strong>' . home_url() . '/profile/john</strong>'); ?>
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row"><?php _e('Convert Authors to Profile', 'profilepress'); ?></th>
                                                <td>
                                                    <label for="author_slug_to_profile"><strong><?php _e('Enable option', 'profilepress'); ?></strong></label>
                                                    <input id="author_slug_to_profile" name="author_slug_to_profile" type="checkbox" value="on" <?php isset($db_settings_data['author_slug_to_profile']) ? checked($db_settings_data['author_slug_to_profile'], 'on') : ''; ?>/>

                                                    <p class="description">
                                                        <?php echo sprintf(__('Redirect author\'s page %s to his front-end profile %s', 'profilepress'), '<strong>(' . home_url() . '/author/admin)</strong>', '<strong>(' . home_url() . '/profile/admin)</strong>'); ?>
                                                    </p>
                                                </td>
                                            </tr>

                                        </table>
                                        <p>
                                            <?php wp_nonce_field('general_settings_nonce'); ?>
                                            <input class="button-primary" type="submit" name="general_settings_submit" value="<?php _e('Save All Changes', 'profilepress'); ?>">
                                        </p>
                                    </div>
                                </div>


                                <?php if(class_exists('BuddyPress')) : ?>
                                <div class="postbox">
                                    <button type="button" class="handlediv button-link" aria-expanded="true">
                                        <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
                                        <span class="toggle-indicator" aria-hidden="true"></span>
                                    </button>
                                    <h3 class="hndle ui-sortable-handle">
                                        <span><?php _e('BuddyPress Settings', 'profilepress'); ?></span></h3>

                                    <div class="inside">
                                        <table class="form-table">
                                            <tr>
                                                <th scope="row"><?php _e('Registration Page', 'profilepress'); ?></th>
                                                <td>
                                                    <label for="redirect_bp_registration_page"><strong><?php _e('Check to enable', 'profilepress'); ?></strong></label>
                                                    <input id="redirect_bp_registration_page" name="redirect_bp_registration_page" type="checkbox" value="yes" <?php isset($db_settings_data['redirect_bp_registration_page']) ? checked($db_settings_data['redirect_bp_registration_page'], 'yes') : ''; ?>/>

                                                    <p class="description"><?php echo sprintf(__('Check to redirect BuddyPress registration page to your selected %s', 'profilepress'), '<a href="#registration_page">custom registration page</a>'); ?></p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row"><?php _e('Override Avatar', 'profilepress'); ?></th>
                                                <td>
                                                    <label for="override_bp_avatar"><strong><?php _e('Check to enable', 'profilepress'); ?></strong></label>
                                                    <input id="override_bp_avatar" name="override_bp_avatar" type="checkbox" value="yes" <?php isset($db_settings_data['override_bp_avatar']) ? checked($db_settings_data['override_bp_avatar'], 'yes') : ''; ?>/>

                                                    <p class="description"><?php _e('Check to override BuddyPress users uploaded avatars with that of ProfilePress.', 'profilepress'); ?></p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row"><?php _e('Override Profile URL', 'profilepress'); ?></th>
                                                <td>
                                                    <label for="override_bp_profile_url"><strong><?php _e('Check to enable', 'profilepress'); ?></strong></label>
                                                    <input id="override_bp_profile_url" name="override_bp_profile_url" type="checkbox" value="yes" <?php isset($db_settings_data['override_bp_profile_url']) ? checked($db_settings_data['override_bp_profile_url'], 'yes') : ''; ?>/>

                                                    <p class="description"><?php _e('Check to change the profile URL of BuddyPress users to ProfilePress front-end profile.', 'profilepress'); ?></p>
                                                </td>
                                            </tr>
                                        </table>
                                        <p>
                                            <?php wp_nonce_field('general_settings_nonce'); ?>
                                            <input class="button-primary" type="submit" name="general_settings_submit" value="<?php _e('Save All Changes', 'profilepress'); ?>">
                                        </p>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if(class_exists('bbPress')) : ?>
                                <div class="postbox">
                                    <button type="button" class="handlediv button-link" aria-expanded="true">
                                        <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
                                        <span class="toggle-indicator" aria-hidden="true"></span>
                                    </button>
                                    <h3 class="hndle ui-sortable-handle">
                                        <span><?php _e('BbPress Settings', 'profilepress'); ?></span></h3>

                                    <div class="inside">
                                        <table class="form-table">
                                            <tr>
                                                <th scope="row"><?php _e('Override Profile URL', 'profilepress'); ?></th>
                                                <td>
                                                    <label for="override_bbp_profile_url"><strong><?php _e('Check to enable', 'profilepress'); ?></strong></label>
                                                    <input id="override_bbp_profile_url" name="override_bbp_profile_url" type="checkbox" value="yes" <?php isset($db_settings_data['override_bbp_profile_url']) ? checked($db_settings_data['override_bbp_profile_url'], 'yes') : ''; ?>/>

                                                    <p class="description"><?php _e('Check to change bbPress profile URL to ProfilePress front-end profile.', 'profilepress'); ?></p>
                                                </td>
                                            </tr>
                                        </table>
                                        <p>
                                            <?php wp_nonce_field('general_settings_nonce'); ?>
                                            <input class="button-primary" type="submit" name="general_settings_submit" value="<?php _e('Save All Changes', 'profilepress'); ?>">
                                        </p>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <div class="postbox">
                                    <button type="button" class="handlediv button-link" aria-expanded="true">
                                        <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
                                        <span class="toggle-indicator" aria-hidden="true"></span>
                                    </button>
                                    <h3 class="hndle ui-sortable-handle">
                                        <span><?php _e('Redirection', 'profilepress'); ?></span></h3>

                                    <div class="inside">
                                        <table class="form-table">
                                            <tr>
                                                <th scope="row"><?php _e('Log out', 'profilepress'); ?></th>
                                                <td><?php
                                                    $log_out_args = array(
                                                        'name' => 'set_log_out_url',
                                                        'show_option_none' => 'Default..',
                                                        'option_none_value' => 'current_view_page',
                                                        'selected' => isset($db_settings_data['set_log_out_url']) ? $db_settings_data['set_log_out_url'] : ''
                                                    );

                                                    wp_dropdown_pages($log_out_args);
                                                    ?>
                                                    <input placeholder="Custom URL Here" name="custom_url_log_out" type="text" class="regular-text code" value="<?php echo isset($db_settings_data['custom_url_log_out']) ? $db_settings_data['custom_url_log_out'] : ''; ?>">
                                                    <p class="description"><?php _e('Select the page users will be redirected to after logout. To redirect to a custom URL instead of a selected page, enter the URL in input field directly above this description.', 'profilepress'); ?></p>
                                                    <p class="description"><?php _e('Leave the "custom URL" field empty to fallback to the selected page.', 'profilepress'); ?></p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row"><?php _e('Login', 'profilepress'); ?></th>
                                                <td><?php
                                                    $set_reg_args = array(
                                                        'echo' => 0,
                                                        'name' => 'set_login_redirect',
                                                        'selected' => isset($db_settings_data['set_login_redirect']) ? $db_settings_data['set_login_redirect'] : ''
                                                    );

                                                    $dropdown = wp_dropdown_pages($set_reg_args);
                                                    $addition = '<option value="current_page"' . selected($db_settings_data['set_login_redirect'], 'current_page', false) . '>' . __('Currently viewed page', 'profilepress') . '</option>';
                                                    $addition .= '<option value="dashboard"' . selected($db_settings_data['set_login_redirect'], 'dashboard', false) . '>' . __('WordPress Dashboard', 'profilepress') . '</option>';
                                                    echo pp_append_option_to_select($addition, $dropdown);
                                                    ?>
                                                    <input placeholder="<?php _e('Custom URL Here', 'profilepress'); ?>" name="custom_url_login_redirect" type="text" class="regular-text code" value="<?php echo isset($db_settings_data['custom_url_login_redirect']) ? $db_settings_data['custom_url_login_redirect'] : ''; ?>">
                                                    <p class="description"><?php _e('Select the page or custom URL users will be redirected to after login. To redirect to a custom URL instead of a selected page, enter the URL in input field directly above this description.', 'profilepress'); ?></p>
                                                    <p class="description"><?php _e('Leave the "custom URL" field empty to fallback to the selected page.', 'profilepress'); ?></p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row"><?php _e('Password Reset', 'profilepress'); ?></th>
                                                <td><?php
                                                    $set_reg_args = array(
                                                        'echo' => 0,
                                                        'show_option_none' => 'Default..',
                                                        'option_none_value' => 'no_redirect',
                                                        'name' => 'set_password_reset_redirect',
                                                        'selected' => isset($db_settings_data['set_password_reset_redirect']) ? $db_settings_data['set_password_reset_redirect'] : ''
                                                    );

                                                    $dropdown = wp_dropdown_pages($set_reg_args);
                                                    echo $dropdown;
                                                    ?>
                                                    <input placeholder="<?php _e('Custom URL Here', 'profilepress'); ?>" name="custom_url_password_reset_redirect" type="text" class="regular-text code" value="<?php echo isset($db_settings_data['custom_url_password_reset_redirect']) ? $db_settings_data['custom_url_password_reset_redirect'] : ''; ?>">
                                                    <p class="description"><?php _e('Select the page or custom URL users will be redirected to after they successfully reset or change their password. To redirect to a custom URL instead of a selected page, enter the URL in input field directly above this description.', 'profilepress'); ?></p>
                                                    <p class="description"><?php _e('Leave the "custom URL" field empty to fallback to the selected page.', 'profilepress'); ?></p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row"><?php _e('Global Redirect', 'profilepress'); ?></th>
                                                <td><?php
                                                    $set_reg_args = array(
                                                        'name' => 'global_redirect',
                                                        'show_option_none' => 'Deactivate Global Redirect',
                                                        'option_none_value' => 'deactivate',
                                                        'selected' => isset($db_settings_data['global_redirect']) ? $db_settings_data['global_redirect'] : ''
                                                    );

                                                    wp_dropdown_pages($set_reg_args); ?>
                                                    <p class="description"><?php _e('Selecting a page from the dropdown menu above will cause all non-logged in users to be redirected to the selected page.', 'profilepress'); ?></p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row"><?php _e('Edit User Profile', 'profilepress'); ?></th>
                                                <td>
                                                    <label for="redirect_default_edit_profile"><strong><?php _e('Activate', 'profilepress'); ?></strong></label>
                                                    <input type="checkbox" id="redirect_default_edit_profile" name="redirect_default_edit_profile_to_custom" value="yes" <?php isset($db_settings_data['redirect_default_edit_profile_to_custom']) ? checked($db_settings_data['redirect_default_edit_profile_to_custom'], 'yes') : ''; ?>>

                                                    <p class="description">
                                                        <?php _e('Redirect', 'profilepress'); ?>
                                                        <a href="<?php echo site_url() ?>/wp-admin/profile.php"><?php _e('default WordPress profile', 'profilepress'); ?></a> <?php _e('to the custom', 'profilepress'); ?>
                                                        <a href="#edit_user_profile_page"><?php _e('Edit Profile page', 'profilepress'); ?></a>.
                                                    </p>
                                                </td>
                                            </tr>

                                        </table>
                                        <p>
                                            <?php wp_nonce_field('general_settings_nonce'); ?>
                                            <input class="button-primary" type="submit" name="general_settings_submit" value="<?php _e('Save All Changes', 'profilepress'); ?>">
                                        </p>
                                    </div>
                                </div>

                                <div class="postbox">
                                    <button type="button" class="handlediv button-link" aria-expanded="true">
                                        <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
                                        <span class="toggle-indicator" aria-hidden="true"></span>
                                    </button>
                                    <h3 class="hndle ui-sortable-handle">
                                        <span><?php _e('Registration Settings', 'profilepress'); ?></span></h3>

                                    <div class="inside">
                                        <table class="form-table">
                                            <tr>
                                                <th scope="row"><?php _e('Auto-login after registration', 'profilepress'); ?></th>
                                                <td>
                                                    <label for="pp_auto_login"><strong>Enable auto-login</strong></label>
                                                    <input id="pp_auto_login" name="set_auto_login_after_reg" type="checkbox" value="on" <?php isset($db_settings_data['set_auto_login_after_reg']) ? checked($db_settings_data['set_auto_login_after_reg'], 'on') : ''; ?>/>

                                                    <p class="description">
                                                        <?php _e('Check this option to automatically login users after successful registration.', 'profilepress'); ?>
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row"><?php _e('Send users welcome message', 'profilepress'); ?></th>
                                                <td>
                                                    <label for="pp_set_welcome_message_after_reg"><strong><?php _e('Enable welcome message', 'profilepress'); ?></strong></label>
                                                    <input id="pp_set_welcome_message_after_reg"
                                                           name="set_welcome_message_after_reg"
                                                           type="checkbox"
                                                           value="on" <?php isset($db_settings_data['set_welcome_message_after_reg']) ? checked($db_settings_data['set_welcome_message_after_reg'], 'on') : ''; ?>/>


                                                    <p class="description">
                                                        <?php _e('Check this option to send users a welcome message immediately after registration', 'profilepress'); ?>
                                                        .
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                        <p>
                                            <?php wp_nonce_field('general_settings_nonce'); ?>
                                            <input class="button-primary" type="submit" name="general_settings_submit"
                                                   value="<?php _e('Save All Changes', 'profilepress'); ?>">
                                        </p>
                                    </div>
                                </div>

                                <div class="postbox">
                                    <button type="button" class="handlediv button-link" aria-expanded="true">
                                        <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
                                        <span class="toggle-indicator" aria-hidden="true"></span>
                                    </button>
                                    <h3 class="hndle ui-sortable-handle">
                                        <span><?php _e('Welcome Message Settings', 'profilepress'); ?></span></h3>

                                    <div class="inside">
                                        <table class="form-table">

                                            <tr>
                                                <th scope="row"><?php _e('Sender Name', 'profilepress'); ?></th>
                                                <td>
                                                    <input type="text" name="welcome_message_sender_name" class="regular-text code" value="<?php echo isset($db_settings_data['welcome_message_sender_name']) ? $db_settings_data['welcome_message_sender_name'] : pp_site_title(); ?>"/>

                                                    <p class="description">
                                                        <?php _e('Enter your Website name or whatever name the user will see as the sender of the welcome message. If empty, website title will be used.', 'profilepress'); ?>
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row">Sender Email Address</th>
                                                <td>
                                                    <input type="text" name="welcome_message_sender_email" class="regular-text code" value="<?php echo isset($db_settings_data['welcome_message_sender_email']) ? $db_settings_data['welcome_message_sender_email'] : pp_admin_email(); ?>"/>

                                                    <p class="description">
                                                        Enter the email address the user will see as the sender of the
                                                        <strong>welcome message</strong>.<br/> If empty, E-mail address
                                                        of blog administrator will be used.
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr id="welcome_message_type">
                                                <th scope="row">Message content-type</th>
                                                <td>
                                                    <select name="welcome_message_type" id="wlcm_msg_type">
                                                        <option value="plain-text" <?php isset($db_settings_data['welcome_message_type']) ? selected($db_settings_data['welcome_message_type'], 'plain-text') : '' ?>>
                                                            Plain text
                                                        </option>
                                                        <option value="html" <?php isset($db_settings_data['welcome_message_type']) ? selected($db_settings_data['welcome_message_type'], 'html') : '' ?>>
                                                            HTML
                                                        </option>
                                                    </select>


                                                    <p class="description">Select the content type to be use for the
                                                        <strong>welcome message</strong> email.</p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row">
                                                    <label for="pp_welcome_message_subject"><?php _e('Message subject', 'profilepress'); ?></label>
                                                </th>
                                                <td>
                                                    <textarea name="pp_welcome_message_subject" id="pp_welcome_message_subject"><?php echo isset($db_settings_data['pp_welcome_message_subject']) ? $db_settings_data['pp_welcome_message_subject'] : ''; ?></textarea>

                                                    <p class="description">
                                                        Enter the subject or title for the
                                                        <strong>Welcome message</strong> mail.</p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row">
                                                    <label for="pp_welcome_message_after_reg">Welcome Message</label>
                                                </th>
                                                <td>
                                                    <textarea class="widefat" rows="10" name="pp_welcome_message_after_reg" id="pp_welcome_message_after_reg"><?php echo isset($db_settings_data['pp_welcome_message_after_reg']) ? $db_settings_data['pp_welcome_message_after_reg'] : ''; ?></textarea>

                                                    <p class="description">Setup the
                                                        <strong>welcome message</strong> that will be sent to the user
                                                        after successful registration.
                                                    </p>

                                                    <p>HTML get parsed only when
                                                        <a href="#welcome_message_type"><strong><em>email
                                                                    content-type </em></strong></a> is set as
                                                        <strong>HTML</strong></p>
                                                    <br/>

                                                    <p>You can also use the following placeholders: <br/>
                                                        <strong>{{username}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;Username
                                                        of the registered user.
                                                        <br/>
                                                        <strong>{{email}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;Email
                                                        address
                                                        of the registered user.
                                                        <br/>
                                                        <strong>{{password}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;Password
                                                        of the registered user.
                                                        <br/>
                                                        <strong>{{site_title}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;Site
                                                        title as defined in
                                                        <a target="_blank" href="<?php echo site_url() . '/wp-admin/options-general.php'; ?>">General
                                                            Settings</a>
                                                        <br/>
                                                        <strong>{{first_name}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;First
                                                        Name entered by user on registration.<br/>
                                                        <strong>{{last_name}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;Last
                                                        Name entered by user on registration.
                                                        <br/>
                                                        <strong>{{password_reset_link}}</strong>&nbsp;&nbsp;&nbsp; -
                                                        &nbsp;&nbsp;Link to reset password.
                                                        <br/><br/>
                                                    </p>
                                                </td>
                                            </tr>

                                        </table>
                                        <p>
                                            <?php wp_nonce_field('general_settings_nonce'); ?>
                                            <input class="button-primary" type="submit" name="general_settings_submit" value="<?php _e('Save All Changes', 'profilepress'); ?>">
                                        </p>
                                    </div>
                                </div>

                                <div class="postbox">
                                    <button type="button" class="handlediv button-link" aria-expanded="true">
                                        <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
                                        <span class="toggle-indicator" aria-hidden="true"></span>
                                    </button>
                                    <h3 class="hndle ui-sortable-handle"><span>Password Reset Email Settings</span></h3>

                                    <div class="inside">
                                        <table class="form-table">

                                            <tr>
                                                <th scope="row">Email Sender</th>
                                                <td>
                                                    <input type="text" name="password_reset_sender_name" class="regular-text code" value="<?php echo isset($db_settings_data['password_reset_sender_name']) ? $db_settings_data['password_reset_sender_name'] : ''; ?>"/>

                                                    <p class="description">
                                                        Enter your Website name or whatever name the user will see as
                                                        the sender of the
                                                        <strong>Password reset message</strong> email.<br/> If empty,
                                                        <a target="_blank" href="<?php echo site_url() . '/wp-admin/options-general.php'; ?>">site
                                                            title</a> will be used.
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row"><?php _e('Sender Email', 'profilepress'); ?></th>
                                                <td>
                                                    <input type="text" name="password_reset_sender_email" class="regular-text code" value="<?php echo isset($db_settings_data['password_reset_sender_email']) ? $db_settings_data['password_reset_sender_email'] : ''; ?>"/>


                                                    <p class="description">
                                                        Enter the email address the user will see as the sender of the
                                                        <strong>Password Reset message</strong>.<br/> If empty, E-mail
                                                        address of blog administrator will be used.
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr id="password_reset_type">
                                                <th scope="row"><?php _e('Content-type', 'profilepress'); ?></th>
                                                <td>
                                                    <select id="psw_reset_type" name="password_reset_type">
                                                        <option value="text/plain" <?php isset($db_settings_data['password_reset_type']) ? selected($db_settings_data['password_reset_type'], 'text/plain') : '' ?>>
                                                            Plain text
                                                        </option>
                                                        <option value="text/html" <?php isset($db_settings_data['password_reset_type']) ? selected($db_settings_data['password_reset_type'], 'text/html') : '' ?>>
                                                            HTML
                                                        </option>
                                                    </select>


                                                    <p class="description">
                                                        Select the content type for the
                                                        <strong>Password Reset</strong> email.</p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row">
                                                    <label for="password_reset_subject"><?php _e('Message subject', 'profilepress'); ?></label>
                                                </th>
                                                <td>
                                                    <textarea name="password_reset_subject" id="password_reset_subject"><?php echo isset($db_settings_data['password_reset_subject']) ? $db_settings_data['password_reset_subject'] : ''; ?></textarea>

                                                    <p class="description">
                                                        Enter the subject or title for the
                                                        <strong>Password Reset</strong> mail.</p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row">
                                                    <label for="password_reset_message">Reset Message</label>
                                                </th>
                                                <td>
                                                    <textarea class="widefat" rows="10" name="password_reset_message" id="password_reset_message"><?php echo isset($db_settings_data['password_reset_message']) ? $db_settings_data['password_reset_message'] : ''; ?></textarea>

                                                    <p class="description">
                                                        Setup the <strong>password reset</strong> message.
                                                    </p><br/>

                                                    <p>
                                                        HTML get parsed only when
                                                        <a href="#password_reset_type"><strong><em>content-type </em></strong></a>
                                                        is set to
                                                        <strong>HTML</strong>
                                                    </p>
                                                    <br/>

                                                    <p>The following placeholders are available for use: <br/>
                                                        <strong>{{username}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;Username
                                                        of the user.
                                                        <br/>
                                                        <strong>{{email}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;Email
                                                        address of the user.
                                                        <br/>
                                                        <strong>{{password_reset_link}}</strong>&nbsp;&nbsp;&nbsp; -
                                                        &nbsp;&nbsp;Link to reset password.
                                                        <br/><br/>
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                        <p>
                                            <?php wp_nonce_field('general_settings_nonce'); ?>
                                            <input class="button-primary" type="submit" name="general_settings_submit" value="<?php _e('Save All Changes', 'profilepress'); ?>">
                                        </p>
                                    </div>
                                </div>

                                <div class="postbox">
                                    <button type="button" class="handlediv button-link" aria-expanded="true">
                                        <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
                                        <span class="toggle-indicator" aria-hidden="true"></span>
                                    </button>
                                    <h3 class="hndle ui-sortable-handle">
                                        <span><?php _e('Account Status Notification', 'profilepress'); ?></span></h3>

                                    <div class="inside">
                                        <table class="form-table">
                                            <tr>
                                                <th scope="row"><?php _e('Disable Notification', 'profilepress'); ?></th>
                                                <td>
                                                    <label for="disable_account_status_notification"><strong><?php _e('Disable', 'profilepress'); ?></strong></label>
                                                    <input id="disable_account_status_notification" name="disable_account_status_notification" type="checkbox" value="on" <?php isset($db_settings_data['disable_account_status_notification']) ? checked($db_settings_data['disable_account_status_notification'], 'on') : ''; ?>/>

                                                    <p class="description">
                                                        <?php _e('Check to disable the sending of account status notifications.', 'profilepress'); ?>
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row">Email Sender</th>
                                                <td>
                                                    <input type="text" name="account_status_sender_name" class="regular-text code"
                                                           value="<?php echo isset($db_settings_data['account_status_sender_name']) ? $db_settings_data['account_status_sender_name'] : get_option('blogname'); ?>"/>

                                                    <p class="description">Enter your Website name or whatever name the
                                                        user will see as the sender of the
                                                        <strong>Notification</strong></p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row"><?php _e('Sender Email', 'profilepress'); ?></th>
                                                <td>
                                                    <input type="text" name="account_status_sender_email" class="regular-text code"
                                                           value="<?php echo isset($db_settings_data['account_status_sender_email']) ? $db_settings_data['account_status_sender_email'] : pp_admin_email(); ?>"/>

                                                    <p class="description">Enter the email address the user will see as
                                                        the sender of the
                                                        <strong>account status notification</strong>.</p>
                                                </td>
                                            </tr>

                                            <tr id="account_status_type">
                                                <th scope="row"><?php _e('Content-type', 'profilepress'); ?></th>
                                                <td>
                                                    <select name="account_status_type">
                                                        <option
                                                                value="text/plain" <?php isset($db_settings_data['account_status_type']) ? selected($db_settings_data['account_status_type'],
                                                            'text/plain') : '' ?>>
                                                            Plain text
                                                        </option>
                                                        <option
                                                                value="text/html" <?php isset($db_settings_data['account_status_type']) ? selected($db_settings_data['account_status_type'],
                                                            'text/html') : '' ?>>
                                                            HTML
                                                        </option>
                                                    </select>


                                                    <p class="description">
                                                        Select the content type for the
                                                        <strong>account status notification</strong> message.
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row">
                                                    <label for="account_status_subject"><?php _e('Message subject', 'profilepress'); ?></label>
                                                </th>
                                                <td>
                                                    <textarea name="account_status_subject" id="account_status_subject"><?php echo isset($db_settings_data['account_status_subject']) ? $db_settings_data['account_status_subject'] : 'Account Status - ' . pp_site_title(); ?></textarea>

                                                    <p class="description">Enter the subject or title for the
                                                        <strong>Password Reset</strong> mail.</p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row">
                                                    <label for="account_status_pending_message">Pending Approval</label>
                                                </th>
                                                <td>
                                                    <textarea class="widefat" rows="10" name="account_status_pending_message" id="account_status_pending_message"><?php echo isset($db_settings_data['account_status_pending_message']) ? $db_settings_data['account_status_pending_message'] : ''; ?></textarea>

                                                    <p class="description">Setup the message sent to newly registered
                                                        users
                                                        <strong>pending approval</strong>.</p><br/>

                                                    <p>HTML get parsed only when <a href="#account_status_type"><strong><em>content-type </em></strong></a>
                                                        is set to<strong>HTML</strong>
                                                    </p><br/>

                                                    <p>The following placeholders are available for use: <br/>
                                                        <strong>{{username}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;Username
                                                        of the user.
                                                        <br/>
                                                        <strong>{{email}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;User's
                                                        email address.<br>
                                                        <strong>{{first_name}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;User's
                                                        first name.<br>
                                                        <strong>{{last_name}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;User's
                                                        last name.<br>
                                                        <br/><br/>
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row">
                                                    <label for="account_status_approval_message">Approval
                                                        Message</label>
                                                </th>
                                                <td>
                                                    <textarea class="widefat" rows="10" name="account_status_approval_message" id="account_status_approval_message"><?php echo isset($db_settings_data['account_status_approval_message']) ? $db_settings_data['account_status_approval_message'] : ''; ?></textarea>

                                                    <p class="description">Setup the
                                                        <strong>account approval</strong> message.</p><br/>

                                                    <p>HTML get parsed only when <a href="#account_status_type"><strong><em>content-type </em></strong></a>
                                                        is set to<strong>HTML</strong>
                                                    </p><br/>

                                                    <p>The following placeholders are available for use: <br/>
                                                        <strong>{{username}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;Username
                                                        of the user.
                                                        <br/>
                                                        <strong>{{email}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;User's
                                                        email address.<br>
                                                        <strong>{{first_name}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;User's
                                                        first name.<br>
                                                        <strong>{{last_name}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;User's
                                                        last name.<br>
                                                        <br/><br/>
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row">
                                                    <label for="account_status_block_message">Blocked Message</label>
                                                </th>
                                                <td>
                                                    <textarea class="widefat" rows="10" name="account_status_block_message" id="account_status_block_message"><?php echo isset($db_settings_data['account_status_block_message']) ? $db_settings_data['account_status_block_message'] : ''; ?></textarea>

                                                    <p class="description">Setup the email sent when a user account is
                                                        blocked.</p>
                                                    <br/>

                                                    <p>HTML get parsed only when <a href="#account_status_type"><strong><em>content-type </em></strong></a>
                                                        is setto<strong>HTML</strong>
                                                    </p><br/>

                                                    <p>The following placeholders are available for use: <br/>
                                                        <strong>{{username}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;Username
                                                        of the user.
                                                        <br/>
                                                        <strong>{{email}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;User's
                                                        email address.<br>
                                                        <strong>{{first_name}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;User's
                                                        first name.<br>
                                                        <strong>{{last_name}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;User's
                                                        last name.<br>
                                                        <br/><br/>
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row">
                                                    <label for="account_status_unblock_message">Unblocked
                                                        Message</label>
                                                </th>
                                                <td>
                                                    <textarea class="widefat" rows="10" name="account_status_unblock_message" id="account_status_unblock_message"><?php echo isset($db_settings_data['account_status_unblock_message']) ? $db_settings_data['account_status_unblock_message'] : ''; ?></textarea>

                                                    <p class="description">Setup the email sent when a user account is
                                                        unblocked.</p>
                                                    <br/>

                                                    <p>HTML get parsed only when <a href="#account_status_type"><strong><em>content-type </em></strong></a>
                                                        is set to<strong>HTML</strong>
                                                    </p><br/>

                                                    <p>The following placeholders are available for use: <br/>
                                                        <strong>{{username}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;Username
                                                        of the user.
                                                        <br/>
                                                        <strong>{{email}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;User's
                                                        email address.<br>
                                                        <strong>{{first_name}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;User's
                                                        first name.<br>
                                                        <strong>{{last_name}}</strong>&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;User's
                                                        last name.<br>
                                                        <br/><br/>
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                        <p>
                                            <?php wp_nonce_field('general_settings_nonce'); ?>
                                            <input class="button-primary" type="submit" name="general_settings_submit" value="<?php _e('Save All Changes', 'profilepress'); ?>">
                                        </p>
                                    </div>
                                </div>

                                <div class="postbox">
                                    <button type="button" class="handlediv button-link" aria-expanded="true">
                                        <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
                                        <span class="toggle-indicator" aria-hidden="true"></span>
                                    </button>
                                    <h3 class="hndle ui-sortable-handle">
                                        <span><?php _e('New User Admin Notification Settings', 'profilepress'); ?></span>
                                    </h3>
                                    <div class="inside">
                                        <table class="form-table">
                                            <tr>
                                                <th scope="row"><?php _e('Email Sender', 'profilepress'); ?></th>
                                                <td>
                                                    <input type="text" name="signup_admin_email_sender_name" class="regular-text code" value="<?php echo isset($db_settings_data['signup_admin_email_sender_name']) ? $db_settings_data['signup_admin_email_sender_name'] : ''; ?>"/>

                                                    <p class="description">
                                                        Enter your Website name or whatever name admin will see as
                                                        the sender of the notification.<br/> If
                                                        empty, it defaults to
                                                        <a target="_blank" href="<?php echo site_url() . '/wp-admin/options-general.php'; ?>">site
                                                            title</a>.
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row"><?php _e('Sender Email', 'profilepress'); ?></th>
                                                <td>
                                                    <input type="text" name="signup_admin_email_sender_email" class="regular-text code" value="<?php echo isset($db_settings_data['signup_admin_email_sender_email']) ? $db_settings_data['signup_admin_email_sender_email'] : ''; ?>"/>


                                                    <p class="description">
                                                        Enter the email address admin will see as the sender of the
                                                        notification.<br/> If
                                                        empty, E-mail address of blog administrator will be used.
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr id="signup_admin_email_type">
                                                <th scope="row"><?php _e('Content-type', 'profilepress'); ?></th>
                                                <td>
                                                    <select id="psw_reset_type" name="signup_admin_email_type">
                                                        <option value="text/plain" <?php isset($db_settings_data['signup_admin_email_type']) ? selected($db_settings_data['signup_admin_email_type'], 'text/plain') : '' ?>>
                                                            Plain text
                                                        </option>
                                                        <option value="text/html" <?php isset($db_settings_data['signup_admin_email_type']) ? selected($db_settings_data['signup_admin_email_type'], 'text/html') : '' ?>>
                                                            HTML
                                                        </option>
                                                    </select>
                                                    <p class="description">Select the content type for the notication
                                                        email.</p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row">
                                                    <label for="signup_admin_email_subject"><?php _e('Message subject', 'profilepress'); ?></label>
                                                </th>
                                                <td>
                                                    <textarea name="signup_admin_email_subject" id="signup_admin_email_subject"><?php echo isset($db_settings_data['signup_admin_email_subject']) ? $db_settings_data['signup_admin_email_subject'] : ''; ?></textarea>

                                                    <p class="description">
                                                        Enter the subject or title for the <strong>"New User Admin Notification"</strong> email.</p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row">
                                                    <label for="signup_admin_email_message"><?php _e('Message Content', 'profilepress'); ?></label>
                                                </th>
                                                <td>
                                                    <textarea class="widefat" rows="10" name="signup_admin_email_message" id="signup_admin_email_message"><?php echo isset($db_settings_data['signup_admin_email_message']) ? $db_settings_data['signup_admin_email_message'] : ''; ?></textarea>

                                                    <p class="description">Setup the <strong>New User Admin
                                                            Notification</strong> message / body.</p><br/>

                                                    <p>HTML get parsed only when
                                                        <a href="#signup_admin_email_type"><strong><em>content-type </em></strong></a>
                                                        is set to <strong>HTML</strong></p>
                                                    <br/>

                                                    <p>The following placeholders are available for use:</p> <br/>
                                                        <strong>{{username}}</strong>&nbsp;&nbsp;&nbsp; -
                                                        &nbsp;&nbsp;<?php _e('Username of the newly registered user.', 'profilepress'); ?>
                                                        <br/>
                                                        <strong>{{user_email}}</strong>&nbsp;&nbsp;&nbsp; -
                                                        &nbsp;&nbsp;<?php _e('Email address of the newly registered user.', 'profilepress'); ?>
                                                        <br/>
                                                        <strong>{{site_title}}</strong>&nbsp;&nbsp;&nbsp; -
                                                        &nbsp;&nbsp;<?php _e('Name or title of your website.', 'profilepress'); ?>
                                                        <br/>
                                                        <strong>{{first_name}}</strong>&nbsp;&nbsp;&nbsp; -
                                                        &nbsp;&nbsp;<?php _e('First name of the newly registered user.', 'profilepress'); ?>
                                                        <br/>
                                                        <strong>{{last_name}}</strong>&nbsp;&nbsp;&nbsp; -
                                                        &nbsp;&nbsp;<?php _e('Last name of the newly registered user.', 'profilepress'); ?>
                                                        <br/><br/>
                                                        <p><?php _e('Custom fields are also supported like so:') ?></p>
                                                        <strong>{{field_key}}</strong>&nbsp;&nbsp;&nbsp; -
                                                        &nbsp;&nbsp;<?php _e('Replace "field_key" with the custom field key.', 'profilepress'); ?>
                                                        <br/><br/>
                                                </td>
                                            </tr>
                                        </table>
                                        <p>
                                            <?php wp_nonce_field('general_settings_nonce'); ?>
                                            <input class="button-primary" type="submit" name="general_settings_submit" value="<?php _e('Save All Changes', 'profilepress'); ?>">
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <?php include_once 'include.plugin-settings-sidebar.php'; ?>

                </div>
                <br class="clear">
            </div>
        </div>
        <style type="text/css">
            .CodeMirror {
                height: 300px !important;
                width: 570px !important;
            }
        </style>
        <script>
            // create a function that accepts args i.e the custom url id and id of input field to remove name attr
            function editor(id) {
                CodeMirror.fromTextArea(document.getElementById(id));
            }
            editor('pp_welcome_message_after_reg');
            editor('password_reset_message');
            editor('signup_admin_email_message');
            editor('account_status_pending_message');
            editor('account_status_approval_message');
            editor('account_status_block_message');
            editor('account_status_unblock_message');

            (function($) {
                $('#pp-general-collapse').click(function(e) {
                    e.preventDefault();

                    $('.pp-general').find('div.postbox').addClass('closed');
                });

                $('#pp-general-expand').click(function(e) {
                    e.preventDefault();

                    $('.pp-general').find('div.postbox').removeClass('closed');
                });
            })(jQuery);

        </script>
        <?php
    }

    /**
     * Save the settings page data
     *
     * @param $post_data
     */
    function save_settings_data($post_data)
    {
        flush_rewrite_rules();

        if (isset($_POST['general_settings_submit']) && check_admin_referer('general_settings_nonce', '_wpnonce')) {

            $settings_data = array();
            foreach ($post_data as $key => $value) {

                // do not save the nonce value to DB
                if ($key == '_wpnonce') {
                    continue;
                }
                // do not save the nonce referer to DB
                if ($key == '_wp_http_referer') {
                    continue;
                }
                // do not save the submit button value
                if ($key == 'general_settings_submit') {
                    continue;
                }

                $settings_data[$key] = stripslashes($value);
            }

            update_option('pp_settings_data', $settings_data);

            // redirect with added query string after submission
            wp_redirect(esc_url_raw(add_query_arg('settings-update', 'true')));
            exit;
        }
    }

    /** Singleton poop */
    static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

}

PP_General_settings_page::get_instance();