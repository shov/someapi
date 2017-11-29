<?php declare(strict_types=1);

namespace App\Domain\User;

use App\Helpers\AppMake;
use App\Exceptions\UserAuthException;
use App\Helpers\CommonHelper;
use App\Domain\UserRole\UserRole;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserService
 * @package App\Services
 */
class UserService
{
    use CommonHelper;

    /**
     * @param string $email
     * @return User|null
     * @throws ValidationException
     */
    public function getUserByEmail(string $email): ?User
    {
        $this->validateArray(compact('email'), ['email' => 'email',]);

        return AppMake::User()::where('email', $email)->first();
    }

    /**
     * @return User
     * @throws UserAuthException
     */
    public function getAuthorizedUser(): User
    {
        try {
            $id = JWTAuth::getPayload()->get('sub');
        } catch (JWTException $e) {
            throw new UserAuthException("Can't get the authorized user");
        }

        if (empty($id)) throw new UserAuthException("Have no authorized user");;

        return AppMake::User()->find($id);
    }

    /**
     * Logout procedure
     * @throws UserAuthException
     */
    public function logoutCurrUser()
    {
        try {
            $token = JWTAuth::getToken();

            if (false === $token) {
                throw new UserAuthException("Have no token");
            }

            JWTAuth::invalidate($token);

        } catch (\Throwable $e) {
            throw new UserAuthException("Token is wrong");
        }
    }

    /**
     * Check is the given user of the given role
     *
     * @param User $user
     * @param UserRole $role
     * @return bool
     */
    public function is(User $user, UserRole $role): bool
    {
        if (empty($user->userRole)) return false;
        return ($user->userRole->id === $role->id);
    }
}