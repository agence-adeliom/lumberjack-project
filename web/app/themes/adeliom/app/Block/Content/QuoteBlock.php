<?php

namespace App\Block\Content;

use Adeliom\Lumberjack\Admin\AbstractBlock;
use Adeliom\Lumberjack\Admin\Fields\Layout\LayoutField;
use Adeliom\Lumberjack\Admin\Fields\Tabs\ContentTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\LayoutTab;
use Adeliom\Lumberjack\Admin\Fields\Typography\HeadingField;
use Adeliom\Lumberjack\Admin\Fields\Typography\TextField;
use App\Enum\BlocksTwigPath;
use App\Enum\GutBlockName;

/**
 * Class QuoteBlock
 * @see https://github.com/wordplate/extended-acf#fields
 * @package App\FlexibleLayout
 */
class QuoteBlock extends AbstractBlock
{
    public const NAME = "quote";
    public const TITLE = "Citation";
    public const DESCRIPTION = "Bloc permettant la mise en avant d’un témoignage ou d’une citation";

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
            TextField::make("Citation")
        ]);

        yield from LayoutTab::make()->fields([
            LayoutField::margin()
        ]);
    }
}
