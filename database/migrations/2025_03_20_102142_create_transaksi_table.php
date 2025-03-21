<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->integer('idtransaksi')->autoIncrement();
            $table->integer('idpesanan');
            $table->integer('total');
            $table->integer('bayar');
            // Kolom tambahan
            $table->integer('iduser')->nullable(); // Kasir yang memproses
            $table->timestamp('tanggal')->useCurrent();
            $table->enum('metode_pembayaran', ['tunai', 'kartu', 'e-wallet'])->default('tunai');
            $table->integer('kembalian')->virtualAs('bayar - total');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('idpesanan')->references('idpesanan')->on('pesanan');
            $table->foreign('iduser')->references('iduser')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
