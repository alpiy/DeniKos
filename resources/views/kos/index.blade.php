@extends('app')

@section('content')
    <h1 class="text-4xl font-extrabold text-indigo-600 mb-10 text-center">Detail Kamar Kos</h1>
    @if (session('error'))
        <div id="notif-error" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 bg-red-100 border border-red-300 text-red-700 px-6 py-3 rounded-lg shadow-lg mb-6 transition-opacity duration-500">
            {{ session('error') }}
        </div>
    @endif

    @php
        $lantai2 = $dataKos->where('lantai', '2');
        $lantai3 = $dataKos->where('lantai', '3');
        $lantai2_pertama = $lantai2->first();
        $lantai3_pertama = $lantai3->first();
        $lantai2_tersedia = $lantai2->where('status_kamar', 'tersedia');
        $lantai3_tersedia = $lantai3->where('status_kamar', 'tersedia');
    @endphp

    <div class="mb-12">
        <h2 class="text-2xl font-bold text-indigo-700 mb-4">Lantai 2</h2>
        @if($lantai2_pertama)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 bg-white rounded-2xl shadow-lg overflow-hidden">
            {{-- Carousel Foto --}}
            <div class="swiper mySwiperDetail-2 w-full h-80 rounded-xl overflow-hidden relative">
                <div class="swiper-wrapper">
                    @foreach ($lantai2_pertama->foto as $src)
                        <div class="swiper-slide">
                            <img src="{{ asset('storage/' . $src) }}" class="w-full h-80 object-cover" alt="Foto Kos">
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-next swiper-button-next-detail-2"></div>
                <div class="swiper-button-prev swiper-button-prev-detail-2"></div>
                <div class="swiper-pagination swiper-pagination-detail-2 mt-2"></div>
            </div>
            {{-- Info Kos --}}
            <div class="p-6 md:p-10 flex flex-col justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-indigo-600 mb-2">Kamar Lantai 2</h1>
                    <p class="text-gray-500 text-sm mb-4">{{ $lantai2_pertama->alamat }}</p>
                    <div class="flex flex-wrap items-center gap-4 mb-4">
                        <span class="inline-block bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-base font-semibold">Luas kamar: <span class="font-bold">{{ $lantai2_pertama->luas_kamar }} m<sup>2</sup></span></span>
                        <span class="inline-block bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-base font-semibold">Rp{{ number_format($lantai2_pertama->harga_bulanan, 0, ',', '.') }} <span class="font-normal">/ bulan</span></span>
                    </div>
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Deskripsi</h2>
                        <p class="text-gray-700 leading-relaxed">{{ $lantai2_pertama->deskripsi }}</p>
                    </div>
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Fasilitas</h2>
                        <ul class="list-disc list-inside text-gray-700 space-y-1">
                            @foreach ($lantai2_pertama->fasilitas as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <form id="form-pesan-lantai2" action="{{ route('user.pesan.create', ['id' => 'multi']) }}" method="get" onsubmit="return validateCheckboxLogin('kamar2', 'form-pesan-lantai2', {{ Auth::check() ? 'true' : 'false' }});">
                    <label class="block mb-2 font-semibold">Pilih Kamar (bisa lebih dari satu):</label>
                    <div id="kamar2" class="grid grid-cols-2 gap-2 mb-4">
                        @foreach($lantai2_tersedia as $kamar)
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="kamar[]" value="{{ $kamar->id }}" class="accent-indigo-600">
                                <span>Kamar {{ $kamar->nomor_kamar }}</span>
                            </label>
                        @endforeach
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 transition duration-300 w-full">Pesan Sekarang</button>
                </form>
            </div>
        </div>
        @else
            <div class="text-gray-500">Tidak ada kamar di lantai 2.</div>
        @endif
    </div>

    <div class="mb-12">
        <h2 class="text-2xl font-bold text-indigo-700 mb-4">Lantai 3</h2>
        @if($lantai3_pertama)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 bg-white rounded-2xl shadow-lg overflow-hidden">
            {{-- Carousel Foto --}}
            <div class="swiper mySwiperDetail-3 w-full h-80 rounded-xl overflow-hidden relative">
                <div class="swiper-wrapper">
                    @foreach ($lantai3_pertama->foto as $src)
                        <div class="swiper-slide">
                            <img src="{{ asset('storage/' . $src) }}" class="w-full h-80 object-cover" alt="Foto Kos">
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-next swiper-button-next-detail-3"></div>
                <div class="swiper-button-prev swiper-button-prev-detail-3"></div>
                <div class="swiper-pagination swiper-pagination-detail-3 mt-2"></div>
            </div>
            {{-- Info Kos --}}
            <div class="p-6 md:p-10 flex flex-col justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-indigo-600 mb-2">Kamar Lantai 3</h1>
                    <p class="text-gray-500 text-sm mb-4">{{ $lantai3_pertama->alamat }}</p>
                    <div class="flex flex-wrap items-center gap-4 mb-4">
                        <span class="inline-block bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-base font-semibold">Luas kamar: <span class="font-bold">{{ $lantai3_pertama->luas_kamar }} m<sup>2</sup></span></span>
                        <span class="inline-block bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-base font-semibold">Rp{{ number_format($lantai3_pertama->harga_bulanan, 0, ',', '.') }} <span class="font-normal">/ bulan</span></span>
                    </div>
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Deskripsi</h2>
                        <p class="text-gray-700 leading-relaxed">{{ $lantai3_pertama->deskripsi }}</p>
                    </div>
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Fasilitas</h2>
                        <ul class="list-disc list-inside text-gray-700 space-y-1">
                            @foreach ($lantai3_pertama->fasilitas as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <form id="form-pesan-lantai3" action="{{ route('user.pesan.create', ['id' => 'multi']) }}" method="get" onsubmit="return validateCheckboxLogin('kamar3', 'form-pesan-lantai3', {{ Auth::check() ? 'true' : 'false' }});">
                    <label class="block mb-2 font-semibold">Pilih Kamar (bisa lebih dari satu):</label>
                    <div id="kamar3" class="grid grid-cols-2 gap-2 mb-4">
                        @foreach($lantai3_tersedia as $kamar)
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="kamar[]" value="{{ $kamar->id }}" class="accent-indigo-600">
                                <span>Kamar {{ $kamar->nomor_kamar }}</span>
                            </label>
                        @endforeach
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 transition duration-300 w-full">Pesan Sekarang</button>
                </form>
            </div>
        </div>
        @else
            <div class="text-gray-500">Tidak ada kamar di lantai 3.</div>
        @endif
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new Swiper(".mySwiperDetail-2", {
                loop: true,
                pagination: {
                    el: ".mySwiperDetail-2 .swiper-pagination",
                    clickable: true,
                },
            });
            new Swiper(".mySwiperDetail-3", {
                loop: true,
                pagination: {
                    el: ".mySwiperDetail-3 .swiper-pagination",
                    clickable: true,
                },
            });
        });
        function validateCheckboxLogin(containerId, formId, isLoggedIn) {
            var checkboxes = document.querySelectorAll('#' + containerId + ' input[type=checkbox]:checked');
            if (checkboxes.length === 0) {
                alert('Silakan pilih minimal satu kamar terlebih dahulu!');
                return false;
            }
            if (!isLoggedIn) {
                alert('Anda harus login atau register terlebih dahulu untuk memesan kamar!');
                window.location.href = "{{ route('auth.login.form') }}";
                return false;
            }
            return true;
        }
    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
@endsection
