<?php

namespace App\Domain\Post\Facades;

use App\Domain\Post\Post;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Facade;

/**
 * Class UserService
 * @package App\Facades
 * @method static Post getPost(int $id)
 */
class PostService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return App::make(\App\Domain\Post\PostService::class);
    }
}