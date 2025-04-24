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
        Schema::create('kos', function (Blueprint $table) {
            $table->id();
            $table->text('alamat');
            $table->integer('harga_bulanan');
            $table->enum('lantai', [2, 3]); // Lantai 2 atau 3
            $table->string('nomor_kamar')->unique(); // Penomoran kamar 1-12
            $table->text('deskripsi')->nullable();
            $table->json('fasilitas');
            $table->json('foto')->nullable();
            $table->string('denah_kamar')->nullable(); // Opsional untuk menyimpan URL gambar denah
            $table->enum('status_kamar', ['tersedia', 'terpesan'])->default('tersedia');
            $table->timestamps();
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kos');
    }
};
