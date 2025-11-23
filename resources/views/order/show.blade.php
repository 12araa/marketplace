<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Pesanan #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->role == 'admin')
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-blue-800">Admin Control Panel</h3>
                        <p class="text-sm text-blue-600">Status pesanan</p>
                    </div>

                    <form action="{{ route('dashboard.order.update', $order) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')

                        <label for="status_admin" class="sr-only">Ubah Status</label>
                        <select name="status" id="status_admin" class="rounded border-gray-300 text-sm">
                            <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Paid" {{ $order->status == 'Paid' ? 'selected' : '' }}>Paid</option>
                            <option value="Shipped" {{ $order->status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>

                        <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700"
                                onclick="return confirm('Apakah Anda yakin ingin mengubah status pesanan ini secara manual?')">
                            Update Status
                        </button>
                    </form>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="flex justify-between items-center border-b pb-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Pesanan</p>
                        <p class="font-bold">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <span class="px-3 py-1 rounded-full text-sm font-bold
                            @if($order->status == 'Pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'Shipped') bg-blue-100 text-blue-800
                            @elseif($order->status == 'Completed') bg-green-100 text-green-800
                            @elseif($order->status == 'Cancelled') bg-red-100 text-red-800
                            @endif">
                            {{ $order->status }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold mb-3">Barang yang Dibeli</h3>
                        <ul class="divide-y divide-gray-200">
                            @foreach ($order->items as $item)
                                @if(auth()->user()->role !== 'vendor' || $item->product->vendor_id === auth()->id())
                                    <li class="py-3 flex justify-between">
                                        <div>
                                            <p class="font-medium">{{ $item->product->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price) }}</p>
                                        </div>
                                        <p class="font-semibold">Rp {{ number_format($item->quantity * $item->price) }}</p>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        <div class="border-t pt-3 mt-3 text-right">
                            <p class="text-lg font-bold">Total Order: Rp {{ number_format($order->total_price) }}</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg h-fit">
                        <h3 class="text-lg font-semibold mb-3">Info Pengiriman</h3>
                        <div class="text-sm text-gray-700 space-y-2">
                            <p><span class="font-medium">Penerima:</span> {{ $order->shipping_name }}</p>
                            <p><span class="font-medium">No. HP:</span> {{ $order->shipping_phone }}</p>
                            <p><span class="font-medium">Alamat:</span><br>{{ $order->shipping_address }}</p>
                        </div>

                        <div class="mt-6 border-t pt-4">
                            <h3 class="text-lg font-semibold mb-2">Data Customer</h3>
                            <p class="text-sm font-medium">{{ $order->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $order->user->email }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline">&larr; Kembali ke Riwayat</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
