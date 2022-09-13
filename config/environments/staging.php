<?php
/**
 * Configuration overrides for WP_ENV === 'staging'
 */

use Roots\WPConfig\Config;

/**
 * You should try to keep staging as close to production as possible. However,
 * should you need to, you can always override production configuration values
 * with `Config::define`.
 *
 * Example: `Config::define('WP_DEBUG', true);`
 * Example: `Config::define('DISALLOW_FILE_MODS', false);`
 */

Config::define('WP_DEBUG', false);
Config::define('DISALLOW_FILE_MODS', true);

Config::define('DISALLOW_INDEXING', true);

Config::define('ENABLED_HTTP_LOGIN', true);
//Config::define('HTTP_USER', "example"); // default : adeliom
//Config::define('HTTP_PASSWD', password_hash("example", PASSWORD_DEFAULT));  // default : @deliom[YEAR]! -> ex: @deliom2022!
