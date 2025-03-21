<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'idtransaksi';
    public $incrementing = true;

    protected $fillable = [
        'idpesanan',
        'total',
        'bayar',
        'iduser',
        'tanggal',
        'metode_pembayaran'
    ];

    // Relasi ke Pesanan
    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'idpesanan', 'idpesanan');
    }

    // Relasi ke User (kasir)
    public function kasir(): BelongsTo
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }

    // Kembalian (virtual)
    public function getKembalianAttribute()
    {
        return $this->Bayar - $this->Total;
    }
}
