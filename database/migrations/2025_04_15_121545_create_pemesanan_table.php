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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kos_id')->constrained('kos')->onDelete('cascade');
             $table->boolean('is_perpanjangan')->default(false);
            $table->date('tanggal_pesan');
            $table->date('tanggal_masuk')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->integer('lama_sewa');
            $table->integer('total_pembayaran');
            $table->enum('status_pemesanan', ['pending', 'diterima', 'ditolak','batal'])->default('pending');
            $table->enum('status_refund', ['belum', 'proses', 'selesai'])->default('belum');
            
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
