<?php
/**
 * Your base production configuration goes in this file. Environment-specific
 * overrides go in their respective config/environments/{{WP_ENV}}.php file.
 *
 * A good default policy is to deviate from the production config as little as
 * possible. Try to define as much of your configuration in this file as you
 * can.
 */

use Roots\WPConfig\Config;
use Symfony\Component\Dotenv\Dotenv;

use function Env\env;

/**
 * Directory containing all of the site's files
 *
 * @var string
 */
$root_dir = dirname(__DIR__);

/**
 * Document Root
 *
 * @var string
 */
$webroot_dir = $root_dir . '/web';

/**
 * Use Dotenv to set required environment variables and load .env file in root
 */
$dotenv = new Dotenv($root_dir);
$dotenv
    ->usePutenv()
    ->loadEnv($root_dir.'/.env')
;

$isDdevProject = isset($_ENV['IS_DDEV_PROJECT'])&& $_ENV['IS_DDEV_PROJECT'];

if (empty(env('WP_HOME'))) {
    throw new \ErrorException("WP_HOME var is missing");
}
if (!$isDdevProject && !env('DATABASE_URL')) {
    if (empty(env('DB_NAME'))) {
        throw new \ErrorException("DB_NAME var is missing");
    }
    if (empty(env('DB_USER'))) {
        throw new \ErrorException("DB_USER var is missing");
    }
    if (empty(env('DB_PASSWORD'))) {
        throw new \ErrorException("DB_PASSWORD var is missing");
    }
}


/**
 * Set up our global environment constant and load its config first
 * Default: production
 */
define('WP_ENV', env('APP_ENV') ?: 'production');

/**
 * Sentry
 */
define('WP_ENVIRONMENT_TYPE', WP_ENV);
if (env('WP_SENTRY_DSN') && in_array(WP_ENVIRONMENT_TYPE, ["staging", "production"])) {
    Config::define('WP_SENTRY_ENV', WP_ENV);
    Config::define('WP_SENTRY_PHP_DSN', env('WP_SENTRY_DSN'));
    Config::define('WP_SENTRY_BROWSER_DSN', env('WP_SENTRY_DSN'));
    Config::define('WP_SENTRY_ERROR_TYPES', env('WP_SENTRY_ERROR_TYPES') ?? E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_USER_DEPRECATED);
    Config::define('WP_SENTRY_SEND_DEFAULT_PII', true);
    Config::define('WP_SENTRY_BROWSER_TRACES_SAMPLE_RATE', env('WP_SENTRY_BROWSER_TRACES_SAMPLE_RATE') ?? 0.3);
}

/**
 * URLs
 */
Config::define('WP_HOME', $isDdevProject ? 'https://'.$_ENV['DDEV_HOSTNAME'] : env('WP_HOME'));
Config::define('WP_SITEURL', Config::get('WP_HOME') . '/wp');

/**
 * Custom Content Directory
 */
Config::define('CONTENT_DIR', '/app');
Config::define('WP_CONTENT_DIR', $webroot_dir . Config::get('CONTENT_DIR'));
Config::define('WP_CONTENT_URL', Config::get('WP_HOME') . Config::get('CONTENT_DIR'));
Config::define('WP_DEFAULT_THEME', env('WP_DEFAULT_THEME') ?? "adeliom");

/**
 * DB settings
 */
Config::define('DB_NAME', $isDdevProject ? $_ENV['PGDATABASE'] : env('DB_NAME'));
Config::define('DB_USER', $isDdevProject ? $_ENV['PGUSER'] : env('DB_USER'));
Config::define('DB_PASSWORD', $isDdevProject ? $_ENV['PGPASSWORD'] : env('DB_PASSWORD'));
Config::define('DB_HOST', $isDdevProject ? $_ENV['PGHOST'] : (env('DB_HOST') ?: 'localhost'));
Config::define('DB_CHARSET', 'utf8mb4');
Config::define('DB_COLLATE', '');
$table_prefix = env('DB_PREFIX') ?: 'wp_';

if (env('DATABASE_URL')) {
    $dsn = (object) parse_url(env('DATABASE_URL'));

    Config::define('DB_NAME', substr($dsn->path, 1));
    Config::define('DB_USER', $dsn->user);
    Config::define('DB_PASSWORD', isset($dsn->pass) ? $dsn->pass : null);
    Config::define('DB_HOST', isset($dsn->port) ? "{$dsn->host}:{$dsn->port}" : $dsn->host);
}

/**
 * Authentication Unique Keys and Salts
 */
Config::define('AUTH_KEY', env('AUTH_KEY'));
Config::define('SECURE_AUTH_KEY', env('SECURE_AUTH_KEY'));
Config::define('LOGGED_IN_KEY', env('LOGGED_IN_KEY'));
Config::define('NONCE_KEY', env('NONCE_KEY'));
Config::define('AUTH_SALT', env('AUTH_SALT'));
Config::define('SECURE_AUTH_SALT', env('SECURE_AUTH_SALT'));
Config::define('LOGGED_IN_SALT', env('LOGGED_IN_SALT'));
Config::define('NONCE_SALT', env('NONCE_SALT'));

/**
 * Custom Settings
 */
Config::define('AUTOMATIC_UPDATER_DISABLED', true);
Config::define('ALLOW_UNFILTERED_UPLOADS', true);
Config::define('DISABLE_WP_CRON', env('DISABLE_WP_CRON') ?: false);
// Disable the plugin and theme file editor in the admin
Config::define('DISALLOW_FILE_EDIT', true);
// Disable plugin and theme updates and installation from the admin
Config::define('DISALLOW_FILE_MODS', true);
// Limit the number of post revisions that Wordpress stores (true (default WP): store every revision)
Config::define('WP_POST_REVISIONS', env('WP_POST_REVISIONS') ?: true);

/**
 * Debugging Settings
 */
Config::define('WP_DEBUG', env('APP_DEBUG') ?? false);
Config::define('WP_DEBUG_DISPLAY', env('WP_DEBUG_DISPLAY') ?? false);
Config::define('WP_DEBUG_LOG', Config::get('WP_DEBUG') ? "debug.log" : false);
Config::define('SCRIPT_DEBUG', false);

error_reporting(E_ALL & ~E_WARNING & ~E_DEPRECATED & ~E_USER_DEPRECATED & ~E_NOTICE);
@ini_set('display_errors', '0');

/**
 * Allow WordPress to detect HTTPS when used behind a reverse proxy or a load balancer
 * See https://codex.wordpress.org/Function_Reference/is_ssl#Notes
 */
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

$env_config = __DIR__ . '/environments/' . WP_ENV . '.php';

if (file_exists($env_config)) {
    require_once $env_config;
}

Config::apply();

/**
 * Bootstrap WordPress
 */
if (!defined('ABSPATH')) {
    define('ABSPATH', $webroot_dir . '/wp/');
}
