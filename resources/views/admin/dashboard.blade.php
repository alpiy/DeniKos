@extends('app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Dashboard Admin</h1>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-indigo-100 p-6 rounded-xl shadow text-center">
            <h2 class="text-lg font-semibold text-indigo-700">Total Kos</h2>
            <p class="text-3xl font-bold text-indigo-800 mt-2">12</p>
        </div>
        <div class="bg-green-100 p-6 rounded-xl shadow text-center">
            <h2 class="text-lg font-semibold text-green-700">Total Pemesanan</h2>
            <p class="text-3xl font-bold text-green-800 mt-2">8</p>
        </div>
        <div class="bg-yellow-100 p-6 rounded-xl shadow text-center">
            <h2 class="text-lg font-semibold text-yellow-700">Belum Diverifikasi</h2>
            <p class="text-3xl font-bold text-yellow-800 mt-2">3</p>
        </div>
    </div>

    {{-- List Kos --}}
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Data Kos</h2>
        <a href="/admin/kos/tambah" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
            + Tambah Kos
        </a>
    </div>

    <div class="overflow-x-auto">
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
                @for ($i = 1; $i <= 5; $i++)
                    <tr>
                        <td class="p-3 border">{{ $i }}</td>
                        <td class="p-3 border">Kos Deni {{ $i }}</td>
                        <td class="p-3 border">Jl. Contoh No.{{ $i }}</td>
                        <td class="p-3 border">Rp{{ number_format(750000 + $i * 50000, 0, ',', '.') }}</td>
                        <td class="p-3 border">
                            <a href="/admin/kos/edit" class="text-blue-600 hover:underline mr-2">Edit</a>
                            <a href="#" class="text-red-600 hover:underline">Hapus</a>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
@endsection
