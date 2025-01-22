<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category; // Import model Category
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $productsData = [
            [
                'name' => 'PC MAXIMUM GAMING RTX 3070 - 12400F',
                'description' => 'PC MAXIMUM GAMING RTX 3070 -12400F (All NEW - Bảo hành 36 tháng) - 9slots - 5 Hà Nội - 4 HCM',
                'price' => 17980000,
                'stock' => 10,
                'category_id' => Category::where('name', 'Máy tính nguyên bộ')->first()->id,
            ],
            [
                'name' => 'Chuột Logitech G102',
                'description' => 'Chuột chơi game Logitech G102 Lightsync RGB',
                'price' => 500000,
                'stock' => 50,
                'category_id' => Category::where('name', 'Chuột')->first()->id, // Tìm ID theo tên Category
            ],
            [
                'name' => 'Bàn phím cơ AKKO 3087',
                'description' => 'Bàn phím cơ AKKO 3087 DS Ocean Star',
                'price' => 1500000,
                'stock' => 20,
                'category_id' => Category::where('name', 'Bàn phím')->first()->id, // Tìm ID theo tên Category
            ],
        ];

        foreach ($productsData as $productData) {
            $productData['slug'] = Str::slug($productData['name']);

            if ($productData['category_id']) {
                Product::updateOrCreate(['slug' => $productData['slug']], $productData);
            } else {
                $this->command->info("Không tìm thấy category cho sản phẩm: " . $productData['name']);
            }
        }
    }
}
