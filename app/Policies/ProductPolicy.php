<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * PENTING: Fungsi ini memberikan hak akses penuh (Super Admin)
     * ke user dengan role 'admin'. Admin akan otomatis lolos
     * semua cek policy di bawah ini.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }
        return null; // Biarkan policy method di bawah yang memutuskan
    }

    /**
     * Menentukan apakah user boleh MELIHAT DAFTAR produk.
     * (Dipakai oleh controller method 'index')
     */
    public function viewAny(User $user): bool
    {
        // Customer tidak boleh lihat, hanya Vendor dan Admin
        return $user->role === 'vendor';
        // (Admin sudah otomatis lolos karena ada 'before')
    }

    /**
     * Menentukan apakah user boleh MELIHAT DETAIL produk.
     * (Dipakai oleh 'show')
     */
    public function view(User $user, Product $product): bool
    {
        // Boleh lihat jika dia adalah vendor pemilik produk ini
        return $user->id === $product->vendor_id;
    }

    /**
     * Menentukan apakah user boleh MEMBUAT produk.
     * (Dipakai oleh 'create' dan 'store')
     */
    public function create(User $user): bool
    {
        // Hanya vendor yang boleh membuat produk baru
        return $user->role === 'vendor';
    }

    /**
     * Menentukan apakah user boleh MENG-UPDATE produk.
     * (Dipakai oleh 'edit' dan 'update')
     */
    public function update(User $user, Product $product): bool
    {
        // Boleh update jika dia adalah vendor pemilik produk ini
        return $user->id === $product->vendor_id;
    }

    /**
     * Menentukan apakah user boleh MENGHAPUS produk.
     * (Dipakai oleh 'destroy')
     */
    public function delete(User $user, Product $product): bool
    {
        // Boleh hapus jika dia adalah vendor pemilik produk ini
        return $user->id === $product->vendor_id;
    }

    // ... (method restore dan forceDelete bisa Anda isi sama dengan 'delete')
}
