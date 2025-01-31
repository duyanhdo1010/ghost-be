<?php

namespace App\Services;

use App\Exceptions\ResourceCanNotCreateException;
use App\Exceptions\ResourceNotFoundException;
use App\Repositories\ImageRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
{
    protected $productRepository;
    protected $imageRespository;

    /**
     * @param $productRepository
     * @param $imageRepository
     */
    public function __construct(ProductRepository $productRepository, ImageRepository $imageRepository)
    {
        $this->productRepository = $productRepository;
        $this->imageRespository = $imageRepository;
    }

    public function getAllProducts()
    {
        try {
            return $this->productRepository->getAllProducts();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getProductBySlug($slug)
    {
        try {
            $product = $this->productRepository->getProductBySlug($slug);
            if (!$product) {
                throw new ResourceNotFoundException('Product not found');
            }
            return $product;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function createProduct($data)
    {
        DB::beginTransaction();
        try {
//            create product and create slug for product
            $newProductData = [
                'name'        => $data['name'],
                'description' => $data['description'],
                'price'       => $data['price'],
                'stock'       => $data['stock'],
                'category_id' => $data['category_id'],
                'slug'        => Str::slug($data['name'], '-')
            ];
            $newProduct = $this->productRepository->createProduct($newProductData);

            if (!$newProduct) {
                throw new ResourceCanNotCreateException('Failed to create product');
            }
//            create image and save path to image table
            $imageData = [];
            foreach ($data['images'] as $uploadedImage) {
                $path = $uploadedImage->store('products', 'public');
                $imageData[] = ['path' => $path, 'alt_text' => Str::slug($data['name'], '-')];
            }
            // Tạo các ảnh và lấy danh sách Image model
            $createdImages = $this->imageRespository->createImages($imageData);

            // Lấy danh sách ID của các ảnh vừa tạo
            $imageIds = $createdImages->pluck('id')->toArray();

            // Liên kết sản phẩm với các ảnh qua bảng image_product
            $newProduct->images()->attach($imageIds);

            DB::commit();

            return $newProduct->load('images');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
