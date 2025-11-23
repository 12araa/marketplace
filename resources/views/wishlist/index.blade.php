<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Wishlist Saya ❤️
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-400 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if($favorites->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <p class="text-lg">Anda belum menyukai produk apapun.</p>
                            <a href="{{ route('shop.index') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                                Cari Produk Dulu
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach ($favorites as $product)
                                <div class="border border-gray-200 rounded-lg shadow-sm overflow-hidden relative group">

                                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute top-2 right-2 z-10">
                                        @csrf
                                        <button type="submit" class="p-2 bg-white rounded-full shadow hover:bg-gray-100 text-red-500" title="Hapus dari Wishlist">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>

                                    <a href="#"> {{-- Link ke Detail Produk nanti --}}
                                        @if ($product->image_path)
                                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                                        @else
                                            <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400">No Image</div>
                                        @endif

                                        <div class="p-4">
                                            <h3 class="text-lg font-semibold truncate">{{ $product->name }}</h3>
                                            <p class="text-blue-600 font-bold mt-1">Rp {{ number_format($product->price) }}</p>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $favorites->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
