{{--
  File: resources/views/categories/index.blade.php
  Halaman ini menampilkan daftar semua kategori dalam tabel.
--}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Kategori
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-4">
                        <a href="{{ route('categories.create') }}"
                           class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                           + Tambah Kategori Baru
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-400 rounded" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">Nama Kategori</th>
                                    <th class="py-2 px-4 border-b text-left">Induk Kategori</th>
                                    <th class="py-2 px-4 border-b text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Loop data kategori. @forelse digunakan untuk handle jika data kosong --}}
                                @forelse ($categories as $category)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2 px-4 border-b">{{ $category->name }}</td>

                                        <td class="py-2 px-4 border-b">
                                            {{-- Cek apakah kategori punya parent.
                                                 Ini butuh 'eager loading' di controller! --}}
                                            {{ $category->parent->name ?? '--- Tidak Ada ---' }}
                                        </td>

                                        <td class="py-2 px-4 border-b">
                                            <a href="{{ route('categories.edit', $category) }}"
                                               class="text-blue-600 hover:text-blue-900 mr-2">
                                               Edit
                                            </a>

                                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    {{-- Tampilan jika tidak ada data sama sekali --}}
                                    <tr>
                                        <td colspan="3" class="py-4 px-4 text-center text-gray-500">
                                            Belum ada data kategori.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
