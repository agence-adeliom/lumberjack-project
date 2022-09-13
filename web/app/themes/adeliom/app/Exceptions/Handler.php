<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rareloop\Lumberjack\Exceptions\Handler as LumberjackHandler;
use Rareloop\Lumberjack\Facades\Config;
use Rareloop\Lumberjack\Facades\Log;
use Rareloop\Lumberjack\Http\Responses\TimberResponse;
use Timber\Timber;

class Handler extends LumberjackHandler
{
    /**
     * @var mixed[]
     */
    protected $dontReport = [];

    public function report(Exception $e): void
    {
        parent::report($e);

        if (function_exists('wp_sentry_safe')) {
            wp_sentry_safe(static function (\Sentry\State\HubInterface $client) use ($e): void {
                $client->captureException($e);
            });
        }
    }

    public function render(ServerRequestInterface $request, Exception $e): ResponseInterface
    {
        // Provide a customisable error rendering when not in debug mode
        try {
            if (Config::get('app.debug') === false) {
                $data = Timber::get_context();
                $data['exception'] = $e;
                return new TimberResponse('errors/500.html.twig', $data, 500);
            }
        } catch (Exception $exception) {
            // Something went wrong in the custom renderer, log it and show the default rendering
            Log::error($exception);
        }

        return parent::render($request, $e);
    }
}
