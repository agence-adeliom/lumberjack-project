<?php

declare(strict_types=1);

/*
 * You can place your custom package configuration in here.
 */
return [
    'allowed_functions' => [],
    'extensions' => [
        \App\TwigExtensions\NewsExtension::class,
    ],
];
