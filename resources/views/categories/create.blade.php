{{--
  File: resources/views/categories/create.blade.php
  Kita "bungkus" semua konten kita dengan <x-app-layout>
  Ini akan otomatis menambahkan navbar Breeze, dll.
--}}

<x-app-layout>
    {{-- Ini adalah slot untuk judul halaman yang akan muncul di header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Buat Kategori Baru
        </h2>
    </x-slot>

    {{-- Ini adalah konten utama halaman Anda --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- MENAMPILKAN SEMUA ERROR VALIDASI DI ATAS --}}
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

                    {{-- AWAL FORM --}}
                    {{-- Form ini akan mengirim data ke route 'categories.store' --}}
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf  {{-- WAJIB: Token Keamanan Laravel --}}

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                            <input type="text" name="name" id="name"
                                   {{-- 'old('name')' agar data tidak hilang jika validasi gagal --}}
                                   value="{{ old('name') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>

                            {{-- Menampilkan error spesifik untuk 'name' --}}
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="parent_id" class="block text-sm font-medium text-gray-700">Induk Kategori (Opsional)</label>

                            {{-- Ini adalah dropdown (select) untuk memilih parent_id --}}
                            <select name="parent_id" id="parent_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

                                {{-- Opsi pertama adalah 'Tidak Ada Induk' --}}
                                <option value="">--- Tidak Ada Induk ---</option>

                                {{-- Loop ini mengambil data dari CategoryController@create --}}
                                @foreach ($parentCategories as $category)
                                    <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('parent_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-black rounded-md hover:bg-blue-700">
                                Simpan Kategori
                            </button>
                        </div>
                    </form>
                    {{-- AKHIR FORM --}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
