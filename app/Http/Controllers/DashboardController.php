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
        $data = [];

        if ($user->role === 'admin') {
            $data['pendingOrders'] = Order::pending()
                                    ->with('user')
                                    ->latest()
                                    ->get();
            // Tambahkan data lain untuk admin jika perlu
            return view('dashboard', $data);
        }

        if ($user->role === 'vendor') {
            // Cek status verifikasi dari profil vendor
            $status = $user->vendorProfile?->verification_status;

            if ($status === 'approved') {
                // VENDOR DISETUJUI -> Lihat pesanan masuk
                $data['pendingOrders'] = Order::pending()
                    ->whereHas('items.product', function ($query) use ($user) {
                        $query->where('vendor_id', $user->id);
                    })
                    ->with('user', 'items.product')
                    ->latest()
                    ->get();
            }

            // Jika 'pending' atau 'rejected', $data['pendingOrders'] akan kosong,
            // dan view dashboard akan menangani tampilannya (menampilkan alert).

            return view('dashboard', $data);
        }

        if ($user->role === 'customer') {
            $data['orders'] = Order::where('user_id', $user->id)
                                    ->with('items.product')
                                    ->latest()
                                    ->take(5)
                                    ->get();

            return view('dashboard', $data);
        }

        // Default fallback (jika ada role aneh)
        return view('dashboard');
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
