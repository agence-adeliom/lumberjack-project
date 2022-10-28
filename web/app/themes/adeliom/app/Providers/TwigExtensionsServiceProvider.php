<?php

namespace App\Providers;

use Rareloop\Lumberjack\Config;
use Rareloop\Lumberjack\Providers\ServiceProvider;
use Timber\Twig_Function;

class TwigExtensionsServiceProvider extends ServiceProvider
{
    /**
     * Register all extensions listed into the config file
     * @param Config $config
     */
    public function boot(Config $config): void
    {
        add_filter('timber/twig', function ($twig) use ($config) {
            $functionsToRegister = $config->get('twig.allowed_functions');

            foreach ($functionsToRegister ?? [] as $function) {
                $twig->addFunction(new Twig_Function($function, $function));
            }

            return $twig;
        });

        $extensionsToRegister = $config->get('twig.extensions');
        if (is_array($extensionsToRegister)) {
            foreach ($extensionsToRegister as $extension) {
                add_filter("timber/twig", [$extension, "register"], 10, 1);
            }
        }
    }
}
