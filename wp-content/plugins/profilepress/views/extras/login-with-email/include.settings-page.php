<?php $db_settings_data = get_option('pp_extra_login_with_email'); ?>
<div class="postbox">
    <button type="button" class="handlediv button-link" aria-expanded="true">
        <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
        <span class="toggle-indicator" aria-hidden="true"></span>
    </button>
    <h3 class="hndle ui-sortable-handle"><span><?php _e('Login with Email', 'profilepress'); ?></span></h3>
    <div class="inside">
        <table class="form-table">
            <tr>
                <th scope="row"><label for="activate"><?php _e('Activate Extra', 'profilepress'); ?></label></th>
                <td>
                    <strong><?php _e('Activate', 'profilepress'); ?></strong>
                    <input type="checkbox" id="activate" name="activate_login_with_email" value="active" <?php isset($db_settings_data) ? checked($db_settings_data, 'active') : '' ?> />
                    <p class="description"><?php _e('Allow registered users to be able to login with their email address besides their username.', 'profilepress'); ?>
                </td>
            </tr>
        </table>
        <p>
            <input class="button-primary" type="submit" name="save_extras" value="<?php _e('Save Changes', 'profilepress'); ?>">
        </p>
    </div>
</div>