<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Produksi;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::with('user');

        // Pelanggan hanya melihat pesanannya sendiri
        if (Auth::user()->role === 'pelanggan') {
            $query->where('user_id', Auth::id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('kode_pesanan', 'like', '%' . $request->search . '%');
        }

        $pesanan = $query->latest()->paginate(15);

        return view('pesanan.index', compact('pesanan'));
    }

    public function create()
    {
        $produk = Produk::where('is_aktif', true)->with('kategoriProduk')->get();
        return view('pesanan.create', compact('produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_deadline'           => 'nullable|date|after_or_equal:today',
            'catatan_pelanggan'          => 'nullable|string',
            'items'                      => 'required|array|min:1',
            'items.*.produk_id'          => 'required|exists:produk,id',
            'items.*.jumlah'             => 'required|integer|min:1',
            'items.*.ukuran'             => 'nullable|string|max:50',
            'items.*.bahan'              => 'nullable|string|max:100',
            'items.*.finishing'          => 'nullable|string|max:100',
            'items.*.keterangan'         => 'nullable|string',
            'items.*.file_desain'        => 'nullable|file|mimes:jpg,jpeg,png,pdf,ai,cdr|max:10240',
        ]);

        DB::beginTransaction();

        try {
            $kodePesanan = 'ECA-' . date('Ymd') . '-' . str_pad(
                Pesanan::whereDate('created_at', today())->count() + 1,
                3, '0', STR_PAD_LEFT
            );

            $pesanan = Pesanan::create([
                'kode_pesanan'      => $kodePesanan,
                'user_id'           => Auth::id(),
                'tanggal_pesan'     => today(),
                'tanggal_deadline'  => $request->tanggal_deadline,
                'status'            => 'menunggu_konfirmasi',
                'catatan_pelanggan' => $request->catatan_pelanggan,
                'total_harga'       => 0,
            ]);

            $totalHarga = 0;

            foreach ($request->items as $index => $item) {
                $produk      = Produk::findOrFail($item['produk_id']);
                $subtotal    = $produk->harga_satuan * $item['jumlah'];
                $totalHarga += $subtotal;

                $fileDesain = null;
                if (isset($item['file_desain']) && $item['file_desain'] instanceof \Illuminate\Http\UploadedFile) {
                    $fileDesain = $item['file_desain']->store('desain/' . $pesanan->id, 'public');
                }

                $pesanan->detailPesanan()->create([
                    'produk_id'    => $item['produk_id'],
                    'jumlah'       => $item['jumlah'],
                    'ukuran'       => $item['ukuran'] ?? null,
                    'bahan'        => $item['bahan'] ?? null,
                    'finishing'    => $item['finishing'] ?? null,
                    'harga_satuan' => $produk->harga_satuan,
                    'subtotal'     => $subtotal,
                    'file_desain'  => $fileDesain,
                    'keterangan'   => $item['keterangan'] ?? null,
                ]);
            }

            $pesanan->update(['total_harga' => $totalHarga]);

            // Buat entri produksi awal
            Produksi::create([
                'pesanan_id'       => $pesanan->id,
                'status_produksi'  => 'antrian',
            ]);

            // Notifikasi ke admin
            $this->notifikasiAdmin(
                'Pesanan Baru Masuk',
                "Pesanan {$kodePesanan} dari " . Auth::user()->name . " menunggu konfirmasi."
            );

            DB::commit();

            return redirect()->route('pesanan.show', $pesanan)
                             ->with('success', "Pesanan {$kodePesanan} berhasil dibuat!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Pesanan $pesanan)
    {
        // Pastikan pelanggan hanya bisa lihat pesanannya sendiri
        if (Auth::user()->role === 'pelanggan' && $pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        $pesanan->load('user', 'detailPesanan.produk', 'produksi.operator', 'pembayaran');

        return view('pesanan.show', compact('pesanan'));
    }

    public function edit(Pesanan $pesanan)
    {
        // Hanya boleh edit jika masih menunggu konfirmasi
        if ($pesanan->status !== 'menunggu_konfirmasi') {
            return back()->with('error', 'Pesanan tidak dapat diubah karena sudah diproses.');
        }

        $produk = Produk::where('is_aktif', true)->get();

        return view('pesanan.edit', compact('pesanan', 'produk'));
    }

    public function update(Request $request, Pesanan $pesanan)
    {
        // Hanya admin yang bisa update status
        if (Auth::user()->role === 'admin') {
            $request->validate([
                'status'          => 'required|in:menunggu_konfirmasi,dikonfirmasi,dalam_produksi,selesai_produksi,siap_diambil,selesai,dibatalkan',
                'catatan_admin'   => 'nullable|string',
                'tanggal_deadline'=> 'nullable|date',
            ]);

            $pesanan->update($request->only('status', 'catatan_admin', 'tanggal_deadline'));

            // Notifikasi ke pelanggan saat status berubah
            Notifikasi::create([
                'user_id' => $pesanan->user_id,
                'judul'   => 'Status Pesanan Diperbarui',
                'pesan'   => "Pesanan {$pesanan->kode_pesanan} kini berstatus: " . ucwords(str_replace('_', ' ', $pesanan->status)),
                'tipe'    => 'info',
            ]);

            return redirect()->route('pesanan.show', $pesanan)
                             ->with('success', 'Status pesanan berhasil diperbarui.');
        }

        abort(403);
    }

    public function destroy(Pesanan $pesanan)
    {
        if (!in_array($pesanan->status, ['menunggu_konfirmasi', 'dibatalkan'])) {
            return back()->with('error', 'Pesanan yang sudah diproses tidak dapat dihapus.');
        }

        $pesanan->delete();

        return redirect()->route('pesanan.index')
                         ->with('success', 'Pesanan berhasil dihapus.');
    }

    public function batalkan(Request $request, Pesanan $pesanan)
    {
        if (!in_array($pesanan->status, ['menunggu_konfirmasi', 'dikonfirmasi'])) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan pada status ini.');
        }

        $pesanan->update(['status' => 'dibatalkan']);

        Notifikasi::create([
            'user_id' => $pesanan->user_id,
            'judul'   => 'Pesanan Dibatalkan',
            'pesan'   => "Pesanan {$pesanan->kode_pesanan} telah dibatalkan.",
            'tipe'    => 'peringatan',
        ]);

        return redirect()->route('pesanan.index')
                         ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    private function notifikasiAdmin(string $judul, string $pesan): void
    {
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notifikasi::create([
                'user_id' => $admin->id,
                'judul'   => $judul,
                'pesan'   => $pesan,
                'tipe'    => 'info',
            ]);
        }
    }
}
