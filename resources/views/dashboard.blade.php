<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900 border-b border-gray-200">
                    Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong>!
                    <p class="text-sm text-gray-600">
                        Anda login sebagai:
                        <span class="font-semibold
                            @if(auth()->user()->role == 'admin') text-red-600 @endif
                            @if(auth()->user()->role == 'vendor') text-blue-600 @endif
                            @if(auth()->user()->role == 'customer') text-green-600 @endif
                        ">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </p>
                </div>

                {{-- ===== TAMPILAN DASHBOARD UNTUK ADMIN ===== --}}
                @if(auth()->user()->role == 'admin')
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">
                            Pesanan 'Pending' Terbaru
                        </h3>
                        <div class="overflow-x-auto border border-gray-200 rounded">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($pendingOrders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">#{{ $order->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                {{ $order->user->name }}
                                                <div class="text-xs text-gray-400">{{ $order->user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">
                                                Rp {{ number_format($order->total_price) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">
                                                    {{ $order->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->created_at->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:underline font-medium">
                                                    Show Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                                Tidak ada pesanan pending saat ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900 hover:underline">
                                Lihat Semua Riwayat Pesanan &rarr;
                            </a>
                        </div>
                    </div>

                {{-- ===== TAMPILAN DASHBOARD UNTUK VENDOR ===== --}}
                @elseif(auth()->user()->role == 'vendor')

                    {{-- CEK STATUS VERIFIKASI VENDOR --}}
                    @if(auth()->user()->vendorProfile?->verification_status == 'approved')
                        {{-- JIKA SUDAH DISETUJUI --}}
                        <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Pesanan Masuk (Produk Saya)</h3>

                        <div class="overflow-x-auto border border-gray-200 rounded">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Info Pengiriman</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($pendingOrders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">#{{ $order->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">Rp {{ number_format($order->total_price) }}</td>

                                            {{-- Kolom Status --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 py-1 text-xs font-semibold rounded
                                                    @if($order->status == 'Pending') bg-yellow-100 text-yellow-800
                                                    @elseif($order->status == 'Shipped') bg-blue-100 text-blue-800
                                                    @elseif($order->status == 'Completed') bg-green-100 text-green-800
                                                    @elseif($order->status == 'Cancelled') bg-red-100 text-red-800
                                                    @endif">
                                                    {{ $order->status }}
                                                </span>
                                            </td>

                                            {{-- Kolom Info Pengiriman (Fitur Baru) --}}
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                @if($order->shipping_address)
                                                    <div class="text-xs">
                                                        <strong>{{ $order->shipping_name }}</strong><br>
                                                        {{ $order->shipping_phone }}<br>
                                                        <span class="italic">{{ Str::limit($order->shipping_address, 30) }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-400">-</span>
                                                @endif
                                            </td>

                                            {{-- Kolom Aksi (Update Status) --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <form action="{{ route('dashboard.order.update', $order) }}" method="POST" class="flex flex-col gap-2">
                                                    @csrf
                                                    @method('PATCH')

                                                    <select name="status" class="text-xs rounded border-gray-300 py-1">
                                                        <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="Shipped" {{ $order->status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                                        <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                                        <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                    </select>

                                                    <button type="submit" class="bg-blue-600 text-white text-xs px-2 py-1 rounded hover:bg-blue-700">
                                                        Update
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                                Tidak ada pesanan masuk saat ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @elseif(auth()->user()->vendorProfile?->verification_status == 'pending')
                        {{-- JIKA MASIH PENDING --}}
                        <div class="p-6 text-gray-900">
                            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                                <p class="font-bold">Akun Anda Sedang Ditinjau</p>
                                <p>Terima kasih telah mendaftar. Akun vendor Anda sedang dalam proses verifikasi oleh Admin. Anda akan bisa mengelola produk setelah akun Anda disetujui.</p>
                                <p class="mt-2">Silakan lengkapi profil Anda jika belum.</p>
                            </div>
                        </div>

                    @else
                        {{-- JIKA DITOLAK (REJECTED) --}}
                        <div class="p-6 text-gray-900">
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                                <p class="font-bold">Akun Vendor Ditolak</p>
                                <p>Maaf, pengajuan vendor Anda telah ditolak. Silakan hubungi admin untuk informasi lebih lanjut.</p>
                            </div>
                        </div>
                    @endif

                {{-- ===== TAMPILAN DASHBOARD UNTUK CUSTOMER ===== --}}
                @elseif(auth()->user()->role == 'customer')
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Pesanan Terakhir Anda</h3>
                        {{-- ... (Looping Pesanan Customer) ... --}}
                    </div>
                @endif
                {{-- =============================================== --}}

            </div>
        </div>
    </div>
</x-app-layout>
