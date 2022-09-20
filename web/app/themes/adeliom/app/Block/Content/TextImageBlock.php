<?php

namespace App\Block\Content;

use Adeliom\Lumberjack\Admin\AbstractBlock;
use Adeliom\Lumberjack\Admin\Fields\Tabs\ContentTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\LayoutTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\MediaTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\SettingsTab;
use Adeliom\Lumberjack\Admin\Fields\Typography\HeadingField;
use Adeliom\Lumberjack\Admin\Fields\Typography\WysiwygField;
use App\Enum\BlocksTwigPath;
use App\Enum\GutBlockName;

/**
 * Class TextImageBlock
 * @see https://github.com/wordplate/extended-acf#fields
 * @package App\FlexibleLayout
 */
class TextImageBlock extends AbstractBlock
{
    public const NAME = "text-image";
    public const TITLE = "Texte + média";
    public const DESCRIPTION = "Bloc de texte accolé d'une image";

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

        yield from ContentTab::make();
        yield HeadingField::group();
        yield WysiwygField::default();

        yield from MediaTab::make();

        yield from LayoutTab::make([
            LayoutTab::MEDIA_POSITION,
            LayoutTab::DARK_MODE
        ]);
    }
}
