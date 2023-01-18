<?php
/**
 * Destroy hybridauth session when a user logs out.
 */
function pp_destroy_hybridauth_session()
{

    Hybrid_Auth::logoutAllProviders();

    $_SESSION["HA::STORE"]  = array();
    $_SESSION['HA::CONFIG'] = array();
}

add_action('wp_logout', 'pp_destroy_hybridauth_session');