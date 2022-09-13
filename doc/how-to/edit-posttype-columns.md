## Modifier les colonnes de votre post type

Dans l'exemple si dessous nous ajoutons une colonne `My field` au post type `example`.

```php
<?php

declare(strict_types=1);

namespace App\Hooks;

use Adeliom\Lumberjack\Hooks\Models\Filter;

/**
 * Class ExampleColumnsHooks
 * @package App\Hooks
 */
class ExampleColumnsHooks
{
    /**
     * Display custom columns in "example" list
     * @Filter("manage_example_posts_columns")
     */
    #[Filter("manage_example_posts_columns")]
    public static function addCustomColumn($column)
    {
        $column['my_field_key'] = 'My field';
        return $column;
    }

    /**
     * Display custom columns in "example" list
     *
     * @Filter("manage_edit-example_sortable_columns")
     */
    #[Filter("manage_edit-example_sortable_columns")]
    public static function sortCustomColumn($columns)
    {
        $columns['my_field_key'] = 'My field';
        return $columns;
    }

    /**
     * Render data in custom columns
     *
     * @Filter("manage_example_posts_custom_column", 10, 2)
     */
    #[Filter("manage_example_posts_custom_column", 10, 2)]
    public static function customColumn(string $column, int $post_id): void
    {
        if ($column == 'my_field_key') {
            echo get_field('my_field_key', $post_id);
        } else {
            echo '';
        }
    }
}
```
