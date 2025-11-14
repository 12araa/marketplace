<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
        // Ambil ID kategori yang sedang diedit dari route
        // 'category' adalah nama parameter di route: categories/{category}
        $categoryId = $this->route('category')->id;

        return [
            // 2. INI BEDANYA:
            'name' => [
                'required',
                'string',
                'max:255',
                // Cek unik, TAPI abaikan baris yang punya ID ini
                Rule::unique('categories', 'name')->ignore($categoryId)
            ],

            // Aturan ini tetap sama
            'parent_id' => 'nullable|integer|exists:categories,id',
        ];
    }
}
