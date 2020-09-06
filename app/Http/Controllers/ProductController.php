<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;
use App\Helpers\Color;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * @var ProductService
     */
    protected $productService;

    /**
     * ProductController constructor
     *
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $colors = Color::getColors();
        $products = $this->productService->getProducts();
        return view('products/products_list', compact(['colors', 'products']));
    }

    /**
     * Display a listing of soft deleted products.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function trash()
    {
        $colors = Color::getColors();
        $products = $this->productService->getDeletedProducts();
        return view('products/products_list', compact(['colors', 'products']), ['showTrash' => true]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $colors = Color::getColors();
        return view('products/create_product', compact('colors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['files'] = $request->file('images');

        $this->productService->saveProductData($data);

        return redirect()->route('products.index')
            ->with('success', __('Product created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(int $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $id)
    {
        $colors = Color::getColors();
        $product = $this->productService->getProductById($id);

        return view('products/edit_product', compact(['colors', 'product']));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, int $id)
    {
        $data = $request->all();
        $data['files'] = $request->file('images');
        $this->productService->saveProductData($data, $id);

        return redirect()->route('products.index')
            ->with('success', __('Product updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $this->productService->deleteProductById($id);

        return redirect()->route('products.index')
            ->with('success', __('Product deleted successfully. You can restore it within the following 7 days.'));
    }

    /**
     * Restore the product
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(int $id)
    {
        $this->productService->restoreProduct($id);

        return redirect()->route('products.trash')
            ->with('success', __('Product restored successfully.'));
    }
}
