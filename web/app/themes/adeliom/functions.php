<?php

use App\Http\Lumberjack;

// Create the Application Container
$app = require __DIR__ . '/bootstrap/app.php';

// Bootstrap Lumberjack from the Container
$lumberjack = $app->make(Lumberjack::class);
$lumberjack->bootstrap();

// Import our routes file
require_once __DIR__ . '/routes.php';

// Set global params in the Timber context
add_filter('timber_context', [$lumberjack, 'addToContext']);

//How to Disable WordPress Deprecated Warnings
add_filter('deprecated_function_trigger_error', '__return_false');
add_filter('deprecated_argument_trigger_error', '__return_false');
add_filter('deprecated_file_trigger_error', '__return_false');
//Not to trigger any errors when a deprecated function or method is called.
add_filter('deprecated_hook_trigger_error', '__return_false');
