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
            'nama_kos' => 'Kos Deni Putra',
            'alamat' => 'Gang 19 No.05',
            'harga' => 750000,
            'deskripsi' => 'Kos nyaman dengan kamar mandi dalam, WiFi, dapur, dan parkiran. Cocok untuk pelajar dan mahasiswa.',
            'fasilitas' => ([
                'Kamar mandi dalam',
                'WiFi 24 jam',
                'Dapur bersama',
                'Parkir motor'
            ]),
            'foto' => 'https://via.placeholder.com/600x400'
        ]);
        
    }
}
