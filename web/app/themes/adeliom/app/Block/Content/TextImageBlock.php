<?php

namespace App\Blocks;

use Adeliom\Lumberjack\Admin\AbstractBlock;
use Adeliom\Lumberjack\Admin\Fields\Tabs\MediaTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\SettingsTab;
use Adeliom\Lumberjack\Admin\Fields\Typography\HeadingField;
use App\Admin\Fields\Tabs\ContentTab;
use App\Admin\Fields\Tabs\LayoutTab;
use App\Admin\Fields\Typography\WysiwygField;
use App\Enum\BlocksTwigPath;
use App\Enum\GutBlockName;

/**
 * Class TextImageBlock
 * @see https://github.com/wordplate/extended-acf#fields
 * @package App\FlexibleLayout
 */
class TextImageBlock extends AbstractBlock
{
    public function __construct()
    {
        parent::__construct([
            'title' => __('Texte + média'),
            'description' => __('Description : Bloc avec texte accolé à une image'),
            'category' => GutBlockName::CONTENT,
            'mode' => 'edit',
            'dir' => BlocksTwigPath::CONTENT
        ]);
    }

    protected function registerFields(): \Traversable
    {

        yield from ContentTab::make();
        yield HeadingField::group();
        yield WysiwygField::default();

        yield from MediaTab::make();

        yield from LayoutTab::make([
            LayoutTab::MEDIA_POSITION,
            LayoutTab::DARK_MODE
        ]);

        yield from SettingsTab::make([
           SettingsTab::ANCHOR
        ]);
    }
}
