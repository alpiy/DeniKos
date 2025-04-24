@extends('admin.layout')

@section('title', 'Kelola Kos')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Data Kos</h1>
        <a href="{{ route('admin.kos.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Tambah Kos</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full bg-white shadow rounded-lg">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="p-3">Nomor Kamar</th>
                <th class="p-3">Lantai</th>
                <th class="p-3">Harga Bulanan</th>
                <th class="p-3">Status Kamar</th>
                <th class="p-3">Alamat</th>
                <th class="p-3">Fasilitas</th>
                <th class="p-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $kos)
                <tr class="border-t">
                    <!-- Nomor Kamar -->
                    <td class="p-3">{{ $kos->nomor_kamar }}</td>
                

                    <!-- Lantai -->
                    <td class="p-3">{{ $kos->lantai == 2 ? 'Lantai 2' : 'Lantai 3' }}</td>

                    <!-- Harga Bulanan -->
                    <td class="p-3">Rp{{ number_format($kos->harga_bulanan, 0, ',', '.') }}</td>

                    <!-- Status Kamar -->
                    <td class="p-3">
                        <span class="{{ $kos->status_kamar == 'tersedia' ? 'text-green-600' : 'text-red-600' }}">
                            {{ ucfirst($kos->status_kamar) }}
                        </span>
                    </td>

                    <!-- Alamat -->
                    <td class="p-3">{{ $kos->alamat }}</td>

                    <!-- Fasilitas -->
                    <td class="p-3">
                        @foreach ($kos->fasilitas as $fasilitas) <!-- Tidak perlu json_decode() lagi -->
                            <span class="block text-sm">{{ ucfirst($fasilitas) }}</span>
                        @endforeach
                    </td>

                    <!-- Aksi -->
                    <td class="p-3 space-x-2">
                        <a href="{{ route('admin.kos.edit', $kos->id) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('admin.kos.destroy', $kos->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin hapus?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
