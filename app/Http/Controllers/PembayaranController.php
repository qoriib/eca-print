<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with('pesanan.user');

        if (Auth::user()->role === 'pelanggan') {
            $query->whereHas('pesanan', fn($q) => $q->where('user_id', Auth::id()));
        }

        if ($request->filled('status')) {
            $query->where('status_konfirmasi', $request->status);
        }

        $pembayaran = $query->latest()->paginate(15);

        return view('pembayaran.index', compact('pembayaran'));
    }

    public function create(Pesanan $pesanan)
    {
        // Pastikan pesanan milik pelanggan yg login
        if (Auth::user()->role === 'pelanggan' && $pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        return view('pembayaran.create', compact('pesanan'));
    }

    public function store(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'jenis_pembayaran' => 'required|in:dp,pelunasan,full',
            'metode_pembayaran' => 'required|in:transfer,tunai,qris',
            'tanggal_bayar' => 'required|date',
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'catatan' => 'nullable|string',
        ]);

        $kodePembayaran = 'PAY-' . date('Ymd') . '-' . str_pad(
            Pembayaran::whereDate('created_at', today())->count() + 1,
            3,
            '0',
            STR_PAD_LEFT
        );

        $data = $request->only(
            'jumlah_bayar',
            'jenis_pembayaran',
            'metode_pembayaran',
            'tanggal_bayar',
            'catatan'
        );
        $data['pesanan_id'] = $pesanan->id;
        $data['kode_pembayaran'] = $kodePembayaran;
        $data['status_konfirmasi'] = 'menunggu';

        if ($request->hasFile('bukti_pembayaran')) {
            $data['bukti_pembayaran'] = $request->file('bukti_pembayaran')
                ->store('pembayaran/' . $pesanan->id, 'public');
        }

        Pembayaran::create($data);

        // Notifikasi ke admin
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notifikasi::create([
                'user_id' => $admin->id,
                'judul' => 'Bukti Pembayaran Baru',
                'pesan' => "Pembayaran {$kodePembayaran} untuk pesanan {$pesanan->kode_pesanan} menunggu konfirmasi.",
                'tipe' => 'info',
            ]);
        }

        return redirect()->route('pesanan.show', $pesanan)
            ->with('success', 'Bukti pembayaran berhasil dikirim, menunggu konfirmasi admin.');
    }

    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load('pesanan.user', 'dikonfirmasiOleh');
        return view('pembayaran.show', compact('pembayaran'));
    }

    public function konfirmasi(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'status_konfirmasi' => 'required|in:dikonfirmasi,ditolak',
            'catatan' => 'nullable|string',
        ]);

        $pembayaran->update([
            'status_konfirmasi' => $request->status_konfirmasi,
            'catatan' => $request->catatan,
            'dikonfirmasi_oleh' => Auth::id(),
        ]);

        // Update status pembayaran pesanan
        if ($request->status_konfirmasi === 'dikonfirmasi') {
            $pesanan = $pembayaran->pesanan;
            $totalDibayar = $pesanan->pembayaran()
                ->where('status_konfirmasi', 'dikonfirmasi')
                ->sum('jumlah_bayar');

            $statusPembayaran = $totalDibayar >= $pesanan->total_harga ? 'lunas' : 'dp';
            $pesanan->update(['status_pembayaran' => $statusPembayaran]);

            Notifikasi::create([
                'user_id' => $pesanan->user_id,
                'judul' => 'Pembayaran Dikonfirmasi',
                'pesan' => "Pembayaran {$pembayaran->kode_pembayaran} telah dikonfirmasi. Status: {$statusPembayaran}.",
                'tipe' => 'sukses',
            ]);
        } else {
            Notifikasi::create([
                'user_id' => $pembayaran->pesanan->user_id,
                'judul' => 'Pembayaran Ditolak',
                'pesan' => "Pembayaran {$pembayaran->kode_pembayaran} ditolak. Silakan periksa kembali bukti pembayaran.",
                'tipe' => 'peringatan',
            ]);
        }

        return redirect()->route('pembayaran.show', $pembayaran)
            ->with('success', 'Konfirmasi pembayaran berhasil disimpan.');
    }

    public function destroy(Pembayaran $pembayaran)
    {
        if ($pembayaran->status_konfirmasi === 'dikonfirmasi') {
            return back()->with('error', 'Pembayaran yang sudah dikonfirmasi tidak dapat dihapus.');
        }

        if ($pembayaran->bukti_pembayaran) {
            Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
        }

        $pembayaran->delete();

        return back()->with('success', 'Data pembayaran berhasil dihapus.');
    }
}
