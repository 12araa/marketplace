<?php
namespace App\Http\Controllers;
use App\Models\Product; // <-- Jangan lupa import
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        // Ambil semua produk yang 'active'
        $products = Product::where('status', 'active')
                            ->with('vendor') // Eager load
                            ->paginate(15);

        // Kirim data ke view baru
        return view('shop.index', compact('products'));
    }
}
