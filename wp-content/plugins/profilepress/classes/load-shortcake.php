<?php
require CLASSES . '/shortcake-tinymce-button.php';

$pp_builder_pages = array(
    REGISTRATION_BUILDER_SETTINGS_PAGE_SLUG,
    LOGIN_BUILDER_SETTINGS_PAGE_SLUG,
    PASSWORD_RESET_BUILDER_SETTINGS_PAGE_SLUG,
    EDIT_PROFILE_BUILDER_SETTINGS_PAGE_SLUG,
    USER_PROFILE_BUILDER_SETTINGS_PAGE_SLUG,
    MELANGE_SETTINGS_PAGE_SLUG
);


if (isset($_GET['page']) && in_array($_GET['page'], $pp_builder_pages)) {
    require CLASSES . '/global-shortcodes/shortcake.php';
}

if (isset($_GET['page']) && $_GET['page'] == REGISTRATION_BUILDER_SETTINGS_PAGE_SLUG) {
    require VIEWS . '/registration-form-builder/shortcake.php';
}

if (isset($_GET['page']) && $_GET['page'] == LOGIN_BUILDER_SETTINGS_PAGE_SLUG) {
    require VIEWS . '/login-form-builder/shortcake.php';
}

if (isset($_GET['page']) && $_GET['page'] == PASSWORD_RESET_BUILDER_SETTINGS_PAGE_SLUG) {
    require VIEWS . '/password-reset-builder/shortcake.php';
}
if (isset($_GET['page']) && $_GET['page'] == EDIT_PROFILE_BUILDER_SETTINGS_PAGE_SLUG) {
    require VIEWS . '/edit-user-profile/shortcake.php';
}

if (isset($_GET['page']) && $_GET['page'] == USER_PROFILE_BUILDER_SETTINGS_PAGE_SLUG) {
    require VIEWS . '/front-end-user-profile/shortcake.php';
}

if (isset($_GET['page']) && $_GET['page'] == MELANGE_SETTINGS_PAGE_SLUG) {

    require VIEWS . '/melange/shortcake.php';
    require VIEWS . '/login-form-builder/shortcake.php';
    require VIEWS . '/registration-form-builder/shortcake.php';
    require VIEWS . '/password-reset-builder/shortcake.php';
    require VIEWS . '/edit-user-profile/shortcake.php';
    require VIEWS . '/front-end-user-profile/shortcake.php';
}
