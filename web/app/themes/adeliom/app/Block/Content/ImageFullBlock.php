<?php

namespace App\Block\Content;

use Adeliom\Lumberjack\Admin\AbstractBlock;
use Adeliom\Lumberjack\Admin\Fields\Layout\LayoutField;
use Adeliom\Lumberjack\Admin\Fields\Medias\MediaField;
use Adeliom\Lumberjack\Admin\Fields\Tabs\LayoutTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\MediaTab;
use App\Enum\BlocksTwigPath;
use App\Enum\GutBlockName;

class ImageFullBlock extends AbstractBlock
{
    public const NAME = "image-full";
    public const TITLE = "Image pleine largeur";
    public const DESCRIPTION = "Bloc contenant une image de la largeur de l'écran";

    public function __construct()
    {
        parent::__construct([
            'mode' => 'edit',
            'category' => GutBlockName::CONTENT,
            'dir' => BlocksTwigPath::CONTENT,
            'supports' => [
                "anchor" => true
            ]
        ]);
    }

    public static function getFields(): ?\Traversable
    {
        yield from MediaTab::make()->fields([
            MediaField::image()
        ]);

        yield from LayoutTab::make()->fields([
            LayoutField::margin()
        ]);
    }
}
