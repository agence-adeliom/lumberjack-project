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

        yield from ContentTab::make()->fields([
            HeadingField::make()->tag(),
            WysiwygField::make()->default(),
            ButtonField::make()->group(true)
        ]);

        yield from MediaTab::make()->fields([
            MediaField::make()
        ]);

        yield from LayoutTab::make()->fields([
            LayoutField::mediaPosition(),
            TrueFalseField::make("Contraindre le ratio du média", "has_ratio"),
            RadioButton::make("Ratio", "ratio")->choices([
                "auto" => "Automatique",
                "paysage" => "Paysage",
                "portrait" => "Portrait"
            ])->conditionalLogic([
                ConditionalLogic::where("has_ratio", "==", 1)
            ]),
            LayoutField::margin()
        ]);
    }
}
