<?php

namespace Database\Seeders;

use App\Models\Produksi;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProduksiSeeder extends Seeder
{
    public function run(): void
    {
        $pesanan = Pesanan::all();
        $operators = User::where('role', 'operator')->get();

        foreach ($pesanan as $index => $p) {
            $status = 'antrian';
            if ($p->status === 'dalam_produksi') $status = 'proses';
            if ($p->status === 'selesai_produksi' || $p->status === 'selesai') $status = 'selesai';

            Produksi::create([
                'pesanan_id' => $p->id,
                'operator_id' => $status !== 'antrian' ? $operators->random()->id : null,
                'status_produksi' => $status,
                'tanggal_mulai' => $status !== 'antrian' ? now()->subDays(1) : null,
                'tanggal_selesai' => $status === 'selesai' ? now() : null,
                'catatan_produksi' => 'Catatan produksi untuk pesanan ' . $p->kode_pesanan,
            ]);
        }
    }
}
