<?php

namespace App\Block\Cta;

use Adeliom\Lumberjack\Admin\AbstractBlock;
use Adeliom\Lumberjack\Admin\Fields\Buttons\ButtonField;
use Adeliom\Lumberjack\Admin\Fields\Choices\TrueFalseField;
use Adeliom\Lumberjack\Admin\Fields\Layout\LayoutField;
use Adeliom\Lumberjack\Admin\Fields\Medias\MediaField;
use Adeliom\Lumberjack\Admin\Fields\Settings\SettingsField;
use Adeliom\Lumberjack\Admin\Fields\Tabs\ContentTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\LayoutTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\MediaTab;
use Adeliom\Lumberjack\Admin\Fields\Tabs\SettingsTab;
use Adeliom\Lumberjack\Admin\Fields\Typography\HeadingField;
use Adeliom\Lumberjack\Admin\Fields\Typography\TextField;
use Adeliom\Lumberjack\Admin\Fields\Typography\WysiwygField;
use App\Enum\BlocksTwigPath;
use App\Enum\GutBlockName;
use Extended\ACF\ConditionalLogic;
use Extended\ACF\Fields\RadioButton;

/**
 * Class CtaBlock
 * @see https://github.com/wordplate/extended-acf#fields
 * @package App\FlexibleLayout
 */
class CtaBlock extends AbstractBlock
{
    public const NAME = "cta";
    public const TITLE = "Accroche + bouton";
    public const DESCRIPTION = "Accroche + bouton";

    public function __construct()
    {
        parent::__construct([
            'mode' => 'edit',
            'category' => GutBlockName::CTA,
            'dir' => BlocksTwigPath::CTA,
            'supports' => [
                "anchor" => true
            ]
        ]);
    }

    public static function getFields(): ?\Traversable
    {

        yield from ContentTab::make()->fields([
            HeadingField::make()->tag(),
            TextField::make(),
            ButtonField::make()
        ]);

        yield from LayoutTab::make()->fields([
            LayoutField::margin(),
        ]);
    }
}
