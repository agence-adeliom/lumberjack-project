<?php

declare(strict_types=1);

return [
    /**
     * The current application environment
     */
    'environment' => getenv('WP_ENV'),

    /**
     * Is debug mode enabled?
     */
    'debug' => WP_DEBUG,

    /**
     * List of providers to initialise during app boot
     */
    'providers' => [
        // Http Login Provider
        App\Providers\HttpLoginServiceProvider::class,
        // Lumberjack Providers
        Rareloop\Lumberjack\Providers\RouterServiceProvider::class,
        Rareloop\Lumberjack\Providers\WordPressControllersServiceProvider::class,
        Rareloop\Lumberjack\Providers\TimberServiceProvider::class,
        Rareloop\Lumberjack\Providers\CustomPostTypesServiceProvider::class,
        Rareloop\Lumberjack\Providers\MenusServiceProvider::class,
        Rareloop\Lumberjack\Providers\LogServiceProvider::class,
        Rareloop\Lumberjack\Providers\ThemeSupportServiceProvider::class,
        Rareloop\Lumberjack\Providers\QueryBuilderServiceProvider::class,
        Rareloop\Lumberjack\Providers\SessionServiceProvider::class,
        Rareloop\Lumberjack\Providers\EncryptionServiceProvider::class,

        // Adeliom Providers
        \Adeliom\Lumberjack\Admin\AdminProvider::class,
        \Adeliom\Lumberjack\Cron\CronProvider::class,
        \Adeliom\Lumberjack\Hooks\HookProvider::class,
        \Adeliom\Lumberjack\Taxonomy\CustomTaxonomyServiceProvider::class,
        \Adeliom\Lumberjack\Assets\AssetsProvider::class,

        // Application Providers
        App\Providers\AppServiceProvider::class,
        App\Providers\QueryBuilderProvider::class,
        App\Providers\HttpLoginServiceProvider::class,
    ],

    'aliases' => [
        'Config' => Rareloop\Lumberjack\Facades\Config::class,
        'Log' => Rareloop\Lumberjack\Facades\Log::class,
        'Router' => Rareloop\Lumberjack\Facades\Router::class,
        'Session' => Rareloop\Lumberjack\Facades\Session::class,
    ],

    /**
     * Logs enabled, path and level
     *
     * When path is `false` the default Apache/Nginx error logs are used. By setting path to a string, no logs will be sent
     * to the default and instead a file will be created. To disable all logging output set `enabled` to `false`.
     */
    'logs' => [
        'enabled' => true,
        'path' => false,
        'level' => Monolog\Logger::ERROR,
    ],

    'themeSupport' => [
        'post-thumbnails'
    ],

    /**
     * The key used by the Encrypter. This should be a random 32 character string.
     */
    'key' => getenv('APP_KEY'),
];
