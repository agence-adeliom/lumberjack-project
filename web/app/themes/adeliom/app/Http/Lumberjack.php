<?php

declare(strict_types=1);

namespace App\Http;

use App\Exceptions\RegisterExceptionHandler;
use Rareloop\Lumberjack\Bootstrappers\BootProviders;
use Rareloop\Lumberjack\Bootstrappers\LoadConfiguration;
use Rareloop\Lumberjack\Bootstrappers\RegisterAliases;
use Rareloop\Lumberjack\Bootstrappers\RegisterFacades;
use Rareloop\Lumberjack\Bootstrappers\RegisterProviders;
use Rareloop\Lumberjack\Bootstrappers\RegisterRequestHandler;
use Rareloop\Lumberjack\Http\Lumberjack as LumberjackCore;

class Lumberjack extends LumberjackCore
{
    protected $bootstrappers = [
        LoadConfiguration::class,
        RegisterExceptionHandler::class,
        RegisterFacades::class,
        RegisterProviders::class,
        BootProviders::class,
        RegisterAliases::class,
        RegisterRequestHandler::class,
    ];

    /**
     * @param string|array $twigPath
     * @param int $id
     * @return string|array
     */
    public static function passwordRender(string|array $twigPath, int $id): string|array
    {
        return post_password_required($id) ? 'errors/password.html.twig' : $twigPath;
    }

    /**
     * @param array $context
     * @return array
     */
    public function addToContext(array $context): array
    {
        $context['is_home'] = is_home();
        $context['is_front_page'] = is_front_page();
        $context['is_logged_in'] = is_user_logged_in();
        $context['menu'] = new \Timber\Menu('main-nav');

        return $context;
    }
}
