

@extends('app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Daftar Kos</h1>

    <a href="{{ route('admin.kos.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">+ Tambah Kos</a>

    <div class="overflow-x-auto mt-6">
        <table class="w-full border border-gray-300 text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">#</th>
                    <th class="p-3 border">Nama Kos</th>
                    <th class="p-3 border">Alamat</th>
                    <th class="p-3 border">Harga</th>
                    <th class="p-3 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kos as $k)
                    <tr>
                        <td class="p-3 border">{{ $loop->iteration }}</td>
                        <td class="p-3 border">{{ $k->nama }}</td>
                        <td class="p-3 border">{{ $k->alamat }}</td>
                        <td class="p-3 border">Rp{{ number_format($k->harga, 0, ',', '.') }}</td>
                        <td class="p-3 border">
                            <a href="{{ route('admin.kos.edit', $k->id) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                            <a href="#" class="text-red-600 hover:underline">Hapus</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
