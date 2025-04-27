<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meja extends Model
{
    use HasFactory;

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
