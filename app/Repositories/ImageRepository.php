<?php

namespace App\Repositories;

use App\Models\Image;

class ImageRepository
{
    protected $model;

    /**
     * @param $model
     */
    public function __construct(Image $model)
    {
        $this->model = $model;
    }

    public function createImages(array $imageData)
    {
        $images = [];
        foreach ($imageData as $data) {
            $image = $this->model->create($data);
            $images[] = $image;
        }
        return collect($images);
    }
}
