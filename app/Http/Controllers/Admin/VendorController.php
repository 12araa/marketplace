<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VendorProfile;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * list pending vendor
     */
    public function index()
    {
        $pendingVendors = VendorProfile::where('verification_status', 'pending')
                                       ->with('user')
                                       ->latest()
                                       ->get();

        return view('admin.vendors.index', compact('pendingVendors'));
    }

    /**
     * Approve vendor.
     */
    public function approve(VendorProfile $vendor)
    {
        $vendor->update(['verification_status' => 'approved']);

        return redirect()->back()->with('success', 'Vendor ' . $vendor->shop_name . ' telah disetujui!');
    }

    /**
     * Reject vendor.
     */
    public function reject(VendorProfile $vendor)
    {
        $vendor->update(['verification_status' => 'rejected']);

        return redirect()->back()->with('success', 'Vendor ' . $vendor->shop_name . ' telah ditolak.');
    }
}
