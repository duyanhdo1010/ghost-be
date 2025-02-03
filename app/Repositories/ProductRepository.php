<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    protected $model;

    /**
     * @param $model
     */
    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function getAllProducts(){
        return $this->model->active()->with('images')->get();
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function getProductBySlug($slug)
    {
        return $this->model->where('slug', $slug)->active()->first();
    }

    public function createProduct($data)
    {
        return $this->model->create($data);
    }

    public function updateProduct($product, $data)
    {
        $product->update($data);
        return $product;
    }

    public function deleteProductBySlug($slug)
    {
        return $this->model->where('slug', $slug)->update(['active_flg' => 0]);
    }
}
