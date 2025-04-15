@extends('app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Foto Kos --}}
        <img src="https://via.placeholder.com/600x400" alt="Foto Kos" class="w-full h-auto rounded-xl shadow-md">

        {{-- Info Kos --}}
        <div>
            <h1 class="text-3xl font-bold text-indigo-600">Kos Deni Putra</h1>
            <p class="text-gray-600 mt-2">Jl. Contoh No. 5, Bandung</p>
            <p class="text-gray-800 font-semibold mt-4">Rp750.000 / bulan</p>

            <div class="mt-6">
                <h2 class="text-xl font-semibold">Deskripsi</h2>
                <p class="text-gray-700 mt-2">
                    Kos nyaman dengan kamar mandi dalam, WiFi, dapur, dan parkiran. Cocok untuk pelajar dan mahasiswa.
                </p>
            </div>

            <div class="mt-6">
                <h2 class="text-xl font-semibold">Fasilitas</h2>
                <ul class="list-disc list-inside text-gray-700 mt-2">
                    <li>Kamar mandi dalam</li>
                    <li>WiFi 24 jam</li>
                    <li>Dapur bersama</li>
                    <li>Parkir motor</li>
                </ul>
            </div>

            <a href="#" class="inline-block mt-8 bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 transition">
                Pesan Sekarang
            </a>
        </div>
    </div>
@endsection
