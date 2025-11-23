<?php

namespace App\Observers;

use App\Models\User;
// IMPORT MODEL PROFIL KITA
use App\Models\CustomerProfile;
use App\Models\VendorProfile;

class UserObserver
{
    /**
     * Handle the User "created" event.
     * Ini akan berjalan OTOMATIS setelah user baru register.
     */

    public function created(User $user): void
    {
        // Cek jika dia customer DAN belum punya profil
        if ($user->role === 'customer' && !$user->customerProfile) {
            CustomerProfile::create([
                'user_id' => $user->id,
            ]);
        }

        // Cek jika dia vendor DAN belum punya profil
        elseif ($user->role === 'vendor' && !$user->vendorProfile) {
            // Ini tidak akan berjalan jika daftar via form vendor,
            // tapi akan berjalan jika admin membuat vendor secara manual
            VendorProfile::create([
                'user_id' => $user->id,
                'shop_name' => $user->name . "'s Shop",
                'verification_status' => 'pending',
            ]);
        }
    }
    // ... (method updated, deleted, dll biarkan saja)
}
