<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductHistoryRepository;
use App\Repositories\ProductImageRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var ProductImageRepository
     */
    protected $productImageRepository;

    /**
     * @var ProductHistoryRepository
     */
    protected $productHistoryRepository;

    /**
     * ProductService constructor
     *
     * @param ProductRepository $productRepository
     * @param ProductImageRepository $productImageRepository
     * @param ProductHistoryRepository $productHistoryRepository
     */
    public function __construct(ProductRepository $productRepository, ProductImageRepository $productImageRepository,
                                ProductHistoryRepository $productHistoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->productImageRepository = $productImageRepository;
        $this->productHistoryRepository = $productHistoryRepository;
    }

    /**
     * Get all products
     *
     * @return LengthAwarePaginator
     */
    public function getProducts(): LengthAwarePaginator
    {
        return $this->productRepository->getAll();
    }

    /**
     * Get soft deleted products
     *
     * @return LengthAwarePaginator
     */
    public function getDeletedProducts(): LengthAwarePaginator
    {
        return $this->productRepository->getAllDeleted();
    }

    /**
     * Validate product data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @param int|null $id
     * @return Product
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveProductData(array $data, ?int $id = null): Product
    {
        Validator::make($data, [
            'name' => 'required|string|max:255',
            'ean' => 'required|numeric|unique:products,ean,'.$id.',id|digits:13',
            'type' => [
                'required',
                Rule::in(['mens', 'ladies']),
            ],
            'weight' => 'required|numeric',
            'color' => 'required|string|max:255',
            'active' => 'boolean',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric|digits_between:1,10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192'
        ])->validate();

        $hasChanges = $this->hasPriceOrQuantityChanged($id, $data);
        $storedProduct = $this->productRepository->save($data, $id);

        if ($hasChanges || !isset($id)) {
            $data['product_id'] = $storedProduct->id;
            $this->productHistoryRepository->save($data);
        }

        if (isset($data['files'])) {
            $images = $this->uploadImages($data['files'], $storedProduct->id);
            $this->productImageRepository->saveBulk($images);
        }

        return $storedProduct;
    }

    /**
     * Get product by id
     *
     * @param int $id
     * @param bool $includeHistory
     * @return Product
     */
    public function getProductById(int $id, bool $includeHistory = false): Product
    {
        return $this->productRepository->findById($id, $includeHistory);
    }

    /**
     * Soft delete product by id
     *
     * @param int $id
     * @return bool
     */
    public function deleteProductById(int $id): bool
    {
        return $this->productRepository->softDeleteById($id);
    }

    /**
     * Restore soft deleted product by id
     *
     * @param int $id
     * @return bool
     */
    public function restoreProduct(int $id): bool
    {
        return $this->productRepository->restore($id);
    }

    /**
     * Upload product images and return list of images data
     *
     * @param array $images
     * @param int $productId
     * @return array
     */
    private function uploadImages(array $images, int $productId): array
    {
        $uploadedImages = [];
        foreach($images as $image)
        {
            $name = time() . '_' . $image->getClientOriginalName();
            Storage::putFileAs('images/product_images', $image, $name);
            $uploadedImages[] = [
                'product_id' => $productId,
                'name' => $name
            ];
        }

        return $uploadedImages;
    }

    /**
     * Check for price or quantity changes after update
     * @param int|null $id
     * @param array $newValues
     * @return bool
     */
    private function hasPriceOrQuantityChanged(?int $id, array $newValues): bool
    {
        if (isset($id)) {
            $currentProduct = $this->productRepository->findById($id);

            if ($currentProduct->price != $newValues['price'] ||
                $currentProduct->quantity != $newValues['quantity']) {
                return true;
            }
        }

        return false;
    }
}
