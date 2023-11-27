<?php

namespace Adeliom\Installer;

use Composer\Script\Event;
use Symfony\Component\Dotenv\Dotenv;

class Install
{
    public const CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!"#$%&()*+,-./:;<=>?@[]^_`{|}~';

    /**
     * Builds the environment for the given event.
     *
     * @param Event $event The event object for which the environment is being built.
     *
     * @return void
     */
    public static function buildEnv(Event $event)
    {
        $envFilePath    = dirname(__DIR__) . "/.env";
        $envExamplePath = dirname(__DIR__) . "/.env.example";

        $projectENV = !is_file($envFilePath) ? file_get_contents($envExamplePath) : file_get_contents($envFilePath);

        $projectVars = self::generateProjectVars();

        self::updateEnvironmentFile($envFilePath, $projectENV, $projectVars);

        if (getenv('IS_DDEV_PROJECT')) {
            self::updateLocalEnvironmentFile();
            self::pluginEnv();
        }
    }

    /**
     * Generates project variables based on the given environment file path.
     *
     *
     * @return array The generated project variables.
     */
    public static function generateProjectVars(): array
    {
        $installLockPath = dirname(__DIR__) . "/.installer/install.lock";

        if (!is_file($installLockPath)) {
            $salts       = self::generateSalts();
            $appKey      = self::generateAppKey();
            $projectVars = [
                "APP_KEY" => $appKey
            ];

            $projectVars += $salts;
        } else {
            $projectVars = json_decode(file_get_contents($installLockPath), true);
        }

        return $projectVars;
    }

    /**
     * Updates the environment file with the given project variables.
     *
     * @param string $envFilePath The path to the environment file.
     * @param string $projectENV The current content of the environment file.
     * @param array $projectVars An associative array of project variables to update.
     *
     * @return void
     */
    public static function updateEnvironmentFile($envFilePath, $projectENV, $projectVars)
    {
        foreach ($projectVars as $k => $v) {
            $projectENV = preg_replace('/^'.$k.'=?.+$/m', $k . '=' . $v, $projectENV);
        }

        file_put_contents($envFilePath, $projectENV);

        $lockFilePath = dirname(__DIR__) . "/.installer/install.lock";

        if (!is_file($lockFilePath)) {
            file_put_contents($lockFilePath, json_encode($projectVars, JSON_PRETTY_PRINT));
        }
    }

    /**
     * Generate salts.
     *
     * @return array An array of salts generated.
     */
    public static function generateSalts()
    {
        $salts = [];
        $keys  = [ 'AUTH', 'SECURE_AUTH', 'LOGGED_IN', 'NONCE' ];
        foreach ($keys as $key) {
            $salts[ $key . '_KEY' ]  = self::salt();
            $salts[ $key . '_SALT' ] = self::salt();
        }

        return $salts;
    }

    /**
     * Generates an application key.
     *
     * @return string the generated application key.
     */
    public static function generateAppKey()
    {
        return "'" . 'base64:' . base64_encode(sha1(random_int(1, 90000) . self::salt(32))) . "'";
    }

    /**
     * Generates a random string of characters.
     *
     * @param int $length The length of the random string (default: 64).
     *
     * @return string The randomly generated string.
     */
    public static function salt($length = 64): string
    {
        $charactersLength = strlen(self::CHARS);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= self::CHARS[ random_int(0, $charactersLength - 1) ];
        }

