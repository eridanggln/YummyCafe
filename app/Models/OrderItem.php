<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'id';

    public $timestamps = false; // ⬅️ WAJIB

    protected $fillable = [
        'id_order',
        'menu_id',
        'menu_name',
        'qty',
        'price',
        'subtotal',
    ];

    public function addons()
    {
        // Menghubungkan Item ke tabel Addon
        return $this->hasMany(OrderItemAddon::class, 'id_order_item', 'id');
    }
}
