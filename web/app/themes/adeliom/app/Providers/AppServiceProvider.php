<?php

declare(strict_types=1);

namespace App\Providers;

use Ajgl\Twig\Extension\BreakpointExtension;
use Djboris88\Twig\Extension\CommentedIncludeExtension;
use HelloNico\Twig\DumpExtension;
use Rareloop\Lumberjack\Config;
use Rareloop\Lumberjack\Providers\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Perform any additional boot required for this application
     */
    public function boot(Config $config): void
    {
        $stages = $config->get('stages');
        if (is_array($stages)) {
            define('ENVIRONMENTS', serialize($stages));
        }

        $mimesTypes = $config->get('images.mimes_types', []);
        add_filter("upload_mimes", static function (array $mime_types = []) use ($mimesTypes) {
            return array_merge($mime_types, $mimesTypes);
        });

        if (defined('WP_DEBUG') && WP_DEBUG && function_exists('add_filter')) {
            add_filter('timber/loader/twig', function ($twig) {
                $twig->addExtension(new CommentedIncludeExtension());
                $twig->addExtension(new DumpExtension());
                $twig->addExtension(new BreakpointExtension());

                return $twig;
            });

            add_filter('timber/output', function ($output, $data, $file) {
                return "\n<!-- Begin output of '" . $file . "' -->\n" . $output . "\n<!-- / End output of '" . $file . "' -->\n";
            }, 10, 3);
        }
    }
}
