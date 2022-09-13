<?php

return [
    'categories' => [
        //"example" => ['title' => __('Examples'), 'icon'  => 'images-alt'],
    ],
    "settings" => [
        // You can disable blocks with a :
        // - regex | ex : /((core|yoast|yoast-seo|gravityforms)\/\w*)/
        // - array | ex : [ 'core/*', 'yoast/breadcrumb' ]
        "disable_blocks" => "/((core|yoast|yoast-seo|gravityforms)\/\w*)/"
    ],
    "templates" => [
        "post" => [],
        "page" => [],
    ]
];
