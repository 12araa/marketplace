<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // <-- WAJIB IMPORT INI

class UpdateProductRequest extends FormRequest
{
    /**
     * Otorisasi sudah ditangani oleh ProductPolicy.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mendapatkan aturan validasi untuk form update produk.
     */
    public function rules(): array
    {
        // Ambil ID produk yang sedang diedit dari route
        $productId = $this->route('product')->id;

        return [
            // 'sometimes' berarti: validasi HANYA jika field ini dikirim
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                // Cek unik, TAPI abaikan ID produk ini sendiri
                Rule::unique('products', 'name')->ignore($productId)
            ],
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'category_id' => 'sometimes|required|integer|exists:categories,id',
            'status' => 'sometimes|string|in:active,draft,out_of_stock',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
