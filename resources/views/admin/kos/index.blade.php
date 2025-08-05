@extends('admin.layout')

@section('title', 'Manajemen Kamar Kos')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <h1 class="text-3xl font-bold text-gray-800">Manajemen Kamar Kos</h1>
        <a href="{{ route('admin.kos.create') }}" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Tambah Kamar Kos
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
            <p class="font-bold">Berhasil!</p>
            <p class="text-sm">{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
            <p class="font-bold">Gagal!</p>
            <p class="text-sm">{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-xl">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-700">Daftar Kamar Kos ({{ $totalKos }})</h2>
            <div class="mt-2 md:mt-0 text-sm text-gray-600">
                Tersedia: {{ $tersediaCount }} | Terpesan: {{ $terpesanCount }}
            </div>
        </div>
        
        {{-- Filter Form --}}
        <form method="GET" class="mb-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-4 items-end">
            <div>
                <label for="q" class="block text-sm font-medium text-gray-700">Cari No. Kamar</label>
                <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Contoh: 101" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2.5 px-3 text-sm">
            </div>
            <div>
                <label for="status_kamar" class="block text-sm font-medium text-gray-700">Status Kamar</label>
                <select name="status_kamar" id="status_kamar" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2.5 px-3 text-sm">
                    <option value="">Semua Status</option>
                    <option value="tersedia" {{ request('status_kamar') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="terpesan" {{ request('status_kamar') == 'terpesan' ? 'selected' : '' }}>Terpesan</option>
                </select>
            </div>
            <div>
                <label for="lantai" class="block text-sm font-medium text-gray-700">Lantai</label>
                <select name="lantai" id="lantai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2.5 px-3 text-sm">
                    <option value="">Semua Lantai</option>
                    <option value="2" {{ request('lantai') == '2' ? 'selected' : '' }}>Lantai 2</option>
                    <option value="3" {{ request('lantai') == '3' ? 'selected' : '' }}>Lantai 3</option>
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Filter
                </button>
                @if(request()->hasAny(['q', 'status_kamar', 'lantai']))
                    <a href="{{ route('admin.kos.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        @if($dataKos->isEmpty())
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-600 mb-2">Data Kamar Kos Tidak Ditemukan</h3>
                <p class="text-gray-400 text-sm mb-4">
                    @if(request()->hasAny(['q', 'status_kamar', 'lantai']))
                        Tidak ada data yang cocok dengan filter Anda.
                    @else
                        Belum ada kamar kos yang ditambahkan.
                    @endif
                </p>
                @if(!request()->hasAny(['q', 'status_kamar', 'lantai']))
                    <a href="{{ route('admin.kos.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                        + Tambah Kamar Kos
                    </a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            @php
                                $columns = [
                                    'nomor_kamar' => 'No. Kamar',
                                    'lantai' => 'Lantai',
                                    'harga_bulanan' => 'Harga/Bulan',
                                    'status_kamar' => 'Status',
                                ];
                            @endphp
                            @foreach ($columns as $key => $label)
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                @php
                                    $currentSortDirection = ($sortBy == $key && $sortDirection == 'asc') ? 'desc' : 'asc';
                                    $arrow = ($sortBy == $key) ? ($sortDirection == 'asc' ? '&uarr;' : '&darr;') : '';
                                @endphp
                                <a href="{{ route('admin.kos.index', array_merge(request()->query(), ['sort_by' => $key, 'sort_direction' => $currentSortDirection])) }}" class="hover:text-indigo-700">
                                    {{ $label }} {!! $arrow !!}
                                </a>
                            </th>
                            @endforeach
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Luas</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fasilitas</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($dataKos as $kos)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $kos->nomor_kamar }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">Lantai {{ $kos->lantai }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">Rp{{ number_format($kos->harga_bulanan, 0, ',', '.') }}</td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $kos->status_kamar == 'tersedia' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($kos->status_kamar) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <span class="text-gray-600 font-medium">{{ $kos->luas_kamar }} m</span>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    @if(is_array($kos->fasilitas) && count($kos->fasilitas) > 0)
                                        <div class="max-w-xs truncate">{{ Str::limit(implode(', ', array_map('ucfirst', $kos->fasilitas)), 50) }}</div>
                                    @else
                                        <span class="text-gray-400 italic">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.kos.show', $kos->id) }}" title="Lihat Detail & Foto Kamar" class="text-blue-600 hover:text-blue-800 p-1 hover:bg-blue-100 rounded-full transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.kos.edit', $kos->id) }}" title="Edit Kamar" class="text-green-600 hover:text-green-800 p-1 hover:bg-green-100 rounded-full transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.kos.destroy', $kos->id) }}" method="POST" class="inline" onsubmit="return confirm('Anda yakin ingin memindahkan kamar {{ $kos->nomor_kamar }} ke arsip? (Soft Delete)')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Arsipkan Kamar" class="text-red-600 hover:text-red-800 p-1 hover:bg-red-100 rounded-full transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            <div class="mt-6">
                {{ $dataKos->links() }}
            </div>
        @endif
    </div>
</div>
@endsection