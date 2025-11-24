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

    public function show(Product $product)
    {
        $product->load(['reviews.user', 'vendor']);
        $canReview = false;

        if (auth()->check()) {
            $user = auth()->user();

            $alreadyReviewed = $product->reviews()->where('user_id', $user->id)->exists();
            $hasPurchased = \App\Models\Order::where('user_id', $user->id)
                ->where('status', 'Completed')
                ->whereHas('items', function ($q) use ($product) {
                    $q->where('product_id', $product->id);
                })
                ->exists();

            $canReview = $hasPurchased && !$alreadyReviewed;
        }

        return view('shop.show', compact('product', 'canReview'));
    }
}
