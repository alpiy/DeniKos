<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\PemesananController as AdminPemesananController;
use App\Http\Controllers\Admin\KosController as AdminKosController;
use App\Http\Controllers\User\PemesananController as UserPemesananController;
use App\Http\Controllers\User\KosController as UserKosController;

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// ------------------ ROUTE UNTUK USER ------------------
Route::prefix('user')->name('user.')->group(function () {
    // List dan detail kos
    Route::get('/kos', [UserKosController::class, 'index'])->name('kos.index');
    Route::get('/kos/{id}', [UserKosController::class, 'show'])->name('kos.show');

    // Form pemesanan
    Route::get('/kos/{id}/pesan', [UserPemesananController::class, 'create'])->name('pesan.create');
    Route::post('/pesan', [UserPemesananController::class, 'store'])->name('pesan.store');

    // Daftar & riwayat pemesanan user
    Route::get('/pemesanan', [UserPemesananController::class, 'index'])->name('pemesanan.index'); // daftar
    Route::get('/riwayat', [UserPemesananController::class, 'riwayat'])->name('riwayat');
});

// ------------------ ROUTE UNTUK ADMIN ------------------
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

    // CRUD kos
    Route::get('/kos', [AdminKosController::class, 'index'])->name('kos.index');
    Route::get('/kos/create', [AdminKosController::class, 'create'])->name('kos.create');
    Route::post('/kos', [AdminKosController::class, 'store'])->name('kos.store');
    Route::get('/kos/{id}/edit', [AdminKosController::class, 'edit'])->name('kos.edit');
    Route::put('/kos/{id}', [AdminKosController::class, 'update'])->name('kos.update');
    Route::delete('/kos/{id}', [AdminKosController::class, 'destroy'])->name('kos.destroy');

    // Daftar pemesanan
    Route::get('/pemesanan', [AdminPemesananController::class, 'index'])->name('pemesanan.index');
});