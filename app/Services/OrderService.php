<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    /**
     * Logika inti untuk memproses checkout.
     * Mengambil semua data dari session cart dan menyimpannya ke database.
     */
    public function processCheckout(User $user, array $data)
    {
        // 1. Ambil data keranjang dari session
        $cartItems = session()->get('cart', []);

        if (empty($cartItems)) {
            throw new \Exception('Keranjang Anda kosong.');
        }

        // Mulai "Transaksi Database"
        // Ini memastikan jika ada 1 langkah gagal (misal stok habis),
        // semua proses akan dibatalkan (rollback).
        return DB::transaction(function () use ($user, $cartItems, $data) {

            // 2. Hitung total harga
            $totalPrice = 0;
            foreach ($cartItems as $id => $item) {
                $totalPrice += $item['price'] * $item['quantity'];
            }

            // 3. Buat "Induk" Order-nya
            $order = Order::create([
                'user_id'     => $user->id,
                'total_price' => $totalPrice,
                'status'      => 'Pending',
                'shipping_name'    => $data['shipping_name'],
                'shipping_phone'   => $data['shipping_phone'],
                'shipping_address' => $data['shipping_address'],
            ]);

            // 4. Looping & simpan "Anak" OrderItem (detail barang)
            foreach ($cartItems as $id => $item) {

                // 5. Kurangi Stok Produk (Logika Krusial!)
                $product = Product::find($id); // Temukan produk
                if (!$product) {
                    throw new \Exception('Produk tidak ditemukan.');
                }

                // Cek stok dulu
                if ($product->stock < $item['quantity']) {
                    // Jika stok tidak cukup, batalkan semua (throw Exception)
                    throw new \Exception('Stok produk ' . $product->name . ' tidak mencukupi.');
                }

                // Stok aman, kurangi stoknya
                $product->decrement('stock', $item['quantity']);

                // 6. Buat OrderItem
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $id,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'], // Harga saat dibeli
                ]);
            }

            // 7. Kosongkan session cart setelah berhasil
            session()->forget('cart');

            // 8. Kembalikan data order yang baru dibuat
            return $order;
        });
    }
}
