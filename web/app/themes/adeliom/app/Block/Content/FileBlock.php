<?php

namespace App\Block\Content;

use Adeliom\Lumberjack\Admin\AbstractBlock;
use Adeliom\Lumberjack\Admin\Fields\Layout\LayoutField;
use Adeliom\Lumberjack\Admin\Fields\Medias\FileField;
use Adeliom\Lumberjack\Admin\Fields\Tabs\ContentTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\LayoutTab;
use Adeliom\Lumberjack\Admin\Fields\Typography\HeadingField;
use Adeliom\Lumberjack\Admin\Fields\Typography\TextField;
use App\Enum\BlocksTwigPath;
use App\Enum\GutBlockName;
use Extended\ACF\Fields\Repeater;

/**
 * Class FileBlock
 * @see https://github.com/wordplate/extended-acf#fields
 * @package App\FlexibleLayout
 */
class FileBlock extends AbstractBlock
{
    public const NAME = "file";
    public const TITLE = "Documents à télécharger";
    public const DESCRIPTION = "Bloc comprenant un lien de téléchargement de document.";

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
            Repeater::make("Fichiers", "files")
                ->fields([
                    HeadingField::make(),
                    FileField::make()->pdf()->returnFormat("url")
                ])
                ->layout("block")
                ->min(1)
        ]);

        yield from LayoutTab::make()->fields([
            LayoutField::margin()
        ]);
    }
}
