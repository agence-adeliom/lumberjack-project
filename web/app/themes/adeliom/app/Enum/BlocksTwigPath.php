<?php

namespace App\Enum;

class BlocksTwigPath
{
    public const TWIG_PATH = 'views/blocks';

    public const CONTENT = self::TWIG_PATH . '/content';
    public const HERO = self::TWIG_PATH . '/hero';
    public const LISTING = self::TWIG_PATH . '/listing';
}
