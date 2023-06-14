<?php

namespace App\Block\Content;

use Adeliom\Lumberjack\Admin\AbstractBlock;
use Adeliom\Lumberjack\Admin\Fields\Buttons\ButtonField;
use Adeliom\Lumberjack\Admin\Fields\Layout\LayoutField;
use Adeliom\Lumberjack\Admin\Fields\Tabs\SettingsTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\ContentTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\LayoutTab;
use Adeliom\Lumberjack\Admin\Fields\Typography\HeadingField;
use Adeliom\Lumberjack\Admin\Fields\Typography\TextField;
use Adeliom\Lumberjack\Admin\Fields\Typography\WysiwygField;
use Adeliom\Lumberjack\Assets\Assets;
use App\Enum\BlocksTwigPath;
use App\Enum\GutBlockName;
use Extended\ACF\Fields\Group;
use Extended\ACF\Fields\Repeater;

/**
 * Class TitleTextBlock
 * @see https://github.com/wordplate/extended-acf#fields
 * @package App\FlexibleLayout
 */
class StickyMenuBlock extends AbstractBlock
{
    public const NAME = "sticky-menu";
    public const TITLE = "Sticky menu";
    public const DESCRIPTION = "Bloc sticky avec un répéteur d‘ancre";

    public function __construct()
    {
        parent::__construct([
            'mode' => 'edit',
            'category' => GutBlockName::NAVIGATION,
            'dir' => BlocksTwigPath::NAVIGATION,
            'supports' => [
                "anchor" => true
            ],
            'enqueue_assets' => function () {
                Assets::enqueue('scripts/blocks/sticky-menu', 'scripts/blocks/sticky-menu', []);
            },
        ]);
    }

    public static function getFields(): ?\Traversable
    {
        yield from ContentTab::make()->fields([
            Repeater::make('Sticky menu d’ancre', 'sticky_menu')
                ->fields([
                    TextField::make('Titre de l’ancre', 'title'),
                    TextField::make('ID de l’Ancre', 'anchor')
                ])
                ->instructions('Pour des raisons d’affichage, ce menu ne peut contenir que 5 éléments. Veuillez tout de même prêter attention à la longueur des noms des liens.')
                ->min(3)
                ->max(5),
            ButtonField::make()->types()
        ]);

        yield from LayoutTab::make()->fields([
            LayoutField::margin()
        ]);
        yield from SettingsTab::make()->fields([
            SettingsTab::anchor()
        ]);
    }
}
