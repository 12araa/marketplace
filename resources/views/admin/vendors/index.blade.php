{{-- File: resources/views/admin/vendors/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Verifikasi Vendor Baru
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Notifikasi Sukses --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-400 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-bold mb-4">Daftar Vendor Menunggu Persetujuan</h3>

                    @if($pendingVendors->isEmpty())
                        <p class="text-gray-500 text-center py-4">Tidak ada pengajuan vendor baru saat ini.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Nama User</th>
                                        <th class="px-4 py-2 text-left">Email</th>
                                        <th class="px-4 py-2 text-left">Nama Toko</th>
                                        <th class="px-4 py-2 text-left">Tanggal Daftar</th>
                                        <th class="px-4 py-2 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingVendors as $profile)
                                        <tr class="border-t">
                                            <td class="px-4 py-2">{{ $profile->user->name }}</td>
                                            <td class="px-4 py-2">{{ $profile->user->email }}</td>
                                            <td class="px-4 py-2 font-bold">{{ $profile->shop_name }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-500">
                                                {{ $profile->created_at->format('d M Y') }}
                                            </td>
                                            <td class="px-4 py-2 text-center space-x-2">

                                                {{-- Tombol Approve --}}
                                                <form action="{{ route('admin.vendors.approve', $profile) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700" onclick="return confirm('Setujui vendor ini?')">
                                                        Setujui
                                                    </button>
                                                </form>

                                                {{-- Tombol Reject --}}
                                                <form action="{{ route('admin.vendors.reject', $profile) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700" onclick="return confirm('Tolak vendor ini?')">
                                                        Tolak
                                                    </button>
                                                </form>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
