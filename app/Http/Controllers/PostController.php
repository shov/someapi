<?php

namespace App\Http\Controllers;

use App\Domain\User\Facades\UserService;
use App\Domain\UserRole\Facades\UserRoleService;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\UserAuthException;
use App\Helpers\AppMake;
use App\Helpers\ControllerHelper;
use Illuminate\Http\Request;

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

            if(!$havePermission) {
                throw new UserAuthException("Not enough permissions!");
            }

            $post = AppMake::Post()
                ->with('category')
                ->newQuery()
                ->find($id);

            if(is_null($post)) {
                throw new EntityNotFoundException(
                    sprintf("Have no that post with id=%d", $id));
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
}
