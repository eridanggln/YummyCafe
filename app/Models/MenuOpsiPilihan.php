<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuOpsiPilihan extends Model
{
    protected $table = 'menu_opsi_detail';
    protected $primaryKey = 'id_opsi_detail';

    protected $fillable = [
        'id_kategori',
        'id_menu',
        'id_opsi',
        'nama_pilihan',
        'harga_tambah',
        'status'
    ];
}
