<?php

namespace Database\Seeders;

use App\Models\Produk;
use App\Models\KategoriProduk;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $brosur = KategoriProduk::where('nama_kategori', 'Brosur & Flier')->first()->id;
        $banner = KategoriProduk::where('nama_kategori', 'Banner & Spanduk')->first()->id;
        $kartu = KategoriProduk::where('nama_kategori', 'Kartu Nama')->first()->id;
        $stiker = KategoriProduk::where('nama_kategori', 'Stiker')->first()->id;

        $produk = [
            [
                'kategori_produk_id' => $brosur,
                'nama_produk' => 'Brosur A4 Art Paper 150gr',
                'deskripsi' => 'Cetak brosur full color 1 sisi, minimal 100 lembar.',
                'harga_satuan' => 2500,
                'satuan' => 'lembar'
            ],
            [
                'kategori_produk_id' => $brosur,
                'nama_produk' => 'Flier A5 HVS 80gr',
                'deskripsi' => 'Cetak flier ekonomis untuk promosi massal.',
                'harga_satuan' => 500,
                'satuan' => 'lembar'
            ],
            [
                'kategori_produk_id' => $banner,
                'nama_produk' => 'Spanduk Flexy 280gr',
                'deskripsi' => 'Spanduk standar outdoor, tahan cuaca.',
                'harga_satuan' => 25000,
                'satuan' => 'meter'
            ],
            [
                'kategori_produk_id' => $banner,
                'nama_produk' => 'X-Banner Standar',
                'deskripsi' => 'Termasuk rangka X dan cetak bahan albatros.',
                'harga_satuan' => 85000,
                'satuan' => 'set'
            ],
            [
                'kategori_produk_id' => $kartu,
                'nama_produk' => 'Kartu Nama Standar (Box)',
                'deskripsi' => 'Isi 100 kartu, bahan Art Carton 260gr.',
                'harga_satuan' => 35000,
                'satuan' => 'box'
            ],
            [
                'kategori_produk_id' => $kartu,
                'nama_produk' => 'Kartu Nama Premium Laminating',
                'deskripsi' => 'Isi 100 kartu, laminating doff/glossy.',
                'harga_satuan' => 55000,
                'satuan' => 'box'
            ],
            [
                'kategori_produk_id' => $stiker,
                'nama_produk' => 'Stiker Label Bontax A3+',
                'deskripsi' => 'Cetak stiker label per lembar A3+, sudah kiss-cut.',
                'harga_satuan' => 12000,
                'satuan' => 'lembar'
            ],
            [
                'kategori_produk_id' => $stiker,
                'nama_produk' => 'Stiker Vinyl Outdoor',
                'deskripsi' => 'Stiker tahan air dan matahari untuk branding kendaraan.',
                'harga_satuan' => 75000,
                'satuan' => 'meter'
            ],
        ];

        foreach ($produk as $p) {
            Produk::create(array_merge($p, ['is_aktif' => true]));
        }
    }
}
