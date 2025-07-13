<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\PemesananController as AdminPemesananController;
use App\Http\Controllers\Admin\KosController as AdminKosController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PenyewaController as AdminPenyewaController;
use App\Http\Controllers\Admin\LaporanSewaController as AdminLaporanSewaController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\User\PemesananController as UserPemesananController;
use App\Http\Controllers\User\KosController as UserKosController;
use App\Http\Controllers\User\ForgotPasswordController;
use App\Http\Controllers\User\AuthController;

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// ------------------ ROUTE UNTUK AUTH ------------------
Route::prefix('auth')->name('auth.')->group(function () {
    // Register
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form')->middleware('guest');
    Route::post('/register', [AuthController::class, 'register'])->name('register')->middleware('guest');
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form')->middleware('guest');
    Route::post('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
    // Resend verification dari login page
    Route::post('/resend-verification', [AuthController::class, 'resendVerificationFromLogin'])->name('resend-verification')->middleware('guest');
    
    // Forgot Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request')->middleware('guest');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('guest');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset')->middleware('guest');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update')->middleware('guest');
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Email Verification Routes (tanpa middleware auth untuk verify)
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/email/verify', [AuthController::class, 'emailVerificationNotice'])
        ->middleware('auth')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'emailVerificationVerify'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [AuthController::class, 'emailVerificationResend'])
        ->middleware('auth')->name('verification.send');
});

// Admin Auth (Terpisah)
Route::prefix('admin/auth')->name('admin.auth.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post')->middleware('guest');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// Daftar & detail kos BISA diakses guest
Route::get('/kos', [UserKosController::class, 'index'])->name('user.kos.index');
Route::get('/kos/{id}', [UserKosController::class, 'show'])->name('user.kos.show');

// ------------------ ROUTE UNTUK USER ------------------
Route::prefix('user')->name('user.')->middleware(['role:user', 'verified'])->group(function () {
    // Profile
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    // // List dan detail kos
    // Route::get('/kos', [UserKosController::class, 'index'])->name('kos.index');
    // Route::get('/kos/{id}', [UserKosController::class, 'show'])->name('kos.show');

    // Form pemesanan
    Route::get('/kos/{id}/pesan', [UserPemesananController::class, 'create'])->name('pesan.create');
    Route::post('/pesan', [UserPemesananController::class, 'store'])->name('pesan.store');

    // Daftar & riwayat pemesanan user
    Route::get('/riwayat', [UserPemesananController::class, 'index'])->name('riwayat'); // daftar
   
    Route::get('/pesan/success/{id}', [UserPemesananController::class, 'success'])->name('pesan.success');
    // Perpanjang sewa
    Route::get('/pemesanan/{id}/perpanjang', [UserPemesananController::class, 'perpanjangForm'])->name('pesan.perpanjang');
    Route::post('/pemesanan/{id}/perpanjang', [UserPemesananController::class, 'perpanjangStore'])->name('pesan.perpanjang.store');
    Route::get('/pesan/perpanjang/success/{id}', [UserPemesananController::class, 'perpanjangSuccess'])->name('pesan.perpanjang.success');
    // Pembatalan pemesanan
    Route::post('/pemesanan/{id}/batal', [UserPemesananController::class, 'batal'])->name('pesan.batal');
    // Upload pelunasan pembayaran
    Route::post('/pemesanan/{id}/pelunasan', [UserPemesananController::class, 'pelunasan'])->name('pembayaran.pelunasan');

    // Route untuk download tanda terima pembayaran
    Route::get('/pemesanan/{id}/receipt', [UserPemesananController::class, 'downloadReceipt'])->name('pemesanan.downloadReceipt');
});

// ------------------ ROUTE UNTUK ADMIN ------------------
Route::prefix('admin')->name('admin.')->middleware(['role:admin', 'prevent.back'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD kos
    Route::resource('/kos', AdminKosController::class)->names('kos');

    // Daftar pemesanan
    Route::resource('/pemesanan', AdminPemesananController::class)->names('pemesanan')->except(['create','edit', 'store']);
    Route::get('/pemesanan/{id}/detail', [AdminPemesananController::class, 'show'])->name('pemesanan.show');
    Route::post('/pemesanan/{id}/approve', [AdminPemesananController::class, 'approve'])->name('pemesanan.approve');
    Route::post('/pemesanan/{id}/reject', [AdminPemesananController::class, 'reject'])->name('pemesanan.reject');
    Route::post('/pemesanan/{id}/refund', [AdminPemesananController::class, 'refund'])->name('pemesanan.refund');
    // Route::get('/perpanjang', [AdminPemesananController::class, 'perpanjangIndex'])->name('pemesanan.perpanjang');

    //data penyewa
    Route::get('/penyewa', [AdminPenyewaController::class, 'index'])->name('penyewa.index');
    Route::post('/penyewa/{id}/selesai', [AdminPenyewaController::class, 'markAsCompleted'])->name('penyewa.complete');

    //laporanSewa
    Route::get('/laporan-sewa', [AdminLaporanSewaController::class, 'index'])->name('laporan.index');
    Route::get('/laporan-sewa/export-excel', [AdminLaporanSewaController::class, 'exportExcel'])->name('laporan.exportExcel');
    Route::get('/laporan-sewa/export-pdf', [AdminLaporanSewaController::class, 'exportPDF'])->name('laporan.exportPDF');

    // Verifikasi Pembayaran
    Route::post('/pembayaran/{id}/verifikasi', [AdminPemesananController::class, 'verifikasiPembayaran'])->name('pembayaran.verifikasi');
});