        return "'" . $randomString . "'" ;
    }

    /**
     * Updates the local environment file with the necessary variables.
     */
    public static function updateLocalEnvironmentFile(): void
    {
        $localVars = [
            "APP_ENV"     => "local",
            "DB_HOST"     => getenv("PGHOST"),
            "DB_NAME"     => getenv("PGDATABASE"),
            "DB_USER"     => getenv("PGUSER"),
            "DB_PASSWORD" => getenv("PGPASSWORD"),
            "WP_HOME"     => 'https://' . getenv('DDEV_HOSTNAME')
        ];

        $localENVPath = dirname(__DIR__) . "/.env.local";

        if (!is_file($localENVPath)) {
            $initialContent = implode('=' . PHP_EOL, array_keys($localVars)) . '=' . PHP_EOL;
            file_put_contents($localENVPath, $initialContent);
        }

        $localENV = file_get_contents($localENVPath);
        foreach ($localVars as $key => $value) {
            $localENV = preg_replace('/^'.$key.'=?.+$/m', $key . '=' . $value, $localENV);
        }

        file_put_contents($localENVPath, $localENV);
    }

    /**
     * Dumps environment variables to a file.
     *
     * @param Event $event The event object for dumping environment variables.
     *
     * @return int Exit code indicating success or failure.
     */
    public static function dumpEnv(Event $event)
    {
        $io = $event->getIO();

        $path = dirname(__DIR__) . '/.env';

        $vars = self::loadEnv($path, null);
        $env  = $vars['APP_ENV'];

        $vars = var_export($vars, true);
        $vars = <<<EOF
<?php

// This file was generated by running "composer dump-env $env"

return $vars;

EOF;
        file_put_contents($path . '.local.php', $vars, \LOCK_EX);

        $io->writeError('Successfully dumped .env files in <info>.env.local.php</>');

        return 0;
    }

    /**
     * Loads environment variables from the given path and environment name.
     *
     * @param string $path The path to the environment file.
     * @param string|null $env The name of the environment.
     *
     * @return array An array of loaded environment variables.
     *
     * @throws \RuntimeException If unable to load environment variables.
     */
    private static function loadEnv(string $path, ?string $env): array
    {
        if (!file_exists($autoloadFile = dirname(__DIR__) . '/vendor/autoload.php')) {
            throw new \RuntimeException(sprintf('Please run "composer install" before running this command: "%s" not found.', $autoloadFile));
        }

        require $autoloadFile;

        if (!class_exists(Dotenv::class)) {
            throw new \RuntimeException('Please run "composer require symfony/dotenv" to load the ".env" files configuring the application.');
        }

        $globalsBackup = [ $_SERVER, $_ENV ];
        unset($_SERVER['APP_ENV']);
        $_ENV                           = [ 'APP_ENV' => $env ];
        $_SERVER['SYMFONY_DOTENV_VARS'] = implode(',', array_keys($_SERVER));
        putenv('SYMFONY_DOTENV_VARS=' . $_SERVER['SYMFONY_DOTENV_VARS']);

        try {
            $dotenv = new Dotenv();

            if (!$env && file_exists($p = "$path.local")) {
                $env = $_ENV['APP_ENV'] = $dotenv->parse(file_get_contents($p), $p)['APP_ENV'] ?? null;
            }

            if (!$env) {
                throw new \RuntimeException('Please provide the name of the environment either by using the "--env" command line argument or by defining the "APP_ENV" variable in the ".env.local" file.');
            }

            if (method_exists($dotenv, 'loadEnv')) {
                $dotenv->loadEnv($path);
            } else {
                // fallback code in case your Dotenv component is not 4.2 or higher (when loadEnv() was added)
                $dotenv->load(file_exists($path) || !file_exists($p = "$path.dist") ? $path : $p);

                if ('test' !== $env && file_exists($p = "$path.local")) {
                    $dotenv->load($p);
                }

                if (file_exists($p = "$path.$env")) {
                    $dotenv->load($p);
                }

                if (file_exists($p = "$path.$env.local")) {
                    $dotenv->load($p);
                }
            }

            unset($_ENV['SYMFONY_DOTENV_VARS']);
            $env = $_ENV;
        } finally {
            list($_SERVER, $_ENV) = $globalsBackup;
        }

        return $env;
    }

    /**
     * Generates the function comment for the given function.
     */
    public static function pluginEnv(): void
    {
        echo "\n\e[1;32mWelcome to the plugin configurator!\e[0m\n\n";

        $plugins = [
            1 => "Gravity Forms Pro",
            2 => "ACF Pro",
            3 => "FontAwesome Pro"
        ];

        $existingConfigs = self::checkExistingConfig($plugins);
        $availablePlugins = self::getAvailablePlugins($plugins, $existingConfigs);

        if (empty($availablePlugins)) {
            echo "\e[1;32mNo plugins available for configuration. All plugins are already configured.\e[0m\n\n";
            return;
        }

        $configData = [];

        foreach ($availablePlugins as $plugin) {
            if ($plugin === 'Gravity Forms Pro' && !$existingConfigs[1]) {
                $configData = array_merge($configData, self::configureGravityForms());
            } elseif ($plugin === 'ACF Pro' && !$existingConfigs[2]) {
                $configData = array_merge($configData, self::configureACFPro());
            } elseif ($plugin === 'FontAwesome Pro' && !$existingConfigs[3]) {
                $configData = array_merge($configData, self::configureFontAwesome());
            }
        }

        self::writeConfigToFile($configData);

        echo "\n\e[1;32mPlugin configuration completed.\e[0m\n\n";
    }

    /**
     * Returns an array of plugins that are available, based on the given list
     * of plugins and existing configurations.
     *
     * @param array $plugins The list of plugins to check.
     * @param array $existingConfigs The existing configurations.
     * @return array The available plugins.
     */
    private static function getAvailablePlugins(array $plugins, array $existingConfigs): array
    {
        return array_filter($plugins, function ($key) use ($existingConfigs) {
            return !$existingConfigs[$key];
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Check existing configuration for plugins.
     *
     * @param array $plugins An associative array of plugins.
     * @return array An associative array indicating whether each plugin has an existing configuration.
     */
    private static function checkExistingConfig(array $plugins): array
    {
        $existingConfigs = array_fill_keys(array_keys($plugins), false);

        $envFilePath  = '.env';
        $authFilePath = 'auth.json';
        $npmrcFilePath = dirname(__DIR__) . '/web/app/themes/adeliom/.npmrc';

        if (file_exists($envFilePath)) {
            $envContent = file_get_contents($envFilePath);

            $pluginsToCheck = [
                'Gravity Forms Pro' => 'GRAVITY_FORMS_KEY',
            ];

            foreach ($pluginsToCheck as $plugin => $key) {
                if (str_contains($envContent, $key)) {
                    echo "\e[33mConfiguration for $plugin found in .env file.\e[0m\n";
                    $existingConfigs[array_search($plugin, $plugins)] = true;
                }
            }
        } else {
            echo "\e[31mThe .env file does not exist.\e[0m\n";
        }

        if (file_exists($npmrcFilePath)) {
            $npmrcContent = file_get_contents($npmrcFilePath);

            if (str_contains($npmrcContent, '@fortawesome:registry=https://npm.fontawesome.com/')
                && str_contains($npmrcContent, '//npm.fontawesome.com/:_authToken=')) {

                echo "\e[33mConfiguration for FontAwesome Pro found in .npmrc file.\e[0m\n";

                $existingConfigs[array_search('FontAwesome Pro', $plugins)] = true;
            } else {
                echo "\e[33mConfiguration for FontAwesome Pro was not found in the .npmrc file.\e[0m\n";
            }
        } else {
            echo "\e[31mThe .npmrc file does not exist.\e[0m\n";
        }

        if (file_exists($authFilePath)) {
            $authContent = json_decode(file_get_contents($authFilePath), true);

            if (isset($authContent['http-basic']['connect.advancedcustomfields.com'])) {
                echo "\e[33mConfiguration for ACF Pro found in the authentication file.\e[0m\n";
                $existingConfigs[array_search('ACF Pro', $plugins)] = true;
            }
        } else {
            echo "\e[31mThe authentication file does not exist.\e[0m\n";
        }

        return $existingConfigs;
    }


    /**
     * Writes configuration data to the .env.plugins file.
     *
     * @param array $configData Configuration data to write
     *
     * @return void
     */
    private static function writeConfigToFile(array $configData): void
    {
        if (isset($configData['ACF_PRO_USERNAME'], $configData['ACF_PRO_PASSWORD'])) {
            self::writeACFProAuthToFile($configData['ACF_PRO_USERNAME'], $configData['ACF_PRO_PASSWORD']);
        }

        if (isset($configData['GRAVITY_FORMS_KEY'])) {
            self::writePluginConfigToFile('Gravity Forms Pro', 'GRAVITY_FORMS_KEY', $configData['GRAVITY_FORMS_KEY']);
        }

        if (isset($configData['FONTAWESOME_AUTH_TOKEN'])) {
            self::writeFontAwesomeConfigToFile($configData['FONTAWESOME_AUTH_TOKEN']);
        }
    }

    /**
     * Configures parameters for Gravity Forms.
     *
     * @return array Configured parameters for Gravity Forms
     */
    private static function configureGravityForms(): array
    {
        echo "\n\e[1;34mGravity Forms Pro Configuration :\e[0m\n";

        echo "Enter the license key :";
        $password = self::hideInput();

        return [ "GRAVITY_FORMS_KEY" => $password ];
    }

    /**
     * Configures parameters for ACF Pro.
     *
     * @return array Configured parameters for ACF Pro
     */
    private static function configureACFPro(): array
    {
        echo "\n\e[1;34mACF Pro Configuration :\e[0m\n";

        echo "Enter the license key : ";
        $username = self::hideInput();

        if (getenv('IS_DDEV_PROJECT')) {
            $localURL = 'https://' . getenv('DDEV_HOSTNAME');
            echo "\n\n\e[33mDetected DDEV environment. Using local URL: \e[33;1m$localURL\e[0m\n";
        } else {
            echo "\nEnter the website URL : ";
            $localURL = trim(fgets(STDIN));
        }

        return [
            "ACF_PRO_USERNAME" => $username,
            "ACF_PRO_PASSWORD" => $localURL
        ];
    }

    /**
     * Configures parameters for FontAwesome Pro.
     *
     * @return array Configured parameters for FontAwesome Pro
     */
    private static function configureFontAwesome(): array
    {
        echo "\n\e[1;34mFontAwesome Pro Configuration :\e[0m\n";

        echo "Enter the authentication token :";
        $token = self::hideInput();

        return [ "FONTAWESOME_AUTH_TOKEN" => $token ];
    }

    /**
     * Writes configuration data to the .env.plugins file.
     *
     * @param string $pluginName Name of the plugin (e.g., "Gravity Forms", "FontAwesome")
     * @param string $keyName Name of the key (e.g., "GRAVITY_FORMS_KEY", "FONTAWESOME_AUTH_TOKEN")
     * @param string $keyValue Value of the key
     *
     * @return void
     */
    private static function writePluginConfigToFile(string $pluginName, string $keyName, string $keyValue): void
    {
        $envFilePath = '.env';

        if (!file_exists($envFilePath)) {
            file_put_contents($envFilePath, '');
        }

        if (!is_writable($envFilePath)) {
            echo "\n\e[31mThe .env file is not writable.\e[0m\n";
            return;
        }

        $envContent = "$keyName=$keyValue\n";

        file_put_contents($envFilePath, $envContent, FILE_APPEND);
        echo "\n\e[32mFile .env configured successfully for $pluginName.\e[0m\n";
    }

    /**
     * Writes FontAwesome configuration data to the .npmrc file.
     *
     * @param string $token API token for FontAwesome
     *
     * @return void
     */
    private static function writeFontAwesomeConfigToFile(string $token): void
    {
        $filePath = dirname(__DIR__) . '/web/app/themes/adeliom/.npmrc';

        if (!file_exists($filePath)) {
            file_put_contents($filePath, '');
        }

        if (!is_writable($filePath)) {
            echo "\n\e[31mThe .npmrc file for FontAwesome is not writable.\e[0m\n";
            return;
        }

        $configLines = "@fortawesome:registry=https://npm.fontawesome.com/\n";
        $configLines .= "//npm.fontawesome.com/:_authToken=$token\n";

        file_put_contents($filePath, $configLines, FILE_APPEND);
        echo "\n\e[32mFile .npmrc configured successfully for FontAwesome Pro.\e[0m\n";
    }

    /**
     * Writes ACF Pro authentication data to the auth.json file.
     *
     * @param string $username Username for ACF Pro
     * @param string $password Password for ACF Pro
     *
     * @return void
     */
    private static function writeACFProAuthToFile(string $username, string $password): void
    {
        $filePath = 'auth.json';

        if (!file_exists($filePath)) {
            file_put_contents($filePath, '');
        }

        if (!is_writable($filePath)) {
            echo "\n\e[31mThe file $filePath is not writable.\e[0m\n";
            return;
        }

        $authData = [
            'http-basic' => [
                'connect.advancedcustomfields.com' => ['username' => $username, 'password' => $password]
            ]
        ];

        $jsonContent = json_encode($authData, JSON_PRETTY_PRINT);
        file_put_contents($filePath, $jsonContent);
        echo "\n\e[32mAuth.json file configured successfully for ACF Pro.\e[0m\n";
    }

    /**
     * Generates a function comment for the given function body.
     *
     * @return string
     */
    private static function hideInput(): string
    {
        $password = '';

        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            system('stty -echo');
            $password = trim(fgets(STDIN));
            system('stty echo');
        } else {
            // For Windows
            $password = trim(shell_exec("powershell -Command \"$password = read-host -AsSecureString ; ^$BSTR=[System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($password); [System.Runtime.InteropServices.Marshal]::PtrToStringAuto($BSTR)\""));
        }

        return $password;
    }
}
