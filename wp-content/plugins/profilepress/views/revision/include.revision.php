<?php
// field id to edit.
if (!isset($_GET['revision']) || empty($_GET['revision'])) {
    wp_redirect('?page=' . LOGIN_BUILDER_SETTINGS_PAGE_SLUG);
    exit;
}


$revision_id = absint($_GET['revision']);

$revision_type = PROFILEPRESS_sql::get_revision_type($revision_id);

$revision_parent_id = PROFILEPRESS_sql::get_revision_parent_id($revision_id);

$revision_parent_title = PROFILEPRESS_sql::get_a_builder_title($revision_type, $revision_parent_id);

$css = PROFILEPRESS_sql::get_revision_css($revision_id);

$structure = PROFILEPRESS_sql::get_revision_structure($revision_id);


/**
 * Build the url to the edit builder screen of the form/builder the revision is for.
 *
 * @param string $revision_type
 * @param int $revision_parent_id
 *
 * @return string
 */
function pp_build_edit_screen_url($revision_type, $revision_parent_id)
{

    // create nonce for redirection to builder/form edit page
    switch ($revision_type) {
        case 'login':
            $nonce = wp_create_nonce('pp_edit_login');
            $edit_slug = 'login';
            $status = 'login-edited';
            $page_slug = LOGIN_BUILDER_SETTINGS_PAGE_SLUG;
            break;
        case 'registration':
            $nonce = wp_create_nonce('pp_edit_registration');
            $edit_slug = 'registration';
            $status = 'registration-edited';
            $page_slug = REGISTRATION_BUILDER_SETTINGS_PAGE_SLUG;
            break;
        case 'password_reset':
            $nonce = wp_create_nonce('pp_edit_pass');
            $edit_slug = 'password-reset';
            $status = 'password-reset-edited';
            $page_slug = PASSWORD_RESET_BUILDER_SETTINGS_PAGE_SLUG;
            break;
        case 'edit_user_profile':
            $nonce = wp_create_nonce('pp_edit_edit_profile');
            $edit_slug = 'edit-profile';
            $status = 'edit-profile-edited';
            $page_slug = EDIT_PROFILE_BUILDER_SETTINGS_PAGE_SLUG;
            break;
        case 'front_end_profile':
            $nonce = wp_create_nonce('pp_edit_user_profile');
            $edit_slug = 'user-profile';
            $status = 'user-profile-edited';
            $page_slug = USER_PROFILE_BUILDER_SETTINGS_PAGE_SLUG;
            break;
        case 'melange':
            $nonce = wp_create_nonce('pp_edit_melange');
            $edit_slug = 'melange';
            $status = 'melange-edited';
            $page_slug = MELANGE_SETTINGS_PAGE_SLUG;
            break;
    }

    return sprintf(
        '?page=%s&action=edit&%s=%d&_wpnonce=%s&%s=true',
        $page_slug,
        $edit_slug,
        $revision_parent_id,
        $nonce,
        $status
    );
}

// update builder if restore button is clicked
if (isset ($_POST['pp_restore_revision'])) {
    PROFILEPRESS_sql::update_builder_with_revision($revision_parent_id, $revision_type, $structure, $css);
    wp_redirect(pp_build_edit_screen_url($revision_type, $revision_parent_id));
    exit;
}


/**
 * Return the url to the edit builder screen without status (i.e xyz added | edited successfully)
 *
 * @param string $revision_type
 * @param int $revision_parent_id
 *
 * @return string
 */
function pp_edit_screen_url_without_status($revision_type, $revision_parent_id)
{

    // create nonce for redirection to builder/form edit page
    switch ($revision_type) {
        case 'login':
            $nonce = wp_create_nonce('pp_edit_login');
            $edit_slug = 'login';
            $page_slug = LOGIN_BUILDER_SETTINGS_PAGE_SLUG;
            break;
        case 'registration':
            $nonce = wp_create_nonce('pp_edit_registration');
            $edit_slug = 'registration';
            $page_slug = REGISTRATION_BUILDER_SETTINGS_PAGE_SLUG;
            break;
        case 'password_reset':
            $nonce = wp_create_nonce('pp_edit_pass');
            $edit_slug = 'password-reset';
            $page_slug = PASSWORD_RESET_BUILDER_SETTINGS_PAGE_SLUG;
            break;
        case 'edit_user_profile':
            $nonce = wp_create_nonce('pp_edit_edit_profile');
            $edit_slug = 'edit-profile';
            $page_slug = EDIT_PROFILE_BUILDER_SETTINGS_PAGE_SLUG;
            break;
        case 'front_end_profile':
            $nonce = wp_create_nonce('pp_edit_user_profile');
            $edit_slug = 'user-profile';
            $page_slug = USER_PROFILE_BUILDER_SETTINGS_PAGE_SLUG;
            break;
        case 'melange':
            $nonce = wp_create_nonce('pp_edit_melange');
            $edit_slug = 'melange';
            $page_slug = MELANGE_SETTINGS_PAGE_SLUG;
            break;
    }

    return sprintf(
        '?page=%s&action=edit&%s=%d&_wpnonce=%s',
        $page_slug,
        $edit_slug,
        $revision_parent_id,
        $nonce
    );
}

?>
<div class="wrap">
    <h2 class="long-header"><?php _e('Revision of', 'pp'); ?>
        "<a href="<?php echo pp_edit_screen_url_without_status($revision_type, $revision_parent_id); ?>"><?php echo $revision_parent_title; ?></a>"
    </h2>
    <br/>

    <div
    >
        <a href="<?php echo pp_edit_screen_url_without_status($revision_type, $revision_parent_id); ?>">&larr; Return to
            post editor</a
        ></div>
    <br/>

    <div id="poststuff" class="ppview">

        <div id="post-body" class="metabox-holder columns-2">

            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <form method="post">
                        <div class="postbox">
                            <div style="float: right;margin-top: 3px;margin-right: 4px"><?php submit_button(__('Restore Revision', 'pp_mc '), 'primary', 'pp_restore_revision', false); ?></div>
                            <button type="button" class="handlediv button-link" aria-expanded="true">
                                <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
                                <span class="toggle-indicator" aria-hidden="true"></span>
                            </button>
                            <h3 class="hndle ui-sortable-handle"><span>Revision</span></h3>

                            <div class="inside">
                                <table class="form-table">
                                    <tr>
                                        <th scope="row"><label for="pp_revision">Structure</label></th>
                                        <td>
                                            <?php
                                            $content = $structure;
                                            $editor_id = 'pp_revision';

                                            $wp_editor_args = array(
                                                'textarea_rows' => 20,
                                                'wpautop' => true,
                                                'teeny' => false,
                                                'tinymce' => false
                                            );

                                            wp_editor($content, $editor_id, $wp_editor_args); ?>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <div style="margin-top: -25px;" class="postbox">
                            <div class="inside">
                                <table class="form-table">
                                    <tr>
                                        <th scope="row"><label for="pp_revision_css">CSS Stylesheet</label></th>
                                        <td>
                                            <textarea rows="30" id="pp_revision_css"><?php echo $css; ?></textarea>
                                        </td>
                                    </tr>
                                </table>
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
        var codemirror_editor = CodeMirror.fromTextArea(document.getElementById("pp_revision_css"), {lineNumbers: true});
    </script>