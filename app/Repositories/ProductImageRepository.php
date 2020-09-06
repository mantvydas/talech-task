<?php

namespace App\Repositories;

use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;

class ProductImageRepository
{
    /**
     * Create image records
     *
     * @param array $images
     * @param int|null $id
     */
    public function saveBulk(array $images)
    {
        DB::table('product_images')->insert($images);
    }

    /**
     * Find image by id
     *
     * @param int $id
     * @return ProductImage
     */
    public function findById(int $id): ProductImage
    {
        return ProductImage::findOrFail($id);
    }

    /**
     * Delete product image
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return ProductImage::destroy($id);
    }
}
