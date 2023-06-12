<?php

namespace App\Block\Faq;

use Adeliom\Lumberjack\Admin\AbstractBlock;
use Adeliom\Lumberjack\Admin\Fields\Layout\LayoutField;
use Adeliom\Lumberjack\Admin\Fields\Medias\FileField;
use Adeliom\Lumberjack\Admin\Fields\Tabs\ContentTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\LayoutTab;
use Adeliom\Lumberjack\Admin\Fields\Typography\HeadingField;
use Adeliom\Lumberjack\Admin\Fields\Typography\TextField;
use Adeliom\Lumberjack\Admin\Fields\Typography\WysiwygField;
use Adeliom\Lumberjack\Assets\Assets;
use App\Enum\BlocksTwigPath;
use App\Enum\GutBlockName;
use App\PostTypes\Question;
use App\Services\PostTypeService;
use Extended\ACF\Fields\Repeater;
use Adeliom\Lumberjack\Admin\Fields\Relations\RelationField;

/**
 * Class FaqBlock
 * @see https://github.com/wordplate/extended-acf#fields
 * @package App\FlexibleLayout
 */
class FaqBlock extends AbstractBlock
{
    public const NAME = "faq";
    public const TITLE = "Remontée de FAQ";
    public const DESCRIPTION = "Bloc remontant les dernières FAQ.";
    public const QUESTION = "question";
    public function __construct()
    {
        parent::__construct([
            'mode' => 'edit',
            'category' => GutBlockName::RELATION,
            'dir' => BlocksTwigPath::RELATION,
            'enqueue_assets' => function () {
                Assets::enqueue('scripts/components/accordion', 'scripts/components/accordion', []);
            },
        ]);
    }

    public static function getFields(): ?\Traversable
    {
        yield from ContentTab::make()->fields([
            HeadingField::make()->tag(),
            WysiwygField::make()->default(),
            RelationField::make(__('Questions à remonter'), self::QUESTION)
                ->postTypes([Question::getPostType()])
                ->min(1)
        ]);

        yield from LayoutTab::make()->fields([
            LayoutField::margin()
        ]);
    }

    public function addToContext(): array
    {
        return [
            'postTypeService' => Question::getPostType(),
            'getParams' => $_GET,
        ];
    }
}
