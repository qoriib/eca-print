<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DetailPesananController extends Controller
{
    public function index(Pesanan $pesanan)
    {
        $detail = $pesanan->detailPesanan()->with('produk')->get();
        return view('detail_pesanan.index', compact('pesanan', 'detail'));
    }

    public function store(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'produk_id'   => 'required|exists:produk,id',
            'jumlah'      => 'required|integer|min:1',
            'ukuran'      => 'nullable|string|max:50',
            'bahan'       => 'nullable|string|max:100',
            'finishing'   => 'nullable|string|max:100',
            'keterangan'  => 'nullable|string',
            'file_desain' => 'nullable|file|mimes:jpg,jpeg,png,pdf,ai,cdr|max:10240',
        ]);

        $produk   = Produk::findOrFail($request->produk_id);
        $subtotal = $produk->harga_satuan * $request->jumlah;

        $fileDesain = null;
        if ($request->hasFile('file_desain')) {
            $fileDesain = $request->file('file_desain')->store('desain/' . $pesanan->id, 'public');
        }

        $pesanan->detailPesanan()->create([
            'produk_id'    => $request->produk_id,
            'jumlah'       => $request->jumlah,
            'ukuran'       => $request->ukuran,
            'bahan'        => $request->bahan,
            'finishing'    => $request->finishing,
            'harga_satuan' => $produk->harga_satuan,
            'subtotal'     => $subtotal,
            'file_desain'  => $fileDesain,
            'keterangan'   => $request->keterangan,
        ]);

        // Update total harga pesanan
        $pesanan->update([
            'total_harga' => $pesanan->detailPesanan()->sum('subtotal'),
        ]);

        return back()->with('success', 'Item pesanan berhasil ditambahkan.');
    }

    public function update(Request $request, DetailPesanan $detailPesanan)
    {
        $request->validate([
            'jumlah'      => 'required|integer|min:1',
            'ukuran'      => 'nullable|string|max:50',
            'bahan'       => 'nullable|string|max:100',
            'finishing'   => 'nullable|string|max:100',
            'keterangan'  => 'nullable|string',
            'file_desain' => 'nullable|file|mimes:jpg,jpeg,png,pdf,ai,cdr|max:10240',
        ]);

        $subtotal = $detailPesanan->harga_satuan * $request->jumlah;

        $data = $request->only('jumlah', 'ukuran', 'bahan', 'finishing', 'keterangan');
        $data['subtotal'] = $subtotal;

        if ($request->hasFile('file_desain')) {
            if ($detailPesanan->file_desain) {
                Storage::disk('public')->delete($detailPesanan->file_desain);
            }
            $data['file_desain'] = $request->file('file_desain')
                ->store('desain/' . $detailPesanan->pesanan_id, 'public');
        }

        $detailPesanan->update($data);

        // Update total harga pesanan
        $detailPesanan->pesanan->update([
            'total_harga' => $detailPesanan->pesanan->detailPesanan()->sum('subtotal'),
        ]);

        return back()->with('success', 'Item pesanan berhasil diperbarui.');
    }

    public function destroy(DetailPesanan $detailPesanan)
    {
        $pesanan = $detailPesanan->pesanan;

        if ($pesanan->detailPesanan()->count() <= 1) {
            return back()->with('error', 'Pesanan harus memiliki minimal 1 item.');
        }

        if ($detailPesanan->file_desain) {
            Storage::disk('public')->delete($detailPesanan->file_desain);
        }

        $detailPesanan->delete();

        $pesanan->update([
            'total_harga' => $pesanan->detailPesanan()->sum('subtotal'),
        ]);

        return back()->with('success', 'Item berhasil dihapus dari pesanan.');
    }
}
