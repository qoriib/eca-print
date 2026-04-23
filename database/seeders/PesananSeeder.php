<?php

namespace Database\Seeders;

use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PesananSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'pelanggan')->take(5)->get();
        
        $statuses = ['menunggu_konfirmasi', 'dikonfirmasi', 'dalam_produksi', 'selesai_produksi', 'selesai'];

        foreach ($users as $index => $user) {
            Pesanan::create([
                'kode_pesanan' => 'ECA-20260423-00' . ($index + 1),
                'user_id' => $user->id,
                'tanggal_pesan' => now()->subDays(5 - $index),
                'tanggal_deadline' => now()->addDays(2 + $index),
                'status' => $statuses[$index],
                'total_harga' => 150000 + ($index * 50000),
                'catatan_pelanggan' => 'Catatan pesanan untuk ' . $user->name,
            ]);
        }
    }
}
