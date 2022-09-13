<?php

declare(strict_types=1);

namespace App\Taxonomies;

use Adeliom\Lumberjack\Taxonomy\Term;

class Tag extends Term
{
    public static function getTaxonomyType(): ?string
    {
        return "post_tag";
    }
}
