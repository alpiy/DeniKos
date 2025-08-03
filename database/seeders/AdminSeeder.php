<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat admin baru
        User::create([
            'name' => 'Admin DeniKos',
            'email' => 'admin@denikos.com',
            'password' => Hash::make('admin'), // pastikan menggunakan password yang aman
            'role' => 'admin', // menentukan role sebagai admin
            'no_hp' => '081234567890',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Contoh No. 123, Kota X',
            'email_verified_at' => now(), // Admin langsung verified
        ]);
        
         // User 1
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('poy123'),
            'no_hp' => '081234567891',
            'alamat' => 'Jl. Mawar No. 2',
            'jenis_kelamin' => 'Laki-laki',
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // User 2
        User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('poy123'),
            'no_hp' => '081234567892',
            'alamat' => 'Jl. Melati No. 3',
            'jenis_kelamin' => 'Laki-laki',
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
    
    }
}
