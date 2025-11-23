<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Checkout Pengiriman</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('order.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-bold mb-4">Alamat Pengiriman</h3>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Nama Penerima</label>
                                <input type="text" name="shipping_name" value="{{ old('shipping_name', $user->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">No. HP</label>
                                <input type="text" name="shipping_phone" value="{{ old('shipping_phone', $user->customerProfile->phone_number ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                                <textarea name="shipping_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('shipping_address', $user->customerProfile->address_line_1 ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded">
                            <h3 class="text-lg font-bold mb-4">Ringkasan Pesanan</h3>
                            {{-- Loop cart items sekilas --}}
                            @foreach($cart as $item)
                                <div class="flex justify-between text-sm mb-2">
                                    <span>{{ $item['name'] }} (x{{ $item['quantity'] }})</span>
                                    <span>Rp {{ number_format($item['price'] * $item['quantity']) }}</span>
                                </div>
                            @endforeach
                            <div class="border-t mt-4 pt-4 font-bold text-xl">
                                Total: Rp {{ number_format(collect($cart)->sum(fn($i) => $i['price'] * $i['quantity'])) }}
                            </div>

                            <button type="submit" class="w-full mt-6 px-4 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 font-bold">
                                Buat Pesanan Sekarang
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
