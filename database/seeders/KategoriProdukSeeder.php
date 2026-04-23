<?php

namespace Database\Seeders;

use App\Models\KategoriProduk;
use Illuminate\Database\Seeder;

class KategoriProdukSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            ['nama' => 'Brosur & Flier', 'desk' => 'Segala jenis media promosi cetak lembaran.'],
            ['nama' => 'Banner & Spanduk', 'desk' => 'Cetak outdoor dan indoor ukuran besar.'],
            ['nama' => 'Kartu Nama', 'desk' => 'Identitas profesional dengan berbagai bahan.'],
            ['nama' => 'Stiker', 'desk' => 'Stiker label, cutting, dan branding.'],
            ['nama' => 'Dokumen & Jilid', 'desk' => 'Fotocopy, print warna, dan penjilidan.'],
        ];

        foreach ($kategori as $k) {
            KategoriProduk::create([
                'nama_kategori' => $k['nama'],
                'deskripsi' => $k['desk'],
            ]);
        }
    }
}
