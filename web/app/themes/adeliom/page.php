<?php

/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 */

declare(strict_types=1);

namespace App;

use App\Http\Controllers\Controller;
use App\Http\Lumberjack;
use App\PostTypes\Page;
use Rareloop\Lumberjack\Exceptions\TwigTemplateNotFoundException;
use Rareloop\Lumberjack\Http\Responses\TimberResponse;
use Timber\Timber;

class PageController extends Controller
{
    /**
     * @throws TwigTemplateNotFoundException
     */
    public function handle(): TimberResponse
    {
        $context = Timber::get_context();
        $page = new Page();

        $context['post'] = $page;
        $context['title'] = $page->title();
        $context['content'] = $page->content();

        $template = Lumberjack::passwordRender([
            sprintf('page/page-%s.html.twig', $page->id),
            sprintf('page/page-%s.html.twig', $page->slug),
            'page/page.html.twig'
        ], $page->id);
        return new TimberResponse($template, $context);
    }
}
