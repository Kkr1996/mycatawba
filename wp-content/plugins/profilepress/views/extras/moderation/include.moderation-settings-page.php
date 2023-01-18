<?php $db_settings_data = get_option('pp_extra_moderation', array()); ?>

<div class="postbox">
    <button type="button" class="handlediv button-link" aria-expanded="true">
        <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
        <span class="toggle-indicator" aria-hidden="true"></span>
    </button>
    <h3 class="hndle ui-sortable-handle"><span><?php _e('User Moderation', 'profilepress'); ?></span></h3>

    <div class="inside">
        <table class="form-table">
            <tr id="activate_user_moderation">
                <th scope="row"><label for="activate_mod"><?php _e('Activate Extra', 'profilepress'); ?></label></th>
                <td>
                    <strong><?php _e('Activate Moderation', 'profilepress'); ?></strong>
                    <input type="checkbox" id="activate_mod" name="activate_moderation" value="active" <?php isset($db_settings_data['activate_moderation']) ? checked($db_settings_data['activate_moderation'], 'active') : '' ?> />

                    <p class="description"><?php _e('Check to enable the "user moderation" module', 'profilepress'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="error_message"><?php _e('Blocked Error Message', 'profilepress'); ?></label>
                </th>
                <td>
                    <input type="text" id="error_message" name="blocked_error_message" class="all-options" value="<?php echo isset($db_settings_data['blocked_error_message']) ? $db_settings_data['blocked_error_message'] : ''; ?>"/>

                    <p class="description">
                        <?php _e('Error message displayed when a blocked user is trying to login.', 'profilepress'); ?>
                    </p>
                </td>
            </tr>


            <tr>
                <th scope="row"><label for="error_message"><?php _e('Pending Error Message', 'profilepress'); ?></label>
                </th>
                <td>
                    <input type="text" id="error_message" name="pending_error_message" class="all-options" value="<?php echo isset($db_settings_data['pending_error_message']) ? $db_settings_data['pending_error_message'] : ''; ?>"/>

                    <p class="description">
                        <?php _e('Error message displayed when a registered user yet to be approved is trying to login.', 'profilepress'); ?>
                    </p>
                </td>
            </tr>
        </table>
    </div>

</div>

<div class="postbox" style="margin-top:-25px;">
    <h3 class="hndle ui-sortable-handle">
        <span><?php _e('Admin Notification When a New User Is Pending Approval', 'profilepress'); ?></span></h3>

    <div class="inside">
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="notification_subject"><?php _e('Notification Subject', 'profilepress'); ?></label></th>
                <td>
                    <textarea name="notification_subject" id="notification_subject"><?php echo isset($db_settings_data['notification_subject']) ? $db_settings_data['notification_subject'] : ''; ?></textarea>
                    <p class="description"><?php _e('Subject or title of the email notification.', 'profilepress'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="notification_content"><?php _e('Notification Message', 'profilepress'); ?></label></th>
                <td>
                    <textarea class="widefat" rows="10" name="notification_content" id="notification_content"><?php echo isset($db_settings_data['notification_content']) ? $db_settings_data['notification_content'] : ''; ?></textarea>

                    <p class="description"><?php _e('The content of the notification message.', 'profilepress'); ?></p>
                    <br/>

                    <p>The following placeholders are available for use: <br/>
                        <strong>{{username}}</strong>&nbsp;&nbsp;&nbsp; -
                        &nbsp;&nbsp;<?php _e('Username of the user pending approval.', 'profilepress'); ?>
                        <br/>
                        <strong>{{email}}</strong>&nbsp;&nbsp;&nbsp; -
                        &nbsp;&nbsp;<?php _e('Email address of the user pending approval.', 'profilepress'); ?>
                        <br/>

                        <strong>{{first_name}}</strong>&nbsp;&nbsp;&nbsp; -
                        &nbsp;&nbsp;<?php _e('First name of the user pending approval.', 'profilepress'); ?>
                        <br/>

                        <strong>{{last_name}}</strong>&nbsp;&nbsp;&nbsp; -
                        &nbsp;&nbsp;<?php _e('Last name of the user pending approval.', 'profilepress'); ?>
                        <br/>

                        <strong>{{approval_url}}</strong>&nbsp;&nbsp;&nbsp; -
                        &nbsp;&nbsp;<?php _e('URL to approve user pending approval.', 'profilepress'); ?>
                        <br/>

                        <strong>{{block_url}}</strong>&nbsp;&nbsp;&nbsp; -
                        &nbsp;&nbsp;<?php _e('URL to block user pending approval.', 'profilepress'); ?>
                        <br/><br/>
                    </p>
                </td>
            </tr>
        </table>
        <p>
            <input class="button-primary" type="submit" name="save_extras" value="<?php _e('Save Changes', 'profilepress'); ?>">
        </p>
    </div>

</div>