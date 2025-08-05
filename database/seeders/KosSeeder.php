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
            // Variasi foto untuk kamar lantai 2
            $fotoLantai2 = [
                'kos/KamarLantai2' . $i . '.jpeg', // Foto spesifik per kamar
                'kos/KamarLantai2(1).jpeg',       // Foto sample lantai 2
            ];
            
            Kos::create([
                'alamat' => 'Jl. Laksda Adi Sucipto, Gg. 19, Pandanwangi, Blimbing, Kota Malang, Jawa Timur', // Alamat DeniKos yang real
                'harga_bulanan' => 450000,
                'lantai' => '2',
                'nomor_kamar' => $i,
                'deskripsi' => 'Kamar nyaman di lantai 2 dengan fasilitas lengkap, cocok untuk mahasiswa. Dekat dengan area umum dan akses mudah ke kampus.',
                'fasilitas' => [
                    'WiFi',
                    'Lemari',
                    'Kasur',
                    'Bantal',
                    'Guling',
                    'Dapur bersama',
                    'Kamar mandi luar',
                ],
                'foto' => $fotoLantai2,
                'status_kamar' =>'tersedia',
                'luas_kamar' => '2x3',
            ]);
        }
        
        // Kamar 7-12 di lantai 3, harga 350000
        for ($i = 7; $i <= 12; $i++) {
            // Variasi foto untuk kamar lantai 3
            $fotoLantai3 = [
                'kos/kamarLantai3' . $i . '.jpeg', // Foto spesifik per kamar
                'kos/kamarLantai3(1).jpeg',       // Foto sample lantai 3
            ];
            
            Kos::create([
                'alamat' => 'Jl. Laksda Adi Sucipto, Gg. 19, Pandanwangi, Blimbing, Kota Malang, Jawa Timur', // Alamat DeniKos yang real
                'harga_bulanan' => 350000,
                'lantai' => '3',
                'nomor_kamar' => $i,
                'deskripsi' => 'Kamar nyaman di lantai 3 dengan view yang bagus, cocok untuk mahasiswa. Suasana tenang dan sejuk, view ke taman.',
                'fasilitas' => [
                    'WiFi',
                    'Lemari',
                    'Kasur',
                    'Bantal',
                    'Guling',
                    'Dapur bersama',
                    'Kamar mandi luar',
                ],
                'foto' => $fotoLantai3,
                'status_kamar' => 'tersedia',
                'luas_kamar' => '2x3',
            ]);
        }
    }
}
