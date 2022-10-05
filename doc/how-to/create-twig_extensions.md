# Créer une extension Twig

Avec les extensions Twig vous pouvez ajouter des fonctions et des filtres.

### Exemple de filtre

Dans le dossier `web/app/themes/adeliom/app/Extensions/Twig` créer un fichier pour votre extension. ex: `SlugExtension.php`

```php
<?php
//web/app/themes/adeliom/app/Extensions/Twig/SlugExtension.php
declare(strict_types=1);

namespace App\Extensions\Twig;

use Rareloop\Lumberjack\Post;
use Timber\Timber;
use Timber\Twig_Filter;
use Twig\Environment;
use Twig\Extension\AbstractExtension;

class SlugExtension extends AbstractExtension
{
    /**
     * Adds functionality to Twig.
     *
     * @param Environment $twig The Twig environment.
     */
    public static function register(Environment $twig): Environment
    {
        $twig->addFilter(new Twig_Filter('slugify', function ($title) {
            return sanitize_title( $title );
        }));
        return $twig;
    }
}
```

### Exemple de fonction

Dans le dossier `web/app/themes/adeliom/app/Extensions/Twig` créer un fichier pour votre extension. ex: `NewsExtension.php`

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
            return $customNews ? (Timber::get_posts($customNews) ?? []) : Post::all(3, 'date', 'DESC')->toArray();
        }));
        return $twig;
    }
}
```

# Déclarer l'utilisation de l'extension

Dans le fichier de configuration `web/app/themes/adeliom/config/twig.php` ajouter une entrée comme si dessous.

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
        \App\Extensions\Twig\SlugExtension::class,
    ],
];
```
