<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * Admin tetap lolos (tidak perlu cek status verifikasi)
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }
        return null;
    }

    /**
     * Boleh lihat daftar produk?
     */
    public function viewAny(User $user): bool
    {
        // YA, jika dia vendor DAN statusnya approved
        return $user->role === 'vendor' && $user->vendorProfile?->verification_status === 'approved';
    }

    /**
     * Boleh lihat detail produk?
     */
    public function view(User $user, Product $product): bool
    {
        // YA, jika dia pemilik DAN statusnya approved
        return $user->id === $product->vendor_id && $user->vendorProfile?->verification_status === 'approved';
    }

    /**
     * Boleh buat produk?
     */
    public function create(User $user): bool
    {
        // YA, jika dia vendor DAN statusnya approved
        return $user->role === 'vendor' && $user->vendorProfile?->verification_status === 'approved';
    }

    /**
     * Boleh update produk?
     */
    public function update(User $user, Product $product): bool
    {
        // YA, jika dia pemilik DAN statusnya approved
        return $user->id === $product->vendor_id && $user->vendorProfile?->verification_status === 'approved';
    }

    /**
     * Boleh hapus produk?
     */
    public function delete(User $user, Product $product): bool
    {
        // YA, jika dia pemilik DAN statusnya approved
        return $user->id === $product->vendor_id && $user->vendorProfile?->verification_status === 'approved';
    }

    // ... (method restore dan forceDelete)
}
