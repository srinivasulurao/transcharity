<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'i953682_wp6');

/** MySQL database username */
define('DB_USER', 'i953682_wp6');

/** MySQL database password */
define('DB_PASSWORD', 'B#5&WUzwV366(*6');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'uyB2oAsdSTZgdk5gd9Ncs4ArvAV49cfoEw1Iyd48LcD6owDfFnJiYn54xF2PKTxv');
define('SECURE_AUTH_KEY',  'og844nj4cfSHdGiQvQHo8KWFh6Iw6si6iQZEKuR8kniWV987wV0QzbhrXmWxlS36');
define('LOGGED_IN_KEY',    'aYCpPZN9IrGo9vMaXlKf37zv2Px1bDUItKVFb69mm4Gv8bkRjhFMzv5tpPGtFiWC');
define('NONCE_KEY',        'YvUTDjLeFWPjjXp4vU6Bs9eQAGMQBgu0221Gf1MB3YAiZCQvYMIHk2EeH3OLLp9k');
define('AUTH_SALT',        'Ip4sdouBPMhaOOyeZMLptJmsswRcyT4iXbbn8pPjeBs1rDH3aS2rSifqrOOIYMqR');
define('SECURE_AUTH_SALT', 'Bzgca2TbeR3QOlqzK0pmb5GB1GTjYXfosikIyCoZ5zkS0pP591hIKBqyEqJdtZVb');
define('LOGGED_IN_SALT',   'StddalwUaCYGI1lLqSw8YCLMg3cwTfpN1BQTFszJuY8DSox9ACx8OuJNuuyaWVIf');
define('NONCE_SALT',       'Rap12XGygoTyUinFh99u2FdlgpQiBH3Z1Ur7ibnvspMnNjoyTSSlln7fsyaVMRgn');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
