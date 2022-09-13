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
