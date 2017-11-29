<?php declare(strict_types=1);

namespace App\Helpers;

use App\Domain\Category\Category;
use App\Domain\Post\Post;
use App\Domain\User\User;
use App\Domain\UserRole\UserRole;
use Illuminate\Support\Facades\App;

/**
 * Class AppMake
 * Takes out entities from IoC container
 * @package App
 */
class AppMake
{
    public static function User(): User
    {
        return App::make(User::class);
    }

    public static function UserRole(): UserRole
    {
        return App::make(UserRole::class);
    }

    public static function Category(): Category
    {
        return App::make(Category::class);
    }

    public static function Post(): Post
    {
        return App::make(Post::class);
    }
}