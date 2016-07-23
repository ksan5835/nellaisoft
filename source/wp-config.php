<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

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
define('DB_NAME', 'nellaisoft');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         '{?DR/nbF*BG?NcKri>h{x8Ph@26q&c*-HA.]n0SgGuKzmKX8_AO@JBmZ~*iIPDLo');
define('SECURE_AUTH_KEY',  'X,6]=9o2i:a.d.<]Ee?eoda%7%:Z]Agj0Wa$F//C ^yOMf_zX,l<OoTf+8-Ugu8M');
define('LOGGED_IN_KEY',    'ux*s,*Zu#h}X2L+H?+rubhBcVr`Ok01(F)r>o=+{ETMS0(`Phi7lMoz3<*s^DBO)');
define('NONCE_KEY',        'd3qD>Sff.)Uvmat0%[f?h$)~iZ<P?i,@A+N8(z<MvVR rEH(Q,E<nX0fuq4A;nq+');
define('AUTH_SALT',        'QH0<JNJr5HD8!DcyJQb igUeS6V,CPQ~:tI/G!v: -+.+Wul>(&n0Q*trsI3o]Tr');
define('SECURE_AUTH_SALT', 'G*ZQf31sQCpJ8p;Q-(SQNv&D^E-2tw:HHk%2-Am7S[k2^bjU3Uy9b4L8~r54!GXg');
define('LOGGED_IN_SALT',   'Idpl5m%(7x9zC7jjAOrVa7a:)<Y5#^h5;/QhJfFw[7H#rTe2/Q9I)3X^]Nt[V7*u');
define('NONCE_SALT',       '-~DJW|$m[N6eczC.W0{[G@l96!V=lI7%xeSn5w;CE+_]O8=levI[&z![eo%5PZT`');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'ns_';

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
