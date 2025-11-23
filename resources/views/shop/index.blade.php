<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Selamat Datang di Toko Kami
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @forelse ($products as $product)
                        <div class="border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                            <a href="#"> {{-- Nanti ini ke halaman detail produk --}}
                                @if ($product->image_path)
                                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}"
                                         class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400">
                                        No Image
                                    </div>
                                @endif
                            </a>

                            <div class="p-4">
                                <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                                <p class="text-gray-500 text-sm mb-2">by {{ $product->vendor->name ?? 'N/A' }}</p>
                                <p class="text-xl font-bold text-blue-600 mb-4">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">

                                    <button type="submit"
                                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        + Tambah ke Keranjang
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="col-span-full text-center text-gray-500">
                            Belum ada produk yang dijual saat ini.
                        </p>
                    @endforelse

                </div>
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
