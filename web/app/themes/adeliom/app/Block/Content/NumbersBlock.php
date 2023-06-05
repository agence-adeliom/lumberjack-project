<?php

namespace App\Blocks\Content;

use Adeliom\Lumberjack\Admin\AbstractBlock;
use Adeliom\Lumberjack\Admin\Fields\Layout\LayoutField;
use Adeliom\Lumberjack\Admin\Fields\Tabs\ContentTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\LayoutTab;
use Adeliom\Lumberjack\Admin\Fields\Typography\HeadingField;
use Adeliom\Lumberjack\Admin\Fields\Typography\TextField;
use App\Enum\BlocksTwigPath;
use App\Enum\GutBlockName;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Number;

/**
 * Class NumbersBlock
 * @see https://github.com/wordplate/extended-acf#fields
 * @package App\FlexibleLayout
 */
class NumbersBlock extends AbstractBlock
{

    public const NAME = 'numbers-block';
    public const TITLE = 'Chiffres clés';
    public const DESCRIPTION = 'Bloc permettant la mise en avant de chiffres clés.';

    public function __construct()
    {
        parent::__construct([
            'category' => GutBlockName::CONTENT,
            'mode' => 'edit',
            'dir' => BlocksTwigPath::CONTENT
        ]);
    }

    public static function getFields(): \Traversable
    {
        yield from ContentTab::make()->fields([
            HeadingField::make()->tag(),
            Repeater::make('Nombres clés', 'key_numbers')
                ->fields([
                    Number::make('Nombre mis en avant', 'number'),
                    TextField::make("Contenu textuel")
                ])
                ->min(3)
                ->max(4)
        ]);

        yield from LayoutTab::make()->fields([
            LayoutField::margin()
        ]);
    }
}
