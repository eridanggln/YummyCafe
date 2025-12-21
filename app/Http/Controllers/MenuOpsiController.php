<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuOpsi;
use App\Models\MenuOpsiPilihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MenuOpsiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_menu' => 'required|integer|exists:menu,id_menu',
            'nama_opsi' => 'required|string|max:255',
            'tipe_opsi' => 'required|in:radio,checkbox',
            'required' => 'nullable|boolean',
            'pilihan' => 'required|array|min:1',
            'pilihan.*.nama' => 'required|string|max:255',
            'pilihan.*.harga' => 'required|numeric|min:0' // Ubah dari nullable menjadi required
        ], [
            // Pesan error kustom (Opsional)
            'nama_opsi.required' => 'Nama Add On wajib diisi!',
            'pilihan.*.nama.required' => 'Nama pilihan tidak boleh kosong!',
            'pilihan.*.harga.required' => 'Harga wajib diisi (isi 0 jika gratis)!',
        ]);

        DB::transaction(function () use ($request) {

            // 🔎 Ambil menu (untuk id_kategori)
            $menu = Menu::findOrFail($request->id_menu);

            // 1️⃣ SIMPAN OPSI
            $opsi = MenuOpsi::create([
                'id_menu' => $menu->id_menu,
                'nama_opsi' => $request->nama_opsi,
                'tipe_opsi' => $request->tipe_opsi,
                'required' => $request->required ?? 0
            ]);

            // 2️⃣ SIMPAN PILIHAN
            foreach ($request->pilihan as $item) {
                MenuOpsiPilihan::create([
                    'id_kategori' => $menu->id_kategori,
                    'id_menu' => $menu->id_menu,
                    'id_opsi' => $opsi->id_opsi,
                    'nama_pilihan' => $item['nama'],
                    'harga_tambah' => $item['harga'] ?? 0
                ]);
            }
        });

        return back()->with('success', 'Add On berhasil disimpan');
    }

    public function getByMenu($id)
    {
        $opsi = MenuOpsi::with('pilihan')
            ->where('id_menu', $id)
            ->get();

        return response()->json($opsi);
    }

    public function togglePilihan($id)
    {
        $pilihan = MenuOpsiPilihan::findOrFail($id);

        // toggle
        $pilihan->status = $pilihan->status == 1 ? 0 : 1;
        $pilihan->save();

        return response()->json([
            'status' => $pilihan->status
        ]);
    }

    public function update(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // 1. Update Data Menu Utama
                $menu = Menu::findOrFail($request->id_menu);
                $menu->update([
                    'nama_menu' => $request->nama_menu,
                    'harga_dasar' => $request->harga_dasar,
                    'deskripsi' => $request->deskripsi,
                ]);

                // 2. Logika Gambar (Hapus/Update)
                if ($request->hapus_gambar == 1) {
                    if ($menu->gambar && $menu->gambar !== 'default.jpg') {
                        Storage::disk('public')->delete($menu->gambar);
                    }
                    $menu->gambar = 'default.jpg';
                }
                if ($request->hasFile('gambar')) {
                    if ($menu->gambar && $menu->gambar !== 'default.jpg') {
                        Storage::disk('public')->delete($menu->gambar);
                    }
                    $menu->gambar = $request->file('gambar')->store('menu', 'public');
                }
                $menu->save();

                // 3. Update Add On & Pilihan
                if ($request->has('opsi')) {
                    foreach ($request->opsi as $id_opsi => $opsiData) {
                        $opsi = MenuOpsi::findOrFail($id_opsi);
                        $opsi->update([
                            'nama_opsi' => $opsiData['nama_opsi'],
                            'tipe_opsi' => $opsiData['tipe_opsi'],
                        ]);

                        if (isset($opsiData['pilihan'])) {
                            foreach ($opsiData['pilihan'] as $p) {
                                if (!empty($p['id_opsi_detail'])) {
                                    // Update pilihan yang sudah ada (termasuk status)
                                    MenuOpsiPilihan::where('id_opsi_detail', $p['id_opsi_detail'])
                                        ->update([
                                            'nama_pilihan' => $p['nama'],
                                            'harga_tambah' => $p['harga'] ?? 0,
                                            'status' => $p['status'] ?? 1,
                                        ]);
                                } else {
                                    // Insert pilihan baru
                                    MenuOpsiPilihan::create([
                                        'id_opsi' => $opsi->id_opsi,
                                        'id_menu' => $menu->id_menu,
                                        'id_kategori' => $menu->id_kategori,
                                        'nama_pilihan' => $p['nama'],
                                        'harga_tambah' => $p['harga'] ?? 0,
                                        'status' => 1,
                                    ]);
                                }
                            }
                        }
                    }
                }
            });

            return back()->with('success', 'Menu berhasil diperbarui');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

}
