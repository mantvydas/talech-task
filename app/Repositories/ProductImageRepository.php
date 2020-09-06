<?php

namespace App\Repositories;

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
}
