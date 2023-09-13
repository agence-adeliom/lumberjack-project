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
use Adeliom\Lumberjack\Admin\Fields\Tabs\SettingsTab;
use Adeliom\Lumberjack\Admin\Fields\Typography\HeadingField;
use Adeliom\Lumberjack\Admin\Fields\Typography\WysiwygField;
use Adeliom\Lumberjack\Assets\Assets;
use App\Enum\BlocksTwigPath;
use App\Enum\GutBlockName;
use Extended\ACF\ConditionalLogic;
use Extended\ACF\Fields\Group;
use Extended\ACF\Fields\RadioButton;
use Extended\ACF\Fields\TrueFalse;

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
            ],
            'enqueue_assets' => function () {
                Assets::enqueue('scripts/blocks/media', 'scripts/blocks/media', []);
            },
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
            Group::make("Ratio du média", "media_ratio")->fields([
                TrueFalse::make("Choisir le ratio du média", "has_ratio")
                    ->stylisedUi(),
                RadioButton::make("Format d'image", "ratio")
                    ->choices([
                        "auto" => "Automatique",
                        "paysage" => "Paysage",
                        "portrait" => "Portrait",
                        "square" => "Carré"
                    ])
                    ->conditionalLogic([
                        ConditionalLogic::where("has_ratio", "==", 1)
                    ]),
                RadioButton::make("Ratio d'aspect", "aspect_ratio")
                    ->choices([
                        "aspect-4/3" => "4/3",
                        "aspect-3/2" => "3/2",
                        "aspect-16/9" => "16/9"
                    ])
                    ->conditionalLogic([
                        ConditionalLogic::where("has_ratio", "==", 1)->and("ratio", "==", 'paysage'),
                        ConditionalLogic::where("has_ratio", "==", 1)->and("ratio", "==", 'portrait'),
                    ]),
            ]),
            LayoutField::margin()
        ]);
        yield from SettingsTab::make()->fields([
            SettingsTab::anchor()
        ]);
    }
}
