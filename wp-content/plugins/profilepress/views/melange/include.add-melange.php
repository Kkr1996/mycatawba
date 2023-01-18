<?php
require_once VIEWS . '/include.settings-page-tab.php';

$default_melange_css = '/* css class for registration form generated errors */
.profilepress-login-status {
  border-radius: 6px;
  font-size: 17px;
  line-height: 1.471;
  padding: 10px 19px;
  background-color: #e74c3c;
  color: #ffffff;
  font-weight: normal;
  display: block;
  text-align: center;
  vertical-align: middle;
  margin: 5px 0;
}

/* css class for registration form generated errors */
.profilepress-reg-status {
  border-radius: 6px;
  font-size: 17px;
  line-height: 1.471;
  padding: 10px 19px;
  background-color: #e74c3c;
  color: #ffffff;
  font-weight: normal;
  display: block;
  text-align: center;
  vertical-align: middle;
  margin: 5px 0;
}

/* css class for password reset form generated errors */
.profilepress-reset-status {
  border-radius: 6px;
  font-size: 17px;
  line-height: 1.471;
  padding: 10px 19px;
  background-color: #e74c3c;
  color: #ffffff;
  font-weight: normal;
  display: block;
  text-align: center;
  vertical-align: middle;
  margin: 5px 0;
}

/* css class for the edit-profile form generated errors */
.profilepress-edit-profile-status {
  border-radius: 6px;
  font-size: 17px;
  line-height: 1.471;
  padding: 10px 19px;
  background-color: #e74c3c;
  color: #ffffff;
  font-weight: normal;
  display: block;
  text-align: center;
  vertical-align: middle;
  margin: 5px 0;
}';
?>
<br/>
<a class="button-secondary" href="?page=<?php echo MELANGE_SETTINGS_PAGE_SLUG; ?>" title="<?php _e('Back to Catalog', 'profilepress'); ?>"><?php _e('Back to Catalog', 'profilepress'); ?></a>

