@extends('admin.layout')

@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow p-6">
        <h1 class="text-3xl font-bold mb-6 text-indigo-700 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l4 4 4-4m0-5V3a1 1 0 00-1-1H5a1 1 0 00-1 1v16a1 1 0 001 1h3" /></svg>
            Detail Kamar {{ $kos->nomor_kamar }}
        </h1>
        <div class="flex flex-col md:flex-row gap-8">
            <div class="md:w-1/2">
                @if(is_array($kos->foto) && count($kos->foto) > 0)
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        @foreach($kos->foto as $foto)
                            <img src="{{ asset('storage/'.$foto) }}" alt="Foto Kamar" class="w-full h-32 object-cover rounded shadow border">
                        @endforeach
                    </div>
                @else
                    <div class="text-gray-400 mb-4">Tidak ada foto kamar.</div>
                @endif
                @if($kos->denah_kamar)
                    <div class="mb-2">
                        <span class="font-semibold">Denah Kamar:</span><br>
                        <img src="{{ asset('storage/'.$kos->denah_kamar) }}" alt="Denah Kamar" class="w-48 h-auto rounded shadow border mt-2">
                    </div>
                @endif
            </div>
            <div class="md:w-1/2 space-y-3">
                <div class="flex items-center gap-2">
                    <span class="font-semibold">Nomor Kamar:</span>
                    <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded text-sm font-bold">{{ $kos->nomor_kamar }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-semibold">Lantai:</span>
                    <span class="bg-gray-100 px-3 py-1 rounded text-sm">{{ $kos->lantai == 2 ? 'Lantai 2' : 'Lantai 3' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-semibold">Harga Bulanan:</span>
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded text-sm">Rp{{ number_format($kos->harga_bulanan, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-semibold">Status Kamar:</span>
                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $kos->status_kamar == 'tersedia' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($kos->status_kamar) }}</span>
                </div>
                <div>
                    <span class="font-semibold">Alamat:</span>
                    <div class="text-gray-700">{{ $kos->alamat }}</div>
                </div>
                <div>
                    <span class="font-semibold">Fasilitas:</span>
                    <ul class="list-disc ml-6 text-gray-700">
                        @foreach($kos->fasilitas as $fasilitas)
                            <li>{{ ucfirst($fasilitas) }}</li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <span class="font-semibold">Deskripsi:</span>
                    <div class="text-gray-700">{{ $kos->deskripsi ?? '-' }}</div>
                </div>
            </div>
        </div>
        <div class="mt-6">
            <a href="{{ route('admin.kos.index') }}" class="text-blue-600 hover:underline">&larr; Kembali ke Daftar Kos</a>
        </div>
    </div>
@endsection
