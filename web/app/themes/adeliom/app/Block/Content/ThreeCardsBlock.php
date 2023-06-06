<?php

namespace App\Block\Content;

use Adeliom\Lumberjack\Admin\AbstractBlock;
use Adeliom\Lumberjack\Admin\Fields\Buttons\ButtonField;
use Adeliom\Lumberjack\Admin\Fields\Layout\LayoutField;
use Adeliom\Lumberjack\Admin\Fields\Medias\ImageField;
use Adeliom\Lumberjack\Admin\Fields\Tabs\ContentTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\LayoutTab;
use Adeliom\Lumberjack\Admin\Fields\Typography\HeadingField;
use Adeliom\Lumberjack\Admin\Fields\Typography\TextField;
use Adeliom\Lumberjack\Admin\Fields\Typography\WysiwygField;
use App\Enum\BlocksTwigPath;
use App\Enum\GutBlockName;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\TrueFalse;

/**
 * Class ThreCardsBlock
 * @see https://github.com/wordplate/extended-acf#fields
 * @package App\FlexibleLayout
 */
class ThreCardsBlock extends AbstractBlock
{
    public const NAME = "three-cards";
    public const TITLE = "Blocs 3 cartes";
    public const DESCRIPTION = "Bloc permettant l\'ajout de trois cartes avec image ou icone, titre, description et lien.";

    public function __construct()
    {
        parent::__construct([
            'mode' => 'edit',
            'category' => GutBlockName::CONTENT,
            'dir' => BlocksTwigPath::CONTENT
        ]);
    }

    public static function getFields(): ?\Traversable
    {
        yield from ContentTab::make()->fields([
            HeadingField::make()->tag(),
            TrueFalse::make("Mode image")->stylisedUi(),
            Repeater::make("Cards", 'cards')
                ->fields([
                    ImageField::make()->Instructions('Icon : png ou svg 256x256 <br/> Image : format portrait'),
                    HeadingField::make()->tag(),
                    WysiwygField::make()->default()
                ])
                ->layout("block")
                ->min(3)
                ->max(3),
            ButtonField::make(),
        ]);

        yield from LayoutTab::make()->fields([
            LayoutField::margin()
        ]);
    }
}
