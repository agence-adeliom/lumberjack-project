<?php
namespace Deployer;

require 'recipe/wordpress.php';

// Set WP-CLI path and install if not found
set('bin/wp', function () {
    if (test('[ -f {{deploy_path}}/.dep/wp-cli.phar ]')) {
        return '{{bin/php}} {{deploy_path}}/.dep/wp-cli.phar';
    }

    if (commandExist('wp')) {
        return 'wp'; // Assumes WP-CLI is globally accessible in PATH
    }

    warning("WP-CLI binary wasn't found. Installing latest WP-CLI to \"{{deploy_path}}/.dep/wp-cli.phar\".");
    run("curl -o {{deploy_path}}/.dep/wp-cli.phar https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar");
    return '{{bin/php}} {{deploy_path}}/.dep/wp-cli.phar';
});

// Set the default theme name, prompting user if not set in the .env file
set('theme', function () {
    // Define the path to the .env file
    $envFilePath = __DIR__ . '/.env';

    // Check if the .env file exists, parse its content if available
    $envArray = file_exists($envFilePath) ? parse_ini_string(file_get_contents($envFilePath)) : [];

    // Check if the WP_DEFAULT_THEME is set in the .env file, if not, prompt the user
    return $envArray['WP_DEFAULT_THEME'] ?? ask("Please enter the default theme name: ", 'adeliom');
});

// Set database configuration options prompting user for values
set('database_host', function () {
    return ask('Database Host: ', 'localhost');
});

set('database_name', function () {
    return ask('Database Name: ');
});

set('database_user', function () {
    return ask('Database User: ', '{{database_name}}');
});

set('database_password', function () {
    return askHiddenResponse('Database Password: ');
});

// Set environment variables from .env or user input
set('env', function () {
    // Define the path to the .env file
    $envFilePath = __DIR__ . '/.env';

    // Check if the .env file exists, parse its content if available
    $envArray = file_exists($envFilePath) ? parse_ini_string(file_get_contents($envFilePath)) : [];

    // If GRAVITY_FORMS_KEY is not set in the .env file
    if (!isset($envArray['GRAVITY_FORMS_KEY'])) {
        // Prompt the user to enter GRAVITY_FORMS_KEY
        $gravityFormsKey = ask("Please enter the Gravity Forms API key: ");

        // Set the environment array based on user input
        $envArray = $gravityFormsKey ? ['GRAVITY_FORMS_KEY' => $gravityFormsKey] : [];
    } else {
        // If GRAVITY_FORMS_KEY is set, create an array with that key and its value
        $envArray = ['GRAVITY_FORMS_KEY' => $envArray['GRAVITY_FORMS_KEY']];
    }

    return $envArray;
});

set('shared_dirs', ['web/app/uploads', 'web/app/sessions']);
set('shared_files', [
    'web/.htaccess',
]);
set('writable_dirs', ['web/app/uploads', 'web/app/sessions']);

set('writable_mode', "chmod");
set('writable_chmod_mode', "2775");
set('writable_recursive', false);

// Hosts
import('.inventory.yaml');

// Task to set the repository URL based on the Git configuration
task('set:repository', static function (): void {
    $remoteRepo = runLocally('git config --get remote.origin.url');

    // Case 1: RunLocally returns an address in the 'git@github.com:...' format
    if (str_starts_with($remoteRepo, 'git@github.com:')) {
        $repo = trim($remoteRepo);
    }
    // Case 2: RunLocally successfully returns the correct value
    elseif (str_starts_with($remoteRepo, 'https://github.com/')) {
        // Modify the URL to match the 'git@github.com:' format while retaining '.git' at the end
        $repo = str_replace('https://github.com/', 'git@github.com:', trim($remoteRepo));
    }
    // Case 3: RunLocally returns nothing or encounters an error, prompting the user to enter the repository URL
    else {
        $repo = ask('Please enter the repository URL: ', 'adeliom');
    }

    set('repository', $repo); // Set the repository URL
});

