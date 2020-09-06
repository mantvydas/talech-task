<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

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
     * @param bool $includeHistory
     * @param int $historyMaxDays
     * @return Product
     */
    public function findById(int $id, bool $includeHistory = false, int $historyMaxDays = 90): Product
    {
        if ($includeHistory) {
            $with = ['history' => function ($query) use ($historyMaxDays) {
                $query->where('created_at', '>=', Carbon::now()->subDays($historyMaxDays));
            }];
        }
        $with[] = 'images';

        return Product::with($with)->findOrFail($id);
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

    /**
     * Get soft deleted products which are older than given days
     *
     * @param int $olderThan
     * @return object
     */
    public function getOldDeleted(int $olderThan): object
    {
        return Product::onlyTrashed()
            ->where('deleted_at', '<', Carbon::now()->subDays($olderThan))
            ->with('images')
            ->get();
    }

    /**
     * Delete soft deleted products which are older than given days
     *
     * @param int $olderThan
     */
    public function forceDelete(int $olderThan)
    {
        Product::onlyTrashed()
            ->where('deleted_at', '<', Carbon::now()->subDays($olderThan))
            ->forceDelete();
    }
}
