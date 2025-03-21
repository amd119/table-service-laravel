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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->integer('idpesanan')->autoIncrement();
            $table->integer('idmenu');
            $table->integer('idpelanggan');
            $table->integer('iduser');
            $table->integer('jumlah');
            // Kolom tambahan
            $table->integer('idmeja')->nullable();
            $table->timestamp('tanggal')->useCurrent();
            $table->enum('status', ['baru', 'diproses', 'selesai', 'dibayar'])->default('baru');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('idmenu')->references('idmenu')->on('menu');
            $table->foreign('idpelanggan')->references('idpelanggan')->on('pelanggan');
            $table->foreign('iduser')->references('iduser')->on('users');
            $table->foreign('idmeja')->references('idmeja')->on('meja');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
