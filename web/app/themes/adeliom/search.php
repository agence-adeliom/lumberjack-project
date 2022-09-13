<?php

/**
 * Search results page
 */

declare(strict_types=1);

namespace App;

use App\Http\Controllers\Controller;
use App\PostTypes\Post;
use Rareloop\Lumberjack\Exceptions\TwigTemplateNotFoundException;
use Rareloop\Lumberjack\Http\Responses\TimberResponse;
use Timber\Timber;

class SearchController extends Controller
{
    /**
     * @throws TwigTemplateNotFoundException
     */
    public function handle(): TimberResponse
    {
        $context = Timber::get_context();
        $searchQuery = get_search_query();

        $context['title'] = "Search results for '" . htmlspecialchars($searchQuery) . "'";
        $context['posts'] = Post::query([
            's' => $searchQuery,
        ]);

        return new TimberResponse([
            'page/search.html.twig',
            'post/archive.html.twig',
            'page/index.html.twig'
        ], $context);
    }
}
