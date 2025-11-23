<?php

namespace App\Http\Controllers;

use App\Models\Order;
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

    public function index()
    {
        $user = auth()->user();
        $orders = [];

        // 1. LOGIKA ADMIN (Lihat Semua)
        if ($user->role === 'admin') {
            $orders = Order::with('user')
                           ->latest()
                           ->paginate(10);
        }

        // 2. LOGIKA VENDOR (Lihat yang berisi produk dia saja)
        elseif ($user->role === 'vendor') {
            $orders = Order::whereHas('items.product', function ($query) use ($user) {
                                $query->where('vendor_id', $user->id);
                            })
                           ->with('user', 'items.product')
                           ->latest()
                           ->paginate(10);
        }

        // 3. LOGIKA CUSTOMER (Lihat punya sendiri)
        elseif ($user->role === 'customer') {
            $orders = Order::where('user_id', $user->id)
                           ->with('items.product')
                           ->latest()
                           ->paginate(10);
        }

        return view('order.index', compact('orders'));
    }

    public function create()
    {
        $cart = session()->get('cart');
        if (!$cart) {
            return redirect()->route('shop.index');
        }

        // Ambil data user untuk pre-fill form (jika sudah punya profil)
        $user = auth()->user();

        return view('checkout.index', compact('cart', 'user'));
    }

    // Update method STORE yang lama
    public function store(Request $request)
    {
        // 1. Validasi Input Alamat
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
        ]);

        try {
            // Kirim data request ke Service
            $order = $this->orderService->processCheckout(auth()->user(), $request->all());

            return redirect()->route('order.success')->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
        }
        elseif ($user->role === 'customer' && $order->user_id !== $user->id) {
            abort(403); // Forbidden
        }
        elseif ($user->role === 'vendor') {
            // Cek apakah ada setidaknya satu item di order ini yang milik vendor ini
            $hasProduct = $order->items()->whereHas('product', function ($query) use ($user) {
                $query->where('vendor_id', $user->id);
            })->exists();

            if (!$hasProduct) {
                abort(403); // Forbidden
            }
        }

        $order->load('items.product', 'user');

        return view('order.show', compact('order'));
    }

    public function success()
    {
        // Pastikan user tidak bisa asal buka halaman ini
        // 'success' hanya boleh ada jika baru checkout
        // nanti diisi pake detail shipping
        if (!session('success')) {
            return redirect()->route('shop.index');
        }

        return view('order.success'); // Kita akan buat view ini
    }

    public function markAsCompleted(Order $order)
    {
        if (auth()->id() !== $order->user_id) {
            abort(403);
        }

        if ($order->status !== 'Shipped') {
            return back()->with('error', 'Pesanan belum dikirim.');
        }

        $order->update(['status' => 'Completed']);

        return back()->with('success', 'Terima kasih! Transaksi selesai.');
    }
}
