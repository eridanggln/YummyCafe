<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cafe Yummy')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 overflow-hidden">

    <div class="flex min-h-screen">

        <aside class="w-64 bg-red-800 text-white p-6 shadow-xl flex flex-col pt-6 h-screen overflow-hidden shrink-0">
            <div class="flex justify-center mb-6">
                <img src="{{ asset('storage/images/logo.png') }}" alt="Logo Cafe Yummy"
                    class="h-28 w-auto object-contain">
            </div>

            <nav class="space-y-2 text-sm font-medium flex-1">
                <a href="{{ route('dashboard.index') }}"
                    class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('dashboard.*') ? 'bg-yellow-400 text-red-900' : 'hover:bg-red-700 hover:text-yellow-300' }}">
                    Dashboard
                </a>

                <a href="{{ route('pesanan.index') }}"
                    class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('pesanan.*') ? 'bg-yellow-400 text-red-900' : 'hover:bg-red-700 hover:text-yellow-300' }}">
                    Pesanan
                </a>

                <a href="{{ route('riwayat.index') }}"
                    class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('riwayat.*') ? 'bg-yellow-400 text-red-900' : 'hover:bg-red-700 hover:text-yellow-300' }}">
                    Riwayat Pesanan
                </a>

                {{-- KHUSUS ADMIN --}}
                @if(Auth::user()->role === 'admin')
                    <div x-data="{ open: {{ request()->routeIs('menu.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="w-full flex justify-between items-center px-4 py-2 rounded-lg transition {{ request()->routeIs('menu.*') ? 'bg-yellow-400 text-red-900' : 'hover:bg-red-700 hover:text-yellow-300' }}">
                            <span>Kelola Menu</span>
                            <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition class="mt-1 ml-4 space-y-1 border-l-2 border-yellow-500/30">
                            @php
                                $categories = [
                                    'makanan' => 'Makanan',
                                    'minuman' => 'Minuman',
                                    'snack' => 'Snack',
                                    'dessert' => 'Dessert'
                                ];
                            @endphp
                            @foreach ($categories as $route => $label)
                                <a href="{{ route('menu.' . $route) }}"
                                    class="block px-4 py-1.5 transition {{ request()->fullUrlIs(route('menu.' . $route)) ? 'text-yellow-400 font-bold' : 'hover:text-yellow-300 text-gray-300' }}">
                                    • {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <a href="{{ route('payment.index') }}"
                        class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('payment.*') ? 'bg-yellow-400 text-red-900' : 'hover:bg-red-700 hover:text-yellow-300' }}">
                        Kelola Payment
                    </a>

                    <a href="{{ route('user.index') }}"
                        class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('user.*') ? 'bg-yellow-400 text-red-900' : 'hover:bg-red-700 hover:text-yellow-300' }}">
                        Kelola Akun
                    </a>
                @endif
            </nav>

            <div class="mt-auto pt-6 border-t border-red-700">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-900 hover:bg-red-950 text-white rounded-lg transition text-sm font-bold shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        LOGOUT
                    </button>
                </form>
            </div>
        </aside>
        <main class="flex-1 flex flex-col">

            <header class="bg-yellow-400 px-8 py-4 shadow-md z-10">
                <h2 class="text-xl font-bold text-red-800 uppercase tracking-tight">
                    @yield('header')
                </h2>
            </header>

            <div class="p-8 flex-1 overflow-y-auto bg-white">
                @yield('content')
            </div>

            @stack('scripts')

        </main>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>