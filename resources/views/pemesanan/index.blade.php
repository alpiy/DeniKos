@extends('app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Riwayat Pemesanan Anda</h1>
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-300 text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">#</th>
                    <th class="p-3 border">Kamar</th>
                    <th class="p-3 border">Tanggal Pesan</th>
                    <th class="p-3 border">Lama Sewa</th>
                    <th class="p-3 border">Status</th>
                    <th class="p-3 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pesanans as $no => $p)
                    <tr>
                        <td class="p-3 border">{{ $no+1 }}</td>
                        <td class="p-3 border">{{ $p->kos->nomor_kamar ?? '-' }}</td>
                        <td class="p-3 border">{{ $p->tanggal_pesan }}</td>
                        <td class="p-3 border">{{ $p->lama_sewa }} bulan</td>
                        <td class="p-3 border capitalize">{{ $p->status_pemesanan }}</td>
                        <td class="p-3 border">
                            @if ($p->status_pemesanan == 'diterima')
                                <a href="{{ route('user.pesan.perpanjang', $p->id) }}" class="text-blue-600 hover:underline">Ajukan Perpanjangan</a>
                            @else
                                <a href="{{ asset('storage/'.$p->bukti_pembayaran) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Bukti Pembayaran</a>
                            @endif
                        {{-- <td class="p-3 border">
                            <a href="{{ asset('storage/'.$p->bukti_pembayaran) }}" target="_blank" class="text-blue-600 underline text-sm">Bukti</a>
                        </td> --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-3 border text-center text-gray-500">Belum ada pemesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection