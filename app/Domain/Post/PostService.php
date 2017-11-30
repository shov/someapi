<?php declare(strict_types=1);

namespace App\Domain\Post;

use App\Exceptions\EntityNotFoundException;
use App\Helpers\AppMake;
use App\Helpers\CommonHelper;

/**
 * Class PostService
 * @package App\Services
 */
class PostService
{
    use CommonHelper;

    /**
     * Just get the post by given id
     * @param int $id
     * @return Post
     * @throws EntityNotFoundException
     */
    public function getPost(int $id): Post
    {
        $post = AppMake::Post()
            ->with('category')
            ->newQuery()
            ->find($id);

        if (is_null($post)) {
            throw new EntityNotFoundException(
                sprintf("Have no post with id=%d", $id));
        }

        return $post;
    }
}