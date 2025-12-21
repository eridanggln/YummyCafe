<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\Payment;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        // payment aktif
        $payments = Payment::where('is_active', 1)->get();

        // filter kategori
        $kategoriAktif = $request->get('kategori');

        if ($kategoriAktif) {
            $menus = Menu::where('status', 1)
                ->where('id_kategori', $kategoriAktif)
                ->get();
        } else {
            $menus = Menu::where('status', 1)->get();
        }

        return view('pesanan.index', [
            'payments'       => $payments,
            'menus'          => $menus,
            'kategoriAktif'  => $kategoriAktif,
        ]);
    }

    public function checkout()
    {
        $payments = Payment::where('is_active', 1)->get();
        return view('order.checkout', compact('payments'));
    }
}