// Task to set environment variables and configuration
task('dotenv:set-env', static function (): void {
    $envFile = '.env.local';
    $sharedPath = "{{deploy_path}}/shared";

    // Check if the shared env.dist file doesn't exist
    if (!test("[ -f $sharedPath/$envFile ]")) {
        $inputVars = [
            'APP_ENV' => 'app_env',
            'DB_HOST' => 'database_host',
            'DB_NAME' => 'database_name',
            'DB_USER' => 'database_user',
            'DB_PASSWORD' => 'database_password',
            'WP_HOME' => 'wp_home'
        ];

        // Create the content for the .env.dist file
        $envContent = '';

        // Iterate through input variables
        foreach ($inputVars as $envVarName => $configVar) {
            // Retrieve the value of each configuration variable
            $value = get($configVar);

            // Append the key-value pair to the .env.dist content
            $envContent .= "$envVarName=$value\n";
        }

        // Write the content to the .env.dist file in the shared directory
        run("echo '$envContent' > $sharedPath/$envFile");
    }

    // Create a symbolic link from the shared .env.dist file to the release directory
    run("{{bin/symlink}} $sharedPath/$envFile {{release_path}}/$envFile");

    $authFile = 'auth.json';
    // Check if the shared auth.json file doesn't exist
    if (!test("[ -f $sharedPath/$authFile ]")) {
        // Prompt user for ACF Composer key
        $acfComposerKey = askHiddenResponse("Please enter your ACF Composer key:");

        if ($acfComposerKey) {
            $wpHome = get('wp_home');
            // Prepare authentication data
            $authData = [
                'http-basic' => [
                    'connect.advancedcustomfields.com' => ['username' => $acfComposerKey, 'password' => $wpHome]
                ]
            ];
            // Encode authentication data to JSON
            $jsonContent = json_encode($authData, JSON_PRETTY_PRINT);
            // Create the auth.json file in the shared directory
            run("echo '$jsonContent' > $sharedPath/$authFile");
        }
    }

    // Create a symbolic link from the shared auth.json file to the release or current directory
    run("{{bin/symlink}} $sharedPath/$authFile {{release_path}}/auth.json");

    $npmrcFile = 'web/app/themes/adeliom/.npmrc'; // File name for Font Awesome's .npmrc
    // Check if the shared .npmrc file doesn't exist
    $dirname = dirname(parse($npmrcFile));

    // Create dir of shared file if not existing
    if (!test("[ -d $sharedPath/$dirname ]")) {
        run("mkdir -p $sharedPath/$dirname");
    }

    if (!test("[ -f $sharedPath/$npmrcFile ]")) {
        // Prompt user for Font Awesome authentication details
        $fontAwesomeToken = askHiddenResponse("Please enter your Font Awesome token:");

        if ($fontAwesomeToken) {
            // Prepare authentication data for Font Awesome .npmrc
            $npmrcContent = "@fortawesome:registry=https://npm.fontawesome.com/\n//npm.fontawesome.com/:_authToken=$fontAwesomeToken\n";
            // Create the .npmrc file in the shared directory
            run("echo \"$npmrcContent\" > $sharedPath/$npmrcFile");
        }
    }

    // Create a symbolic link from the shared .npmrc file to the release or current directory
    run("{{bin/symlink}} $sharedPath/$npmrcFile {{release_path}}/$npmrcFile");
});

// Task to build assets with npm
task('npm:build', static function (): void {
    $theme = get('theme');

    if (commandExist('npm')) {
        run('cd {{release_or_current_path}}/web/app/themes/' . $theme . ' && npm install && npm run build:production');
    } else {
        runLocally( 'cd web/app/themes/' . $theme . ' && npm install && npm run build:production' );
        upload( 'web/app/themes/{{theme}}/build/', '{{release_or_current_path}}/web/app/themes/' . $theme . '/build/' );

        run( 'find {{release_or_current_path}}/web/app/themes/' . $theme . '/build -type d -exec chmod 755 {} \;' );
        run( 'find {{release_or_current_path}}/web/app/themes/' . $theme . '/build -type f -exec chmod 644 {} \;' );
    }
});

// Task to reset the W3 Total Cache plugin
task('reset:cache', static function (): void {
    // Run WP-CLI commands to check if the W3 Total Cache plugin is installed and active
    $installed = run("cd {{release_or_current_path}} && {{bin/wp}} plugin is-installed w3-total-cache && echo $?");
    $active = run("cd {{release_or_current_path}} && {{bin/wp}} plugin is-active w3-total-cache && echo $?");

    // Check if the plugin is installed and active
    if (trim($installed) === '0' && trim($active) === '0') {
        // If installed and active, trigger the cache flush_all command
        run("cd {{release_or_current_path}} && {{bin/wp}} w3-total-cache flush all");
        info("W3 Total Cache cleared successfully!");
    }  else {
        warning('W3 Total Cache is not installed or active.');
    }
});

// Task to upload media files
task('upload:medias', static function (): void {
    upload('web/app/uploads/', '{{release_or_current_path}}/web/app/uploads/');
});

// Task to download media files
task('download:medias', static function (): void {
    download('{{release_or_current_path}}/web/app/uploads/', 'web/app/uploads/');
});

// Define deployment flow
before('deploy:update_code', 'set:repository');
after('deploy:update_code', 'dotenv:set-env');
after('dotenv:set-env', 'deploy:vendors');
before('deploy:symlink', 'npm:build');
after('deploy:symlink', 'reset:cache');
after('deploy:failed', 'deploy:unlock');
