<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $favorites = $user->favorites()->latest()->paginate(10);

        return view('wishlist.index', compact('favorites'));
    }

    /**
     * Toggle: Kalau belum ada -> tambah. Kalau sudah ada -> hapus.
     */
    public function toggle(Product $product)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $changes = $user->favorites()->toggle($product->id);

        if (!empty($changes['attached'])) {
            return back()->with('success', 'Produk ditambahkan ke Wishlist ❤️');
        } else {
            return back()->with('success', 'Produk dihapus dari Wishlist 💔');
        }
    }
}
