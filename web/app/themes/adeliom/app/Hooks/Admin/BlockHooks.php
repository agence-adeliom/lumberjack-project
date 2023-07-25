<?php

declare(strict_types=1);

namespace App\Hooks\Admin;

use Adeliom\Lumberjack\Hooks\Models\Filter;
use App\Enum\GutBlockName;

class BlockHooks
{
    /**
     * Ajout d'un nouveau type de catégorie dans les blocs GUT
     */
    #[Filter(tag: 'block_categories_all', priority: 10, accepted_args: 2)]
    public static function blockCategory($categories, $post)
    {
        return array_merge(
            $categories,
            [
                [
                    'slug' => GutBlockName::GENERIC,
                    'title' => 'Texte et Images',
                    'icon' => 'align-right',
                ],
                [
                    'slug' => GutBlockName::LISTING,
                    'title' => 'Listes',
                    'icon' => 'editor-ul',
                ],
                [
                    'slug' => GutBlockName::CTA,
                    'title' => 'CTA',
                    'icon' => 'button',
                ],
                [
                    'slug' => GutBlockName::DOWNLOAD,
                    'title' => 'Téléchargement',
                    'icon' => 'download',
                ],
                [
                    'slug' => GutBlockName::ACCORDIONS,
                    'title' => 'Accordéons',
                    'icon' => 'arrow-down-alt2',
                ],
                [
                    'slug' => GutBlockName::TESTIMONIALS,
                    'title' => 'Témoignages',
                    'icon' => 'format-quote',
                ],
                [
                    'slug' => GutBlockName::LATEST,
                    'title' => 'Remontées automatiques',
                    'icon' => 'page',
                ],
                [
                    'slug' => GutBlockName::OTHERS,
                    'title' => 'Autre',
                    'icon' => 'marker',
                ],
                [
                    'slug' => GutBlockName::NAVIGATION,
                    'title' => 'Navigation',
                    'icon' => 'menu',
                ],
                [
                    'slug' => GutBlockName::RELATION,
                    'title' => 'Relation',
                    'icon' => 'networking',
                ],
                [
                    'slug' => GutBlockName::HERO,
                    'title' => 'Header',
                    'icon' => 'editor-kitchensink',
                ],
                [
                    'slug' => GutBlockName::CONTENT,
                    'title' => 'Contenu',
                    'icon' => 'admin-post',
                ]
            ]
        );
    }
}
