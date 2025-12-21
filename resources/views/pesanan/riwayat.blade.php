@extends('layout.layout')

@section('title', 'Riwayat Pesanan')

@section('header')
    <div class="flex justify-between items-center w-full">
        <span>Cafe Yummy</span>

        <div x-data="{ userOpen: false }" class="relative inline-block text-left">
            <button @click="userOpen = !userOpen" @click.away="userOpen = false"
                class="flex items-center focus:outline-none group">
                <div class="text-right mr-3 hidden md:block">
                    <p class="text-xs font-bold text-red-800 uppercase leading-none">{{ Auth::user()->nama }}</p>
                    <p class="text-[10px] text-red-700 font-medium leading-none">{{ Auth::user()->role }}</p>
                </div>

                <div class="bg-red-800 p-2 rounded-full shadow-md group-hover:bg-red-900 transition flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>

            <div x-show="userOpen" x-transition x-cloak
                class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-xl border border-gray-100 py-1 z-50 overflow-hidden">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-bold flex items-center gap-2">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div x-data="{ detailOpen: false, selectedOrder: { items: [] } }" class="p-4 space-y-6 h-[calc(100vh-120px)] overflow-y-auto">

        <div class="bg-white rounded-xl p-6 w-full max-w-8xl shadow-lg">
            <h1 class="text-center text-red-800 text-2xl font-bold mb-8">RIWAYAT PESANAN</h1>

            <div class="flex flex-row justify-between items-center mb-6 gap-4">
                <a href="{{ route('export.history') }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm flex items-center shadow-sm transition whitespace-nowrap">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Excel
                </a>

                <div class="relative flex-grow md:flex-grow-0">
                    <input type="text" id="searchInput" placeholder="Cari pesanan..."
                        class="border border-gray-300 rounded-lg px-4 py-2 w-full md:w-64 focus:outline-none focus:ring-2 focus:ring-red-500 shadow-sm">
                </div>
            </div>

            <div class="overflow-x-auto border rounded-lg">
                <table class="w-full border-collapse" id="historyTable">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-3 text-sm font-bold text-gray-700 border-b">Tanggal</th>
                            <th class="px-3 py-3 text-sm font-bold text-gray-700 border-b text-left">Nomor Pesanan</th>
                            <th class="px-3 py-3 text-sm font-bold text-gray-700 border-b text-left">Customer</th>
                            <th class="px-3 py-3 text-sm font-bold text-gray-700 border-b">Tipe</th>
                            <th class="px-3 py-3 text-sm font-bold text-right text-gray-700 border-b">Total</th>
                            <th class="px-3 py-3 text-sm font-bold text-gray-700 border-b text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse ($historyOrders as $order)
                            <tr class="hover:bg-gray-50 transition border-b border-gray-100">
                                <td class="px-3 py-4 text-sm text-center">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}
                                </td>
                                <td class="px-3 py-4 text-sm font-medium">{{ $order->order_number }}</td>
                                <td class="px-3 py-4 text-sm">{{ $order->customer_name }}</td>
                                <td class="px-3 py-4 text-sm text-center">
                                    <span class="px-2 py-1 bg-gray-100 rounded text-[10px] font-bold uppercase">{{ $order->order_type }}</span>
                                </td>
                                <td class="px-3 py-4 text-sm text-right font-semibold text-red-800">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-4 text-sm text-center">
                                    <button 
                                        data-order="{{ $order->toJson() }}"
                                        @click="selectedOrder = JSON.parse($el.getAttribute('data-order')); detailOpen = true"
                                        class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition shadow-sm"
                                        title="Lihat Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-8 text-center text-gray-500 italic">Belum ada data pesanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="detailOpen" 
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 overflow-hidden" 
             x-cloak>
            
            <div x-show="detailOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 class="fixed inset-0 bg-gray-900 bg-opacity-60" 
                 @click="detailOpen = false"></div>

            <div x-show="detailOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden z-[101] flex flex-col max-h-[90vh]">
                
                <div class="bg-red-800 px-6 py-4 flex justify-between items-center text-white shrink-0">
                    <div>
                        <h3 class="text-lg font-bold">Detail Pesanan</h3>
                        <p class="text-xs text-red-100" x-text="'ID: ' + selectedOrder.order_number"></p>
                    </div>
                    <button @click="detailOpen = false" class="text-2xl leading-none hover:text-yellow-400">&times;</button>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-b grid grid-cols-2 gap-2 text-sm text-gray-700 shrink-0">
                    <p>Nama: <span class="font-bold block" x-text="selectedOrder.customer_name"></span></p>
                    <p>Tipe: <span class="font-bold block uppercase" x-text="selectedOrder.order_type"></span></p>
                </div>

                <div class="p-0 overflow-y-auto flex-grow">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-500 sticky top-0 border-b">
                            <tr>
                                <th class="text-left px-6 py-3 font-medium">Menu</th>
                                <th class="text-center px-4 py-3 font-medium">Qty</th>
                                <th class="text-right px-6 py-3 font-medium">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="item in selectedOrder.items" :key="item.id">
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800" x-text="item.menu_name"></div>
                                        <template x-for="addon in item.addons" :key="addon.id">
                                            <div class="text-[10px] text-gray-500 italic ml-2">
                                                + <span x-text="addon.addon_name"></span>
                                            </div>
                                        </template>
                                    </td>
                                    <td class="px-4 py-4 text-center text-gray-600" x-text="item.qty"></td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-900">
                                        Rp <span x-text="new Intl.NumberFormat('id-ID').format(item.subtotal)"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div class="shrink-0 border-t">
                    <div class="px-6 py-4 bg-red-50 flex justify-between items-center">
                        <span class="font-bold text-red-800 uppercase tracking-wider">Total</span>
                        <span class="text-xl font-black text-red-800">
                            Rp <span x-text="new Intl.NumberFormat('id-ID').format(selectedOrder.total)"></span>
                        </span>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 flex justify-between gap-3">
                         <a :href="'/order/print/' + selectedOrder.order_number" target="_blank"
                            class="flex-1 text-center py-2 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Cetak
                        </a>
                        <button @click="detailOpen = false" class="flex-1 py-2 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300 transition">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const table = document.getElementById('historyTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            searchInput.addEventListener('keyup', function () {
                const filter = searchInput.value.toLowerCase();
                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    if (row.cells.length < 2) continue;
                    let text = row.innerText.toLowerCase();
                    row.style.display = text.includes(filter) ? "" : "none";
                }
            });
        });
    </script>
@endsection