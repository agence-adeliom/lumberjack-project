<?php

declare(strict_types=1);

use App\TwigExtensions\ImageExtension;

/*
 * You can place your custom package configuration in here.
 */
return [
    'allowed_functions' => [],
    'extensions' => [
        ImageExtension::class
    ],
];
