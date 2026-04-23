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

// === DASHBOARD & SHARED ===
Route::middleware('auth')->group(function () {
    
    // Dashboard redirects are handled in AuthController, but we protect the routes here too
    Route::get('/dashboard/admin',     [DashboardController::class, 'admin'])->middleware('role:admin')->name('dashboard.admin');
    Route::get('/dashboard/operator',  [DashboardController::class, 'operator'])->middleware('role:operator,admin')->name('dashboard.operator');
    Route::get('/dashboard/pelanggan', [DashboardController::class, 'pelanggan'])->middleware('role:pelanggan')->name('dashboard.pelanggan');

    // === ADMIN ONLY ===
    Route::middleware('role:admin')->group(function () {
        Route::resource('users',           UserController::class);
        Route::resource('kategori-produk', KategoriProdukController::class);
        Route::post('pembayaran/{pembayaran}/konfirmasi', [PembayaranController::class, 'konfirmasi'])->name('pembayaran.konfirmasi');

    });

    // === OPERATOR & ADMIN ===
    Route::middleware('role:operator,admin')->group(function () {
        Route::resource('produksi', ProduksiController::class)->only(['index', 'show', 'update']);
        Route::post('produksi/{produksi}/ambil', [ProduksiController::class, 'ambilPekerjaan'])->name('produksi.ambil');
    });

    // === PRODUK (Semua bisa lihat, Admin bisa kelola) ===
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    
    Route::middleware('role:admin')->group(function () {
        Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');
        Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
        Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
        Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
        Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');
    });

    Route::get('/produk/{produk}', [ProdukController::class, 'show'])->name('produk.show');

    // === PESANAN (Pelanggan buat, Admin/Operator lihat) ===
    Route::resource('pesanan', PesananController::class);
    Route::post('pesanan/{pesanan}/batalkan', [PesananController::class, 'batalkan'])->name('pesanan.batalkan');

    // === DETAIL PESANAN ===
    Route::get( 'pesanan/{pesanan}/detail',             [DetailPesananController::class, 'index'])->name('detail-pesanan.index');
    Route::post('pesanan/{pesanan}/detail',             [DetailPesananController::class, 'store'])->name('detail-pesanan.store');
    Route::put( 'detail-pesanan/{detailPesanan}',       [DetailPesananController::class, 'update'])->name('detail-pesanan.update');
    Route::delete('detail-pesanan/{detailPesanan}',     [DetailPesananController::class, 'destroy'])->name('detail-pesanan.destroy');

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