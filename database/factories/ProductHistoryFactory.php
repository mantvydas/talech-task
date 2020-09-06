<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ProductHistory;
use Faker\Generator as Faker;

$factory->define(ProductHistory::class, function (Faker $faker) {
    $products = App\Models\Product::pluck('id')->toArray();
    return [
        'product_id' => $faker->randomElement($products),
        'price' => $faker->randomFloat(2, 1, 10000),
        'quantity' => $faker->numberBetween(1, 1000),
        'created_at' => $faker->dateTimeInInterval($startDate = '-100 days', $interval = '+100 days')
    ];
});
