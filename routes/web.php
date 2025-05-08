<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\PemesananController as AdminPemesananController;
use App\Http\Controllers\Admin\KosController as AdminKosController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\User\PemesananController as UserPemesananController;
use App\Http\Controllers\User\KosController as UserKosController;
use App\Http\Controllers\User\AuthController;

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// ------------------ ROUTE UNTUK AUTH ------------------
Route::prefix('auth')->name('auth.')->group(function () {
    // Register
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ------------------ ROUTE UNTUK USER ------------------
Route::prefix('user')->name('user.')->group(function () {
    // Profile
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    // List dan detail kos
    Route::get('/kos', [UserKosController::class, 'index'])->name('kos.index');
    Route::get('/kos/{id}', [UserKosController::class, 'show'])->name('kos.show');

    // Form pemesanan
    Route::get('/kos/{id}/pesan', [UserPemesananController::class, 'create'])->name('pesan.create');
    Route::post('/pesan', [UserPemesananController::class, 'store'])->name('pesan.store');

    // Daftar & riwayat pemesanan user
    Route::get('/pemesanan', [UserPemesananController::class, 'index'])->name('pemesanan.index'); // daftar
    Route::get('/riwayat', [UserPemesananController::class, 'riwayat'])->name('riwayat');
    Route::get('/pesan/success/{id}', [UserPemesananController::class, 'success'])->name('pesan.success');
});

// ------------------ ROUTE UNTUK ADMIN ------------------
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD kos
    Route::resource('/kos', AdminKosController::class)->names('kos');

    // Daftar pemesanan
    Route::resource('/pemesanan', AdminPemesananController::class)->names('pemesanan')->except(['create','edit', 'store']);
    Route::get('/pemesanan/{id}/detail', [AdminPemesananController::class, 'show'])->name('pemesanan.show');
    Route::post('/pemesanan/{id}/approve', [AdminPemesananController::class, 'approve'])->name('pemesanan.approve');
    Route::post('/pemesanan/{id}/reject', [AdminPemesananController::class, 'reject'])->name('pemesanan.reject');
});