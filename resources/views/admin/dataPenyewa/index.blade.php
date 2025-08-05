@extends('admin.layout')

@section('title', 'Data Penyewa Aktif')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <h1 class="text-3xl font-bold text-gray-800">Data Penyewa Aktif</h1>
        {{-- Mungkin tombol tambah penyewa manual? (Jika ada flow-nya) --}}
        {{-- <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
            + Tambah Penyewa Manual
        </a> --}}
    </div>

    {{-- <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <h1 class="text-3xl font-bold text-gray-800">Manajemen Penyewa</h1>
        <div class="flex space-x-2 mt-4 md:mt-0">
            <a href="{{ route('admin.penyewa.index') }}" 
               class="px-4 py-2 text-sm font-medium rounded-lg {{ !request()->routeIs('admin.penyewa.all-users') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Penyewa Aktif
            </a>
            <a href="{{ route('admin.penyewa.all-users') }}" 
               class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.penyewa.all-users') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Semua User
            </a>
        </div>
    </div> --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
            <p class="font-bold">Berhasil!</p>
            <p class="text-sm">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
            <p class="font-bold">Gagal!</p>
            <p class="text-sm">{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-xl border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-700">Daftar Penyewa Aktif ({{ $totalPenyewaAktif }})</h2>
        </div>
        
        {{-- Filter Form --}}
        <form method="GET" action="{{ route('admin.penyewa.index') }}" class="mb-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4 items-end">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Cari (Nama/Email/No.Kamar)</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2.5 px-3 text-sm" placeholder="Ketik pencarian...">
            </div>
            <div>
                <label for="lantai" class="block text-sm font-medium text-gray-700">Lantai Kamar</label>
                <select name="lantai" id="lantai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2.5 px-3 text-sm">
                    <option value="">Semua Lantai</option>
                    <option value="2" {{ request('lantai') == '2' ? 'selected' : '' }}>Lantai 2</option>
                    <option value="3" {{ request('lantai') == '3' ? 'selected' : '' }}>Lantai 3</option>
                </select>
            </div>
            {{-- <div>
                <label for="akan_berakhir" class="block text-sm font-medium text-gray-700">Sewa Akan Berakhir</label>
                <select name="akan_berakhir" id="akan_berakhir" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2.5 px-3 text-sm">
                    <option value="">Semua</option>
                    <option value="1" {{ request('akan_berakhir') == '1' ? 'selected' : '' }}>Dalam 1 Bulan</option>
                </select>
            </div> --}}
             <div class="lg:col-start-4 flex space-x-2">
                <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Filter
                </button>
                 @if(request()->hasAny(['search', 'lantai', 'akan_berakhir']))
                    <a href="{{ route('admin.penyewa.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Penyewa</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kontak</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kamar</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Periode Sewa</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Dibayar</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($penyewaAktif as $pemesanan)
                        @php
                            $tanggalSelesai = $pemesanan->tanggal_selesai ? \Carbon\Carbon::parse($pemesanan->tanggal_selesai) : null;
                            $totalDibayar = $pemesanan->pembayaran->where('status', 'diterima')->sum('jumlah');
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-4 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $pemesanan->user->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $pemesanan->user->jenis_kelamin ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-700">{{ $pemesanan->user->email ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $pemesanan->user->no_hp ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm font-medium text-gray-900">Kamar {{ $pemesanan->kos->nomor_kamar ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">Lantai {{ $pemesanan->kos->lantai ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-500">
                                <div>{{ $pemesanan->tanggal_masuk ? \Carbon\Carbon::parse($pemesanan->tanggal_masuk)->format('d M Y') : '-' }}</div>
                                <div class="text-xs text-gray-400">s/d {{ $tanggalSelesai ? $tanggalSelesai->format('d M Y') : '-' }}</div>
                                <div class="text-xs text-blue-600">({{ $pemesanan->lama_sewa }} bulan)</div>
                            </td>
                            <td class="px-4 py-4 text-sm text-green-600 font-semibold">
                                Rp{{ number_format($totalDibayar, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <!-- Detail Button -->
                                    <a href="{{ route('admin.pemesanan.show', $pemesanan->id) }}" 
                                       title="Lihat Detail Pemesanan" 
                                       class="inline-flex items-center px-3 py-2 text-xs font-medium text-blue-700 bg-blue-100 border border-blue-200 rounded-lg hover:bg-blue-200 hover:border-blue-300 transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9 6a1 1 0 012 0v2a1 1 0 11-2 0V6zm1 3a1 1 0 100 2h.01a1 1 0 100-2H10z" clip-rule="evenodd" />
                                        </svg>
                                        Detail
                                    </a>
                                    
                                    <!-- Complete Button -->
                                    <form action="{{ route('admin.penyewa.complete', $pemesanan->id) }}" method="POST" onsubmit="return confirm('⚠️ KONFIRMASI SELESAI SEWA\n\nAnda yakin ingin menandai sewa sebagai SELESAI?\n\n👤 Penyewa: {{ $pemesanan->user->name }}\n🏠 Kamar: {{ $pemesanan->kos->nomor_kamar ?? '-' }}\n\n✅ Aksi ini akan:\n- Mengubah status pemesanan menjadi SELESAI\n- Membebaskan kamar untuk disewakan lagi\n- Kamar akan tersedia untuk penyewa baru\n\nLanjutkan?')" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                title="Tandai Selesai Sewa" 
                                                class="inline-flex items-center px-3 py-2 text-xs font-medium text-green-700 bg-green-100 border border-green-200 rounded-lg hover:bg-green-200 hover:border-green-300 transition-all duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Selesai
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                 <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <p class="text-base font-semibold text-gray-600 mb-1">Tidak Ada Penyewa Aktif</p>
                                    <p class="text-sm text-gray-400">Semua kamar kosong atau data tidak cocok dengan filter.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            {{ $penyewaAktif->links() }}
        </div>
    </div>
</div>
@endsection