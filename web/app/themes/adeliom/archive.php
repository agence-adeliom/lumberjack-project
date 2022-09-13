<?php

/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 */

declare(strict_types=1);

namespace App;

use Adeliom\Lumberjack\Pagination\PaginationViewModel;
use App\Http\Controllers\Controller;
use App\PostTypes\Post;
use Rareloop\Lumberjack\Exceptions\TwigTemplateNotFoundException;
use Rareloop\Lumberjack\Http\Responses\TimberResponse;
use Timber\Timber;

class ArchiveController extends Controller
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
        $context['title'] = 'Archive';


        if (is_day()) {
            $context['title'] = 'Archive: ' . get_the_date('D M Y');
        } elseif (is_month()) {
            $context['title'] = 'Archive: ' . get_the_date('M Y');
        } elseif (is_year()) {
            $context['title'] = 'Archive: ' . get_the_date('Y');
        } elseif (is_tag()) {
            $context['title'] = single_tag_title('', false);
        } elseif (is_category()) {
            $context['title'] = single_cat_title('', false);
        } elseif (is_post_type_archive()) {
            $context['title'] = post_type_archive_title('', false);
        }

        $args = [];
        $context['posts'] = Post::paginate(self::RESULTS_PER_PAGE, $args);
        $context['pagination'] = PaginationViewModel::fromQueryBuilder(self::RESULTS_PER_PAGE, $paged, $args);

        return new TimberResponse(['post/archive.html.twig', 'page/index.html.twig'], $context);
    }
}
