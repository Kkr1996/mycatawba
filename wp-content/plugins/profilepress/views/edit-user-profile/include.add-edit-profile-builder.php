<?php
require_once VIEWS . '/include.settings-page-tab.php';

$default_edit_profile_structure = '<div class="edit-profile">

<p>
<label for="id-username">Username</label>
[edit-profile-username id="id-username" placeholder="username" class="edit-profile-name"]
</p>

<p>
<label for="id-password">Password</label>
[edit-profile-password id="id-password" placeholder="password" class="edit-profile-passkey"]
</p>

<p>
<label for="id-email">Email Address</label>
[edit-profile-email id="id-email" placeholder="Email" class="reg-email"]
</p>

<p>
<label for="id-website">Website</label>
[edit-profile-website class="reg-website" placeholder="Website" id="id-website"]
</p>

<p>
<label for="id-nickname">Nickname</label>
[edit-profile-nickname class="remember-me" placeholder="Nickname" id="id-nickname"]
</p>

<p>
[user-avatar class="demo-avatar"]
</p>
[remove-user-avatar label="remove" class="removed"]

<p>
<label for="id-nickname">Profile Picture</label>
[edit-profile-avatar class="avatar" placeholder="avatar" id="id-avatar"]
</p>

<p>
<label for="id-display-name">Display Name</label>
[edit-profile-display-name class="display-name" placeholder="Display Name" id="id-display-name"]
</p>

<p>
<label for="id-firstname">First Name</label>
[edit-profile-first-name class="remember-me" id="id-firstname"  placeholder="First Name"]
</p>

<p>
<label for="id-lastname">Last Name</label>
[edit-profile-last-name class="remember-me" id="id-lastname" placeholder="Last Name"]
</p>

<p>
[edit-profile-submit value="Edit Profile" class="submit" id="submit-button"]
</p>

</div>';

$default_edit_profile_css = '/* css class for the edit profile generated errors */

.profilepress-edit-profile-status {
 background-color: #34495e;
    color: #ffffff;
    border: medium none;
    border-radius: 4px;
    font-size: 15px;
    font-weight: normal;
    line-height: 1.4;
    padding: 8px 5px;
  	font-weight: bold;
    margin:4px 1px;
}';
?>
<br/>
<a class="button-secondary" href="?page=<?php echo EDIT_PROFILE_BUILDER_SETTINGS_PAGE_SLUG; ?>" title="Back to Front-end Edit Profile"><?php _e('Back to Catalog', 'profilepress'); ?></a>

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
                        <h3 class="hndle ui-sortable-handle"><span>Add New "Edit Profile Form"</span></h3>

                        <div class="inside">
                            <table class="form-table">
                                <tr>
                                    <th scope="row">
                                        <label for="title"><?php _e('Theme Name', 'profilepress'); ?></label></th>
                                    <td>
                                        <input type="text" id="title" name="eup_title" class="regular-text code" value="<?php echo isset($_POST['eup_title']) ? esc_attr($_POST['eup_title']) : ''; ?>" required="required"/>

                                        <p class="description">This is the internal title of the<strong>"Edit User Profile"</strong> page for easyreference.
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row"><label for="structure">Page Design</label>
                                    </th>
                                    <td>
                                        <?php
                                        $content = isset($_POST['eup_structure']) ? stripslashes($_POST['eup_structure']) : $default_edit_profile_structure;
                                        $editor_id = 'pp_edit_profile_structure';
                                        $wp_editor_args = array(
                                            'textarea_name' => 'eup_structure',
                                            'textarea_rows' => 30,
                                            'wpautop' => true,
                                            'teeny' => false,
                                            'tinymce' => false
                                        );

                                        wp_editor($content, $editor_id, $wp_editor_args); ?>
                                        <p class="description">"Edit User Profile" Page Design & Structure</p>
                                    </td>
                                </tr>
                            </table>
                            <p>
                                <?php wp_nonce_field('edit_user_profile_page'); ?>
                                <input class="button-primary" type="submit" name="add_edit_profile" value="<?php _e('Save Changes', 'profilepress'); ?>">
                            </p>
                        </div>
                    </div>

                    <div style="margin-top: -5px;" class="postbox">
                        <button type="button" class="handlediv button-link" aria-expanded="true">
                            <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
                            <span class="toggle-indicator" aria-hidden="true"></span>
                        </button>
                        <h3 class="hndle ui-sortable-handle" style="font-size: 18px;text-align: center">
                            <span>Edit Profile Form Preview</span></h3>

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
                                    <th scope="row"><label for="description">CSS Stylesheet</label>
                                    </th>
                                    <td>
                                        <textarea rows="30" name="eup_css" id="description"><?php echo isset($_POST['eup_css']) ? stripslashes($_POST['eup_css']) : $default_edit_profile_css; ?></textarea>
                                        <p class="description">CSS Stylesheet for "Edit User Profile" Page</p>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row"><label for="message_success">Profile Edited Message</label>
                                    </th>
                                    <td>
                                        <textarea name="eup_success_edit_profile" id="message_success" required="required"><?php echo isset($_POST['eup_success_edit_profile']) ? $_POST['eup_success_edit_profile'] : '<div>Changes saved.</div>'; ?></textarea>
                                        <p class="description">Message to display when a user profile is successfully edited.</p>
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
                                        <label for="description"><?php _e('Create Widget', 'profilepress'); ?></label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="eup_make_widget" id="make-login-widget" value="yes" <?php checked('yes', @esc_attr($_POST['eup_make_widget'])); ?> />
                                        <label for="make-login-widget"><strong><?php _e('Make this a Widget', 'profilepress'); ?></strong></label>

                                        <p class="description">Make this
                                            <strong>Edit Profile Form</strong> available as a
                                            <a href="<?php echo site_url() ?>/wp-admin/widgets.php">widget</a></p>
                                    </td>
                                </tr>
                            </table>
                            <p>
                                <?php wp_nonce_field('add_edit_user_profile_page'); ?>
                                <input class="button-primary" type="submit" name="add_edit_profile" value="<?php _e('Save Changes', 'profilepress'); ?>">
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
            // if TinyMCE is in text mode
            $('#pp_edit_profile_structure').on('change', function (e) {
                window.onbeforeunload = function (e) {
                    return 'The changes you made will be lost if you navigate away from this page.';
                };
            });

            var raw_builder_structure = $('#pp_edit_profile_structure').val();


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
            var raw_builder_structure = $('#pp_edit_profile_structure').val();
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