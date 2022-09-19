<?php

namespace App\Blocks;

use Adeliom\Lumberjack\Admin\AbstractBlock;
use Adeliom\Lumberjack\Admin\Fields\Tabs\MediaTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\SettingsTab;
use App\Enum\BlocksTwigPath;
use App\Enum\GutBlockName;

/**
 * Class ImageFullBlock
 * @see https://github.com/wordplate/extended-acf#fields
 * @package App\FlexibleLayout
 */
class ImageFullBlock extends AbstractBlock
{
    public function __construct()
    {
        parent::__construct([
            'title' => __('Image pleine largeur'),
            'description' => __("Bloc contenant une image de la largeur de l'écran"),
            'category' => GutBlockName::CONTENT,
            'mode' => 'edit',
            'dir' => BlocksTwigPath::CONTENT
        ]);
    }

    protected function registerFields(): \Traversable
    {
        yield from MediaTab::make();

        yield from SettingsTab::make([
            SettingsTab::ANCHOR
        ]);
    }
}
