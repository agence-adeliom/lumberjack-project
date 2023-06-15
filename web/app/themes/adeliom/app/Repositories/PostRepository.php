<?php

namespace App\Repositories;

use App\PostTypes\Post;
use Rareloop\Lumberjack\Post as BasePost;

/**
 * Class PostRepository
 * @package App\PostTypes
 */
class PostRepository extends BasePost
{
    protected const POST_TYPE = 'post';
    protected const CATEGORY = 'category';

    public static function getPostType(): string
    {
        return self::POST_TYPE;
    }

    public static function getCat(): string
    {
        return self::CATEGORY;
    }

    /**
     * Get all cats
     * @return int|WP_Error|WP_Term[]
     */
    public static function getAllCats()
    {
        return get_terms([
            'taxonomy' => self::CATEGORY,
            'hide_empty' => false,
            'orderBy' => 'name',
            'order' => 'ASC',
        ]);
    }

    /**
     * Returns all posts.
     * @param int $limit
     * @param array $taxonomies
     * @return array
     */
    public static function getAll(int $limit = -1, array $taxonomies = []): array
    {
        $qb = Post::builder()
            ->whereStatus(['publish'])
            ->limit($limit)
            ->orderBy('date', 'DESC')
            ->whereTaxonomies($taxonomies);

        $results = $qb->get();

        if ($results->count() > 0) {
            return $results->toArray();
        }

        return [];
    }
}
