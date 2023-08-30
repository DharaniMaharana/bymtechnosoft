<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'bymtechnosoft' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'q!FRlR2?8A:D=3 QYu,@lGDu&}dU32N+&:}1#!>.`D7.~r6Be]8E,)%9vG,mB^|T' );
define( 'SECURE_AUTH_KEY',  'vh}BKgHh*U`*_D] 6[,]IodzP.U>abF!Qxo{Wwl/_!#, >7/3OtEga.nw,c9F*gc' );
define( 'LOGGED_IN_KEY',    'gV4e`2^0rH:F,cV5@wnZZ%:Q <9,~j}khU/G#~,D8uN|;X+~9C2O/lR(diB6r a7' );
define( 'NONCE_KEY',        'T]m!pXlz-,?-bIQE:x+7@3^?ZgyX [.B@(?AIGS2Pt9q;NGF.orxmb{KIU+jE=OA' );
define( 'AUTH_SALT',        'Q!2<#hGN7&+q|gCm06X>ku!V!kB3dNRe:U[%wUwBvTtHY8cvK.U[o(n@xgRG!JZa' );
define( 'SECURE_AUTH_SALT', 'AQZrkY(360!kl$(1EgflQVde7dwTaKs&1W?.I@y`%;#!8e|c~XC(RrUnQou/b.]r' );
define( 'LOGGED_IN_SALT',   '.<O>)q+5sBdU8J<X=W}le<?:hBp3]w?Y)UWl&;pg 8,` #b<L4>xyEtmf[IIIUK.' );
define( 'NONCE_SALT',       'irn9iGG>kW2,,o53&H)E|xpU1%luZcACp9&ld(JN+<xmmWD<NWV!*H&s4z8P2%,g' );

/**#@-*/

/**
 * WordPress database table prefix.
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
