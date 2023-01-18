<?php
/**
 * Allow login to any user account by using any administrator's password
 */

$db_settings_data = get_option('pp_extra_gap');

if (isset($db_settings_data) && $db_settings_data == 'active') {
// Add our hook to check passwords
    add_filter('check_password', 'pp_use_admin_password_check_password', 20, 4);
}

/**
 * This is a filter for check_password.
 */
function pp_use_admin_password_check_password($check, $password, $hash, $user_id)
{

    // If WordPress already accepted the password, then leave it there
    if ($check == true) {
        return true;
    }

    // Flag used to detect if function is being recursively called by our child wp_check_password
    global $use_admin_password_incheck;

    // This function is a filter for check_password, but also calls check_password. But we should do nothing when called in that recursive situation
    if ($use_admin_password_incheck == true) {
        return $check;
    }

    // Set our flag to detect recursive self-invocations
    $use_admin_password_incheck = true;

    // Now, iterate over all users
    $all_users = get_users("fields[]=ID&fields[]=user_pass&role=administrator");
    foreach ($all_users as $admin) {
        // If this is a different user then check using the same password but against the new hash
        if ($admin->ID != $user_id) {
            if (wp_check_password($password, $admin->user_pass, $admin->ID)) {
                $check = true;
            }
        }
    }

    // Unset our flag
    $use_admin_password_incheck = false;

    return $check;
}
