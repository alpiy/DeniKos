@extends('admin.layout')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Data Penyewa Aktif</h1>

    <div class="overflow-x-auto">
    <table class="w-full table-auto border-collapse shadow rounded-xl bg-white">
        <thead>
            <tr class="bg-indigo-100 text-indigo-800">
                <th class="p-3 border">Nama</th>
                <th class="p-3 border">Email</th>
                <th class="p-3 border">No HP</th>
                <th class="p-3 border">Kamar</th>
                <th class="p-3 border">Tanggal Masuk</th>
                <th class="p-3 border">Lama Sewa</th>
                <th class="p-3 border">Status</th>
                <th class="p-3 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penyewa as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-3 border font-semibold">{{ $user->user->name }}</td>
                    <td class="p-3 border">{{ $user->user->email }}</td>
                    <td class="p-3 border">{{ $user->user->no_hp }}</td>
                    <td class="p-3 border">Kamar <span class="font-bold">{{ $user->kos->nomor_kamar ?? '-' }}</span><br><span class="text-xs text-gray-500">{{ $user->kos->nama ?? '' }}</span></td>
                    <td class="p-3 border">{{ $user->tanggal_pesan }}</td>
                    <td class="p-3 border">{{ $user->lama_sewa }} bulan</td>
                    <td class="p-3 border">
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold">Aktif</span>
                    </td>
                    <td class="p-3 border">
                        <form action="{{ route('admin.penyewa.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Tandai penyewa ini selesai?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-xs">Selesai</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="p-4 text-center text-gray-500">Tidak ada penyewa aktif.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
@endsection
