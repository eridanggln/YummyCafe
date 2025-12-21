<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuOpsi extends Model
{
    protected $table = 'menu_opsi';
    protected $primaryKey = 'id_opsi';

    protected $fillable = [
        'id_menu',
        'nama_opsi',
        'tipe_opsi',
        'required'
    ];

    public function pilihan()
    {
        return $this->hasMany(MenuOpsiPilihan::class, 'id_opsi');
    }
}
