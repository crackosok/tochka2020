<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Item;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(3),
        'description' => $faker->text,
        'price' => $faker->randomFloat(2, 0, 1000),
        'stock' => $faker->randomNumber(2)
    ];
});
