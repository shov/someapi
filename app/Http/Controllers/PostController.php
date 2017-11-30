<?php

namespace App\Http\Controllers;

use App\Domain\Post\Facades\PostService;
use App\Helpers\ControllerHelper;
use Illuminate\Http\Request;

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

            $postToUpdate = PostService::getPost($id);

            $newData = $request->only(['header', 'content', 'category-id']);

            PostService::updatePostWithFullData(
                $postToUpdate,
                $newData['header'],
                $newData['content'],
                (int)$newData['category-id']);

            return $this->success();
        });
    }

    public function delete(Request $request, $id)
    {
        return $this->wrapController(function () use ($request, $id) {

            $postToDelete = PostService::getPost($id);

            PostService::delete($postToDelete);

            return $this->success();
        });
    }
}
