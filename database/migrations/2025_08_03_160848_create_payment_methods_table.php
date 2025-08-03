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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'bank_transfer', 'qris', 'ewallet'
            $table->string('name'); // 'Bank BRI', 'QRIS DeniKos', 'Dana'
            $table->string('account_number')->nullable(); // Nomor rekening/akun
            $table->string('account_name')->nullable(); // Nama pemilik
            $table->text('instructions')->nullable(); // Instruksi khusus
            $table->string('logo_path')->nullable(); // Path logo bank/metode
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0); // Urutan tampil
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
