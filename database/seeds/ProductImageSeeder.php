<?php

use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Storage::deleteDirectory('images/product_images');
        Schema::disableForeignKeyConstraints();
        ProductImage::truncate();
        Schema::enableForeignKeyConstraints();
        factory(ProductImage::class, 40)->create();
    }
}
