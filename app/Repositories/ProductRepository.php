<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository
{
    /**
     * Get all products
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator
    {
        return Product::with('images')->paginate(15);
    }

    /**
     * Get soft deleted products
     *
     * @return LengthAwarePaginator
     */
    public function getAllDeleted(): LengthAwarePaginator
    {
        return Product::onlyTrashed()->with('images')->paginate(15);
    }

    /**
     * Create or update product
     *
     * @param array $data
     * @param int|null $id
     * @return Product
     */
    public function save(array $data, ?int $id = null): Product
    {
        $product = isset($id) ? $this->findById($id) : new Product;

        $product->name = $data['name'];
        $product->ean = $data['ean'];
        $product->weight = $data['weight'];
        $product->color = $data['color'];
        $product->active = array_key_exists('active', $data) ? $data['active'] : 0;
        $product->price = $data['price'];
        $product->quantity = $data['quantity'];

        $product->save();

        return $product->fresh();
    }

    /**
     * Find product by id
     *
     * @param int $id
     * @return Product
     */
    public function findById(int $id): Product
    {
        return Product::with('images')->findOrFail($id);
    }

    /**
     * Soft delete product by id
     *
     * @param int $id
     * @return bool
     */
    public function softDeleteById(int $id): bool
    {
        return Product::destroy($id);
    }

    /**
     * Restore soft deleted product
     *
     * @param int $id
     * @return bool
     */
    public function restore(int $id): bool
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        return $product->restore();
    }
}