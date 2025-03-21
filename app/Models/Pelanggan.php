<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $primaryKey = 'idpelanggan';
    public $incrementing = true;

    protected $fillable = [
        'nama_pelanggan',
        'jenis_kelamin',
        'no_hp',
        'alamat'
    ];

    // Relasi ke Pesanan
    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'idpelanggan', 'idpelanggan');
    }
}
