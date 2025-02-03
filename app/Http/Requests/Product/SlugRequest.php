<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class SlugRequest extends FormRequest
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
            'slug' => ['required', 'string', 'regex:/^[a-z0-9-]+$/']
        ];
    }

    public function messages()
    {
        return [
            'slug.required' => 'The slug is required.',
            'slug.string' => 'The slug must be a string.',
            'slug.regex' => 'The slug must contain only lowercase letters, numbers, and hyphens.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['slug' => $this->route('slug')]);
    }
}
