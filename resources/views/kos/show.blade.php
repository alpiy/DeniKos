@extends('app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 bg-white rounded-2xl shadow-lg overflow-hidden">
            {{-- Carousel Foto --}}
            <div class="swiper mySwiperDetail-{{ $kos->id }} w-full h-96 rounded-xl overflow-hidden relative">
                <div class="swiper-wrapper">
                    @foreach ($kos->foto as $src)
                        <div class="swiper-slide">
                            <img src="{{ asset('storage/' . $src) }}"
                                 class="w-full h-96 object-cover" alt="Foto Kos">
                        </div>
                    @endforeach
                </div>
            
                <!-- Tombol panah -->
                <div class="swiper-button-next swiper-button-next-detail-{{ $kos->id }}"></div>
                <div class="swiper-button-prev swiper-button-prev-detail-{{ $kos->id }}"></div>
            
                <!-- Pagination -->
                <div class="swiper-pagination swiper-pagination-detail-{{ $kos->id }} mt-2"></div>
            </div>
            


            {{-- Info Kos --}}
            <div class="p-6 md:p-10">
                <h1 class="text-4xl font-bold text-indigo-600 mb-2">{{ $kos->nomor_kamar }}</h1>
                <p class="text-gray-500 text-sm mb-4">{{ $kos->alamat }}</p>
                <p class="text-2xl font-bold text-gray-800 mb-6">
                    Rp{{ number_format($kos->harga_bulanan, 0, ',', '.') }} <span class="text-base font-normal">/ bulan</span>
                </p>

                {{-- Deskripsi --}}
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Deskripsi</h2>
                    <p class="text-gray-700 leading-relaxed">{{ $kos->deskripsi }}</p>
                </div>

                {{-- Fasilitas --}}
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Fasilitas</h2>
                    <ul class="list-disc list-inside text-gray-700 space-y-1">
                        @foreach ($kos->fasilitas as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>

                {{-- Tombol Pemesanan --}}
                @if($kos->status_kamar == 'tersedia')
                <a href="{{ route('user.pesan.create', $kos->id) }}"
                class="inline-block mt-4 bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 transition duration-300">
                    Pesan Sekarang
                </a>
                @else
                <button disabled
                    class="inline-block mt-4 bg-gray-400 text-white px-6 py-3 rounded-xl cursor-not-allowed opacity-70">
                    Kamar Sudah Dipesan
                </button>
                @endif
            </div>
        </div>
    </div>
@endsection
