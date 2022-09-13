<?php

declare(strict_types=1);

namespace App\Taxonomies;

use Adeliom\Lumberjack\Taxonomy\Term;

class Category extends Term
{
    public static function getTaxonomyType(): ?string
    {
        return "category";
    }
}
