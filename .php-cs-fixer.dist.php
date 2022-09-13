<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . "/web/app/themes/adeliom")
;

$config = new PhpCsFixer\Config();

return $config->setRules([
    '@PSR12' => true,
    'array_syntax' => ['syntax' => 'short'],
])->setFinder($finder);
