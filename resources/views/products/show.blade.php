<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Produk: {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-6">
                        <a href="{{ route('products.index') }}"
                           class="text-blue-600 hover:text-blue-800">
                           &larr; Kembali ke Daftar Produk
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        <div>
                            @if ($product->image_path)
                                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}"
                                     class="w-full h-auto object-cover rounded-lg shadow-md">
                            @else
                                <div class="w-full h-64 flex items-center justify-center bg-gray-100 rounded-lg text-gray-400">
                                    Tidak ada gambar
                                </div>
                            @endif
                        </div>

                        <div>
                            <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>

                            <div class="text-2xl font-semibold text-blue-600 mb-4">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </div>

                            <div class="flex items-center space-x-4 mb-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Stok:</span>
                                    <span class="text-lg font-semibold">{{ $product->stock }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Status:</span>
                                    <span class="px-2 py-1 text-sm font-semibold rounded
                                        @if($product->status == 'active') bg-green-100 text-green-800 @endif
                                        @if($product->status == 'draft') bg-yellow-100 text-yellow-800 @endif
                                        @if($product->status == 'out_of_stock') bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="border-t border-b border-gray-200 py-4 mb-4">
                                <dl class="grid grid-cols-2 gap-x-4 gap-y-2">
                                    <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                                    <dd class="text-sm text-gray-900">{{ $product->category->name ?? 'N/A' }}</dd>

                                    <dt class="text-sm font-medium text-gray-500">Vendor</dt>
                                    <dd class="text-sm text-gray-900">{{ $product->vendor->name ?? 'N/A' }}</dd>
                                </dl>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold mb-2">Deskripsi Produk</h3>
                                <div class="text-gray-700 prose">
                                    {{-- 'nl2br' mengubah baris baru (enter) menjadi tag <br> --}}
                                    {!! nl2br(e($product->description)) !!}
                                </div>
                            </div>

                        </div>
                    </div> </div>
            </div>
        </div>
    </div>
</x-app-layout>
