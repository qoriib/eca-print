<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriProdukController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\DetailPesananController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\NotifikasiController;

// === AUTH ===
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// === DASHBOARD ===
Route::middleware('auth')->group(function () {
    Route::get('/dashboard/admin',     [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/dashboard/operator',  [DashboardController::class, 'operator'])->name('dashboard.operator');
    Route::get('/dashboard/pelanggan', [DashboardController::class, 'pelanggan'])->name('dashboard.pelanggan');

    // === ADMIN ONLY ===
    Route::middleware('role:admin')->group(function () {
        Route::resource('users',           UserController::class);
        Route::resource('kategori-produk', KategoriProdukController::class);
        Route::post('pembayaran/{pembayaran}/konfirmasi', [PembayaranController::class, 'konfirmasi'])->name('pembayaran.konfirmasi');
        Route::patch('pesanan/{pesanan}/update-status',   [PesananController::class, 'update'])->name('pesanan.update');
    });

    // === PRODUK (admin & operator bisa lihat) ===
    Route::resource('produk', ProdukController::class);

    // === PESANAN ===
    Route::resource('pesanan', PesananController::class);
    Route::post('pesanan/{pesanan}/batalkan', [PesananController::class, 'batalkan'])->name('pesanan.batalkan');

    // === DETAIL PESANAN ===
    Route::get( 'pesanan/{pesanan}/detail',             [DetailPesananController::class, 'index'])->name('detail-pesanan.index');
    Route::post('pesanan/{pesanan}/detail',             [DetailPesananController::class, 'store'])->name('detail-pesanan.store');
    Route::put( 'detail-pesanan/{detailPesanan}',       [DetailPesananController::class, 'update'])->name('detail-pesanan.update');
    Route::delete('detail-pesanan/{detailPesanan}',     [DetailPesananController::class, 'destroy'])->name('detail-pesanan.destroy');

    // === PRODUKSI ===
    Route::resource('produksi', ProduksiController::class)->only(['index', 'show', 'update']);
    Route::post('produksi/{produksi}/ambil', [ProduksiController::class, 'ambilPekerjaan'])->name('produksi.ambil');

    // === PEMBAYARAN ===
    Route::resource('pembayaran', PembayaranController::class)->except(['edit', 'update']);
    Route::get('pesanan/{pesanan}/pembayaran/create', [PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('pesanan/{pesanan}/pembayaran',       [PembayaranController::class, 'store'])->name('pembayaran.store');

    // === NOTIFIKASI ===
    Route::get( 'notifikasi',                          [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('notifikasi/{notifikasi}/read',        [NotifikasiController::class, 'markAsRead'])->name('notifikasi.read');
    Route::post('notifikasi/read-all',                 [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.read-all');
    Route::delete('notifikasi/{notifikasi}',           [NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');
    Route::get( 'notifikasi/unread-count',             [NotifikasiController::class, 'unreadCount'])->name('notifikasi.unread-count');
});