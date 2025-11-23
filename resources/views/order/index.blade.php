<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Pesanan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    {{-- Vendor/Admin butuh info Customer --}}
                                    @if(auth()->user()->role !== 'customer')
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                    @endif
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($orders as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">#{{ $order->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">
                                            Rp {{ number_format($order->total_price) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 py-1 text-xs font-semibold rounded
                                                @if($order->status == 'Pending') bg-yellow-100 text-yellow-800
                                                @elseif($order->status == 'Shipped') bg-blue-100 text-blue-800
                                                @elseif($order->status == 'Completed') bg-green-100 text-green-800
                                                @elseif($order->status == 'Cancelled') bg-red-100 text-red-800
                                                @endif">
                                                {{ $order->status }}
                                            </span>
                                        </td>

                                        @if(auth()->user()->role !== 'customer')
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                {{ $order->user->name }}
                                                <div class="text-xs text-gray-400">{{ $order->shipping_address ?? '-' }}</div>
                                            </td>
                                        @endif

                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('orders.show', $order) }}"
                                                class="text-blue-600 hover:text-blue-900 hover:underline font-medium">
                                                Show Detail
                                            </a>

                                            @if($order->status == 'Shipped' && auth()->user()->role == 'customer')
                                                <form action="{{ route('orders.complete', $order) }}" method="POST" class="mt-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="bg-green-600 text-white text-xs px-3 py-1 rounded hover:bg-green-700"
                                                            onclick="return confirm('Pastikan barang sudah diterima dengan baik. Lanjutkan?')">
                                                        Pesanan Diterima
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            Belum ada riwayat pesanan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination Links --}}
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
