<?php

namespace App\Block\Content;

use Adeliom\Lumberjack\Admin\AbstractBlock;
use Adeliom\Lumberjack\Admin\Fields\Buttons\ButtonField;
use Adeliom\Lumberjack\Admin\Fields\Choices\TrueFalseField;
use Adeliom\Lumberjack\Admin\Fields\Layout\LayoutField;
use Adeliom\Lumberjack\Admin\Fields\Medias\MediaField;
use Adeliom\Lumberjack\Admin\Fields\Tabs\ContentTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\LayoutTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\MediaTab;
use Adeliom\Lumberjack\Admin\Fields\Typography\HeadingField;
use Adeliom\Lumberjack\Admin\Fields\Typography\WysiwygField;
use App\Enum\BlocksTwigPath;
use App\Enum\GutBlockName;
use Extended\ACF\ConditionalLogic;
use Extended\ACF\Fields\RadioButton;

/**
 * Class TextTwoColsBlock
 * @see https://github.com/wordplate/extended-acf#fields
 * @package App\FlexibleLayout
 */
class TextTwoColsBlock extends AbstractBlock
{
    public const NAME = "text-two-cols";
    public const TITLE = "Texte deux colonnes";
    public const DESCRIPTION = "Bloc contenant un titre ainsi que deux colonnes de texte.";
    //Wysiwyg Keys
    private const CONTENT_LEFT = "Colonne de gauche";
    private const CONTENT_RIGHT = "Colonne de droite";
    private const COL_LEFT = "col_left";
    private const COL_RIGHT = "col_right";

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

        yield from ContentTab::make()->fields([
            HeadingField::make()->tag(),
            WysiwygField::make(self::CONTENT_LEFT, self::COL_LEFT)->default()->mediaUpload(true),
            WysiwygField::make(self::CONTENT_RIGHT, self::COL_RIGHT)->default()->mediaUpload(true),
        ]);

        yield from LayoutTab::make()->fields([
            LayoutField::darkMode(),
            LayoutField::margin()
        ]);
    }
}
