<?php declare(strict_types=1);

namespace App\Http\Middleware;

use App\Helpers\ControllerHelper;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthWithJWT
{
    use ControllerHelper;

    /**
     * May store refreshed token
     * @var null|string
     */
    protected static $newToken = null;

    /**
     * If token was refreshed, mix it to the response
     * @param array $response
     * @return array
     */
    public static function mixRefreshedTokenToResponse(array $response): array
    {
        if (is_null(static::$newToken)) {
            return $response;
        }

        return array_merge($response, [
            'refreshed-token' => static::$newToken,
        ]);
    }

    /**
     * Make JWT Authentication with token refresh
     *
     * @param Request $request, passes to detect uniqueness of the request, not to getting token,
     * the token is still getting from the global JWTAuth interface. All these manipulations are used for
     * the support laravel test environment
     *
     * @return bool
     */
    public static function isUserAuthorized(Request $request): bool
    {
        static $isUserAuthorized = null;
        static $requestHash = null;
        $requestHash = $requestHash ?? spl_object_hash($request);

        $authProcess = function(Request $request) {

            try {
                JWTAuth::setRequest($request)->parseToken();
            } catch (\Throwable $e) {
                return false;
            }

            try {
                $token = JWTAuth::getToken();

                if (false === $token) {
                    return false;
                }

                $user = JWTAuth::authenticate($token);

                if (false === $user) {
                    return false;
                }

            } catch (TokenExpiredException $e) {
                try {
                    $token = JWTAuth::refresh();
                    static::$newToken = $token;

                } catch (TokenExpiredException $e) {
                    return false;
                }
            } catch (\Throwable $e) {
                return false;
            }

            return true;
        };

        if($requestHash !== spl_object_hash($request)) {
            $isUserAuthorized = $authProcess($request);

        } elseif(is_null($isUserAuthorized)) {
            $isUserAuthorized = $authProcess($request);

        }

        return $isUserAuthorized;
    }

    public static function skipRefreshedToken()
    {
        static::$newToken = null;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!static::isUserAuthorized($request)) {
            return $this->failWithAuth();
        }

        return $next($request);
    }
}