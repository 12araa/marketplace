<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Pesan Sukses/Error Review --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-400 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-400 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Gambar --}}
                    <div>
                        @if ($product->image_path)
                            <img src="{{ Storage::url($product->image_path) }}" class="w-full rounded-lg shadow-md">
                        @else
                            <div class="w-full h-64 bg-gray-100 flex items-center justify-center">No Image</div>
                        @endif
                    </div>

                    {{-- Info Produk --}}
                    <div>
                        <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>
                        <div class="flex items-center mb-4">
                            <span class="text-yellow-500 text-xl mr-1">★</span>
                            <span class="font-bold text-lg">{{ $product->average_rating ?? '0.0' }}</span>
                            <span class="text-gray-500 text-sm ml-2">({{ $product->reviews->count() }} Ulasan)</span>
                        </div>
                        <p class="text-2xl font-bold text-blue-600 mb-4">Rp {{ number_format($product->price) }}</p>
                        <p class="text-gray-700 mb-6">{{ $product->description }}</p>

                        {{-- Tombol Cart --}}
                        <form action="{{ route('cart.add', $product) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                + Tambah ke Keranjang
                            </button>
                        </form>
                    </div>
                </div>

                <hr class="my-8 border-gray-200">

                {{-- AREA REVIEW --}}
                <div>
                    <h3 class="text-2xl font-bold mb-4">Ulasan Pelanggan</h3>

                    {{-- Form Review --}}
                    @auth
                        @if($canReview)
                            {{-- KASUS 1: Boleh Review --}}
                            <div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <h4 class="font-semibold mb-2">Tulis Ulasan Anda</h4>
                                <form action="{{ route('reviews.store', $product) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700">Rating</label>
                                        <select name="rating" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Bagus)</option>
                                            <option value="4">⭐⭐⭐⭐ (4 - Bagus)</option>
                                            <option value="3">⭐⭐⭐ (3 - Biasa)</option>
                                            <option value="2">⭐⭐ (2 - Buruk)</option>
                                            <option value="1">⭐ (1 - Sangat Buruk)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700">Komentar</label>
                                        <textarea name="comment" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Bagaimana kualitas produk ini?" required></textarea>
                                    </div>
                                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Kirim Ulasan</button>
                                </form>
                            </div>

                        @elseif($product->reviews()->where('user_id', auth()->id())->exists())
                            {{-- KASUS 2: Sudah Review --}}
                            <div class="mb-8 p-4 bg-green-50 rounded-lg border border-green-200 text-green-700">
                                <p>✅ Anda sudah memberikan ulasan untuk produk ini.</p>
                            </div>

                        @else
                            {{-- KASUS 3: Belum Beli / Belum Selesai --}}
                            <div class="mb-8 p-4 bg-yellow-50 rounded-lg border border-yellow-200 text-yellow-800 text-sm">
                                <p>💡 Anda bisa memberikan ulasan setelah membeli produk ini dan pesanan selesai.</p>
                            </div>
                        @endif
                    @endauth

                    {{-- Daftar Review --}}
                    <div class="space-y-4">
                        @forelse($product->reviews as $review)
                            <div class="border-b pb-4">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-bold">{{ $review->user->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="text-yellow-500 text-sm mb-2">
                                    {{ str_repeat('★', $review->rating) }}
                                    {{ str_repeat('☆', 5 - $review->rating) }}
                                </div>
                                <p class="text-gray-700">{{ $review->comment }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500">Belum ada ulasan untuk produk ini.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
