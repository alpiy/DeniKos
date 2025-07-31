<?php

namespace Database\Seeders;

use App\Models\Kos;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kamar 1-6 di lantai 2, harga 450000
        for ($i = 1; $i <= 6; $i++) {
            Kos::create([
                'alamat' => 'Jl. Melati No. 10',
                'harga_bulanan' => 450000,
                'lantai' => 2,
                'nomor_kamar' => $i, // Integer langsung
                'deskripsi' => 'Kamar nyaman dengan fasilitas lengkap, cocok untuk mahasiswa.',
                'fasilitas' => [
                    'Kamar mandi dalam',
                    'WiFi 24 jam',
                    'Dapur bersama',
                    'Parkir motor'
                ],
                'foto' => [
                    'https://via.placeholder.com/600x400',
                    'https://via.placeholder.com/600x401'
                ],               
                'status_kamar' => 'tersedia',
                'luas_kamar' => '2x3',
            ]);
        }
        // Kamar 7-12 di lantai 3, harga 350000
        for ($i = 7; $i <= 12; $i++) {
            Kos::create([
                'alamat' => 'Jl. Melati No. 10',
                'harga_bulanan' => 350000,
                'lantai' => 3,
                'nomor_kamar' => $i, // Integer langsung
                'deskripsi' => 'Kamar nyaman dengan fasilitas lengkap, cocok untuk mahasiswa.',
                'fasilitas' => [
                    'Kamar mandi dalam',
                    'WiFi 24 jam',
                    'Dapur bersama',
                    'Parkir motor'
                ],
                'foto' => [
                    'https://via.placeholder.com/600x400',
                    'https://via.placeholder.com/600x401'
                ],
                'status_kamar' => 'tersedia',
                'luas_kamar' => '2x3',
            ]);
        }
    }
}
