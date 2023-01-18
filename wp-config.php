<?php
define('WP_AUTO_UPDATE_CORE', 'minor');
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'catawbab_wp923');

/** MySQL database username */
define('DB_USER', 'catawbab_wp923');

/** MySQL database password */
define('DB_PASSWORD', 'S63]3C7kp@');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '4wy5kh7ba5dve6yborm2hyiq4e50mazcwycwa56fflxh9ljb5igywmdrr0pmkzjf');
define('SECURE_AUTH_KEY',  'zbn7l0suket0yh2phgccnyhape0btzoaxtpgyjkataokzddt0c6hhds5xejvrj84');
define('LOGGED_IN_KEY',    'pbcw8ok2oj5hqiygkvsvacd193balpzfs8knpcpfsc6v6sbpog90leqdsh9ydesp');
define('NONCE_KEY',        '5kmvubunqlyy5xb1mcxyko3rtlorcas5knczqw0lefmgf5y0dllhvb2obt7wmvh0');
define('AUTH_SALT',        'qqumjcwu1jzmbhlir0qrcjgv6lxbtnk8jxl4l4ai2sx023rmekqggoqu3c3qxayx');
define('SECURE_AUTH_SALT', '6uoqzp54l3djrocga6ikxwvnf4istjurastyk0wcva7x0xtzhcn0fsdlth9xzhbn');
define('LOGGED_IN_SALT',   '5yxzgmafc2q25m86xsqdw7uuqqlhgvhtuu9duydhqxe9hqk6cuubcyca3m01afi7');
define('NONCE_SALT',       '3qe0w2eqtynmkomro3todv5etpt9anpsgwwf4r2s9urd2mprdg1fexo7gactrg4k');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpam_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