<div id="poststuff" class="ppview">

    <div id="post-body" class="metabox-holder columns-2">
        <div id="post-body-content">
            <div class="meta-box-sortables ui-sortable">
                <form method="post">
                    <div class="postbox">
                        <button type="button" class="handlediv button-link" aria-expanded="true">
                            <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
                            <span class="toggle-indicator" aria-hidden="true"></span>
                        </button>
                        <h3 class="hndle ui-sortable-handle">
                            <span><?php _e('Add New Melange', 'profilepress'); ?></span></h3>

                        <div class="inside">
                            <table class="form-table">
                                <tr>
                                    <th scope="row"><label for="title"><?php _e('Name', 'profilepress'); ?></label></th>
                                    <td>
                                        <input type="text" id="title" name="mfb_title" class="regular-text code" value="<?php echo isset($_POST['mfb_title']) ? esc_attr($_POST['mfb_title']) : ''; ?>" required="required"/>

                                        <p class="description"><?php _e(sprintf('This will be the internal title of the %s for easy reference.', '<strong>Melange</strong>'), 'profilepress'); ?></p>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">
                                        <label for="structure"><?php _e('Melange Design', 'profilepress'); ?></label>
                                    </th>
                                    <td>
                                        <?php
                                        $content = isset($_POST['mfb_structure']) ? stripslashes($_POST['mfb_structure']) : '';
                                        $editor_id = 'pp_melange_structure';
                                        $wp_editor_args = array(
                                            'textarea_name' => 'mfb_structure',
                                            'textarea_rows' => 30,
                                            'wpautop' => true,
                                            'teeny' => false,
                                            'tinymce' => false
                                        );

                                        wp_editor($content, $editor_id, $wp_editor_args); ?>

                                        <p class="description"><?php _e('Melange Design', 'profilepress'); ?></p>
                                    </td>
                                </tr>
                            </table>
                            <p>
                                <?php wp_nonce_field('add_melange_builder'); ?>
                                <input class="button-primary" type="submit" name="add_melange" value="<?php _e('Save Changes', 'profilepress'); ?>">
                            </p>
                        </div>
                    </div>

                    <div style="margin-top: -5px;" class="postbox">
                        <button type="button" class="handlediv button-link" aria-expanded="true">
                            <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
                            <span class="toggle-indicator" aria-hidden="true"></span>
                        </button>
                        <h3 class="hndle ui-sortable-handle" style="font-size: 18px;text-align: center">
                            <span><?php _e('Melange Preview', 'profilepress'); ?></span></h3>

                        <div class="inside">
                            <iframe width="100%" height="500px" id="indexIframe" src="<?php echo admin_url('admin-ajax.php?action=pp-builder-preview'); ?>"></iframe>
                            <img style="display:none" id="loadingimg" src="<?php echo ASSETS_URL; ?>/images/loading.gif"/>
                            &nbsp;&nbsp;
                            <a style="text-align: center" class="button-secondary" id="preview_iframe"><?php _e('Preview Design', 'profilepress'); ?></a>
                        </div>
                    </div>

                    <div style="margin-top: -15px;" class="postbox">
                        <div class="inside">
                            <table class="form-table">
                                <tr>
                                    <th scope="row">
                                        <label for="description"><?php _e('CSS Stylesheet', 'profilepress'); ?></label>
                                    </th>
                                    <td>
                                        <textarea rows="30" name="mfb_css" id="description"><?php echo isset($_POST['mfb_css']) ? stripslashes($_POST['mfb_css']) : $default_melange_css; ?></textarea>

                                        <p class="description"><?php _e('CSS Stylesheet for Melange', 'profilepress'); ?></p>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">
                                        <label for="reg_message_success"><?php _e('Registration Success', 'profilepress'); ?></label>
                                    </th>
                                    <td>
                                        <textarea name="mfb_success_registration" id="reg_message_success"><?php echo isset($_POST['mfb_success_registration']) ? $_POST['mfb_success_registration'] : '<div>' . __('Registration Successful', 'profilepress') . '</div>'; ?></textarea>

                                        <p class="description"><?php _e('Message displayed on successful user registration.', 'profilepress'); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label for="mfb_success_password_reset"><?php _e('Password Reset Success', 'profilepress'); ?></label>
                                    </th>
                                    <td>
                                        <textarea name="mfb_success_password_reset" id="mfb_success_password_reset"><?php echo isset($_POST['mfb_success_password_reset']) ? $_POST['mfb_success_password_reset'] : '<div>' . __('Check your e-mail for further instruction', 'profilepress') . '</div>'; ?></textarea>

                                        <p class="description"><?php _e('Message displayed on successful user password reset', 'profilepress'); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label for="mfb_success_edit_profile"><?php _e('Edit Profile Success', 'profilepress'); ?></label>
                                    </th>
                                    <td>
                                        <textarea name="mfb_success_edit_profile" id="mfb_success_edit_profile"><?php echo isset($_POST['mfb_success_edit_profile']) ? $_POST['mfb_success_edit_profile'] : '<div>' . __('Profile Successfully Edited', 'profilepress') . '</div>'; ?></textarea>

                                        <p class="description"><?php _e('Message displayed on users successfully editing their profile', 'profilepress'); ?></p>
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div>

                    <div style="margin-top: -15px;" class="postbox">

                        <div class="inside">
                            <table class="form-table">
                                <tr>
                                    <th scope="row">
                                        <label for="description"><?php _e('Disable Username Requirement', 'profilepress'); ?></label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="mfb_disable_username_requirement" id="disable_username_requirement_melange" value="yes" <?php checked('yes', sanitize_text_field(@$_POST['mfb_disable_username_requirement'])); ?> />
                                        <label for="disable_username_requirement_melange"><strong><?php _e('Disable Requirement', 'profilepress'); ?></strong></label>

                                        <p class="description">
                                            <?php _e('Disable requirement for users to enter or have username on registration. Username will automatically be generated from user\'s email.', 'profilepress'); ?>.
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label for="description"><?php _e('Create Widget', 'profilepress'); ?></label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="mfb_make_widget" id="make-login-widget" value="yes" <?php checked('yes', esc_attr(isset($_POST['mfb_make_widget']) ? $_POST['mfb_make_widget'] : '')); ?> />
                                        <label for="make-login-widget"><strong><?php _e('Make this a Widget', 'profilepress'); ?></strong></label>

                                        <p class="description"><?php _e('Make this Melange available as a', 'profilepress'); ?>
                                            <a href="<?php echo site_url() ?>/wp-admin/widgets.php"><?php _e('widget', 'profilepress'); ?></a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            <p>
                                <?php wp_nonce_field('add_melange_builder'); ?>
                                <input class="button-primary" type="submit" name="add_melange" value="<?php _e('Save Changes', 'profilepress'); ?>">
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php include_once VIEWS . '/include.plugin-settings-sidebar.php'; ?>
    </div>
    <br class="clear">
</div>

<script type="text/javascript">
    var codemirror_editor = CodeMirror.fromTextArea(document.getElementById("description"), {lineNumbers: true});

    (function ($) {
        window.onbeforeunload = function (e) {
            return 'The changes you made will be lost if you navigate away from this page.';
        };

        $('input[type="submit"]').click(function () {
            window.onbeforeunload = function (e) {
                e = null;
            };
        });

        $(window).load(function () {

            $('#pp_melange_structure').on('change', function (e) {
                window.onbeforeunload = function (e) {
                    return 'The changes you made will be lost if you navigate away from this page.';
                };
            });

            var raw_builder_structure = $('#pp_melange_structure').val();

            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    builder_structure: raw_builder_structure,
                    action: 'pp-builder-preview'
                }
            })
                .done(function (builder_structure) {

                    var builder_css = codemirror_editor.getValue();
                    var iframe1 = $('#indexIframe').contents();

                    $(iframe1).contents().find('body').html(builder_structure);
                    $(iframe1).contents().find('style').html(builder_css);
                });

            $('input[type="submit"]').click(function () {
                window.onbeforeunload = function (e) {
                    e = null;
                };
            });
        });

        $('#preview_iframe').click(function () {
            var raw_builder_structure = $('#pp_melange_structure').val();
            var builder_css = codemirror_editor.getValue();
            var iframe1 = $('#indexIframe').contents();

            // show pre-loader
            $("#loadingimg").show();

            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    builder_structure: raw_builder_structure,
                    action: 'pp-builder-preview'
                }
            })
                .done(function (builder_structure) {
                    $(iframe1).contents().find('body').html(builder_structure);
                    $(iframe1).contents().find('style').html(builder_css);
                    $("#loadingimg").hide();
                });
        });

    })(jQuery);
</script>