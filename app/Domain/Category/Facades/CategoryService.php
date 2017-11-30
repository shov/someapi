<?php

namespace App\Domain\Category\Facades;

use App\Domain\Category\Category;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Facade;

/**
 * Class CategoryService
 * @package App\Domain\Category\Facades
 * @method static Category getCategory(int $id)
 */
class CategoryService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return App::make(\App\Domain\Category\CategoryService::class);
    }
}