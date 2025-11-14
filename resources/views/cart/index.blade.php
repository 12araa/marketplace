{{--
  File: resources/views/cart/index.blade.php
  Halaman keranjang belanja customer.
--}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Keranjang Belanja Anda
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-400 rounded" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-400 rounded" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Cek apakah keranjang kosong --}}
                    @if (empty($cartItems))
                        <div class="text-center text-gray-500">
                            <p class="text-xl mb-4">Keranjang Anda masih kosong.</p>
                            <a href="{{ route('shop.index') }}"
                               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Mulai Belanja
                            </a>
                        </div>

                    @else
                        {{-- Jika keranjang ADA ISINYA --}}

                        <div class="divide-y divide-gray-200">
                            @foreach ($cartItems as $id => $item)
                            <div class="py-4 flex flex-col md:flex-row justify-between items-center">

                                <div class="flex items-center space-x-4 mb-4 md:mb-0">
                                    <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}"
                                         class="w-16 h-16 object-cover rounded">
                                    <div>
                                        <h3 class="text-lg font-semibold">{{ $item['name'] }}</h3>
                                        <p class="text-gray-600">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-4">

                                    <form action="{{ route('cart.update', $id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                                               min="1" class="w-20 rounded border-gray-300 text-sm">
                                        <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm ml-2">Update</button>
                                    </form>

                                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-200 mt-6 pt-6 text-right">
                            <h3 class="text-2xl font-semibold">
                                Total: Rp {{ number_format($total, 0, ',', '.') }}
                            </h3>
                            <form action="{{ route('order.store') }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="inline-block mt-4 px-6 py-3 bg-green-600 text-white text-lg rounded-md hover:bg-green-700">
                                    Lanjut ke Checkout
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
