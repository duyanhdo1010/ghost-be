<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'images' => ['required', 'array'],
//            check for each element in images array
            'images.*' => [
                'file',
                'image',
                'mimes:jpg,png,webp'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'name.string' => 'Tên sản phẩm phải là chuỗi ký tự.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'description.required' => 'Mô tả sản phẩm là bắt buộc.',
            'description.string' => 'Mô tả sản phẩm phải là chuỗi ký tự.',
            'description.max' => 'Mô tả sản phẩm không được vượt quá 255 ký tự.',
            'price.required' => 'Giá sản phẩm là bắt buộc.',
            'price.numeric' => 'Giá sản phẩm phải là số.',
            'stock.required' => 'Số lượng sản phẩm là bắt buộc.',
            'stock.numeric' => 'Số lượng sản phẩm phải là số.',
            'stock.min' => 'Số lượng sản phẩm không được nhỏ hơn 0.',
            'category_id.required' => 'Danh mục sản phẩm là bắt buộc.',
            'category_id.integer' => 'Danh mục sản phẩm phải là số nguyên.',
            'category_id.exists' => 'Danh mục sản phẩm không tồn tại.',

            // Messages for images array
            'images.required' => 'Hình ảnh sản phẩm là bắt buộc.',
            'images.array' => 'Hình ảnh phải được gửi dưới dạng mảng.',

            // Messages for each image element
            'images.*.file' => 'Mỗi phần tử phải là một tệp tin.',
            'images.*.image' => 'Mỗi tệp phải là định dạng hình ảnh.',
            'images.*.mimes' => 'Chỉ chấp nhận định dạng jpg, png hoặc webp.'
        ];
    }
}
