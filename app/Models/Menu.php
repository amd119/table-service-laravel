<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';
    protected $primaryKey = 'idmenu';
    public $incrementing = true;

    protected $fillable = [
        'nama_menu',
        'harga',
        'gambar',
        'status'
    ];

    // Relasi ke Pesanan
    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'idmenu', 'idmenu');
    }
}
