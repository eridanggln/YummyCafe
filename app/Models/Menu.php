<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'id_menu';

    public $timestamps = true; // ✅ WAJIB

    protected $fillable = [
        'id_kategori',
        'nama_menu',
        'harga_dasar',
        'deskripsi',
        'gambar',
        'status'
    ];
}
