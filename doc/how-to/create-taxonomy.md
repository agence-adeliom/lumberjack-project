# Créer une taxonomy

Dans le dossier `web/app/themes/adeliom/app/Taxonomies` créer un fichier pour votre post type. ex: `Example.php`

```php
<?php
// web/app/themes/adeliom/app/Taxonomies/Example.php

declare(strict_types=1);

namespace App\Taxonomies;

use Adeliom\Lumberjack\Taxonomy\Term as BaseTerm;

/**
 * Class Example
 *
 * @package App\PostTypes
 */
class Example extends BaseTerm
{
    /**
     * Return the key used to register the taxonomy with WordPress
     * First parameter of the `register_taxonomy` function:
     * https://developer.wordpress.org/reference/functions/register_taxonomy/
     *
     * @return string
     */
    public static function getTaxonomyType(): string
    {
        return "example";
    }

    /**
     * Return the object type which use this taxonomy.
     * Second parameter of the `register_taxonomy` function:
     * https://developer.wordpress.org/reference/functions/register_taxonomy/
     *
     * @return array|null
     */
    public static function getTaxonomyObjectTypes(): ?array
    {
        return ['post'];
    }

    /**
     * Return the config to use to register the taxonomy with WordPress
     * Third parameter of the `register_taxonomy` function:
     * https://developer.wordpress.org/reference/functions/register_taxonomy/
     *
     * @return array|null
     */
    protected static function getTaxonomyConfig(): ?array
    {
        return [
            'labels' => [
                'name' => __('Examples'),
                'singular_name' => __('Example'),
                'add_new_item' => __('Add New example'),
            ],
            'public' => true,
        ];
    }
}

```

## Déclarer l'utilisation de la taxonomy

Dans le fichier de configuration `web/app/themes/adeliom/config/taxonomies.php` ajouter une entrée comme si dessous.

```php
<?php

declare(strict_types=1);

return [
    /**
     * List all the sub-classes of Adeliom\Lumberjack\Taxonomy\Term in your app that you wish to
     * automatically register with WordPress as part of the bootstrap process.
     */
    'register' => [
        ...
        \App\Taxonomies\Example::class
    ],
];
```
