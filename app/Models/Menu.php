<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'idmenu';
    public $incrementing = true;

    protected $fillable = [
        'nama_menu',
        'harga',
        'kategori',
        'status'
    ];

    // Relasi ke Pesanan
    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'idmenu', 'idmenu');
    }
}
