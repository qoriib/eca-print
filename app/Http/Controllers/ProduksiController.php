<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use App\Models\Pesanan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProduksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Produksi::with('pesanan.user', 'operator');

        // Operator hanya lihat antrian yang relevan
        if (Auth::user()->role === 'operator') {
            $query->where(function ($q) {
                $q->where('operator_id', Auth::id())
                    ->orWhere('status_produksi', 'antrian');
            });
        }

        if ($request->filled('status')) {
            $query->where('status_produksi', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $produksi = $query->latest()->paginate(15);
        $total_produksi = (clone $query)->count();

        return view('produksi.index', compact('produksi', 'total_produksi'));
    }

    public function show(Produksi $produksi)
    {
        $produksi->load('pesanan.user', 'pesanan.detailPesanan.produk', 'operator');
        return view('produksi.show', compact('produksi'));
    }

    public function update(Request $request, Produksi $produksi)
    {
        $request->validate([
            'status_produksi' => 'required|in:antrian,desain,cetak,finishing,selesai',
            'operator_id' => 'nullable|exists:users,id',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'catatan_produksi' => 'nullable|string',
        ]);

        $data = $request->only(
            'status_produksi',
            'operator_id',
            'tanggal_mulai',
            'tanggal_selesai',
            'catatan_produksi'
        );

        // Otomatis set tanggal mulai saat mulai proses (desain)
        if (in_array($request->status_produksi, ['desain', 'cetak']) && !$produksi->tanggal_mulai) {
            $data['tanggal_mulai'] = today();
        }

        // Otomatis set tanggal selesai & update status pesanan
        if ($request->status_produksi === 'selesai') {
            $data['tanggal_selesai'] = $data['tanggal_selesai'] ?? today();

            $produksi->pesanan->update(['status' => 'selesai_produksi']);

            Notifikasi::create([
                'user_id' => $produksi->pesanan->user_id,
                'judul' => 'Produksi Selesai',
                'pesan' => "Pesanan {$produksi->pesanan->kode_pesanan} telah selesai diproduksi dan sedang disiapkan.",
                'tipe' => 'sukses',
            ]);
        }

        $produksi->update($data);

        return redirect()->route('produksi.show', $produksi)
            ->with('success', 'Status produksi berhasil diperbarui.');
    }

    public function ambilPekerjaan(Produksi $produksi)
    {
        if ($produksi->status_produksi !== 'antrian') {
            return back()->with('error', 'Pekerjaan ini sudah diambil oleh operator lain.');
        }

        $produksi->update([
            'operator_id' => Auth::id(),
            'status_produksi' => 'desain',
            'tanggal_mulai' => today(),
        ]);

        $produksi->pesanan->update(['status' => 'dalam_produksi']);

        return redirect()->route('produksi.show', $produksi)
            ->with('success', 'Pekerjaan berhasil diambil. Selamat bekerja!');
    }
}
