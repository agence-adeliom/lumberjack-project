<?php

declare(strict_types=1);

/*
 * You can place your custom package configuration in here.
 */
return [
    # Choose your assets bundler tool (default: webpack, or use 'webpack' or 'vite')
    // "provider" => "webpack",
    "build_directory" => "build",
    "script_attributes" => [
        "defer" => true,
        //"referrerpolicy" => "origin"
    ],
    "link_attributes" => [
        //"referrerpolicy" => "origin"
    ],
    # if using integrity hashes and need the crossorigin attribute (default: false, or use 'anonymous' or 'use-credentials')
    // "crossorigin" => 'anonymous',

    # preload all rendered script and link tags automatically via the http2 Link header
    // "preload" => true,

    # Throw an exception if the entrypoints.json file is missing or an entry is missing from the data
    // "strict_mode" => false,
];
