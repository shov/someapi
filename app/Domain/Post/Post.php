<?php

namespace App\Domain\Post;

use App\Domain\Category\Category;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
