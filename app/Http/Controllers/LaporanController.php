<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Produksi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function pesanan(Request $request)
    {
        $query = Pesanan::with('user');

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_pesan', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_pesan', '<=', $request->end_date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $laporan = $query->latest()->paginate(20);
        
        return view('laporan.pesanan', compact('laporan'));
    }

    public function pembayaran(Request $request)
    {
        $query = Pembayaran::with('pesanan.user');

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_bayar', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_bayar', '<=', $request->end_date);
        }
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        $laporan = $query->latest()->paginate(20);
        
        return view('laporan.pembayaran', compact('laporan'));
    }

    public function produksi(Request $request)
    {
        $query = Produksi::with('pesanan.user', 'operator');

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_mulai', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_mulai', '<=', $request->end_date);
        }
        if ($request->filled('status')) {
            $query->where('status_produksi', $request->status);
        }

        $laporan = $query->latest()->paginate(20);
        
        return view('laporan.produksi', compact('laporan'));
    }
}
