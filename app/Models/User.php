<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'id_user';

    // ⛔ MATIKAN TIMESTAMP
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'created_at',
    ];

    protected $hidden = [
        'password',
    ];
}
