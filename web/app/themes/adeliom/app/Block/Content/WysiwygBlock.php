<?php

namespace App\Block\Content;

use Adeliom\Lumberjack\Admin\AbstractBlock;
use Adeliom\Lumberjack\Admin\Fields\Buttons\ButtonField;
use Adeliom\Lumberjack\Admin\Fields\Layout\LayoutField;
use Adeliom\Lumberjack\Admin\Fields\Tabs\ContentTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\LayoutTab;
use Adeliom\Lumberjack\Admin\Fields\Typography\HeadingField;
use Adeliom\Lumberjack\Admin\Fields\Typography\WysiwygField;
use App\Enum\BlocksTwigPath;
use App\Enum\GutBlockName;

/**
 * Class WysiwygBlock
 * @see https://github.com/wordplate/extended-acf#fields
 * @package App\Admin
 */
class WysiwygBlock extends AbstractBlock
{
    public const NAME = "wysiwyg";
    public const TITLE = "Texte";
    public const DESCRIPTION = "Bloc de texte";

    public function __construct()
    {
        parent::__construct([
            'mode' => 'edit',
            'category' => GutBlockName::CONTENT,
            'dir' => BlocksTwigPath::CONTENT,
            'supports' => [
                "align" => ['left', 'right', 'center'],
                "anchor" => true
            ],
            'align' => 'center'
        ]);
    }

    public static function getFields(): ?\Traversable
    {
        yield from ContentTab::make()->fields([
            HeadingField::tag(),
            WysiwygField::make()->default()->mediaUpload(true),
            ButtonField::make()->types()
        ]);

        yield from LayoutTab::make()->fields([
            LayoutField::darkMode(),
            LayoutField::margin()
        ]);
    }
}
