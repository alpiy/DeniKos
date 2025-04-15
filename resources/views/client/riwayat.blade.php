@extends('app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Riwayat Pemesanan Anda</h1>

    <div class="overflow-x-auto">
        <table class="w-full border border-gray-300 text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">#</th>
                    <th class="p-3 border">Nama Kos</th>
                    <th class="p-3 border">Tanggal Masuk</th>
                    <th class="p-3 border">Status</th>
                    <th class="p-3 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 1; $i <= 5; $i++)
                    <tr>
                        <td class="p-3 border">{{ $i }}</td>
                        <td class="p-3 border">Kos Deni {{ $i }}</td>
                        <td class="p-3 border">2025-05-0{{ $i }}</td>
                        <td class="p-3 border">
                            @if($i % 2 == 0)
                                <span class="text-green-600 font-semibold">Terverifikasi</span>
                            @else
                                <span class="text-yellow-600 font-semibold">Menunggu</span>
                            @endif
                        </td>
                        <td class="p-3 border">
                            @if($i % 2 == 0)
                                <a href="#" class="text-blue-600 hover:underline">Lihat Bukti Pembayaran</a>
                            @else
                                <a href="#" class="text-red-600 hover:underline">Batalkan Pemesanan</a>
                            @endif
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
@endsection
