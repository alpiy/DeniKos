@extends('app')

@section('content')
    {{-- Hero Section --}}
    <section class="relative text-center py-28 md:py-40 bg-gray-900 text-white rounded-2xl shadow-2xl overflow-hidden">
        {{-- Swiper Background --}}
        <div class="absolute inset-0 z-0">
            <div class="swiper bgSwiper h-full w-full">
                <div class="swiper-wrapper">
                    @if(!empty($backgroundFotos) && is_array($backgroundFotos))
                        @foreach ($backgroundFotos as $foto)
                            <div class="swiper-slide">
                                <img src="{{ asset($foto) }}" class="w-full h-full object-cover" alt="Suasana DeniKos yang nyaman">
                            </div>
                        @endforeach
                    @else
                        {{-- Fallback jika $backgroundFotos kosong atau bukan array --}}
                        <div class="swiper-slide">
                            {{-- Ganti dengan path gambar default Anda yang bagus --}}
                            <img src="{{ asset('images/landing/default-hero-bg.jpg') }}" class="w-full h-full object-cover" alt="Suasana DeniKos default">
                        </div>
                    @endif
                </div>
            </div>
            {{-- Gradient Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-tr from-black/80 via-slate-900/70 to-purple-900/60 z-10"></div>
        </div>

        {{-- Hero Content --}}
        <div class="relative z-20 px-6 flex flex-col items-center">
            <h1 class="text-5xl md:text-7xl font-extrabold mb-6 drop-shadow-lg animate-fade-in-down">
                Selamat Datang di <span class="text-yellow-400 hover:text-yellow-300 transition-colors">DeniKos</span>
            </h1>
            <p class="text-xl md:text-3xl max-w-3xl mx-auto mb-10 font-light animate-fade-in-up tracking-wide">
                Temukan kos idaman yang nyaman, aman, dan <span class="font-semibold text-yellow-200">sesuai budgetmu</span> hanya di DeniKos.
            </p>
            <a href="{{ route('user.kos.index') }}"
               class="mt-4 inline-block bg-yellow-400 text-indigo-900 font-bold px-12 py-5 rounded-xl hover:bg-yellow-300 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-110 focus:outline-none focus:ring-4 focus:ring-yellow-500 focus:ring-opacity-50 animate-bounce">
                Lihat Daftar Kamar
            </a>
        </div>
    </section>
    
    {{-- Feature Section --}}
    <section class="py-20 md:py-28 bg-slate-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16 md:mb-20">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Kenapa Memilih <span class="text-indigo-600">DeniKos</span>?</h2>
                <p class="text-gray-600 mt-2 text-xl max-w-2xl mx-auto">Kami berkomitmen memberikan pengalaman tinggal terbaik dengan berbagai keunggulan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <x-feature-card title="Lokasi Strategis"
                                iconPathD="M17.657 16.657L13.414 12.414M6.343 7.343l4.243 4.243m6.364 0a8 8 0 11-11.314-11.314 8 8 0 0111.314 11.314z">
                    DeniKos terletak strategis, dekat dengan kampus ternama, pusat perkantoran, dan akses mudah ke transportasi publik.
                </x-feature-card>

                <x-feature-card title="Fasilitas Lengkap"
                                iconPathD="M9.75 17l.97-4.243a1 1 0 00-.212-.96l-3.536-3.536a1 1 0 011.414-1.414l3.536 3.536a1 1 0 00.96.212L17 9.75m0 0V7m0 2.75L7 17">
                    Nikmati fasilitas lengkap mulai dari kamar mandi dalam, AC/Non-AC, WiFi super cepat, dapur bersama modern, hingga area parkir yang aman dan luas.
                </x-feature-card>

                <x-feature-card title="Harga Terjangkau"
                                iconPathD="M12 8c1.657 0 3 .895 3 2v6a3 3 0 01-6 0v-6c0-1.105 1.343-2 3-2zM12 15.5A1.5 1.5 0 0110.5 14V9.5a1.5 1.5 0 013 0V14a1.5 1.5 0 01-1.5 1.5z"
                                iconViewBox="0 0 24 24"> {{-- Contoh penggunaan viewBox custom jika SVG-nya beda --}}
                    Kami menawarkan harga sewa yang kompetitif dan terjangkau, mulai dari Rp500.000 per bulan. Pilihan ideal untuk pelajar, mahasiswa, dan profesional muda.
                </x-feature-card>
            </div>
        </div>
    </section>

    {{-- Testimonial Section (Contoh Tambahan) --}}
    <section class="py-20 md:py-28 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16 md:mb-20">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Apa Kata Mereka?</h2>
                <p class="text-gray-600 mt-2 text-xl max-w-2xl mx-auto">Pendapat jujur dari para penghuni DeniKos.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                {{-- Testimonial Card 1 --}}
                <div class="bg-slate-50 p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center mb-4">
                        <img src="https://i.pravatar.cc/60?u=user1" alt="User 1" class="w-14 h-14 rounded-full mr-4 border-2 border-indigo-300">
                        <div>
                            <h4 class="font-semibold text-lg text-gray-800">Rina Pertiwi</h4>
                            <p class="text-indigo-500 text-sm">Mahasiswi Teknik Informatika</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"Kosnya nyaman banget, bersih, dan lokasinya strategis dekat kampus. Fasilitas WiFi juga kencang, sangat membantu untuk kuliah online."</p>
                </div>
                {{-- Testimonial Card 2 --}}
                <div class="bg-slate-50 p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center mb-4">
                        <img src="https://i.pravatar.cc/60?u=user2" alt="User 2" class="w-14 h-14 rounded-full mr-4 border-2 border-indigo-300">
                        <div>
                            <h4 class="font-semibold text-lg text-gray-800">Agus Setiawan</h4>
                            <p class="text-indigo-500 text-sm">Karyawan Swasta</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"Harga sewanya sangat terjangkau untuk fasilitas yang didapat. Lingkungan juga aman dan tenang, cocok untuk istirahat setelah kerja."</p>
                </div>
                {{-- Testimonial Card 3 --}}
                <div class="bg-slate-50 p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center mb-4">
                        <img src="https://i.pravatar.cc/60?u=user3" alt="User 3" class="w-14 h-14 rounded-full mr-4 border-2 border-indigo-300">
                        <div>
                            <h4 class="font-semibold text-lg text-gray-800">Dewi Lestari</h4>
                            <p class="text-indigo-500 text-sm">Freelancer</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"Suka banget sama DeniKos! Ibu kosnya ramah, fasilitasnya lengkap, dan sering ada promo menarik. Recommended!"</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Call to Action Section --}}
    <section class="py-20 md:py-28 bg-gradient-to-r from-indigo-600 to-purple-700 text-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">Siap Menemukan Kos Impian Anda?</h2>
            <p class="text-lg md:text-xl max-w-3xl mx-auto mb-10 font-light tracking-wide">
                Jangan tunda lagi! Segera jelajahi pilihan kos terbaik yang kami tawarkan dan dapatkan kenyamanan tinggal yang tak tertandingi dengan harga yang bersahabat.
            </p>
            <a href="{{ route('user.kos.index') }}"
               class="bg-yellow-400 text-indigo-900 font-bold px-12 py-5 rounded-xl hover:bg-yellow-300 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-110 focus:outline-none focus:ring-4 focus:ring-yellow-500 focus:ring-opacity-50">
                Cari Kos Sekarang
            </a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-800 text-gray-400 py-12">
        <div class="container mx-auto px-6 text-center">
            <p class="text-lg font-semibold text-gray-300 mb-2">DeniKos</p>
            <p class="text-sm mb-1">Jl. Contoh Alamat No. 123, Kota Maju, Indonesia</p>
            <p class="text-sm mb-4">Email: info@denikos.com | Telepon: (021) 123-4567</p>
            <p class="text-xs">&copy; {{ date('Y') }} DeniKos. Hak Cipta Dilindungi. Dibuat dengan ❤️ di Indonesia.</p>
        </div>
    </footer>

@endsection

{{-- Tidak perlu @push('styles') dan @push('scripts') untuk Swiper di sini --}}
{{-- karena sudah di-handle oleh resources/js/app.js dan resources/css/app.css yang dimuat global --}}
{{-- Pastikan app.blade.php memuat Vite assets: @vite(['resources/css/app.css', 'resources/js/app.js']) --}}