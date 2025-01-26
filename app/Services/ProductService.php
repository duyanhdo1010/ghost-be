<?php

namespace App\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Repositories\ProductRepository;

class ProductService
{
    protected $productRepository;

    /**
     * @param $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts()
    {
        try {
            return $this->productRepository->getAllProducts();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getProductBySlug($slug){
        try {
            $product = $this->productRepository->getProductBySlug($slug);
            if (!$product) {
                throw new ResourceNotFoundException('Product not found');
            }
            return $product;
        } catch (\Exception $e){
            throw $e;
        }
    }
}
