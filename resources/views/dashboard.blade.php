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

                {{-- =============================================== --}}
                {{--         KONTEN BARU BERBASIS ROLE             --}}
                {{-- =============================================== --}}

                {{-- ===== TAMPILAN DASHBOARD UNTUK ADMIN & VENDOR ===== --}}
                @if(auth()->user()->role == 'admin' || auth()->user()->role == 'vendor')
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Navigasi Cepat</h3>
                        <p class="mb-4">Gunakan navigasi di bagian atas untuk mengelola data Anda.</p>

                        {{-- Kita bisa tampilkan statistik sederhana di sini nanti --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div class="border border-gray-200 rounded p-4">
                                <h4 class="font-semibold">Total Produk</h4>
                                <p class="text-2xl font-bold">(Segera Hadir)</p>
                            </div>
                            <div class="border border-gray-200 rounded p-4">
                                <h4 class="font-semibold">Total Penjualan</h4>
                                <p class="text-2xl font-bold">(Segera Hadir)</p>
                            </div>
                        </div>
                    </div>

                {{-- ===== TAMPILAN DASHBOARD UNTUK CUSTOMER ===== --}}
                @elseif(auth()->user()->role == 'customer')
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Pesanan Terakhir Anda</h3>
                        {{-- Ini adalah "placeholder" sampai kita membuat fitur Order --}}
                        <div class="border border-gray-200 rounded p-4 text-center text-gray-500">
                            <p>(Anda belum memiliki riwayat pesanan)</p>
                            <p class="mt-2 text-sm">
                                Ayo mulai belanja di halaman <a href="{{ route('shop.index') }}" class="text-blue-600 underline">Shop</a>!
                            </p>
                        </div>

                        {{-- Placeholder untuk Wishlist --}}
                        <h3 class="text-lg font-semibold mb-4 mt-6">Wishlist Saya</h3>
                        <div class="border border-gray-200 rounded p-4 text-center text-gray-500">
                            <p>(Fitur wishlist segera hadir)</p>
                        </div>
                    </div>
                @endif
                {{-- =============================================== --}}

            </div>
        </div>
    </div>
</x-app-layout>
