<?php

namespace App\Repositories;

use App\Models\ProductHistory;

class ProductHistoryRepository
{
    /**
     * Insert new product history record
     *
     * @param array $data
     * @param int|null $id
     */
    public function save(array $data)
    {
        $productHistory = new ProductHistory;

        $productHistory->product_id = $data['product_id'];
        $productHistory->price = $data['price'];
        $productHistory->quantity = $data['quantity'];

        $productHistory->save();
    }
}
