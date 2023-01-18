<?php
$basename = plugin_basename(PROFILEPRESS_SYSTEM_FILE_PATH);
$prefix   = is_network_admin() ? 'network_admin_' : '';
add_filter("{$prefix}plugin_action_links_$basename", 'pp_plugin_action_links', 10, 4);

/** Action links */
function pp_plugin_action_links($actions, $plugin_file, $plugin_data, $context)
{
    $custom_actions = array(
        'themes'     => sprintf('<a href="%s" target="_blank">%s</a>', 'https://profilepress.net/themes', __('Themes', 'profilepress')),
        'extensions' => sprintf('<a href="%s" target="_blank">%s</a>', 'https://profilepress.net/extensions/', __('Extensions', 'profilepress')),
    );

    // add the links to the front of the actions list
    return array_merge($custom_actions, $actions);
}