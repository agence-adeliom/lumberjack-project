<?php

namespace App\Blocks;

use Adeliom\Lumberjack\Admin\AbstractBlock;
use Adeliom\Lumberjack\Admin\Fields\Typography\HeadingField;
use App\Enum\BlocksTwigPath;
use App\Enum\GutBlockName;
use Traversable;

/**
 * Class QuoteBlock
 * @see https://github.com/wordplate/extended-acf#fields
 * @package App\FlexibleLayout
 */
class QuoteBlock extends AbstractBlock
{
    public function __construct()
    {
        parent::__construct([
            'title' => __('Citation'),
            'description' => __('Bloc permettant la mise en avant d’un témoignage ou d’une citation'),
            'category' => GutBlockName::CONTENT,
            'mode' => 'edit',
            'dir' => BlocksTwigPath::CONTENT
        ]);
    }

    /**
     * @return Traversable
     */
    protected function registerFields(): \Traversable
    {
        yield HeadingField::make();
    }
}
