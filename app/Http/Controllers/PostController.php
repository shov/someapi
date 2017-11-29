<?php

namespace App\Http\Controllers;

use App\Domain\User\Facades\UserService;
use App\Domain\UserRole\Facades\UserRoleService;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\UserAuthException;
use App\Helpers\AppMake;
use App\Helpers\ControllerHelper;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Rule;

class PostController extends Controller
{
    use ControllerHelper;

    public function __construct()
    {
        $this->middleware('jwt.auth')->except(['list']);
    }

    public function get($id)
    {
        return $this->wrapController(function () use ($id) {
            $user = UserService::getAuthorizedUser();

            $havePermission = UserService::is($user, UserRoleService::subscriber())
                | UserService::is($user, UserRoleService::editor())
                | UserService::is($user, UserRoleService::admin());

            if (!$havePermission) {
                throw new UserAuthException("Not enough permissions!");
            }

            $post = AppMake::Post()
                ->with('category')
                ->newQuery()
                ->find($id);

            if (is_null($post)) {
                throw new EntityNotFoundException(
                    sprintf("Have no post with id=%d", $id));
            }

            return $this->success([
                'header' => $post->header,
                'content' => $post->content,
                'category' => [
                    'id' => $post->category->id,
                    'name' => $post->category->name,
                ]
            ]);
        });
    }

    public function update(Request $request, $id)
    {
        return $this->wrapController(function () use ($request, $id) {
            $user = UserService::getAuthorizedUser();

            $havePermission = UserService::is($user, UserRoleService::editor())
                | UserService::is($user, UserRoleService::admin());

            if (!$havePermission) {
                throw new UserAuthException("Not enough permissions!");
            }

            $this->validateArray(
                $request->only(['header', 'content', 'category-id']),
                [
                    'header' => 'required|string|min:2',
                    'content' => 'required|string',
                    'category-id' => [
                        'required',
                        'integer',
                        new class() implements Rule
                        {
                            protected $catId;

                            public function passes($attribute, $value)
                            {
                                $this->catId = $value;
                                if($value < 1) return false;
                                return (is_null(AppMake::Category()->newQuery()->find($value)));
                            }

                            public function message()
                            {
                                return sprintf("Wrong category id=%s", $this->catId);
                            }
                        }
                    ],
                ]
            );

            $post = AppMake::Post()
                ->with('category')
                ->newQuery()
                ->find($id);

            if (is_null($post)) {
                throw new EntityNotFoundException(
                    sprintf("Have no post with id=%d", $id));
            }

            return $this->success();
        });
    }
}
