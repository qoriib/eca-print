<?php

namespace Database\Seeders;

use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotifikasiSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            Notifikasi::create([
                'user_id' => $user->id,
                'judul' => 'Selamat Datang!',
                'pesan' => 'Selamat bergabung di aplikasi Eca-Print, ' . $user->name,
                'tipe' => 'info',
                'is_read' => false,
            ]);

            Notifikasi::create([
                'user_id' => $user->id,
                'judul' => 'Info Sistem',
                'pesan' => 'Pastikan profil Anda sudah diperbarui dengan benar.',
                'tipe' => 'sukses',
                'is_read' => true,
            ]);
        }
    }
}
