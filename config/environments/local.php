<?php
/**
 * Configuration overrides for WP_ENV === 'local'
 */

use Roots\WPConfig\Config;

Config::define('WP_DEBUG', true);
Config::define('WP_DEBUG_DISPLAY', true);
ini_set('display_errors', '1');

// Enable plugin and theme updates and installation from the admin
Config::define('DISALLOW_FILE_MODS', false);
Config::define('DISALLOW_INDEXING', true);




