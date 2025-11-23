<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Produk
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{--
                      Gunakan @can untuk mengecek Policy 'create'.
                      Tombol ini hanya akan muncul jika user boleh (Vendor).
                    --}}
                    @can('create', App\Models\Product::class)
                        <div class="mb-4">
                            <a href="{{ route('products.create') }}"
                               class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">
                               + Tambah Produk Baru
                            </a>
                        </div>
                    @endcan

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-400 rounded" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">Gambar</th>
                                    <th class="py-2 px-4 border-b text-left">Nama Produk</th>
                                    <th class="py-2 px-4 border-b text-left">Kategori</th>
                                    @if(auth()->user()->role == 'admin')
                                        <th class="py-2 px-4 border-b text-left">Vendor</th>
                                    @endif
                                    <th class="py-2 px-4 border-b text-left">Harga</th>
                                    <th class="py-2 px-4 border-b text-left">Stok</th>
                                    <th class="py-2 px-4 border-b text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2 px-4 border-b">
                                            @if($product->image_path)
                                                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="h-16 w-16 object-cover rounded">
                                            @else
                                                <span class="text-gray-400 text-xs">No Image</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b">{{ $product->name }}</td>
                                        <td class="py-2 px-4 border-b">{{ $product->category->name ?? 'N/A' }}</td>

                                        @if(auth()->user()->role == 'admin')
                                            <td class="py-2 px-4 border-b">{{ $product->vendor->name ?? 'N/A' }}</td>
                                        @endif

                                        <td class="py-2 px-4 border-b">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td class="py-2 px-4 border-b">{{ $product->stock }}</td>

                                        <td class="py-2 px-4 border-b">
                                            {{-- Tombol Edit, cek Policy 'update' --}}
                                            @can('update', $product)
                                                <a href="{{ route('products.edit', $product) }}"
                                                   class="text-blue-600 hover:text-blue-900 mr-2">
                                                   Edit
                                                </a>
                                            @endcan

                                            {{-- Cek apakah user boleh 'view' produk ini --}}
                                            @can('view', $product)
                                                <a href="{{ route('products.show', $product) }}"
                                                   class="text-blue-600 hover:text-blue-900 mr-2">
                                                Show
                                            </a>
                                            @else
                                                {{-- Jika tidak boleh (misal role aneh), tampilkan nama saja --}}
                                                {{ $product->name }}
                                            @endcan

                                            {{-- Tombol Hapus, cek Policy 'delete' --}}
                                            @can('delete', $product)
                                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-block"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-4 px-4 text-center text-gray-500">
                                            Belum ada data produk.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
