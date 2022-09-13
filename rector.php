<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([__DIR__ . '/web/app/themes/adeliom']);
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_74,
        SetList::TYPE_DECLARATION,
        SetList::CODING_STYLE,
        SetList::CODE_QUALITY
    ]);
    $rectorConfig->phpstanConfig(__DIR__ . '/phpstan.neon');
};
