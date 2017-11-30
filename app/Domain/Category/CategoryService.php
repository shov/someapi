<?php declare(strict_types=1);

namespace App\Domain\Category;

use App\Exceptions\EntityNotFoundException;
use App\Helpers\AppMake;
use App\Helpers\CommonHelper;

/**
 * Class CategoryService
 * @package App\Domain\Category
 */
class CategoryService
{
    use CommonHelper;

    /**
     * Just get the post by given id
     * @param int $id
     * @return Category
     * @throws EntityNotFoundException
     */
    public function getCategory(int $id): Category
    {
        $category = AppMake::Category()
            ->newQuery()
            ->find($id);

        if (is_null($category)) {
            throw new EntityNotFoundException(
                sprintf("Have no category with id=%d", $id));
        }

        return $category;
    }
}