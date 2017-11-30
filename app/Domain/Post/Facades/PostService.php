<?php

namespace App\Domain\Post\Facades;

use App\Domain\Post\Post;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Facade;

/**
 * Class PostService
 * @package App\Domain\Post\Facades
 * @method static Post getPost(int $id)
 * @method static Post updatePostWithFullData(Post $post, string $header, string $content, int $categoryId)
 * @method static delete(Post $post)
 */
class PostService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return App::make(\App\Domain\Post\PostService::class);
    }
}