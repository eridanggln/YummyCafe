<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemAddon;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exports\HistoryExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // VALIDASI DASAR
            if (empty($request->cart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart kosong'
                ], 422);
            }

            // HITUNG TOTAL
            $total = collect($request->cart)->sum('total');

            // SIMPAN ORDER
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'customer_name' => $request->customer_name,
                'order_type' => $request->order_type,
                'table_number' => $request->table_number,
                'payment_method' => $request->payment_method,
                'total' => $total,
                'created_at' => Carbon::now()
            ]);

            // SIMPAN ITEM
            foreach ($request->cart as $item) {

                $orderItem = OrderItem::create([
                    'id_order' => $order->id_order,
                    'menu_id' => $item['menu_id'],
                    'menu_name' => $item['nama'],
                    'qty' => $item['qty'],
                    'price' => $item['harga_dasar'] ?? $item['harga_menu'] ?? $item['harga'] ?? 0,
                    'subtotal' => $item['total']
                ]);

                // RADIO ADDON (1 pilihan)
                if (!empty($item['radioAddon'])) {
                    OrderItemAddon::create([
                        'id_order_item' => $orderItem->id,
                        'addon_name' => $item['radioAddon']['nama'],
                        'price' => $item['radioAddon']['harga'],
                        'qty' => 1
                    ]);
                }

                // CHECKBOX ADDON (banyak)
                foreach ($item['checkboxAddons'] ?? [] as $addon) {
                    OrderItemAddon::create([
                        'id_order_item' => $orderItem->id,
                        'addon_name' => $addon['nama'],
                        'price' => $addon['harga'],
                        'qty' => $addon['qty']
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'order_number' => $order->order_number
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function generateOrderNumber()
    {
        $today = now()->format('Ymd');

        $countToday = DB::table('orders')
            ->whereDate('created_at', now())
            ->count();

        return 'ORD-' . $today . '-' . str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);
    }

    public function exportExcel()
    {
        return Excel::download(new HistoryExport, 'history-penjualan-' . date('Y-m-d') . '.xlsx');
    }

    public function printStruk($order_number)
    {
        $order = Order::with(['items.addons'])
            ->where('order_number', $order_number)
            ->firstOrFail();

        return view('pesanan.print', compact('order'));
    }
}
