@extends('admin.layout')

@section('title', 'Detail Kamar Kos: ' . $kos->nomor_kamar)

@section('content')
<div class="space-y-8">
    {{-- Header Halaman dan Tombol Aksi --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Detail Kamar: <span class="text-indigo-600">{{ $kos->nomor_kamar }}</span></h1>
            <p class="mt-1 text-sm text-gray-500">Informasi lengkap mengenai kamar kos.</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.kos.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali
            </a>
            <a href="{{ route('admin.kos.edit', $kos->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                </svg>
                Edit Kamar
            </a>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-xl overflow-hidden">
        {{-- Galeri Foto & Denah --}}
        @if( (is_array($kos->foto) && count($kos->foto) > 0) )
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Galeri Media</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-md font-medium text-gray-600 mb-2">Foto Kamar:</h3>
                    @if(is_array($kos->foto) && count($kos->foto) > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach($kos->foto as $fotoPath)
                                <a href="{{ asset('storage/'.$fotoPath) }}" data-fancybox="gallery" data-caption="Foto Kamar {{ $kos->nomor_kamar }}">
                                    <img src="{{ asset('storage/'.$fotoPath) }}" alt="Foto Kamar {{ $kos->nomor_kamar }}" class="w-full h-40 object-cover rounded-lg shadow-md hover:shadow-xl transition-shadow duration-200 cursor-pointer">
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic">Tidak ada foto kamar.</p>
                    @endif
                </div>
            
            </div>
        </div>
        @endif

        {{-- Detail Informasi Kamar --}}
        <div class="p-6 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nomor Kamar</dt>
                    <dd class="mt-1 text-lg font-semibold text-indigo-700">{{ $kos->nomor_kamar }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Lantai</dt>
                    <dd class="mt-1 text-lg text-gray-900">Lantai {{ $kos->lantai }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Harga Bulanan</dt>
                    <dd class="mt-1 text-lg font-semibold text-green-600">Rp{{ number_format($kos->harga_bulanan, 0, ',', '.') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status Kamar</dt>
                    <dd class="mt-1">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full 
                            {{ $kos->status_kamar == 'tersedia' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($kos->status_kamar) }}
                        </span>
                    </dd>
                </div>
                 <div>
                    <dt class="text-sm font-medium text-gray-500">Luas Kamar</dt>
                    <dd class="mt-1 text-lg text-gray-900">{{ $kos->luas_kamar ?? '-' }}</dd>
                </div>

                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                    <dd class="mt-1 text-base text-gray-700 leading-relaxed">{{ $kos->alamat }}</dd>
                </div>

                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                    <dd class="mt-1 text-base text-gray-700 leading-relaxed prose max-w-none">
                        {!! nl2br(e($kos->deskripsi ?? '-')) !!}
                    </dd>
                </div>

                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Fasilitas</dt>
                    <dd class="mt-2">
                        @if(is_array($kos->fasilitas) && count($kos->fasilitas) > 0)
                            <ul class="flex flex-wrap gap-2">
                                @foreach($kos->fasilitas as $fasilitas)
                                    <li class="bg-indigo-50 text-indigo-700 text-sm font-medium px-3 py-1 rounded-full">{{ ucfirst($fasilitas) }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500 italic">Tidak ada data fasilitas.</p>
                        @endif
                    </dd>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
{{-- Fancybox CSS (jika menggunakan) --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
<style>
    .prose ul { list-style-type: disc; margin-left: 1.5rem; }
    .prose ol { list-style-type: decimal; margin-left: 1.5rem; }
</style>
@endpush

@push('scripts')
{{-- Fancybox JS (jika menggunakan) --}}
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    Fancybox.bind("[data-fancybox]", {
      // Custom options
    });
  });
</script>
@endpush