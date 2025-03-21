<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'idpesanan';
    public $incrementing = true;

    protected $fillable = [
        'idmenu',
        'idpelanggan',
        'Jumlah',
        'iduser',
        'idmeja',
        'tanggal',
        'status'
    ];

    // Relasi ke Menu
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'idmenu', 'idmenu');
    }

    // Relasi ke Pelanggan
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'idpelanggan', 'idpelanggan');
    }

    // Relasi ke User (waiter)
    public function waiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }

    // Relasi ke Meja
    public function meja(): BelongsTo
    {
        return $this->belongsTo(Meja::class, 'idmeja', 'idmeja');
    }

    // Relasi ke Transaksi
    public function transaksi(): HasOne
    {
        return $this->hasOne(Transaksi::class, 'idpesanan', 'idpesanan');
    }
}
