<?php

/** @var string Directory containing all of the site's files */
$root_dir = dirname(__FILE__);

/**
 * Require autoload vendors
 */
if (file_exists($root_dir . '/../../vendor/autoload.php')) {
    require_once($root_dir . '/../../vendor/autoload.php');
} else {
    echo 'Not found vendor/autoload.php';exit;
}

/**
 * Expose global env() function from oscarotero/env
 */
Env::init();

/**
 * Use Dotenv to set required environment variables and load .env file in root
 */
$dotenv = new Dotenv\Dotenv($root_dir);
if (file_exists($root_dir . '/.env')) {
    $dotenv->load();
    $dotenv->required(['DB_NAME', 'DB_USER', 'DB_PASSWORD', 'WP_HOME', 'WP_SITEURL']);
}

/**
 * Set up our global environment constant and load its config first
 * Default: production
 * Values: develop, production
 */
define('WP_ENV', env('WP_ENV') ?: 'production');
$env_config = $root_dir . '/environments/' . WP_ENV . '.php';
if (file_exists($env_config)) {
    require_once $env_config;
}

/**
 * URLs
 */
define('WP_HOME', env('WP_HOME'));
define('WP_SITEURL', env('WP_SITEURL'));

/**
 * Custom Content Directory
 */
define('WP_CONTENT_DIR', $root_dir . '/../content');
define('WP_CONTENT_URL', WP_HOME . '/wp-content');

/**
 * DB settings
 */
define('DB_NAME', env('DB_NAME'));
define('DB_USER', env('DB_USER'));
define('DB_PASSWORD', env('DB_PASSWORD'));
define('DB_HOST', env('DB_HOST') ?: 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');
$table_prefix = env('DB_PREFIX') ?: 'wp_';

/**
 * Authentication Unique Keys and Salts
 */
define('AUTH_KEY', env('AUTH_KEY'));
define('SECURE_AUTH_KEY', env('SECURE_AUTH_KEY'));
define('LOGGED_IN_KEY', env('LOGGED_IN_KEY'));
define('NONCE_KEY', env('NONCE_KEY'));
define('AUTH_SALT', env('AUTH_SALT'));
define('SECURE_AUTH_SALT', env('SECURE_AUTH_SALT'));
define('LOGGED_IN_SALT', env('LOGGED_IN_SALT'));
define('NONCE_SALT', env('NONCE_SALT'));

/**
 * Language
 * Leave blank for American English
 */
define( 'WPLANG', '' );

/**
 * Load a Memcached config if we have one
 */
if ( file_exists( $root_dir . '/memcached.php' ) )
    $memcached_servers = include( $root_dir . '/memcached.php' );

/**
 * Disable ftp config in admin panel
 */
if(is_admin()) {
    add_filter('filesystem_method', create_function('$a', 'return "direct";' ));
    define( 'FS_CHMOD_DIR', 0751 );
}

/**
 * Bootstrap WordPress
 */
if (!defined('ABSPATH')) {
    define('ABSPATH', $root_dir . '/core/');
}
require_once( ABSPATH . 'wp-settings.php' );