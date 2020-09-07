<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ProductImage;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

$factory->define(ProductImage::class, function (Faker $faker) {

    $products = App\Models\Product::pluck('id')->toArray();
    $image = $faker->image();
    $imageFile = new File($image);
    $path = Storage::disk('public')->putFile('images/product_images', $imageFile);
    $pathArray = explode('/', $path);
    return [
        'product_id' => $faker->randomElement($products),
        'name' => $pathArray[count($pathArray)-1],
    ];
});
