<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('kategoriProduk');

        if ($request->filled('kategori')) {
            $query->where('kategori_produk_id', $request->kategori);
        }

        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        $produk = $query->latest()->paginate(12);
        $kategori = KategoriProduk::all();

        return view('produk.index', compact('produk', 'kategori'));
    }

    public function create()
    {
        $kategori = KategoriProduk::all();
        return view('produk.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_produk_id' => 'required|exists:kategori_produk,id',
            'nama_produk' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'harga_satuan' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:30',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_aktif' => 'boolean',
        ]);

        $data = $request->except('gambar');
        $data['is_aktif'] = $request->boolean('is_aktif', true);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create($data);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Produk $produk)
    {
        $produk->load('kategoriProduk');
        return view('produk.show', compact('produk'));
    }

    public function edit(Produk $produk)
    {
        $kategori = KategoriProduk::all();
        return view('produk.edit', compact('produk', 'kategori'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'kategori_produk_id' => 'required|exists:kategori_produk,id',
            'nama_produk' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'harga_satuan' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:30',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_aktif' => 'boolean',
        ]);

        $data = $request->except('gambar');
        $data['is_aktif'] = $request->boolean('is_aktif', true);

        if ($request->hasFile('gambar')) {
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        if ($produk->detailPesanan()->exists()) {
            return back()->with('error', 'Produk tidak dapat dihapus karena sudah digunakan dalam pesanan.');
        }

        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
