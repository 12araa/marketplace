<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Produk
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-400 rounded">
                            <strong class="font-bold">Oops! Ada yang salah:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori Produk</label>
                            <select name="category_id" id="category_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-black" required>
                                <option value="">--- Pilih Kategori ---</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                            <input type="text" name="name" ... value="{{ old('name', $product->name) }}">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                            <textarea name="description" id="description" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
                            <input type="number" name="price" id="price"
                                   value="{{ old('price', $product->price) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="0" step="any">
                            @error('price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="stock" class="block text-sm font-medium text-gray-700">Stok</label>
                            <input type="number" name="stock" id="stock"
                                   value="{{ old('stock', $product->stock) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="0">
                            @error('stock')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700">Gambar Produk</label>
                            @if ($product->image_path)
                                <div class="my-2">
                                    <p class="text-sm text-gray-500 mb-1">Gambar saat ini:</p>
                                    {{-- Gunakan Storage::url() untuk mendapatkan link publik --}}
                                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}"
                                        class="w-48 h-48 object-cover rounded shadow">
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Upload gambar baru untuk menggantinya (Opsional).</p>
                            @else
                                <p class="text-sm text-gray-500 mt-1">Belum ada gambar. Silakan upload (Opsional).</p>
                            @endif
                            <input type="file" name="image" id="image"
                                class="mt-2 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100">

                            @error('image')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status Produk</label>
                            <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>
                                    Draft
                                </option>
                                <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Simpan Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
