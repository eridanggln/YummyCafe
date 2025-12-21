<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Struk - {{ $order->order_number }}</title>
    <style>
        @page {
            size: 58mm auto;
            margin: 0;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            width: 58mm;
            margin: 0 auto;
            padding: 10px;
            font-size: 12px;
            line-height: 1.3;
            background-color: #f3f4f6;
        }

        .ticket {
            background-color: white;
            padding: 5mm;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .text-center {
            text-align: center;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .flex {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .addon {
            font-size: 11px;
            padding-left: 12px;
            font-style: italic;
            color: #333;
        }
    </style>
</head>

<body>

    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px;">Klik Cetak</button>
    </div>

    <div class="ticket">
        <div class="text-center">
            <strong>CAFE YUMMY</strong><br>
            Jl. Soekarno Hatta No. 123<br>
            Telp: 0812-3456-7890
        </div>

        <div class="divider"></div>

        <div style="font-size: 11px;">
            No: {{ $order->order_number }}<br>
            Tgl: {{ $order->created_at->format('d/m/Y H:i') }}<br>
            Cust: {{ $order->customer_name }}
        </div>

        <div class="divider"></div>

        <div class="divider"></div>

        @foreach($order->items as $item)
            <div class="item-list" style="margin-bottom: 8px;">
                {{-- 1. Baris Menu Utama --}}
                <div class="flex">
                    @php
                        $hargaDasarMenu = $item->price * $item->qty;
                        $selisihAddon = $item->subtotal - $hargaDasarMenu;

                        // Menghapus nama addon dari nama menu agar tidak double
                        $namaMenuBersih = str_ireplace('Sambal Korek', '', $item->menu_name);
                    @endphp
                    <span style="flex: 1; font-weight: bold;">{{ $item->qty }}x {{ trim($namaMenuBersih) }}</span>
                    <span style="margin-left: 10px;">{{ number_format($hargaDasarMenu, 0, ',', '.') }}</span>
                </div>

                {{-- 2. Baris Add-on (Menampilkan Jumlah/Qty Add-on) --}}
                @if($selisihAddon > 0)
                    <div class="flex addon" style="display: flex; justify-content: space-between; margin-top: 1px;">
                        <span style="flex: 1; padding-left: 12px; font-style: italic;">
                            + Sambal Korek
                            {{-- Logika untuk mengambil Qty Add-on --}}
                            @php
                                // Jika relasi database ada, ambil qty asli. 
                                // Jika tidak, kita asumsikan qty berdasarkan harga satuan (misal harga sambal 6.000)
                                $qtyAddon = ($item->addons && $item->addons->first()) ? $item->addons->first()->qty : 1;
                            @endphp
                            <small>({{ $qtyAddon }}x)</small>
                        </span>
                        <span style="font-style: normal;">{{ number_format($selisihAddon, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>
        @endforeach

        <div class="divider"></div>

        <div class="flex" style="font-weight: bold; font-size: 14px;">
            <span>TOTAL</span>
            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
        </div>

        <div class="divider"></div>

        <div class="text-center" style="margin-top: 15px; font-size: 10px;">
            Terima Kasih Atas Kunjungan Anda!
        </div>
    </div>

    <script>
        // Menggunakan JavaScript untuk memastikan window print terbuka otomatis
        window.onload = function () {
            // Jika Anda ingin menggunakan JavaScript untuk menarik data addon yang hilang, 
            // Anda bisa melakukan fetch ke API di sini. 
            // Namun untuk saat ini, pastikan relasi 'with' di controller sudah benar.

            setTimeout(function () {
                window.print();
            }, 800);
        };
    </script>
</body>

</html>