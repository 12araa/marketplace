<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService; // <-- 1. Panggil "Koki"-nya
use Illuminate\Support\Facades\Auth; // <-- 2. Untuk dapatkan user

class OrderController extends Controller
{
    // Variabel untuk menyimpan Service
    protected $orderService;

    // "Suntikkan" (Inject) OrderService saat controller dipanggil
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;

        // Pastikan hanya user yang login yang bisa checkout
        $this->middleware('auth');
    }

    /**
     * Method ini yang akan menangani proses checkout.
     * Ini dipicu oleh tombol "Lanjut ke Checkout"
     */
    public function store(Request $request)
    {
        try {
            // Panggil Service untuk melakukan semua pekerjaan berat
            $order = $this->orderService->processCheckout(Auth::user());

            // Jika berhasil, redirect ke halaman "Success"
            // Kita akan buat halaman ini selanjutnya
            return redirect()->route('order.success')->with('success', 'Pesanan Anda berhasil dibuat!');

        } catch (\Exception $e) {

            // Jika ada error (misal stok habis atau keranjang kosong)
            // Kembalikan ke halaman keranjang dengan pesan error
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman "Terima Kasih" setelah checkout berhasil.
     */
    public function success()
    {
        // Pastikan user tidak bisa asal buka halaman ini
        // 'success' hanya boleh ada jika baru checkout
        if (!session('success')) {
            return redirect()->route('shop.index');
        }

        return view('order.success'); // Kita akan buat view ini
    }
}
