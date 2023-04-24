<?php


namespace App\TwigExtensions;

use Timber\Timber;
use Timber\Twig_Function;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Rareloop\Lumberjack\Post;

class NewsExtension extends AbstractExtension
{
    /**
     * Adds functionality to Twig.
     *
     * @param Environment $twig The Twig environment.
     * @return Environment
     */
    public static function register(Environment $twig): Environment
    {
        $twig->addFunction(new Twig_Function("getLastNews", [__CLASS__, 'getLastNews']));

        return $twig;
    }

    public static function getLastNews($customNews = null, int $perPage = 3, ?array $categorySlugs = [])
    {
        if ($customNews) {
            $lastNews = Timber::get_posts($customNews);
        } else {
            $taxonomies = array_map(function ($slug) {
                if ($slug) {
                    return [
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => $slug,
                        'include_children' => true,
                        'operator' => 'IN',
                    ];
                }
            }, $categorySlugs);

            $query = Post::builder()
                ->orderBy('date', 'DESC')
                ->limit($perPage)
                ->whereTaxonomies($taxonomies);

            $lastNews = $query->get();
        }

        return $lastNews;
    }
}
