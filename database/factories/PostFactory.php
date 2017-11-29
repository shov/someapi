<?php

use App\Domain\Post\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'header' => $faker->sentence(3),
        'content' => $faker->paragraph(4),
    ];
});
