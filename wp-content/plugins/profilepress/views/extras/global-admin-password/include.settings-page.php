<?php $db_settings_data = get_option('pp_extra_gap'); ?>

<div class="postbox">
    <button type="button" class="handlediv button-link" aria-expanded="true">
        <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
        <span class="toggle-indicator" aria-hidden="true"></span>
    </button>
    <h3 class="hndle ui-sortable-handle"><span><?php _e('Global Admin Password', 'profilepress'); ?></span></h3>

    <div class="inside">
        <table class="form-table">
            <tr>
                <th scope="row"><label for="activate-gap"><?php _e('Activate Extra', 'profilepress'); ?></label></th>
                <td>
                    <strong><?php _e('Activate GAP', 'profilepress'); ?></strong>
                    <input type="checkbox" id="activate-gap" name="activate_gap" value="active" <?php isset($db_settings_data) ? checked($db_settings_data, 'active') : '' ?> />

                    <p class="description"><?php _e('Permit website administrator(s) to login to any user\'s account using their administrator\'s password.', 'profilepress'); ?></p>
                </td>
            </tr>
        </table>
        <p>
            <input class="button-primary" type="submit" name="save_extras" value="<?php _e('Save Changes', 'profilepress'); ?>">
        </p>
    </div>

</div>