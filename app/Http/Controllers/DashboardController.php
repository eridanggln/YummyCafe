<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        /* =====================
         * PENJUALAN HARI INI
         * ===================== */
        $todayOrders = DB::table('orders')
            ->whereDate('created_at', $today);

        $totalOrderToday = $todayOrders->count();
        $totalRevenueToday = $todayOrders->sum('total');
        $dineInToday = DB::table('orders')
            ->whereDate('created_at', $today)
            ->where('order_type', 'dine_in')
            ->count();
        $takeAwayToday = DB::table('orders')
            ->whereDate('created_at', $today)
            ->where('order_type', 'take_away')
            ->count();

        /* =====================
         * GRAFIK BULANAN
         * ===================== */
        $monthlyRevenue = DB::table('orders')
            ->select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('SUM(total) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        $dailyRevenue = DB::table('orders')
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('SUM(total) as total')
            )
            ->whereBetween('created_at', [
                Carbon::now()->subDays(6)->startOfDay(),
                Carbon::now()->endOfDay()
            ])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal')
            ->get();

        /* =====================
         * BEST SELLER
         * ===================== */
        // Di Controller Anda
        $rawBestSeller = DB::table('order_items')
            ->join('menu', 'order_items.menu_id', '=', 'menu.id_menu')
            ->select(
                'menu.id_kategori',
                'menu.nama_menu',
                DB::raw('SUM(order_items.qty) as total_qty')
            )
            ->groupBy('menu.id_kategori', 'menu.nama_menu')
            ->orderByDesc('total_qty')
            ->get()
            ->groupBy('id_kategori');

        $kategoriMap = [
            1 => 'makanan',
            2 => 'minuman',
            3 => 'snack',
            4 => 'dessert',
        ];

        $bestSellerByCategory = collect($kategoriMap)->mapWithKeys(function ($nama, $id) use ($rawBestSeller) {
            // Menggunakan get($id) dan default collect() agar key selalu ada di JSON
            return [
                $nama => $rawBestSeller->get($id) ?? collect([])
            ];
        });

        /* =====================
         * HISTORY PENJUALAN
         * ===================== */
        $historyOrders = DB::table('orders')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.index', [
            'totalOrderToday' => $totalOrderToday,
            'totalRevenueToday' => $totalRevenueToday,
            'dineInToday' => $dineInToday,
            'takeAwayToday' => $takeAwayToday,
            'monthlyRevenue' => $monthlyRevenue,
            'dailyRevenue' => $dailyRevenue,
            'bestSellerByCategory' => $bestSellerByCategory,
            'historyOrders' => $historyOrders,
        ]);
    }

    /* Tambahkan ini di dalam DashboardController */

}