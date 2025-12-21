<?php

namespace App\Http\Controllers;

// Import Model Order di bagian atas
use App\Models\Order; 
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
{
    // Pastikan nama relasi 'items' dan 'items.addons' sesuai dengan yang ada di Model
    $historyOrders = Order::with(['items', 'items.addons']) 
        ->orderBy('created_at', 'desc')
        ->get();

    return view('pesanan.riwayat', compact('historyOrders'));
}
}