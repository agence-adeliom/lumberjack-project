<?php

namespace App\Block\Content;

use Adeliom\Lumberjack\Admin\AbstractBlock;
use Adeliom\Lumberjack\Admin\Fields\Layout\LayoutField;
use Adeliom\Lumberjack\Admin\Fields\Medias\ImageField;
use Adeliom\Lumberjack\Admin\Fields\Tabs\ContentTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\LayoutTab;
use Adeliom\Lumberjack\Admin\Fields\Typography\HeadingField;
use Adeliom\Lumberjack\Admin\Fields\Typography\TextField;
use App\Enum\BlocksTwigPath;
use App\Enum\GutBlockName;
use Extended\ACF\Fields\Repeater;

/**
 * Class LogoBlock
 * @see https://github.com/wordplate/extended-acf#fields
 * @package App\FlexibleLayout
 */
class LogoBlock extends AbstractBlock
{
    public const NAME = "logo";
    public const TITLE = "Liste de logos";
    public const DESCRIPTION = "Bloc comprenant une liste de logos (entreprises, partenaires...)";

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
            Repeater::make("Éléments", "items")
                ->fields([
                    TextField::make('Titre', 'title'),
                    ImageField::make('Logo')->required(),
                ])
                ->layout("block")
                ->min(1)
        ]);

        yield from LayoutTab::make()->fields([
            LayoutField::margin()
        ]);
    }
}
