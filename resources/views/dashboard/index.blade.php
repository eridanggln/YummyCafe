@extends('layout.layout')

@section('title', 'Dashboard')

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

                <div
                    class="bg-red-800 p-2 rounded-full shadow-md group-hover:bg-red-900 transition flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </button>

            <div x-show="userOpen" x-transition
                class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-xl border border-gray-100 py-1 z-50 overflow-hidden">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-bold flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="p-4 space-y-6 h-[calc(100vh-120px)] overflow-y-auto">

        <!-- =====================
                        PENJUALAN HARI INI
                    ====================== -->
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="font-bold text-lg mb-4">Penjualan Hari Ini</h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-500">Total Order</p>
                    <h3 class="text-xl font-bold">{{ $totalOrderToday }}</h3>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-500">Total Pendapatan</p>
                    <h3 class="text-xl font-bold">
                        Rp {{ number_format($totalRevenueToday, 0, ',', '.') }}
                    </h3>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-500">Dine In</p>
                    <h3 class="text-xl font-bold">{{ $dineInToday }}</h3>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-500">Take Away</p>
                    <h3 class="text-xl font-bold">{{ $takeAwayToday }}</h3>
                </div>
            </div>
        </div>

        <!-- =====================
                        GRAFIK PENDAPATAN
                    ====================== -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-xl shadow">
                <h2 class="font-bold mb-4">Pendapatan Bulanan</h2>
                <canvas id="monthlyChart"></canvas>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <h2 class="font-bold mb-4">Pendapatan 7 Hari Terakhir</h2>
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>

        <!-- =====================
                        BEST SELLER PER KATEGORI
                    ====================== -->
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="font-bold mb-6">Menu Best Seller per Kategori</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($bestSellerByCategory as $kategori => $menus)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold mb-3 capitalize">
                            {{ $kategori }} Best Seller
                        </h3>

                        @if ($menus->count())
                            <div class="relative h-64 w-full">
                                <canvas id="chart_{{ $kategori }}"></canvas>
                            </div>
                        @else
                            <div class="h-64 flex items-center justify-center">
                                <p class="text-sm text-gray-500 italic">Belum ada penjualan untuk kategori ini</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- =====================
                        HISTORY PENJUALAN
                    ====================== -->
        <div class="bg-white p-6 rounded-xl shadow">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-4">
                <h2 class="font-bold text-lg">History Penjualan</h2>

                <div class="flex flex-row items-center gap-3">
                    <a href="{{ route('export.history') }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm flex items-center shadow-sm transition whitespace-nowrap">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Excel
                    </a>

                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Cari pesanan..."
                            class="border border-gray-300 rounded-lg px-4 py-2 w-full md:w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full border" id="historyTable">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-3 py-2">Tanggal</th>
                            <th class="border px-3 py-2">Nomor Pesanan</th>
                            <th class="border px-3 py-2">Customer</th>
                            <th class="border px-3 py-2">Tipe</th>
                            <th class="border px-3 py-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($historyOrders as $order)
                            <tr>
                                <td class="border px-3 py-2">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}
                                </td>
                                <td class="border px-3 py-2 font-medium">{{ $order->order_number }}</td>
                                <td class="border px-3 py-2">{{ $order->customer_name }}</td>
                                <td class="border px-3 py-2">{{ strtoupper($order->order_type) }}</td>
                                <td class="border px-3 py-2 text-right">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- =====================
    CHART JS
    ====================== --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const monthlyData = @json($monthlyRevenue);
        const dailyData = @json($dailyRevenue);

        // BULANAN
        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: monthlyData.map(d => 'Bulan ' + d.bulan),
                datasets: [{
                    data: monthlyData.map(d => d.total),
                    backgroundColor: '#8B1C1C'
                }]
            },
            options: { plugins: { legend: { display: false } } }
        });

        // HARIAN (7 HARI)
        new Chart(document.getElementById('weeklyChart'), {
            type: 'line',
            data: {
                labels: dailyData.map(d =>
                    new Date(d.tanggal).toLocaleDateString('id-ID', {
                        day: '2-digit', month: 'short'
                    })
                ),
                datasets: [{
                    data: dailyData.map(d => d.total),
                    borderColor: '#1D4ED8',
                    backgroundColor: '#1D4ED8',
                    fill: false,
                    tension: 0.3,
                    pointRadius: 4
                }]
            },
            options: { plugins: { legend: { display: false } } }
        });

        //BEST SELLER
        const bestSellerData = @json($bestSellerByCategory);

        const warnaKategori = {
            makanan: '#16A34A',
            minuman: '#2563EB',
            snack: '#F59E0B',
            dessert: '#DB2777'
        };

        Object.keys(bestSellerData).forEach(kategori => {
            const menus = bestSellerData[kategori].slice(0, 5);

            // Pastikan elemen canvas ada di DOM sebelum inisialisasi
            const canvasElement = document.getElementById('chart_' + kategori);
            if (!menus.length || !canvasElement) return;

            new Chart(canvasElement, {
                type: 'bar',
                data: {
                    // PERBAIKAN: Gunakan nama_menu sesuai hasil query Controller terbaru
                    labels: menus.map(m => m.nama_menu),
                    datasets: [{
                        data: menus.map(m => m.total_qty),
                        backgroundColor: warnaKategori[kategori],
                        borderRadius: 5, // Opsional: agar tampilan bar lebih modern
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1 // Agar skala angka tidak desimal
                            }
                        }
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const table = document.getElementById('historyTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            searchInput.addEventListener('keyup', function () {
                const filter = searchInput.value.toLowerCase();

                for (let i = 0; i < rows.length; i++) {
                    const cells = rows[i].getElementsByTagName('td');
                    let found = false;

                    // Loop melalui setiap kolom di baris tersebut
                    for (let j = 0; j < cells.length; j++) {
                        const cellValue = cells[j].textContent || cells[j].innerText;
                        if (cellValue.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break; // Berhenti jika salah satu kolom cocok
                        }
                    }

                    // Tampilkan baris jika cocok, sembunyikan jika tidak
                    rows[i].style.display = found ? "" : "none";
                }
            });
        });
    </script>
@endsection