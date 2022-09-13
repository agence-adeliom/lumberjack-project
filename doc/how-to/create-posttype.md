# Créer un post type

Dans le dossier `web/app/themes/adeliom/app/PostTypes` créer un fichier pour votre post type. ex: `Example.php`

```php
<?php
// web/app/themes/adeliom/app/PostTypes/Example.php

declare(strict_types=1);

namespace App\PostTypes;

use Rareloop\Lumberjack\Post as BasePost;

/**
 * Class Example
 *
 * @package App\PostTypes
 */
class Example extends BasePost
{
    /**
     * Return the key used to register the post type with WordPress
     * First parameter of the `register_post_type` function:
     * https://codex.wordpress.org/Function_Reference/register_post_type
     *
     * @return string
     */
    public static function getPostType(): string
    {
        return 'example';
    }

    /**
     * Return the config to use to register the post type with WordPress
     * Second parameter of the `register_post_type` function:
     * https://codex.wordpress.org/Function_Reference/register_post_type
     *
     * @return array|null
     */
    protected static function getPostTypeConfig(): ?array
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

## Déclarer l'utilisation du post type

Dans le fichier de configuration `web/app/themes/adeliom/config/posttypes.php` ajouter une entrée comme si dessous.

```php
<?php

declare(strict_types=1);

return [
    /**
     * List all the sub-classes of Rareloop\Lumberjack\Post in your app that you wish to
     * automatically register with WordPress as part of the bootstrap process.
     */
    'register' => [
        ...
        \App\PostTypes\Example::class
    ],
];
```
