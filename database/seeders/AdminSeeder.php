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
            'alamat' => 'Jl. Contoh No. 123, Kota X',
        ]);
    
    }
}
