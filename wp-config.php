<?php

// BEGIN iThemes Security - Do not modify or remove this line
// iThemes Security Config Details: 2
define( 'DISALLOW_FILE_EDIT', true ); // Disable File Editor - Security > Settings > WordPress Tweaks > File Editor
// END iThemes Security - Do not modify or remove this line

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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'oudhiya' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '&lUpYuw89&_s8236L@Y?4;hDEl=6dJ#6bBrZi7vk]W<v@@i*vfpBcX7$KV-{9XhN' );
define( 'SECURE_AUTH_KEY',  'Nh-&kD^m0vSBKmY% C)S+f;QIt9Z,Y+(EQO$,h+%u~NfeE4V6mK qy+};]zi0^_u' );
define( 'LOGGED_IN_KEY',    '6rd-dU7,`rkKyZgI6zR U2|5;XQ+ISbObe4+b-(mWpK^=BbYcwv6KB5MVhF/~f*1' );
define( 'NONCE_KEY',        '^=?YhIUVp?Qkbb2!acd:Ld72s(Eu%&x1V>=/d3E_3Z8ksNx0@}@HoO2{+^CBs2XG' );
define( 'AUTH_SALT',        '.2!!|O}eN.1v)w}*R6xr%K4^^G }oAr}=CTPP:_<^]fENd5I)<AATrA/rY4o.TAE' );
define( 'SECURE_AUTH_SALT', 'fWpfe>_:xdebz!H {O%Q2v 8m*C!X7QwJDk}L`_{me[L[S)zvy,ok9ea17UExVF{' );
define( 'LOGGED_IN_SALT',   'avQ$XIF|,Xi<vP&X.cNODW4FPT#=_%yU7Ec>wpF<MT.L l9uaSzTz-T(NKbhiSE9' );
define( 'NONCE_SALT',       'zL*BkenWiAM4sdBiW$_Jl1b>Iq<h%?28^Y6F*#&e(bbG{;S>w,0ih|?X]?]f/Tny' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
