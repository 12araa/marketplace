<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan data dashboard berdasarkan role user.
     */
    public function index()
    {
        $user = Auth::user();
        $data = []; // Siapkan array untuk data

        if ($user->role == 'customer') {
            // Ambil 5 pesanan terakhir
            $data['orders'] = Order::where('user_id', $user->id)
                                    ->with('items.product') // Eager load
                                    ->latest() // Urutkan dari yg terbaru
                                    ->take(5)  // Ambil 5 saja
                                    ->get();
            // Nanti kita juga bisa tambahkan data Wishlist di sini
            // $data['wishlist'] = ...
        }

        if ($user->role == 'vendor') {
            $data['pendingOrders'] = Order::where('status', 'Pending')
                ->whereHas('items.product', function ($query) use ($user) {
                    $query->where('vendor_id', $user->id);
                })
                ->with('user', 'items.product') // Ambil data customer & produknya
                ->latest()
                ->get();

            // Nanti kita bisa tambahkan statistik di sini
            // $data['totalSales'] = ...
        }

        if ($user->role == 'admin') {
            // Admin melihat SEMUA pesanan 'Pending'
            $data['pendingOrders'] = Order::where('status', 'Pending')
                                    ->with('user') // Ambil data customer
                                    ->latest()
                                    ->get();

            // Nanti kita bisa tambahkan statistik total di sini
            // $data['totalUsers'] = User::count();
        }

        // Kirim semua data yang terkumpul ke view 'dashboard'
        return view('dashboard', $data);
    }

    /**
     * Method baru untuk Admin/Vendor meng-update status pesanan
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|string|in:Pending,Paid,Shipped,Completed,Cancelled',
        ]);

        // Cek Keamanan Sederhana (bisa dipercanggih dengan Policy nanti)
        $user = Auth::user();
        if ($user->role == 'admin') {
            // Admin boleh update
        } else if ($user->role == 'vendor') {
            // Cek apakah vendor ini berhak atas order ini
            $isAllowed = $order->items()
                              ->whereHas('product', fn($q) => $q->where('vendor_id', $user->id))
                              ->exists();
            if (!$isAllowed) {
                return redirect()->route('dashboard')->with('error', 'Anda tidak berhak mengubah status pesanan ini.');
            }
        } else {
            // Customer tidak boleh
            return redirect()->route('dashboard')->with('error', 'Aksi tidak diizinkan.');
        }

        // Update status
        $order->update(['status' => $request->status]);

        return redirect()->route('dashboard')->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
