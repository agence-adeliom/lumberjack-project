<?php

/**
 * The template for displaying 404 pages (Not Found)
 * Methods for TimberHelper can be found in the /functions sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

declare(strict_types=1);

namespace App;

use App\Http\Controllers\Controller;
use Rareloop\Lumberjack\Exceptions\TwigTemplateNotFoundException;
use Rareloop\Lumberjack\Http\Responses\TimberResponse;
use Timber\Timber;

/**
 * Class names can not start with a number so the 404 controller has a special cased name
 */
class Error404Controller extends Controller
{
    /**
     * @throws TwigTemplateNotFoundException
     */
    public function handle(): TimberResponse
    {
        return new TimberResponse('errors/404.html.twig', Timber::get_context(), 404);
    }
}
