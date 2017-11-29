<?php

use App\Domain\Category\Category;
use App\Domain\Post\Post;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Category::class, 10)->create()->each(function (Category $category) {
            $category->posts()->saveMany(factory(Post::class, 12)->make());
        });
    }
}
