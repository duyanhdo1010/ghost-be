<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\CreateRequest;
use App\Http\Requests\Product\DeleteRequest;
use App\Http\Requests\Product\SlugRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Product;
use App\Services\ProductService;
use Symfony\Component\HttpFoundation\Response;

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

    public function store(CreateRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->user()->cannot('create', Product::class)) {
            return $this->errorResponse('You do not have permission to create product');
        }

        $newProduct = $this->productService->createProduct($validatedData);
        return $this->success('Product created successfully', $newProduct);
    }

    public function update(UpdateRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->user()->cannot('update', Product::class)) {
            return $this->errorResponse('You do not have permission to update product');
        }

        $editedProduct = $this->productService->updateProduct($validatedData);
        return $this->success('Product updated successfully', $editedProduct);
    }

    public function destroy(DeleteRequest $request)
    {
        $validatedSlug = $request->validated();

        if ($request->user()->cannot('delete', Product::class)) {
            return $this->errorResponse('You do not have permission to delete product');
        }

        $this->productService->deleteProduct($validatedSlug['slug']);
        return $this->success('Product deleted successfully', null, Response::HTTP_NO_CONTENT);
    }
}
