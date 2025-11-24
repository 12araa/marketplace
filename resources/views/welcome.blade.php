<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel Marketplace') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-900 text-white font-sans selection:bg-blue-500 selection:text-white">

    <nav class="flex items-center justify-between p-6 lg:px-8 max-w-7xl mx-auto">
        <div class="flex lg:flex-1">
            <a href="#" class="-m-1.5 p-1.5 text-2xl font-bold text-white">
                <span class="text-blue-500">Marketplace</span>Kita
            </a>
        </div>

        <div class="flex flex-1 justify-end gap-x-6 items-center">
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-semibold leading-6 text-gray-300 hover:text-white transition">Dashboard <span aria-hidden="true">&rarr;</span></a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold leading-6 text-gray-300 hover:text-white transition mr-6">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm font-semibold leading-6 text-gray-300 hover:text-white transition mr-6">Register Customer</a>
                            <a href="{{ route('vendor.register') }}" class="rounded-md bg-blue-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition">
                                Vendor Register
                            </a>
                        @endif
                    @endauth
                </div>
            @endif

        </div>
    </nav>

        <div class="relative isolate px-6 pt-14 lg:px-8">

            <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
                <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
            </div>

            <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56 text-center">

                <div class="hidden sm:mb-8 sm:flex sm:justify-center">
                    <div class="relative rounded-full px-3 py-1 text-sm leading-6 text-gray-400 ring-1 ring-gray-100/10 hover:ring-gray-100/20 transition">
                        Pusat belanja dan jualan online terpercaya. <a href="#" class="font-semibold text-blue-400"><span class="absolute inset-0" aria-hidden="true"></span>Baca selengkapnya <span aria-hidden="true">&rarr;</span></a>
                    </div>
                </div>

                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-6xl">
                    Temukan Semua Kebutuhanmu Di Sini
                </h1>

                <p class="mt-6 text-lg leading-8 text-gray-400">
                    Nikmati pengalaman belanja yang mudah, aman, dan cepat dengan tampilan yang nyaman di mata. Ribuan produk dari vendor terpercaya siap dikirim.
                </p>

                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <a href="{{ route('shop.index') }}" class="rounded-md bg-blue-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition">
                        Mulai Belanja Sekarang
                    </a>

                    <a href="{{ route('vendor.register') }}" class="text-sm font-semibold leading-6 text-white hover:text-blue-400 transition">
                        Ingin berjualan? <span aria-hidden="true">→</span>
                    </a>
                </div>

            </div>

            <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
                <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
            </div>
        </div>

        <footer class="bg-gray-900 border-t border-gray-800 mt-10">
            <div class="mx-auto max-w-7xl px-6 py-12 md:flex md:items-center md:justify-between lg:px-8">
                <div class="mt-8 md:order-1 md:mt-0">
                    <p class="text-center text-xs leading-5 text-gray-500">
                        &copy; {{ date('Y') }} Marketplace Kita. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>

    </body>
</html>
