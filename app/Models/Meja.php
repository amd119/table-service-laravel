<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meja extends Model
{
    protected $table = 'meja';
    protected $primaryKey = 'idmeja';
    public $incrementing = true;

    protected $fillable = [
        'nomor',
        'kapasitas',
        'status'
    ];

    // Relasi ke Pesanan
    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'idmeja', 'idmeja');
    }
}
