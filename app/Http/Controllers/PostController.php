<?php

namespace App\Http\Controllers;

use App\Domain\Post\Facades\PostService;
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
        $this->middleware('role.subscriber+')->only(['get']);
        $this->middleware('role.editor+')->only(['create', 'update', 'delete']);
    }

    public function get($id)
    {
        return $this->wrapController(function () use ($id) {


            $post = PostService::getPost($id);

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

            $this->validateArray(
                $request->only(['header', 'content', 'category-id']),
                [
                    'header' => 'required|string|min:2',
                    'content' => 'required|string',
                    'category-id' => [
                        'required',
                        'integer',
                        (new class() implements Rule
                        {
                            protected $catId;

                            public function passes($attribute, $value)
                            {
                                $this->catId = $value;
                                if ($value < 1) return false;
                                return (is_null(AppMake::Category()->newQuery()->find($value)));
                            }

                            public function message()
                            {
                                return sprintf("Wrong category id=%s", $this->catId);
                            }
                        })
                    ],
                ]
            );

            $post = PostService::getPost($id);

            return $this->success();
        });
    }
}
