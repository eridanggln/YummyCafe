<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /* =============================
       LIST MENU PER KATEGORI
    ============================== */
    public function indexMakanan()
    {
        return $this->indexByKategori(1, 'menu.makanan');
    }

    public function indexMinuman()
    {
        return $this->indexByKategori(2, 'menu.minuman');
    }

    public function indexSnack()
    {
        return $this->indexByKategori(3, 'menu.snack');
    }

    public function indexDessert()
    {
        return $this->indexByKategori(4, 'menu.dessert');
    }

    private function indexByKategori($kategori, $view)
    {
        $menus = Menu::where('id_kategori', $kategori)
            ->orderBy('created_at', 'desc')
            ->get();

        return view($view, compact('menus'));
    }

    /* =============================
       FORM TAMBAH MENU
    ============================== */
    public function create($kategori)
    {
        return match ((int) $kategori) {
            1 => view('menu.createMakanan', ['id_kategori' => 1]),
            2 => view('menu.createMinuman', ['id_kategori' => 2]),
            3 => view('menu.createSnack', ['id_kategori' => 3]),
            4 => view('menu.createDessert', ['id_kategori' => 4]),
            default => abort(404)
        };
    }

    /* =============================
       SIMPAN MENU (SEMUA KATEGORI)
    ============================== */
    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:150',
            'harga'        => 'required|numeric',
            'gambar'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'deskripsi'    => 'nullable|string',
            'id_kategori'  => 'required|in:1,2,3,4',
        ]);

        $path = $request->hasFile('gambar')
            ? $request->file('gambar')->store('menu', 'public')
            : 'menu/default.jpg';

        Menu::create([
            'id_kategori' => $request->id_kategori,
            'nama_menu'   => $request->nama_menu,
            'harga_dasar' => $request->harga,
            'deskripsi'   => $request->deskripsi,
            'gambar'      => $path,
            'status'      => 1,
        ]);

        return redirect($this->redirectByKategori($request->id_kategori))
            ->with('success', 'Menu berhasil ditambahkan');
    }

    private function redirectByKategori($kategori)
    {
        return match ((int) $kategori) {
            1 => route('menu.makanan'),
            2 => route('menu.minuman'),
            3 => route('menu.snack'),
            4 => route('menu.dessert'),
        };
    }

    /* =============================
       TOGGLE STATUS
    ============================== */
    public function toggleStatus($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->status = !$menu->status;
        $menu->save();

        return back()->with('success', 'Status menu berhasil diubah');
    }

    
}

