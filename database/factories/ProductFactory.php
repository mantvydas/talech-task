<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;
use App\Helpers\Color;

$factory->define(Product::class, function (Faker $faker) {
    $colors = array_keys(Color::getColors());
    return [
        'name' => $faker->name,
        'ean' => $faker->numerify('#############'),
        'type' => $faker->randomElement(array ('mens','ladies')),
        'weight' => $faker->randomFloat(2, 1, 1000),
        'price' => $faker->randomFloat(2, 1, 100000),
        'color' => $faker->randomElement($colors),
        'quantity' => $faker->numberBetween(1, 1000),
        'active' => $faker->numberBetween(0, 1),
    ];
});
