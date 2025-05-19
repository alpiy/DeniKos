@extends('admin.layout')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Daftar Perpanjangan Sewa</h1>
    <table class="w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">Nama</th>
                <th class="p-2 border">Email</th>
                <th class="p-2 border">No.Kamar</th>
                <th class="p-2 border">Tanggal Perpanjang</th>
                <th class="p-2 border">Lama Sewa</th>
                <th class="p-2 border">Status</th>
                <th class="p-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemesananPerpanjang as $item)
                <tr class="hover:bg-gray-50">
                    <td class="p-2 border">{{ $item->user->name }}</td>
                    <td class="p-2 border">{{ $item->user->email }}</td>
                    <td class="p-2 border">{{ $item->kos->nomor_kamar ?? '-' }}</td>
                    <td class="p-2 border">{{ $item->tanggal_pesan }}</td>
                    <td class="p-2 border">{{ $item->lama_sewa }} bulan</td>
                    <td class="p-2 border">
                        <span class="px-2 py-1 text-sm rounded
                            {{ $item->status_pemesanan === 'diterima' ? 'bg-green-100 text-green-700' :
                               ($item->status_pemesanan === 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ ucfirst($item->status_pemesanan) }}
                        </span>
                    </td>
                    <td class="p-2 border space-x-2">
                        <a href="{{ route('admin.pemesanan.show', $item->id) }}" class="text-blue-600 hover:underline">Lihat</a>
                        <form action="{{ route('admin.pemesanan.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pemesanan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
