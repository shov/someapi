<?php

namespace App\Domain\User\Facades;

use App\Domain\User\User;
use App\Domain\UserRole\UserRole;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Facade;

/**
 * Class UserService
 * @package App\Facades
 *
 * @method static null|User getUserByEmail(string $email)
 * @method static User getAuthorizedUser()
 * @method static logoutCurrUser()
 * @method static bool is(User $user, UserRole $role)
 */
class UserService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return App::make(\App\Domain\User\UserService::class);
    }
}