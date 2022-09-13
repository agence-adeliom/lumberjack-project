<?php

/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 */

declare(strict_types=1);

namespace App;

use Adeliom\Lumberjack\Pagination\PaginationViewModel;
use App\Http\Controllers\Controller;
use App\Http\Lumberjack;
use App\PostTypes\Page;
use Rareloop\Lumberjack\Exceptions\TwigTemplateNotFoundException;
use Rareloop\Lumberjack\Http\Responses\TimberResponse;
use App\PostTypes\Post;
use Timber\Timber;

class IndexController extends Controller
{
    public const RESULTS_PER_PAGE = 10;

    /**
     * @throws TwigTemplateNotFoundException
     */
    public function handle(): TimberResponse
    {
        global $paged;
        if (!isset($paged) || !$paged) {
            $paged = 1;
        }

        $context = Timber::get_context();
        $context['page'] = new Page();

        $args = [];
        $context['posts'] = Post::paginate(self::RESULTS_PER_PAGE, $args);
        $context['pagination'] = PaginationViewModel::fromQueryBuilder(self::RESULTS_PER_PAGE, $paged, $args);

        $templates = ['page/index.html.twig'];
        if (is_home()) {
            array_unshift($templates, 'page/front-page.html.twig', 'page/home.html.twig');
        }

        if ($context['page']->id) {
            $templates = Lumberjack::passwordRender($templates, $context['page']->id);
        }
        return new TimberResponse($templates, $context);
    }
}
