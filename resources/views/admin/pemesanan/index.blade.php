@extends('admin.layout')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Daftar Pemesanan</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">Nama</th>
                <th class="p-2 border">Email</th>
                <th class="p-2 border">Kos</th>
                <th class="p-2 border">Tanggal Masuk</th>
                <th class="p-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemesanan as $item)
                <tr class="hover:bg-gray-50">
                    <td class="p-2 border">{{ $item->nama }}</td>
                    <td class="p-2 border">{{ $item->email }}</td>
                    <td class="p-2 border">{{ $item->kos->nama ?? '-' }}</td>
                    <td class="p-2 border">{{ $item->tgl_masuk }}</td>
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
