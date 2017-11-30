<?php

namespace App\Http\Middleware;

use App\Domain\User\Facades\UserService;
use App\Domain\UserRole\Facades\UserRoleService;
use App\Exceptions\UserAuthException;
use App\Helpers\ControllerHelper;
use Closure;

class UserRoleSubscriberOrHigher
{
    use ControllerHelper;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws UserAuthException
     */
    public function handle($request, Closure $next)
    {
        return $this->wrapController(function () use ($request) {
            $user = UserService::getAuthorizedUser();

            $havePermission = UserService::is($user, UserRoleService::subscriber())
                | UserService::is($user, UserRoleService::editor())
                | UserService::is($user, UserRoleService::admin());

            if (!$havePermission) {
                throw new UserAuthException("Not enough permissions!");
            }

        },  $next($request));
    }
}
