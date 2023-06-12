<?php

declare(strict_types=1);

namespace App\PostTypes;

use Rareloop\Lumberjack\Post as BasePost;
use Tightenco\Collect\Support\Collection;

/**
 * Class Question
 *
 * @package App\PostTypes
 */
class Question extends BasePost
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
        return 'questions';
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
                'name' => __('Questions'),
                'singular_name' => __('Question'),
                'add_new_item' => __('Ajouter une nouvelle question'),
            ],
            'public' => true,
        ];
    }

    public static function getAll()
    {
        $query = self::builder()
            ->limit(-1)
            ->offset(0);

        return $query->get();
    }
}
