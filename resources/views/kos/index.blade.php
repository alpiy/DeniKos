@extends('app')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Daftar Kos</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse ($dataKos as $kos)
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <img src="{{ $kos->foto ?? 'https://via.placeholder.com/400x250' }}"
                     alt="Foto Kos" class="w-full h-48 object-cover">

                <div class="p-4">
                    <h2 class="text-xl font-semibold text-indigo-600">{{ $kos->nama_kos }}</h2>
                    <p class="text-gray-600 mt-1">{{ $kos->alamat }}</p>
                    <p class="text-gray-800 font-bold mt-2">Rp{{ number_format($kos->harga, 0, ',', '.') }} / bulan</p>

                    <a href="{{ route('user.kos.show', $kos->id) }}"
                       class="inline-block mt-4 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                        Lihat Detail    
                    </a>
                </div>
            </div>
        @empty
            <p class="text-gray-500 col-span-3">Belum ada data kos tersedia.</p>
        @endforelse
    </div>
@endsection
