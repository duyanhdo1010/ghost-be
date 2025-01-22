<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Linh kiện máy tính',
                'children' => [
                    ['name' => 'CPU'],
                    ['name' => 'Mainboard'],
                    ['name' => 'RAM'],
                    ['name' => 'Ổ cứng'],
                    ['name' => 'VGA (Card màn hình)'],
                    ['name' => 'Nguồn máy tính'],
                    ['name' => 'Vỏ case'],
                    ['name' => 'Tản nhiệt'],
                ],
            ],
            [
                'name' => 'Gaming Gear',
                'children' => [
                    ['name' => 'Chuột'],
                    ['name' => 'Bàn phím'],
                    ['name' => 'Tai nghe'],
                    ['name' => 'Ghế Gaming'],
                    ['name' => 'Lót chuột'],
                ],
            ],
            [
                'name' => 'Màn hình máy tính',
                'children' => [
                    ['name' => 'Màn hình văn phòng'],
                    ['name' => 'Màn hình gaming'],
                    ['name' => 'Màn hình đồ họa'],
                ],
            ],
            ['name' => 'Máy tính nguyên bộ'],
        ];

        foreach ($categories as $categoryData) {
            $parent = Category::updateOrCreate(
                ['slug' => Str::slug($categoryData['name'])], // Tìm theo slug để tránh trùng lặp
                [
                    'name' => $categoryData['name'],
                    'slug' => Str::slug($categoryData['name']),
                    'sort_order' => Category::max('sort_order') + 1, // Tự động tăng sort_order
                    'active_flg' => 1,
                    'parent_id' => null, // Root category
                ]
            );

            if (isset($categoryData['children'])) {
                $sortOrder = 1;
                foreach ($categoryData['children'] as $childCategoryData) {
                    Category::updateOrCreate(
                        ['slug' => Str::slug($childCategoryData['name'])],
                        [
                            'name' => $childCategoryData['name'],
                            'slug' => Str::slug($childCategoryData['name']),
                            'sort_order' => $sortOrder++, // Sort order cho children
                            'active_flg' => 1,
                            'parent_id' => $parent->id,
                        ]
                    );
                }
            }
        }
    }
}
