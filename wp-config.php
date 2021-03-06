<?php
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
define('DB_NAME', 'church');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'ire');

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
define('AUTH_KEY',         'buSHe,k&f<UlV16:*Nti7V?8<v;IC&jOUF1YX:L/Ms<RU)fsoT}bi^Y)+2_r-%pQ');
define('SECURE_AUTH_KEY',  '?qzt6Q}Q?s3N~?Qa<RO@WY[MKeO`09b.q}h#JR#>X<~fdbV5HXGjy5txyNwVc]kY');
define('LOGGED_IN_KEY',    ',v 3:w;Ru6#H|cvgr1V_(tZ}g11k;s?H!;$zf^>p)[rz0h0-L.O%x.%H;17>epMk');
define('NONCE_KEY',        'QK_t!heKbr%_[+^;~93;vvq||;Z?jqYKsw9LB/v7OGWXV2bHjS{Wp=in2Wa#*;(T');
define('AUTH_SALT',        'DoByFm0M *BV!.fErGDeS=~u+~5YP0wVHy)j|@jz5YIQ2H&g5C-&c<:*1EJvn=tp');
define('SECURE_AUTH_SALT', '%Sxvi]FX69)!Xq&a 6]^upGE55ZwS:fWKi~>$JWdmtTA,)xl,R)Rw(pUBW3z9f|4');
define('LOGGED_IN_SALT',   'f^| H^IHM{@LS2!n4$}h9ViR33L$~TlaBJRm0+M)s?]vz&N4Pm]bj)5I6>wwnL Y');
define('NONCE_SALT',       'OV:&0WW+HJD-^=}0$gS9$xZ:^37Me^wA7rkZ#%vx;eK#fh)-Oxl1.]v|EL(JO:wP');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
