@extends('app')

@section('content')
    {{-- Hero Section --}}
    <section class="text-center py-16 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl shadow-lg">
        <h1 class="text-5xl font-extrabold mb-4">Selamat Datang di <span class="text-yellow-300">DeniKos</span></h1>
        <p class="text-lg md:text-xl max-w-xl mx-auto">Temukan kos yang nyaman, aman, dan sesuai budgetmu hanya di DeniKos.</p>
        <a href="{{ route('user.kos.index') }}" class="mt-8 inline-block bg-yellow-400 text-indigo-900 font-semibold px-8 py-3 rounded-xl hover:bg-yellow-300 transition">
            Lihat Daftar Kos
        </a>
    </section>

    {{-- Feature Section --}}
    <section class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition">
            <div class="flex items-center space-x-4">
                <div class="bg-indigo-100 p-3 rounded-full text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 12.414M6.343 7.343l4.243 4.243m6.364 0a8 8 0 11-11.314-11.314 8 8 0 0111.314 11.314z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-indigo-600">Lokasi Strategis</h2>
            </div>
            <p class="text-gray-600 mt-4">DeniKos berada di lokasi yang dekat dengan kampus dan pusat kota.</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition">
            <div class="flex items-center space-x-4">
                <div class="bg-indigo-100 p-3 rounded-full text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9.75 17l.97-4.243a1 1 0 00-.212-.96l-3.536-3.536a1 1 0 011.414-1.414l3.536 3.536a1 1 0 00.96.212L17 9.75m0 0V7m0 2.75L7 17"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-indigo-600">Fasilitas Lengkap</h2>
            </div>
            <p class="text-gray-600 mt-4">Tersedia kamar mandi dalam, wifi, dapur, dan parkir motor.</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition">
            <div class="flex items-center space-x-4">
                <div class="bg-indigo-100 p-3 rounded-full text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c1.657 0 3 .895 3 2v6a3 3 0 01-6 0v-6c0-1.105 1.343-2 3-2z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-indigo-600">Harga Terjangkau</h2>
            </div>
            <p class="text-gray-600 mt-4">Mulai dari Rp500.000 per bulan. Cocok untuk pelajar dan mahasiswa.</p>
        </div>
    </section>
@endsection
