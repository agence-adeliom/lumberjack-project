<?php

namespace App\Block\Content;

use Adeliom\Lumberjack\Admin\AbstractBlock;
use Adeliom\Lumberjack\Admin\Fields\Layout\LayoutField;
use Adeliom\Lumberjack\Admin\Fields\Tabs\ContentTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\LayoutTab;
use Adeliom\Lumberjack\Admin\Fields\Typography\HeadingField;
use Adeliom\Lumberjack\Assets\Assets;
use App\Enum\BlocksTwigPath;
use App\Enum\GutBlockName;
use Extended\ACF\Fields\Gallery;

/**
 * Class FileBlock
 * @see https://github.com/wordplate/extended-acf#fields
 * @package App\FlexibleLayout
 */
class ImageGalleryBlock extends AbstractBlock
{
    public const NAME = "image-gallery";
    public const TITLE = "Gallerie d'imgages";
    public const DESCRIPTION = "Bloc comprenant un ensemble d'images.";

    public function __construct()
    {
        parent::__construct([
            'mode' => 'edit',
            'category' => GutBlockName::CONTENT,
            'dir' => BlocksTwigPath::CONTENT,
            'supports' => [
                "anchor" => true
            ],
            'enqueue_assets' => function () {
                Assets::enqueue('scripts/blocks/image-gallery', 'scripts/blocks/image-gallery', []);
            },
        ]);
    }

    public static function getFields(): ?\Traversable
    {
        yield from ContentTab::make()->fields([
            HeadingField::make()->tag(),
            Gallery::make("Images", "images")
                ->mimeTypes(['jpg', 'jpeg', 'png'])
                ->min(1)
                ->required()
        ]);

        yield from LayoutTab::make()->fields([
            LayoutField::margin()
        ]);
    }
}
