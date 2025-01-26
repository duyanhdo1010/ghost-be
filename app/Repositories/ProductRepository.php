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
        return $this->model::all();
    }

    public function getProductBySlug($slug)
    {
        return $this->model->where('slug', $slug)->first();
    }
}
