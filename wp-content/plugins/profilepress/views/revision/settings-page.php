<?php
add_action('admin_menu', 'pp_revision_settings_page');

function pp_revision_settings_page()
{
    add_submenu_page(
        'index1.php',
        __('Revision', 'profilepress'),
        __('Revision', 'profilepress'),
        'manage_options',
        'pp-revision',
        'pp_revision_settings_callback'
    );

}

/**
 * Output the revision
 */
function pp_revision_settings_callback()
{
    require 'include.revision.php';
}


/**
 * Display a formatted list of revisions that belong to a form/builder ID
 *
 * @param string $type builder type
 * @param int $id builder ID
 */
function pp_display_revisions($type, $id)
{

    // an array of revisions for the type
    $revisions = (array)PROFILEPRESS_sql::get_builder_revisions($type, $id);

    echo '<ul>';
    if (empty($revisions)) {
        $text = __('No revision is available.', 'profilepress');
        echo "<li>$text</li>";

    } else {
        foreach ($revisions as $revision) {
            // date in Unix timestamp
            $revision_date = strtotime($revision['date']);
            // human readable time
            $humanly_time = human_time_diff($revision_date, current_time('timestamp')) . ' ago';
            // revision id
            $id = (int)$revision['id'];

            $link = sprintf('%s (<a href="?page=pp-revision&revision=%d">%s</a>)', date_i18n('M j, Y @ G:i', $revision_date), $id, $humanly_time);

            echo "<li>$link</li>";
        }
    }
    echo '</ul>';

    // delete older revisions
    PROFILEPRESS_sql::delete_older_revisions($type);
}