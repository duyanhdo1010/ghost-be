<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\SlugRequest;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    /**
     * @param $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = $this->productService->getAllProducts();
        return $this->success('Products fetched successfully', $products);
    }

    public function show(SlugRequest $request)
    {
        $validatedData = $request->validated();
        $product = $this->productService->getProductBySlug($validatedData['slug']);
        return $this->success('Product fetched successfully', $product);
    }
}
