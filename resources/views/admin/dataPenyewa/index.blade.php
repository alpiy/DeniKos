@extends('admin.layout')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Data Penyewa Aktif</h1>

    <table class="w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">Nama</th>
                <th class="p-2 border">Email</th>
                <th class="p-2 border">No HP</th>
                <th class="p-2 border">Kamar</th>
                <th class="p-2 border">Tanggal Masuk</th>
                <th class="p-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penyewa as $user)
                <tr class="hover:bg-gray-50">
                    <td class="p-2 border">{{ $user->user->name }}</td>
                    <td class="p-2 border">{{ $user->user->email }}</td>
                    <td class="p-2 border">{{ $user->user->no_hp }}</td>
                    <td class="p-2 border">Kamar {{ $user->kos->nomor_kamar ?? '-' }}</td>
                    <td class="p-2 border">{{ $user->tanggal_pesan }}</td>
                    <td class="p-2 border">
                        <form action="{{ route('admin.penyewa.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Tandai penyewa ini selesai?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-500">Tidak ada penyewa aktif.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
