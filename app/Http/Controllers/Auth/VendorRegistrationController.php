<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VendorProfile; // <-- IMPORT PROFIL VENDOR
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // <-- IMPORT DB
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class VendorRegistrationController extends Controller
{
    /**
     * Menampilkan view form registrasi vendor.
     */
    public function create(): View
    {
        // Kita akan buat view ini di langkah berikutnya
        return view('auth.register-vendor');
    }

    /**
     * Menangani request registrasi vendor.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'shop_name' => ['required', 'string', 'max:255', 'unique:vendor_profiles,shop_name'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Pakai DB Transaction untuk memastikan User & Profil dibuat bersamaan
        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'vendor',
            ]);

            VendorProfile::create([
                'user_id' => $user->id,
                'shop_name' => $request->shop_name,
                'verification_status' => 'pending',
            ]);

            DB::commit(); // Selesaikan transaksi jika semua berhasil

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua jika ada error
            return back()->with('error', 'Registrasi gagal, silakan coba lagi.');
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
