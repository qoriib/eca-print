<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriProduk;

class KategoriProdukController extends Controller
{
    public function index()
    {
        $kategori = KategoriProduk::withCount('produk')->latest()->paginate(10);
        return view('kategori_produk.index', compact('kategori'));
    }

    public function create()
    {
        return view('kategori_produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_produk,nama_kategori',
            'deskripsi' => 'nullable|string',
        ]);

        KategoriProduk::create($request->only('nama_kategori', 'deskripsi'));

        return redirect()->route('kategori-produk.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function show(KategoriProduk $kategoriProduk)
    {
        $kategoriProduk->load('produk');
        return view('kategori_produk.show', compact('kategoriProduk'));
    }

    public function edit(KategoriProduk $kategoriProduk)
    {
        return view('kategori_produk.edit', compact('kategoriProduk'));
    }

    public function update(Request $request, KategoriProduk $kategoriProduk)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_produk,nama_kategori,' . $kategoriProduk->id,
            'deskripsi' => 'nullable|string',
        ]);

        $kategoriProduk->update($request->only('nama_kategori', 'deskripsi'));

        return redirect()->route('kategori-produk.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(KategoriProduk $kategoriProduk)
    {
        if ($kategoriProduk->produk()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk.');
        }

        $kategoriProduk->delete();

        return redirect()->route('kategori-produk.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
