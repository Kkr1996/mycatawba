<?php $db_settings_data = get_option('pp_extra_passwordless'); ?>
<div class="postbox">
    <button type="button" class="handlediv button-link" aria-expanded="true">
        <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
        <span class="toggle-indicator" aria-hidden="true"></span>
    </button>
    <h3 class="hndle ui-sortable-handle"><span><?php _e('One-time Passwordless Login', 'profilepress'); ?></span></h3>

    <div class="inside">
        <table class="form-table">

            <tr>
                <th scope="row">
                    <label for="activate_passwordless"><?php _e('Activate Extra', 'profilepress'); ?></label></th>
                <td>
                    <label for="activate_passwordless"><strong><?php _e('Activate Passwordless', 'profilepress'); ?></strong></label>
                    <input type="checkbox" id="activate_passwordless" name="activate_passwordless" value="active" <?php isset($db_settings_data['activated']) ? checked($db_settings_data['activated'], 'active') : '' ?> />

                    <p class="description"><?php _e('Check to enable the passwordless login feature.', 'profilepress'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="disable_admin"><?php _e('Disable for Admins', 'profilepress'); ?></label>
                </th>
                <td>
                    <label for="disable_admin"><strong><?php _e('Disable', 'profilepress'); ?></strong></label>
                    <input type="checkbox" id="disable_admin" name="disable_admin" value="active" <?php isset($db_settings_data['disable_admin']) ? checked($db_settings_data['disable_admin'], 'active') : '' ?> />

                    <p class="description"><?php _e('Check to disable passwordless login for administrators.', 'profilepress'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php _e('Sender Name', 'profilepress'); ?></th>
                <td>
                    <input type="text" name="passwordless_sender_name" class="regular-text" value="<?php echo isset($db_settings_data['sender_name']) ? $db_settings_data['sender_name'] : ''; ?>"/>

                    <p class="description">
                        <?php
                        printf(__('The name of the sender e.g website name or whatever the user will see as the sender of the passwordless login email.%s If empty, %s will be used instead.', 'pp_ec'), '<br>', '<a target="_blank" href="' . site_url() . '/wp-admin/options-general.php">' . __('website title', 'pp_ec') . '</a>'); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php _e('Sender Email', 'profilepress'); ?></th>
                <td>
                    <input type="text" name="passwordless_sender_email" class="regular-text code" value="<?php echo isset($db_settings_data['sender_email']) ? $db_settings_data['sender_email'] : ''; ?>"/>

                    <p class="description"><?php _e('The sender email address of the passwordless login message. If empty, the site owner or administrator\'s email will be used.', 'pp_ec'); ?></p>
                </td>
            </tr>

            <tr id="passwordless_type">
                <th scope="row"><?php _e('Content-type', 'profilepress'); ?></th>
                <td>
                    <select name="passwordless_type">
                        <option value="text/plain" <?php isset($db_settings_data['type']) ? selected($db_settings_data['type'], 'text/plain') : '' ?>>
                            Plain text
                        </option>
                        <option value="text/html" <?php isset($db_settings_data['type']) ? selected($db_settings_data['type'], 'text/html') : '' ?>>
                            HTML
                        </option>
                    </select>

                    <p class="description"><?php _e('Select the content type for the mail.', 'profilepress'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="passwordless_subject"><?php _e('Message Subject', 'profilepress'); ?></label></th>
                <td>
                    <textarea name="passwordless_subject" id="passwordless_subject"><?php echo isset($db_settings_data['subject']) ? $db_settings_data['subject'] : ''; ?></textarea>

                    <p class="description"><?php _e('Enter the mail subject or title.', 'profilepress'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="passwordless_message"><?php _e('Mail Content', 'profilepress'); ?></label>
                </th>
                <td>
                    <textarea class="widefat" rows="10" name="passwordless_message" id="passwordless_message"><?php echo isset($db_settings_data['message']) ? $db_settings_data['message'] : ''; ?></textarea>

                    <p class="description"><?php _e('Setup the passwordless login message.', 'profilepress'); ?></p>
                    <br/>

                    <p><?php printf(__('HTML only get parsed when the %s is set to	HTML.', 'profilepress'), '<a href="#passwordless_type"><strong><em>' . __('content type', 'pp_ec') . '</em></strong></a>') ?></p>
                    <br/>

                    <p><?php _e('The following placeholders are available for use', 'profilepress'); ?>: <br/>
                        <strong>{{username}}</strong>&nbsp;&nbsp;&nbsp; -
                        &nbsp;&nbsp;<?php _e('Username of the user', 'profilepress'); ?>.
                        <br/>
                        <strong>{{first_name}}</strong>&nbsp;&nbsp;&nbsp;-
                        &nbsp;&nbsp;<?php _e('First name of the user', 'profilepress'); ?>.
                        <br/>
                        <strong>{{last_name}}</strong>&nbsp;&nbsp;&nbsp; -
                        &nbsp;&nbsp;<?php _e('Last name of the user', 'profilepress'); ?>.
                        <br/>
                        <strong>{{passwordless_link}}</strong>&nbsp;&nbsp;&nbsp; -
                        &nbsp;&nbsp;<?php _e('The time limited login URL.', 'profilepress'); ?>
                        <br/><br/>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="passwordless_expiration"><?php _e('Expiration', 'profilepress'); ?></label></th>
                <td>
                    <input name="passwordless_expiration" type="number" id="passwordless_expiration" value="<?php echo isset($db_settings_data['expires']) ? $db_settings_data['expires'] : ''; ?>">

                    <p class="description"><?php _e('Time in minutes the one-time login URL will expire if it isn\'t use by the user. Default to 10mins if this field is left empty.', 'profilepress'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="passwordless_error"><?php _e('Error Message', 'profilepress'); ?></label>
                </th>
                <td>
                    <textarea name="invalid_error" id="passwordless_error"><?php echo isset($db_settings_data['invalid_error']) ? $db_settings_data['invalid_error'] : ''; ?></textarea>

                    <p class="description"><?php _e('Error message displayed when the one-time login URL is invalid or has expired.', 'profilepress'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="success_message"><?php _e('Success Message', 'profilepress'); ?></label>
                </th>
                <td>
                    <textarea name="success_message" id="success_message"><?php echo isset($db_settings_data['success_message']) ? $db_settings_data['success_message'] : ''; ?></textarea>

                    <p class="description"><?php _e('Message or text displayed when the one-time login URL is successfully sent to the user\'s email.', 'profilepress'); ?></p>
                </td>
            </tr>

        </table>
        <p>
            <input class="button-primary" type="submit" name="save_extras" value="<?php _e('Save Changes', 'profilepress'); ?>">
        </p>
    </div>
</div>