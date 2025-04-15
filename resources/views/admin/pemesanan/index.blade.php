// resources/views/admin/pemesanan/index.blade.php

@extends('app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Daftar Pemesanan</h1>

    <div class="overflow-x-auto mt-6">
        <table class="w-full border border-gray-300 text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">#</th>
                    <th class="p-3 border">Nama Penyewa</th>
                    <th class="p-3 border">Kos</th>
                    <th class="p-3 border">Tanggal Masuk</th>
                    <th class="p-3 border">Status</th>
                    <th class="p-3 border">Bukti Pembayaran</th>
                    <th class="p-3 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pemesanan as $p)
                    <tr>
                        <td class="p-3 border">{{ $loop->iteration }}</td>
                        <td class="p-3 border">{{ $p->kos->nama }}</td>
                        <td class="p-3 border">{{ $p->tanggal_masuk }}</td>
                        <td class="p-3 border">{{ $p->status }}</td>
                        <td class="p-3 border">
                            <a href="{{ asset('storage/' . $p->bukti_pembayaran) }}" class="text-indigo-600 hover:underline">Lihat</a>
                        </td>
                        <td class="p-3 border">
                            <a href="#" class="text-blue-600 hover:underline mr-2">Verifikasi</a>
                            <a href="#" class="text-red-600 hover:underline">Hapus</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
