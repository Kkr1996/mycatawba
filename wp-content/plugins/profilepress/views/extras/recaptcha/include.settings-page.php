<?php

$db_settings_data = get_option('pp_extra_recaptcha');

$activate_recaptcha = isset($db_settings_data ['activate_recaptcha']) ? $db_settings_data ['activate_recaptcha'] : '';

$site_key = isset($db_settings_data ['site_key']) ? $db_settings_data ['site_key'] : '';
$secret_key = isset($db_settings_data ['secret_key']) ? $db_settings_data ['secret_key'] : '';

$theme = isset($db_settings_data ['theme']) ? $db_settings_data ['theme'] : '';
$language = isset($db_settings_data ['language']) ? $db_settings_data ['language'] : '';
$error_message = isset($db_settings_data ['error_message']) ? $db_settings_data ['error_message'] : '<strong>ERROR</strong>: Please retry CAPTCHA'; ?>

<div class="postbox">
    <button type="button" class="handlediv button-link" aria-expanded="true">
        <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
        <span class="toggle-indicator" aria-hidden="true"></span>
    </button>
    <h3 class="hndle ui-sortable-handle"><span><?php _e('No CAPTCHA reCAPTCHA', 'profilepress'); ?></span></h3>
    <div class="inside">
        <table class="form-table">
            <tr>
                <th scope="row"><label for="activate_recaptcha"><?php _e('Activate Extra', 'profilepress'); ?></label>
                </th>
                <td>
                    <strong><?php _e('Activate reCAPTCHA', 'profilepress'); ?></strong>
                    <input type="checkbox" id="activate_recaptcha" name="activate_recaptcha" value="active" <?php checked($activate_recaptcha, 'active'); ?> />

                    <p class="description"><?php _e('Check to enable the reCAPTCHA extra to combat spam.', 'profilepress'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="public_key"><?php _e('Site Key', 'profilepress'); ?></label>
                </th>
                <td>
                    <input type="text" id="public_key" name="recaptcha_site_key" class="all-options" value="<?php echo $site_key; ?>"/>

                    <p class="description"><?php echo __('Necessary for displaying the CAPTCHA. Grab it ', 'profilepress') . '<a href="https://www.google.com/recaptcha/admin" target="_blank">' . __('Here', 'profilepress') . '</a>'; ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="secret_key"><?php _e('Secret Key', 'profilepress'); ?></label>
                </th>
                <td>
                    <input type="text" name="recaptcha_secret_key" id="secret_key" class="regular-text" value="<?php echo $secret_key; ?>"/>

                    <p class="description"><?php echo __('Necessary for communication between your site and Google. Grab it ', 'profilepress') . '<a href="https://www.google.com/recaptcha/admin" target="_blank">' . __('Here', 'profilepress') . '</a>'; ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="theme"><?php _e('reCAPTCHA Theme', 'profilepress'); ?></label></th>
                <td>
                    <select id="theme" name="recaptcha_theme">
                        <option value="light" <?php selected('light', $theme); ?>><?php _e('Light', 'profilepress'); ?></option>
                        <option value="dark" <?php selected('dark', $theme); ?>><?php _e('Dark', 'profilepress'); ?></option>
                    </select>

                    <p class="description">
                        <?php _e('The theme colour of the widget.', 'profilepress'); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label
                            for="theme"><?php _e('Language', 'profilepress'); ?></label>
                </th>
                <td>
                    <select id="theme" name="recaptcha_language">
                        <?php
                        $languages = array(
                            __('Auto Detect', 'profilepress') => '',
                            __('English', 'profilepress') => 'en',
                            __('Arabic', 'profilepress') => 'ar',
                            __('Bulgarian', 'profilepress') => 'bg',
                            __('Catalan Valencian', 'profilepress') => 'ca',
                            __('Czech', 'profilepress') => 'cs',
                            __('Danish', 'profilepress') => 'da',
                            __('German', 'profilepress') => 'de',
                            __('Greek', 'profilepress') => 'el',
                            __('British English', 'profilepress') => 'en_gb',
                            __('Spanish', 'profilepress') => 'es',
                            __('Persian', 'profilepress') => 'fa',
                            __('French', 'profilepress') => 'fr',
                            __('Canadian French', 'profilepress') => 'fr_ca',
                            __('Hindi', 'profilepress') => 'hi',
                            __('Croatian', 'profilepress') => 'hr',
                            __('Hungarian', 'profilepress') => 'hu',
                            __('Indonesian', 'profilepress') => 'id',
                            __('Italian', 'profilepress') => 'it',
                            __('Hebrew', 'profilepress') => 'iw',
                            __('Jananese', 'profilepress') => 'ja',
                            __('Korean', 'profilepress') => 'ko',
                            __('Lithuanian', 'profilepress') => 'lt',
                            __('Latvian', 'profilepress') => 'lv',
                            __('Dutch', 'profilepress') => 'nl',
                            __('Norwegian', 'profilepress') => 'no',
                            __('Polish', 'profilepress') => 'pl',
                            __('Portuguese', 'profilepress') => 'pt',
                            __('Romanian', 'profilepress') => 'ro',
                            __('Russian', 'profilepress') => 'ru',
                            __('Slovak', 'profilepress') => 'sk',
                            __('Slovene', 'profilepress') => 'sl',
                            __('Serbian', 'profilepress') => 'sr',
                            __('Swedish', 'profilepress') => 'sv',
                            __('Thai', 'profilepress') => 'th',
                            __('Turkish', 'profilepress') => 'tr',
                            __('Ukrainian', 'profilepress') => 'uk',
                            __('Vietnamese', 'profilepress') => 'vi',
                            __('Simplified Chinese', 'profilepress') => 'zh_cn',
                            __('Traditional Chinese', 'profilepress') => 'zh_tw'
                        );

                        foreach ($languages as $key => $value) {
                            echo "<option value='$value'" . selected($value, $language, true) . ">$key</option>";
                        }
                        ?>
                    </select>

                    <p class="description">
                        <?php _e('Forces the widget to render in a specific language', 'profilepress'); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label
                            for="recaptcha_error_message"><?php _e('Error Message', 'profilepress'); ?></label>
                </th>
                <td>
                    <input type="text" name="recaptcha_error_message" id="recaptcha_error_message" class="regular-text"
                           value="<?php echo $error_message; ?>"/>

                    <p class="description">
                        <?php _e('Message or text to display when CAPTCHA is ignored or the challenge is failed.', 'profilepress'); ?>
                    </p>
                </td>
            </tr>

        </table>
        <p>
            <input class="button-primary" type="submit" name="save_extras" value="<?php _e('Save Changes', 'profilepress'); ?>">
        </p>
    </div>
</div>
