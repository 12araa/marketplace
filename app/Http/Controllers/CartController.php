<?php

namespace App\Http\Controllers;

use App\Models\Product; // <-- Penting untuk method 'add'
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Menampilkan halaman isi keranjang (cart).
     * Ini adalah method GET untuk route '/cart'.
     */
    public function index()
    {
        // 1. Ambil data keranjang dari session.
        // Jika tidak ada, default-nya adalah array kosong [].
        $cartItems = session()->get('cart', []);

        // 2. Hitung total harga
        $total = 0;
        foreach ($cartItems as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        // 3. Tampilkan view 'cart.index' dan kirim datanya
        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Menambahkan produk ke keranjang.
     * Ini adalah method POST untuk route '/cart/add/{product}'.
     */
    public function add(Request $request, Product $product)
    {
        // Validasi agar tidak bisa beli produk 'draft' atau 'out_of_stock'
        if ($product->status !== 'active') {
            return redirect()->back()->with('error', 'Produk ini tidak tersedia.');
        }

        // 1. Ambil keranjang yang ada di session
        $cart = session()->get('cart', []);

        // 2. Ambil kuantitas dari form (default 1 jika tidak diisi)
        $quantity = $request->input('quantity', 1);

        // 3. Cek apakah produk SUDAH ADA di keranjang?
        if (isset($cart[$product->id])) {
            // JIKA SUDAH: Cukup tambahkan kuantitasnya
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            // JIKA BELUM ADA: Tambahkan sebagai item baru
            $cart[$product->id] = [
                "name" => $product->name,
                "quantity" => $quantity,
                "price" => $product->price,
                "image" => $product->image_path // Kita simpan path gambarnya
            ];
        }

        // 4. Simpan kembali array $cart yang baru ke dalam session
        session()->put('cart', $cart);

        // 5. Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Mengubah kuantitas produk di keranjang.
     * Ini adalah method PATCH untuk route '/cart/update/{productId}'.
     */
    public function update(Request $request, $productId)
    {
        // 1. Ambil keranjang dari session
        $cart = session()->get('cart');

        // 2. Cek apakah produk ada di keranjang DAN kuantitas valid
        if (isset($cart[$productId]) && $request->quantity > 0) {

            // 3. Update kuantitasnya
            $cart[$productId]['quantity'] = $request->quantity;

            // 4. Simpan kembali ke session
            session()->put('cart', $cart);

            return redirect()->route('cart.index')->with('success', 'Kuantitas berhasil diperbarui.');
        }

        return redirect()->route('cart.index')->with('error', 'Gagal memperbarui keranjang.');
    }

    /**
     * Menghapus produk dari keranjang.
     * Ini adalah method DELETE untuk route '/cart/remove/{productId}'.
     */
    public function remove($productId)
    {
        // 1. Ambil keranjang dari session
        $cart = session()->get('cart');

        // 2. Cek apakah produk ada di keranjang
        if (isset($cart[$productId])) {

            // 3. Hapus item dari array menggunakan 'unset'
            unset($cart[$productId]);

            // 4. Simpan kembali array $cart yang sudah terhapus ke session
            session()->put('cart', $cart);

            return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
        }

        return redirect()->route('cart.index')->with('error', 'Produk tidak ditemukan di keranjang.');
    }
}
