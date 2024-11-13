<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'testweb' );

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
define( 'AUTH_KEY',         '8*T?)!3Jo60GpiN;1~fk;4bhJJ1E&*shKwn7k<Y%2VYz@vGyY6Mt74L-DLtDPDSr' );
define( 'SECURE_AUTH_KEY',  ']f({*l5g|]<3Qv`Yw|Ly>Du=(k(iqzPj=XH}pqFE/&,^`7w?TzZS8$4T;i[53Tb`' );
define( 'LOGGED_IN_KEY',    'jV0hfuoHrQ3.Wge4>;(RGu1RPN1#My:Lbd5-j0+{#E+gmP;vD1[a(DJ11+G 5H/%' );
define( 'NONCE_KEY',        'h}*07QCUj?yIU6a$ejgS#DS$c6&A}=d%k5l`{DV<5uTd-kB{Fcxy92k[B]xa;_9*' );
define( 'AUTH_SALT',        'zi= oLwN%MW@1ymS,E2BxYk4m36?`i>Q$Mvi<y$VtAn&#Mf:C#yYhu%[wu# c4Sl' );
define( 'SECURE_AUTH_SALT', 'T]2xJ|Sbd8.a!}ixX(BHg+)GpHC,Rkl-!9Z1vR22o>B:3Z=)W8;E8!?g3a4`ZztL' );
define( 'LOGGED_IN_SALT',   'i}}bu@qP0e8NoLgbNRf{.n0&29NrDJ#/wB(gxNhAIbkS 1WXh`oI;0z6`m-B^^n1' );
define( 'NONCE_SALT',       '@/if`XyXoKB0_G43BOL#UKsKR%cI~d ;Id@v]Pf$/5VW&3co|.OQfn.taZ[RM};t' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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
