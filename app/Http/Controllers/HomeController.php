<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\KategoriProduk;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('kategoriProduk')->where('is_aktif', true);

        if ($request->filled('kategori')) {
            $query->where('kategori_produk_id', $request->kategori);
        }

        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        $produk = $query->latest()->paginate(12);
        $categories = KategoriProduk::all();
        $pengaturan = Pengaturan::first();

        return view('home', compact('produk', 'categories', 'pengaturan'));
    }
}
