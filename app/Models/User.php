<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'iduser';
    public $incrementing = true;
    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    // Relasi ke Pesanan (user sebagai waiter)
    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'iduser', 'iduser');
    }

    // Relasi ke Transaksi (user sebagai kasir)
    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'iduser', 'iduser');
    }
}
