<?php

namespace App\Http\Controllers;

use App\Domain\User\Facades\UserService;
use App\Exceptions\UserAuthException;
use App\Helpers\ControllerHelper;
use App\Http\Middleware\AuthWithJWT;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ControllerHelper;

    public function __construct()
    {
        $this->middleware('jwt.auth')->only('logout');
    }

    public function login(Request $request)
    {
        return $this->wrapController(function () use ($request) {
            $credentials = $request->only([
                'email',
                'password',
            ]);

            $token = JWTAuth::attempt($credentials);

            if (false === $token) {
                throw new \Exception("Wrong credentials!");
            }

            $user = UserService::getUserByEmail($credentials['email']);

            if (is_null($user)) {
                throw new UserAuthException("Have no user who already authenticated? OX");
            }

            return $this->success([
                'token' => $token,
            ]);
        });
    }

    public function logout()
    {
        return $this->wrapController(function () {
            UserService::getAuthorizedUser();
            UserService::logoutCurrUser();
            AuthWithJWT::skipRefreshedToken();

            return $this->success();
        });
    }
}
