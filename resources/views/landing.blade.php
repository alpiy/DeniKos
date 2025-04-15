@extends('app')

@section('content')
    <section class="text-center">
        <h1 class="text-4xl font-bold text-indigo-600">Selamat Datang di DeniKos</h1>
        <p class="mt-4 text-gray-700 text-lg">
            Temukan kos yang nyaman, aman, dan sesuai budgetmu hanya di DeniKos.
        </p>
        <a href="/kos" class="mt-6 inline-block bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 transition">
            Lihat Daftar Kos
        </a>
    </section>

    <section class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-semibold">Lokasi Strategis</h2>
            <p class="text-gray-600 mt-2">DeniKos berada di lokasi yang dekat dengan kampus dan pusat kota.</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-semibold">Fasilitas Lengkap</h2>
            <p class="text-gray-600 mt-2">Tersedia kamar mandi dalam, wifi, dapur, dan parkir motor.</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-semibold">Harga Terjangkau</h2>
            <p class="text-gray-600 mt-2">Mulai dari Rp500.000 per bulan. Cocok untuk pelajar dan mahasiswa.</p>
        </div>
    </section>
@endsection
