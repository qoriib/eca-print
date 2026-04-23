<?php

namespace Database\Seeders;

use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Database\Seeder;

class DetailPesananSeeder extends Seeder
{
    public function run(): void
    {
        $pesanan = Pesanan::all();
        $produk = Produk::all();

        foreach ($pesanan as $p) {
            $prod = $produk->random();
            $jumlah = rand(1, 10);
            $subtotal = $prod->harga_satuan * $jumlah;

            DetailPesanan::create([
                'pesanan_id' => $p->id,
                'produk_id' => $prod->id,
                'jumlah' => $jumlah,
                'ukuran' => 'Standar',
                'bahan' => 'Bahan ' . $prod->nama_produk,
                'harga_satuan' => $prod->harga_satuan,
                'subtotal' => $subtotal,
            ]);
            
            // Update total harga di pesanan
            $p->update(['total_harga' => $subtotal]);
        }
    }
}
