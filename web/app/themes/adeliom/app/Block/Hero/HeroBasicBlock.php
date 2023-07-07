<?php

namespace App\Block\Hero;

use Adeliom\Lumberjack\Admin\AbstractBlock;
use Adeliom\Lumberjack\Admin\Fields\Buttons\ButtonField;
use Adeliom\Lumberjack\Admin\Fields\Medias\MediaField;
use Adeliom\Lumberjack\Admin\Fields\Tabs\ContentTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\MediaTab;
use Adeliom\Lumberjack\Admin\Fields\Typography\HeadingField;
use Adeliom\Lumberjack\Admin\Fields\Typography\WysiwygField;
use App\Enum\BlocksTwigPath;
use App\Enum\GutBlockName;

/**
 * Class HeroBasicBlock
 * @see https://github.com/wordplate/extended-acf#fields
 * @package App\FlexibleLayout
 */
class HeroBasicBlock extends AbstractBlock
{
    public const NAME = "hero-basic";
    public const TITLE = "Hero simple";
    public const DESCRIPTION = "Bloc hero pour afficher le titre de la page, une description, un bouton et une image";

    public function __construct()
    {
        parent::__construct([
            'mode' => 'edit',
            'category' => GutBlockName::HERO,
            'dir' => BlocksTwigPath::HERO,
        ]);
    }

    public static function getFields(): ?\Traversable
    {

        yield from ContentTab::make()->fields([
            HeadingField::make(),
            WysiwygField::make()->default(),
            ButtonField::make()->group(true)
        ]);

        yield from MediaTab::make()->fields([
            MediaField::make("Ratio recommandé : 1200 x 1200", [MediaField::HAS_IMAGE])
        ]);
    }
}
