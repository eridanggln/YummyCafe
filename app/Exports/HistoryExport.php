<?php

namespace App\Exports;

use App\Models\Order; // Pastikan ini sesuai nama Model Anda
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class HistoryExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Mengambil semua data histori penjualan
        return Order::latest()->get();
    }

    // Mengatur Judul Kolom di Excel
    public function headings(): array
    {
        return [
            'Tanggal',
            'Nomor Pesanan',
            'Customer',
            'Tipe',
            'Total'
        ];
    }

    // Memetakan data dari database ke kolom Excel
    public function map($order): array
    {
        return [
            \Carbon\Carbon::parse($order->created_at)->format('d-m-Y'),
            $order->order_number,
            $order->customer_name,
            strtoupper($order->order_type),
            $order->total, // Anda bisa menambah number_format jika perlu
        ];
    }
}