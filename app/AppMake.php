<?php declare(strict_types=1);

namespace App;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Models\UserRole;
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
        return App::make('Model.User');
    }

    public static function UserRole(): UserRole
    {
        return App::make('Model.UserRole');
    }

    public static function Category(): Category
    {
        return App::make('Model.Category');
    }

    public static function Post(): Post
    {
        return App::make('Model.Post');
    }
}