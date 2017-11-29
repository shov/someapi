<?php

namespace App\Domain\Category;

use App\Domain\Post\Post;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }
}
