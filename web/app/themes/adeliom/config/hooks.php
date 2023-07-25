<?php

declare(strict_types=1);

/*
 * You can place your custom package configuration in here.
 */
return [
    'register' => [
        App\Hooks\Admin\ConfigHooks::class,
        App\Hooks\Admin\ThemeHooks::class,
        App\Hooks\Admin\WysiwygHooks::class,
        App\Hooks\Admin\BlockHooks::class,
    ],
];
