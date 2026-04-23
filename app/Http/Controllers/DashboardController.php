<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Produksi;
use App\Models\User;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        $data = [
            'total_pesanan' => Pesanan::count(),
            'pesanan_baru' => Pesanan::where('status', 'menunggu_konfirmasi')->count(),
            'pesanan_dalam_produksi' => Pesanan::where('status', 'dalam_produksi')->count(),
            'pesanan_selesai' => Pesanan::where('status', 'selesai')->count(),
            'total_pelanggan' => User::where('role', 'pelanggan')->count(),
            'pembayaran_menunggu' => Pembayaran::where('status_konfirmasi', 'menunggu')->count(),
            'pesanan_terbaru' => Pesanan::with('user')
                ->latest()
                ->take(5)
                ->get(),
            'pembayaran_terbaru' => Pembayaran::with('pesanan.user')
                ->where('status_konfirmasi', 'menunggu')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('dashboard.admin', compact('data'));
    }

    public function operator()
    {
        $data = [
            'antrian_produksi' => Produksi::with('pesanan')
                ->where('status_produksi', 'antrian')
                ->count(),
            'sedang_proses' => Produksi::with('pesanan')
                ->where('status_produksi', 'proses')
                ->where('operator_id', Auth::id())
                ->count(),
            'selesai_hari_ini' => Produksi::whereDate('tanggal_selesai', today())
                ->where('status_produksi', 'selesai')
                ->count(),
            'daftar_antrian' => Produksi::with('pesanan.user', 'pesanan.detailPesanan.produk')
                ->whereIn('status_produksi', ['antrian', 'proses'])
                ->latest()
                ->take(10)
                ->get(),
        ];

        return view('dashboard.operator', compact('data'));
    }

    public function pelanggan()
    {
        $user = Auth::user();

        $data = [
            'total_pesanan' => Pesanan::where('user_id', $user->id)->count(),
            'pesanan_aktif' => Pesanan::where('user_id', $user->id)
                ->whereNotIn('status', ['selesai', 'dibatalkan'])
                ->count(),
            'pesanan_selesai' => Pesanan::where('user_id', $user->id)
                ->where('status', 'selesai')
                ->count(),
            'pesanan_terbaru' => Pesanan::where('user_id', $user->id)
                ->with('detailPesanan.produk')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('dashboard.pelanggan', compact('data'));
    }
}
