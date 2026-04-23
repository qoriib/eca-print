<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Eca Print',
            'email' => 'admin@ecaprint.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'no_telepon' => '081234567890',
            'alamat' => 'Jl. Percetakan No. 1, Jakarta',
        ]);

        // Operator
        User::create([
            'name' => 'Budi Operator',
            'email' => 'budi@ecaprint.com',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'no_telepon' => '081234567891',
            'alamat' => 'Jl. Produksi No. 2, Jakarta',
        ]);

        User::create([
            'name' => 'Siti Operator',
            'email' => 'siti@ecaprint.com',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'no_telepon' => '081234567892',
            'alamat' => 'Jl. Produksi No. 3, Jakarta',
        ]);

        // Pelanggan
        $pelanggan = [
            ['name' => 'Andi Pratama', 'email' => 'andi@gmail.com'],
            ['name' => 'Rina Wijaya', 'email' => 'rina@gmail.com'],
            ['name' => 'Dedi Kurniawan', 'email' => 'dedi@gmail.com'],
            ['name' => 'Maya Sari', 'email' => 'maya@gmail.com'],
            ['name' => 'Eko Susilo', 'email' => 'eko@gmail.com'],
        ];

        foreach ($pelanggan as $p) {
            User::create([
                'name' => $p['name'],
                'email' => $p['email'],
                'password' => Hash::make('password'),
                'role' => 'pelanggan',
                'no_telepon' => '0857' . rand(1111111, 9999999),
                'alamat' => 'Alamat ' . $p['name'],
            ]);
        }
    }
}
