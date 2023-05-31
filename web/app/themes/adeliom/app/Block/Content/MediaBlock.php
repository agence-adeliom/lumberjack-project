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
use Adeliom\Lumberjack\Assets\Assets;
use App\Enum\BlocksTwigPath;
use App\Enum\GutBlockName;
use Extended\ACF\ConditionalLogic;
use Extended\ACF\Fields\RadioButton;

/**
 * Class MediaBlock
 * @see https://github.com/wordplate/extended-acf#fields
 * @package App\FlexibleLayout
 */
class MediaBlock extends AbstractBlock
{
    public const NAME = "media";
    public const TITLE = "Média";
    public const DESCRIPTION = "Bloc média";

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
                Assets::enqueue('components/blocks/media', 'components/blocks/media', []);
            },
        ]);
    }

    public static function getFields(): ?\Traversable
    {

        yield from ContentTab::make()->fields([
            HeadingField::make()->tag(),
        ]);

        yield from MediaTab::make()->fields([
            MediaField::make()
        ]);

        yield from LayoutTab::make()->fields([
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
