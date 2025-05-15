@extends('app')

@section('content')
    <h1 class="text-4xl font-extrabold text-indigo-600 mb-10 text-center">Daftar Kamar</h1>
  @if (session('error'))
        <div id="notif-error" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 bg-red-100 border border-red-300 text-red-700 px-6 py-3 rounded-lg shadow-lg mb-6 transition-opacity duration-500">
            {{ session('error') }}
        </div>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse ($dataKos as $kos)
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition overflow-hidden">
                {{-- Swiper Carousel --}}
                <div class="relative group">
                    <div class="swiper mySwiper-{{ $kos->id }} relative" data-id="{{ $kos->id }}">
                        <div class="swiper-wrapper">
                            @foreach ($kos->foto as $foto)
                                <div class="swiper-slide">
                                    <img src="{{ asset('storage/' . $foto) }}" class="w-full h-52 object-cover" alt="Foto Kos">
                                </div>
                            @endforeach
                        </div>
                
                        <!-- Tombol arah -->
                        <div class="swiper-button-next swiper-button-next-{{ $kos->id }}"></div>
                        <div class="swiper-button-prev swiper-button-prev-{{ $kos->id }}"></div>
                
                        <!-- Pagination -->
                        <div class="swiper-pagination swiper-pagination-{{ $kos->id }}"></div>
                    </div>
                </div>
                

                <div class="p-5">
                    <h2 class="text-2xl font-bold text-indigo-700 mb-1">Kamar {{ $kos->nomor_kamar }}</h2>
                    <p class="text-gray-600 text-sm">Lantai: {{ $kos->lantai }}</p>
                    <p class="text-gray-600 text-sm">Harga: Rp{{ number_format($kos->harga_bulanan, 0, ',', '.') }} / bulan</p>
                    <p class="text-sm font-semibold mt-2">
                        Status: 
                        <span class="{{ $kos->status_kamar == 'tersedia' ? 'text-green-600' : 'text-red-600' }}">
                            {{ ucfirst($kos->status_kamar) }}
                        </span>
                    </p>

                    <a href="{{ route('user.kos.show', $kos->id) }}"
                       class="inline-block mt-4 bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-500 text-lg">
                Belum ada data kos tersedia.
            </div>
        @endforelse
    </div>
@endsection

{{-- @section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @foreach ($dataKos as $kos)
                new Swiper(".mySwiper-{{ $kos->id }}", {
                    loop: true,
                    pagination: {
                        el: ".mySwiper-{{ $kos->id }} .swiper-pagination",
                        clickable: true,
                    },
                });
            @endforeach
        });
    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
@endsection --}}
