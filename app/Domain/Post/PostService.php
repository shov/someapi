<?php declare(strict_types=1);

namespace App\Domain\Post;

use App\Domain\Category\Facades\CategoryService;
use App\Exceptions\EntityNotFoundException;
use App\Helpers\AppMake;
use App\Helpers\CommonHelper;
use Illuminate\Contracts\Validation\Rule;

/**
 * Class PostService
 * @package App\Domain\Post
 */
class PostService
{
    use CommonHelper;

    /**
     * Just get the post by given id
     * @param int $id
     * @return Post
     * @throws EntityNotFoundException
     */
    public function getPost(int $id): Post
    {
        $post = AppMake::Post()
            ->with('category')
            ->newQuery()
            ->find($id);

        if (is_null($post)) {
            throw new EntityNotFoundException(
                sprintf("Have no post with id=%d", $id));
        }

        return $post;
    }

    /**
     * Make full redefinition data of the existing post
     * @param Post $post
     * @param string $header
     * @param string $content
     * @param int $categoryId must be existing
     * @return Post
     */
    public function updatePostWithFullData(Post $post, string $header, string $content, int $categoryId): Post
    {
        $this->validateArray(compact('header', 'content', 'categoryId'),
            [
                'header' => 'required|string|min:2',
                'content' => 'required|string',
                'categoryId' => [
                    'required',
                    'integer',
                    (new class() implements Rule
                    {
                        protected $catId;

                        public function passes($attribute, $value)
                        {
                            $this->catId = (int)$value;
                            if ($this->catId < 1) return false;

                            return (!is_null(AppMake::Category()
                                ->newQuery()
                                ->find($this->catId)));
                        }

                        public function message()
                        {
                            return sprintf("Wrong category id=%s", $this->catId);
                        }
                    })
                ],
            ]
        );

        $post->header = $header;
        $post->content = $content;
        $post->category()->associate(CategoryService::getCategory($categoryId));
        $post->save();
        return $post;
    }

    /**
     * Just delete one post
     * @param Post $post
     */
    public function delete(Post $post)
    {
        $post->delete();
    }
}