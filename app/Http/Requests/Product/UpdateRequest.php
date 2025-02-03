<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'max:255'],
            'price' => ['sometimes', 'numeric'],
            'stock' => ['sometimes', 'numeric', 'min:0'],
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
            'new_images' => ['array', 'nullable'],
            'new_images.*' => [
                'file',
                'image',
                'mimes:jpg,png,webp'
            ],
            'delete_image_ids' => ['array', 'nullable'],
            'delete_image_ids.*' => ['numeric'],
            'id' => ['numeric', 'exists:products,id']
        ];
    }

    public function messages(): array
    {
        return [
            'name.sometimes' => 'Tên sản phẩm là bắt buộc.',
            'name.string' => 'Tên sản phẩm phải là chuỗi ký tự.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'description.sometimes' => 'Mô tả sản phẩm là bắt buộc.',
            'description.string' => 'Mô tả sản phẩm phải là chuỗi ký tự.',
            'description.max' => 'Mô tả sản phẩm không được vượt quá 255 ký tự.',
            'price.sometimes' => 'Giá sản phẩm là bắt buộc.',
            'price.numeric' => 'Giá sản phẩm phải là số.',
            'stock.sometimes' => 'Số lượng sản phẩm là bắt buộc.',
            'stock.numeric' => 'Số lượng sản phẩm phải là số.',
            'stock.min' => 'Số lượng sản phẩm không được nhỏ hơn 0.',
            'category_id.sometimes' => 'Danh mục sản phẩm là bắt buộc.',
            'category_id.integer' => 'Danh mục sản phẩm phải là số nguyên.',
            'category_id.exists' => 'Danh mục sản phẩm không tồn tại.',

            // Messages for images array
            'new_images.array' => 'Hình ảnh phải được gửi dưới dạng mảng.',

            // Messages for each image element
            'new_images.*.file' => 'Mỗi phần tử phải là một tệp tin.',
            'new_images.*.image' => 'Mỗi tệp phải là định dạng hình ảnh.',
            'new_images.*.mimes' => 'Chỉ chấp nhận định dạng jpg, png hoặc webp.',

            // Message for delete image url
            'delete_image_ids.array' => 'Ids of deleted images must be provided in array',
            'delete_image_ids.*.numeric' => 'Id must be integer number',

            // Message for id
            'id.sometimes' => 'ID sản phẩm là bắt buộc.',
            'id.numeric' => 'ID sản phẩm phải là số.',
            'id.exists' => 'ID sản phẩm không tồn tại.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['id' => $this->route('id')]);
    }
}
