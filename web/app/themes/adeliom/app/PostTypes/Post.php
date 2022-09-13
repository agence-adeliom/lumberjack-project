<?php

declare(strict_types=1);

namespace App\PostTypes;

use Adeliom\Lumberjack\Pagination\Pagination;
use Rareloop\Lumberjack\Exceptions\PostTypeRegistrationException;
use Rareloop\Lumberjack\Post as BasePost;

class Post extends BasePost
{
    use Pagination;
}
