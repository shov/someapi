<?php

namespace App\Domain\UserRole\Facades;

use App\Domain\UserRole\UserRole;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Facade;

/**
 * Class UserRoleService
 * @package App\Facades
 *
 * @method static UserRole getUserRole(string $name)
 * @method static UserRole admin()
 * @method static UserRole editor()
 * @method static UserRole subscriber()
 */
class UserRoleService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return App::make(\App\Domain\UserRole\UserRoleService::class);
    }
}