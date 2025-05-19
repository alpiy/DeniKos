@extends('admin.layout')

@section('title', 'Kelola Kos')

@section('content')
<div class="relative">
    <div class="sticky top-0 z-10 bg-transparent pb-3 pt-4 shadow-transparent  w-full" style="margin-left:0;margin-right:0;">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-2">
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-indigo-700">Data Kos</h1>
                <span class="hidden md:inline-block text-gray-400 text-sm">Manajemen seluruh kamar kos Anda</span>
            </div>
            <a href="{{ route('admin.kos.create') }}" class="bg-indigo-600 text-white px-5 py-2 rounded-lg font-semibold shadow hover:bg-indigo-700 transition">Tambah Kos</a>
        </div>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-2">
            <form method="GET" class="flex gap-2 flex-wrap items-center">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Nomor Kamar..." class="border rounded px-3 py-2 focus:ring-2 focus:ring-indigo-200" />
                <select name="status_kamar" class="border rounded px-3 py-2 focus:ring-2 focus:ring-indigo-200">
                    <option value="">Semua Status</option>
                    <option value="tersedia" {{ request('status_kamar') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="terpesan" {{ request('status_kamar') == 'terpesan' ? 'selected' : '' }}>Terpesan</option>
                </select>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 font-semibold">Filter</button>
            </form>
            <div class="flex gap-2 text-sm mt-2 md:mt-0 flex-wrap">
                <span class="bg-gray-100 px-3 py-1 rounded font-semibold">Total: <b>{{ $data->count() }}</b></span>
                <span class="bg-green-100 text-green-700 px-3 py-1 rounded font-semibold">Tersedia: <b>{{ $data->where('status_kamar','tersedia')->count() }}</b></span>
                <span class="bg-red-100 text-red-700 px-3 py-1 rounded font-semibold">Terpesan: <b>{{ $data->where('status_kamar','terpesan')->count() }}</b></span>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto overflow-y-auto max-h-[70vh] mt-2 rounded-lg border bg-white">
        <table class="min-w-full bg-white">
            <thead class="sticky top-0 z-10 bg-indigo-100 text-indigo-800 border-b">
                <tr>
                    <th class="p-3">Foto</th>
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
                    <tr class="border-t {{ $kos->status_kamar == 'terpesan' ? 'bg-red-50' : '' }}">
                        <td class="p-3">
                            @if(is_array($kos->foto) && count($kos->foto) > 0)
                                <img src="{{ asset('storage/'.$kos->foto[0]) }}" alt="Foto Kamar" class="w-16 h-16 object-cover rounded shadow">
                            @else
                                <span class="text-gray-400 text-xs">Tidak ada foto</span>
                            @endif
                        </td>
                        <td class="p-3 font-bold">{{ $kos->nomor_kamar }}</td>
                        <td class="p-3">{{ $kos->lantai == 2 ? 'Lantai 2' : 'Lantai 3' }}</td>
                        <td class="p-3">Rp{{ number_format($kos->harga_bulanan, 0, ',', '.') }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $kos->status_kamar == 'tersedia' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($kos->status_kamar) }}
                            </span>
                        </td>
                        <td class="p-3">{{ $kos->alamat }}</td>
                        <td class="p-3">
                            @foreach ($kos->fasilitas as $fasilitas)
                                <span class="inline-block bg-indigo-50 text-indigo-700 px-2 py-1 rounded text-xs mr-1 mb-1">{{ ucfirst($fasilitas) }}</span>
                            @endforeach
                        </td>
                        <td class="p-3 space-x-2 flex items-center">
                            <a href="{{ route('admin.kos.edit', $kos->id) }}" title="Edit" class="inline-block text-blue-600 hover:text-blue-800"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13h3l8-8a2.828 2.828 0 00-4-4l-8 8v3zm0 0v3h3" /></svg></a>
                            <a href="{{ route('admin.kos.show', $kos->id) }}" title="Detail" class="inline-block text-indigo-600 hover:text-indigo-800"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></a>
                            <form action="{{ route('admin.kos.destroy', $kos->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin hapus?')">
                                @csrf
                                @method('DELETE')
                                <button title="Hapus" class="inline-block text-red-600 hover:text-red-800"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
