<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id_order';

    // karena tabel tidak pakai updated_at
    public $timestamps = false;

    protected $fillable = [
        'order_number',
        'customer_name',
        'order_type',
        'table_number',
        'payment_method',
        'total',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'id_order', 'id_order');
    }

    public function addons()
    {
        return $this->hasMany(OrderItemAddon::class, 'id_order_item', 'id');
    }
}
