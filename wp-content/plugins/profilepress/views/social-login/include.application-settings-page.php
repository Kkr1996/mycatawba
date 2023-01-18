<?php $db_settings_data = get_option('pp_social_login'); ?>

<div class="postbox">
    <button type="button" class="handlediv button-link" aria-expanded="true">
        <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
        <span class="toggle-indicator" aria-hidden="true"></span>
    </button>
    <h3 class="hndle ui-sortable-handle"><span><?php _e('Social Application Settings', 'profilepress'); ?></span></h3>

    <div class="inside">
        <table class="form-table">
            <tr>
                <th scope="row"><?php _e('Facebook Settings', 'profilepress'); ?></th>
            </tr>

            <tr>
                <td><label for="fb-id"><?php _e('Facebook App ID', 'profilepress'); ?></label></td>
                <td>
                    <div>
                        <input type="text" id="fb-id" name="facebook_id" class="all-options" value="<?php echo isset($db_settings_data['facebook_id']) ? $db_settings_data['facebook_id'] : ''; ?>"/>
                    </div>
                </td>
            </tr>
            <tr>
                <td><label for="fb-secret"><?php _e('Facebook App Secret', 'profilepress'); ?></label></td>
                <td>
                    <div>
                        <input type="text" id="fb-secret" name="facebook_secret" class="all-options" value="<?php echo isset($db_settings_data['facebook_secret']) ? $db_settings_data['facebook_secret'] : ''; ?>"/>
                    </div>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php _e('Twitter Settings', 'profilepress'); ?></th>
            </tr>

            <tr>
                <td><label for="twitter-key"><?php _e('Consumer (API) Key', 'profilepress'); ?></label></td>
                <td>
                    <div>
                        <input type="text" id="twitter-key" name="twitter_consumer_key" class="all-options" value="<?php echo isset($db_settings_data['twitter_consumer_key']) ? $db_settings_data['twitter_consumer_key'] : ''; ?>"/>
                    </div>
                </td>
            </tr>
            <tr>
                <td><label for="twitter-secret"><?php _e('Consumer (API) Secret', 'profilepress'); ?></label></td>
                <td>
                    <div>
                        <input type="text" id="twitter-secret" name="twitter_consumer_secret" class="all-options" value="<?php echo isset($db_settings_data['twitter_consumer_secret']) ? $db_settings_data['twitter_consumer_secret'] : ''; ?>"/>
                    </div>
                </td>
            </tr>


            <tr>
                <th scope="row"><?php _e('Google Settings', 'profilepress'); ?></th>
            </tr>

            <tr>
                <td><label for="google-client-id"><?php _e('Client ID', 'profilepress'); ?></label></td>
                <td>
                    <div>
                        <input type="text" id="google-client-id" name="google_client_id" class="all-options" value="<?php echo isset($db_settings_data['google_client_id']) ? $db_settings_data['google_client_id'] : ''; ?>"/>
                    </div>
                </td>
            </tr>
            <tr>
                <td><label for="google-client-secret"><?php _e('Client secret', 'profilepress'); ?></label></td>
                <td>
                    <div>
                        <input type="text" id="google-client-secret" name="google_client_secret" class="all-options" value="<?php echo isset($db_settings_data['google_client_secret']) ? $db_settings_data['google_client_secret'] : ''; ?>"/>
                    </div>
                </td>
            </tr>


            <tr>
                <th scope="row"><?php _e('LinkedIn Settings', 'profilepress'); ?></th>
            </tr>

            <tr>
                <td><label for="linkedin-key"><?php _e('Consumer (API) Key', 'profilepress'); ?></label></td>
                <td>
                    <div>
                        <input type="text" id="linkedin-key" name="linkedin_consumer_key" class="all-options" value="<?php echo isset($db_settings_data['linkedin_consumer_key']) ? $db_settings_data['linkedin_consumer_key'] : ''; ?>"/>
                    </div>
                </td>
            </tr>
            <tr>
                <td><label for="linkedin-secret"><?php _e('Consumer (API) Secret', 'profilepress'); ?> </label></td>
                <td>
                    <div>
                        <input type="text" id="linkedin-secret" name="linkedin_consumer_secret" class="all-options" value="<?php echo isset($db_settings_data['linkedin_consumer_secret']) ? $db_settings_data['linkedin_consumer_secret'] : ''; ?>"/>
                    </div>
                </td>
            </tr>


            <tr>
                <th scope="row"><?php _e('GitHub Settings', 'profilepress'); ?></th>
            </tr>

            <tr>
                <td><label for="github-client-id"><?php _e('Client ID', 'profilepress'); ?></label></td>
                <td>
                    <div>
                        <input type="text" id="github-client-id" name="github_client_id" class="all-options" value="<?php echo isset($db_settings_data['github_client_id']) ? $db_settings_data['github_client_id'] : ''; ?>"/>
                    </div>
                </td>
            </tr>
            <tr>
                <td><label for="github-client-secret"><?php _e('Client secret', 'profilepress'); ?></label></td>
                <td>
                    <div>
                        <input type="text" id="github-client-secret" name="github_client_secret" class="all-options" value="<?php echo isset($db_settings_data['github_client_secret']) ? $db_settings_data['github_client_secret'] : ''; ?>"/>
                    </div>
                </td>
            </tr>


            <tr>
                <th scope="row"><?php _e('VK Settings', 'profilepress'); ?></th>
            </tr>

            <tr>
                <td><label for="vk-application-id"><?php _e('Application ID', 'profilepress'); ?></label></td>
                <td>
                    <div>
                        <input type="text" id="vk-application-id" name="vk_application_id" class="all-options" value="<?php echo isset($db_settings_data['vk_application_id']) ? $db_settings_data['vk_application_id'] : ''; ?>"/>
                    </div>
                </td>
            </tr>
            <tr>
                <td><label for="vk-secure-key"><?php _e('Secure key', 'profilepress'); ?></label></td>
                <td>
                    <div>
                        <input type="text" id="vk-secure-key" name="vk_secure_key" class="all-options" value="<?php echo isset($db_settings_data['vk_secure_key']) ? $db_settings_data['vk_secure_key'] : ''; ?>"/>
                    </div>
                </td>
            </tr>
        </table>
        <p>
            <input class="button-primary" type="submit" name="save_social_login" value="<?php _e('Save Changes', 'profilepress'); ?>">
        </p>
    </div>

</div>