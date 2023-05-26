<?php

namespace App\Block\Latest;

use Adeliom\Lumberjack\Admin\AbstractBlock;
use Adeliom\Lumberjack\Admin\Fields\Layout\LayoutField;
use Adeliom\Lumberjack\Admin\Fields\Tabs\ContentTab;
use Adeliom\Lumberjack\Admin\Fields\Relations\RelationField;
use Adeliom\Lumberjack\Admin\Fields\Relations\TaxonomyField;
use Adeliom\Lumberjack\Admin\Fields\Choices\RadioField;
use Adeliom\Lumberjack\Admin\Fields\Tabs\LayoutTab;
use Adeliom\Lumberjack\Admin\Fields\Typography\HeadingField;
use App\Enum\BlocksTwigPath;
use App\Enum\GutBlockName;
use App\PostTypes\Post;
use Extended\ACF\ConditionalLogic;
use Timber\Timber;

/**
 * Class LastNewsBlock
 * @see https://github.com/wordplate/extended-acf#fields
 * @package App\Admin
 */
class LastNewsBlock extends AbstractBlock
{
    public const NAME = "last-news";
    public const TITLE = "Remontée actualités";
    public const DESCRIPTION = "Bloc permettant d'afficher vos dernières actualités publiées";
    private const TYPE = 'type';
    private const DEFAULT_TYPE = 'default';
    private const CUSTOM_LAST_NEWS = 'custom_last_news';
    private const SPECIFIC_CATEGORY = 'specific_category';
    private const CATEGORY_NAME = 'category';

    public function __construct()
    {
        parent::__construct([
            'mode' => 'edit',
            'category' => GutBlockName::LISTING,
            'dir' => BlocksTwigPath::LISTING,
            'supports' => [
                "anchor" => true
            ]
        ]);
    }

    public static function getFields(): ?\Traversable
    {
        yield from ContentTab::make()->fields([
            HeadingField::tag(),

            RadioField::make("Je souhaite :", 'type')
                ->choices([
                    self::DEFAULT_TYPE => "Remonter automatiquement les dernières actualités publiées sur le site",
                    self::CUSTOM_LAST_NEWS => "Sélectionner manuellement les actualités à remonter",
                    self::SPECIFIC_CATEGORY => "Remonter les actualités d’une catégorie spécifique"
                ]),

            RelationField::make(__('Sélectionnez les actualités à afficher'), self::CUSTOM_LAST_NEWS)
                ->instructions(__('Renseignez au moins une actualité.'))
                ->postTypes([Post::getPostType()])
                ->conditionalLogic([
                    ConditionalLogic::where(self::TYPE, "==", self::CUSTOM_LAST_NEWS),
                ])
                ->min(1)
                ->max(3)
                ->required(),

            TaxonomyField::make('Sélectionnez la catégorie des articles à afficher', self::SPECIFIC_CATEGORY)
                ->taxonomy(self::CATEGORY_NAME)
                ->required()
                ->conditionalLogic([
                    ConditionalLogic::where(self::TYPE, "==", self::SPECIFIC_CATEGORY),
                ])

        ]);

        yield from LayoutTab::make()->fields([
            LayoutField::margin()
        ]);
    }

    public function addToContext(): array
    {
        $fields = get_fields();
        $perPage = 3;
        $isCustomNews = $fields['type'] == self::CUSTOM_LAST_NEWS;
        $isSpecificCategory = $fields['type'] == self::SPECIFIC_CATEGORY;

        $specificCategory = $isSpecificCategory ? $fields['specific_category'] : null;

        if ($isCustomNews) {
            $lastNews = Timber::get_posts($fields['custom_last_news']);
        } else {
            $taxonomies = [];
            if ($specificCategory) {
                $taxonomies = array_map(function ($slug) {
                    if ($slug) {
                        return [
                            'taxonomy' => self::CATEGORY_NAME,
                            'field' => 'slug',
                            'terms' => $slug,
                            'include_children' => true,
                            'operator' => 'IN',
                        ];
                    }
                }, [$specificCategory->slug]);
            }


            $query = \Rareloop\Lumberjack\Post::builder()
                ->orderBy('date', 'DESC')
                ->limit($perPage)
                ->whereTaxonomies($taxonomies);

            $lastNews = $query->get();
        }
        return [
            "lastNews" => $lastNews,
        ];
    }
}
