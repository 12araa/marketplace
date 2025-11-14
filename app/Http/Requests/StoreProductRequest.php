<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Menentukan apakah user berhak membuat request ini.
     * Kita return 'true' karena hak akses SUDAH ditangani
     * oleh ProductPolicy di Controller.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mendapatkan aturan validasi untuk form create produk.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',

            // Cek 'category_id' harus ada dan valid di tabel 'categories'
            'category_id' => 'required|integer|exists:categories,id',

            // 'status' opsional, tapi jika diisi harus salah satu dari ini
            'status' => 'sometimes|string|in:active,draft,out_of_stock',

            // Validasi untuk file gambar (opsional)
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
        ];
    }
}
