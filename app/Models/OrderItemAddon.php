<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemAddon extends Model
{
    protected $table = 'order_item_addons';
    protected $primaryKey = 'id';

    public $timestamps = false;
    protected $fillable = [
        'id_order_item',
        'addon_name',
        'price',
        'qty',
    ];
}
