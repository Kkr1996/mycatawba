<?php
require_once VIEWS . '/include.settings-page-tab.php';

$default_user_profile_structure = <<<HTML
<h1>[profile-username]'s Profile</h1>

<div class="profile-avatar">
	<img class="avatar" src="[profile-avatar-url]">
</div>

<div class="profile-detail">

	<p>
		Username: [profile-username]
	</p>

	<p>
		Email: [profile-email]
	</p>

	<p>
		Website: [profile-website]
	</p>

	<p>
		First Name: [profile-first-name]
	</p>

	<p>
		Last Name: [profile-last-name]
	</p>

	<p>
		Gender: [profile-cpf key="gender"]
	</p>
</div>
HTML;

$default_user_profile_css = '/* CSS Stylesheet here */';
?>
<br/>
<a class="button-secondary" href="?page=<?php echo USER_PROFILE_BUILDER_SETTINGS_PAGE_SLUG; ?>" title="<?php _e('Back to Catalog', 'profilepress'); ?>"><?php _e('Back to Catalog', 'profilepress'); ?></a>

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
                        <h3 class="hndle ui-sortable-handle"><span>Add New "Front-end Profile"</span></h3>

                        <div class="inside">
                            <table class="form-table">
                                <tr>
                                    <th scope="row">
                                        <label for="title"><?php _e('Theme Name', 'profilepress'); ?></label></th>
                                    <td>
                                        <input type="text" id="title" name="fep_title" class="regular-text code" value="<?php echo isset($_POST['fep_title']) ? esc_attr($_POST['fep_title']) : ''; ?>" required="required"/>

                                        <p class="description">This is the internal title of the
                                            <strong>"Front-end Profile"</strong> for easy reference.</p>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row"><label for="structure">Page Design*</label>
                                    </th>
                                    <td>
                                        <?php
                                        $content = isset($_POST['fep_structure']) ? stripslashes($_POST['fep_structure']) : $default_user_profile_structure;
                                        $editor_id = 'pp_fe_profile_structure';
                                        $wp_editor_args = array(
                                            'textarea_name' => 'fep_structure',
                                            'textarea_rows' => 30,
                                            'wpautop' => true,
                                            'teeny' => false,
                                            'tinymce' => false
                                        );

                                        wp_editor($content, $editor_id, $wp_editor_args); ?>

                                        <p class="description">Page Design & Structure</p>
                                    </td>
                                </tr>
                            </table>
                            <p>
                                <?php wp_nonce_field('add_user_profile_page'); ?>
                                <input class="button-primary" type="submit" name="add_user_profile" value="<?php _e('Save Changes', 'profilepress'); ?>">
                            </p>
                        </div>
                    </div>

                    <div style="margin-top: -5px;" class="postbox">
                        <button type="button" class="handlediv button-link" aria-expanded="true">
                            <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
                            <span class="toggle-indicator" aria-hidden="true"></span>
                        </button>
                        <h3 class="hndle ui-sortable-handle" style="font-size: 18px;text-align: center">
                            <span>User Profile Preview</span></h3>

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
                                        <textarea rows="30" name="fep_css" id="description"><?php echo isset($_POST['fep_css']) ? stripslashes($_POST['fep_css']) : $default_user_profile_css; ?></textarea>

                                        <p class="description">CSS Stylesheet for the Page</p>
                                    </td>
                                </tr>
                            </table>
                            <p>
                                <?php wp_nonce_field('add_user_profile_page'); ?>
                                <input class="button-primary" type="submit" name="add_user_profile" value="<?php _e('Save Changes', 'profilepress'); ?>">
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

            $('#pp_fe_profile_structure').on('change', function (e) {
                window.onbeforeunload = function (e) {
                    return 'The changes you made will be lost if you navigate away from this page.';
                };
            });


            var raw_builder_structure = $('#pp_fe_profile_structure').val();

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
            var raw_builder_structure = $('#pp_fe_profile_structure').val();
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