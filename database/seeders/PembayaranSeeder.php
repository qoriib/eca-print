<?php

namespace Database\Seeders;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PembayaranSeeder extends Seeder
{
    public function run(): void
    {
        $pesanan = Pesanan::whereIn('status', ['dikonfirmasi', 'dalam_produksi', 'selesai_produksi', 'selesai'])->get();
        $admin = User::where('role', 'admin')->first();

        foreach ($pesanan as $index => $p) {
            Pembayaran::create([
                'pesanan_id' => $p->id,
                'kode_pembayaran' => 'PAY-20260423-00' . ($index + 1),
                'jumlah_bayar' => $p->total_harga,
                'metode_pembayaran' => 'transfer',
                'jenis_pembayaran' => 'full',
                'status_konfirmasi' => 'dikonfirmasi',
                'tanggal_bayar' => $p->tanggal_pesan->addDay(),
                'dikonfirmasi_oleh' => $admin->id,
                'catatan' => 'Lunas untuk pesanan ' . $p->kode_pesanan,
            ]);
        }
    }
}
