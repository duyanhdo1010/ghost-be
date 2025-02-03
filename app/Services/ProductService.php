<?php

namespace App\Services;

use     App\Exceptions\ResourceCanNotCreateException;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Image;
use App\Repositories\ImageRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
//            create product and create slug for product
        $newProductData = [
            'name'        => $data['name'],
            'description' => $data['description'],
            'price'       => $data['price'],
            'stock'       => $data['stock'],
            'category_id' => $data['category_id'],
            'slug'        => Str::slug($data['name'], '-')
        ];

        try {
            $newProduct = $this->productRepository->createProduct($newProductData);

            if (!$newProduct) {
                throw new ResourceCanNotCreateException('Failed to create product');
            }

            $this->handleNewImages($newProduct, $data['images']);

            DB::commit();

            return $newProduct->load('images');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateProduct($data)
    {
        DB::beginTransaction();
        try {
            $product = $this->productRepository->findById($data['id']);

            $updateData = [
                'name'        => $data['name'] ?? $product->name,
                'description' => $data['description'] ?? $product->description,
                'price'       => $data['price'] ?? $product->price,
                'stock'       => $data['stock'] ?? $product->stock,
                'category_id' => $data['category_id'] ?? $product->category_id,
                'slug'        => isset($data['name']) ? Str::slug($data['name']) : $product->slug,
            ];

            $updatedProduct = $this->productRepository->updateProduct($product, $updateData);
            if (!$updatedProduct) {
                throw new ResourceCanNotCreateException('Failed to update product');
            }

            if (!empty($data['delete_image_ids']) && is_array($data['delete_image_ids'])) {
                $this->handleDeletedImages($updatedProduct, $data['delete_image_ids']);
            }

            if (!empty($data['new_images']) && is_array($data['new_images'])) {
                $this->handleNewImages($updatedProduct, $data['new_images']);
            }

            DB::commit();

            return $updatedProduct->load('images');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteProduct($slug)
    {
        DB::beginTransaction();
        try {
            $product = $this->getProductBySlug($slug);
            $this->productRepository->deleteProductBySlug($product);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function handleNewImages($product, $images)
    {
        $newImageData = [];
        foreach ($images as $uploadedImage) {
            $path = $uploadedImage->store('products', 'public');
            $newImageData[] = [
                'path'     => $path,
                'alt_text' => Str::slug($product->name)
            ];
        }
        $createdImages = $this->imageRespository->createImages($newImageData);
        $product->images()->attach($createdImages->pluck('id')->toArray());
    }

    protected function handleDeletedImages($product, $imageIds)
    {
        $validIds = $product->images()->whereIn('images.id', $imageIds)->pluck('id')->toArray();

        if (!empty($validIds)) {
            $product->images()->detach($validIds);

            $imagesToDelete = Image::whereIn('id', $validIds)->get();
            foreach ($imagesToDelete as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
        }
    }
}
