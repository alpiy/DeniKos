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
        Kos::create([
            'alamat' => 'Jl. Melati No. 10',
            'harga_bulanan' => 750000,
            'lantai' => 2,
            'nomor_kamar' => '201',
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
            'denah_kamar' => 'https://via.placeholder.com/300x200',
            'status_kamar' => 'tersedia',
        ]);
        Kos::create([
            'alamat' => 'Jl. Melati No. 10',
            'harga_bulanan' => 750000,
            'lantai' => 2,
            'nomor_kamar' => '202',
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
            'denah_kamar' => 'https://via.placeholder.com/300x200',
            'status_kamar' => 'tersedia',
        ]);
        Kos::create([
            'alamat' => 'Jl. Melati No. 10',
            'harga_bulanan' => 750000,
            'lantai' => 2,
            'nomor_kamar' => '203',
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
            'denah_kamar' => 'https://via.placeholder.com/300x200',
            'status_kamar' => 'tersedia',
        ]);
        Kos::create([
            'alamat' => 'Jl. Melati No. 10',
            'harga_bulanan' => 750000,
            'lantai' => 2,
            'nomor_kamar' => '204',
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
            'denah_kamar' => 'https://via.placeholder.com/300x200',
            'status_kamar' => 'tersedia',
        ]);
        Kos::create([
            'alamat' => 'Jl. Melati No. 10',
            'harga_bulanan' => 750000,
            'lantai' => 2,
            'nomor_kamar' => '205',
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
            'denah_kamar' => 'https://via.placeholder.com/300x200',
            'status_kamar' => 'tersedia',
        ]);
        Kos::create([
            'alamat' => 'Jl. Melati No. 10',
            'harga_bulanan' => 750000,
            'lantai' => 2,
            'nomor_kamar' => '206',
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
            'denah_kamar' => 'https://via.placeholder.com/300x200',
            'status_kamar' => 'tersedia',
        ]);
        
    }
}
