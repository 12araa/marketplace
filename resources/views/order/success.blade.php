{{--
  File: resources/views/order/success.blade.php
  Halaman "Terima Kasih" setelah checkout berhasil.
--}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pesanan Diterima
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center">

                    @if (session('success'))
                        <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-400 rounded" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <h3 class="text-2xl font-semibold text-green-600 mb-4">
                        Terima Kasih Atas Pesanan Anda!
                    </h3>

                    <p class="text-gray-700 mb-6">
                        Pesanan Anda sedang kami proses dan akan segera kami konfirmasi.
                    </p>

                    <a href="{{ route('shop.index') }}"
                       class="inline-block px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        &larr; Kembali Belanja
                    </a>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
