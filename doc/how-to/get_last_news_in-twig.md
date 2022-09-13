

```php
<?php
//web/app/themes/adeliom/app/Extensions/Twig/NewsExtension.php
declare(strict_types=1);

namespace App\Extensions\Twig;

use Rareloop\Lumberjack\Post;
use Timber\Timber;
use Timber\Twig_Function;
use Twig\Environment;
use Twig\Extension\AbstractExtension;

class NewsExtension extends AbstractExtension
{
    /**
     * Adds functionality to Twig.
     *
     * @param Environment $twig The Twig environment.
     */
    public static function register(Environment $twig): Environment
    {
        $twig->addFunction(new Twig_Function('getLastNews', function ($customNews = null) {
            return self::getLastNews($customNews);
        }));
        return $twig;
    }

    /**
     * @param string|null $customNews
     * @return array
     */
    public static function getLastNews(?string $customNews = null): array
    {
        return $customNews ? (Timber::get_posts($customNews) ?? []) : Post::all(3, 'date', 'DESC')->toArray();
    }
}

```


## Register extensions

```php
<?php
// web/app/themes/adeliom/config/twig.php
declare(strict_types=1);

/*
 * You can place your custom package configuration in here.
 */
return [
    'allowed_functions' => [
        ...
    ],
    'extensions' => [
        ...
        \App\Extensions\Twig\NewsExtension::class,
    ],
];

```
