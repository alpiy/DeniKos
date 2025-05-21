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
                    <p class="text-2xl font-bold text-gray-800 mb-6">
                        Rp{{ number_format($lantai2_pertama->harga_bulanan, 0, ',', '.') }} <span class="text-base font-normal">/ bulan</span>
                    </p>
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
                <form id="form-pesan-lantai2" action="{{ route('user.pesan.create', ['id' => 'KAMAR_ID']) }}" method="get" onsubmit="return validateDropdownLogin('kamar2', 'form-pesan-lantai2', {{ Auth::check() ? 'true' : 'false' }});">
                    <label for="kamar2" class="block mb-2 font-semibold">Pilih Nomor Kamar:</label>
                    <select name="kamar" id="kamar2" class="w-full border rounded-lg px-3 py-2 mb-4">
                        <option value="">-- Pilih Kamar --</option>
                        @foreach($lantai2_tersedia as $kamar)
                            <option value="{{ $kamar->id }}">Kamar {{ $kamar->nomor_kamar }}</option>
                        @endforeach
                    </select>
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
                    <p class="text-2xl font-bold text-gray-800 mb-6">
                        Rp{{ number_format($lantai3_pertama->harga_bulanan, 0, ',', '.') }} <span class="text-base font-normal">/ bulan</span>
                    </p>
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
                <form id="form-pesan-lantai3" action="{{ route('user.pesan.create', ['id' => 'KAMAR_ID']) }}" method="get" onsubmit="return validateDropdownLogin('kamar3', 'form-pesan-lantai3', {{ Auth::check() ? 'true' : 'false' }});">
                    <label for="kamar3" class="block mb-2 font-semibold">Pilih Nomor Kamar:</label>
                    <select name="kamar" id="kamar3" class="w-full border rounded-lg px-3 py-2 mb-4">
                        <option value="">-- Pilih Kamar --</option>
                        @foreach($lantai3_tersedia as $kamar)
                            <option value="{{ $kamar->id }}">Kamar {{ $kamar->nomor_kamar }}</option>
                        @endforeach
                    </select>
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

        function validateDropdownLogin(selectId, formId, isLoggedIn) {
            var select = document.getElementById(selectId);
            if (!select.value) {
                alert('Silakan pilih nomor kamar terlebih dahulu!');
                return false;
            }
            if (!isLoggedIn) {
                alert('Anda harus login atau register terlebih dahulu untuk memesan kamar!');
                window.location.href = "{{ route('auth.login.form') }}";
                return false;
            }
            // Ganti action form dengan id kamar yang dipilih
            var form = document.getElementById(formId);
            var action = form.getAttribute('action');
            form.setAttribute('action', action.replace('KAMAR_ID', select.value));
            return true;
        }
    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
@endsection
