<?php

use App\Domain\Category\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->sentence(1),
    ];
});
