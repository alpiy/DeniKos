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
            <h2 class="text-xl font-semibold text-gray-700">Daftar Penyewa Saat Ini ({{ $totalPenyewaAktif }})</h2>
            {{-- Tambahkan link ke data penyewa yang sudah selesai jika perlu --}}
            {{-- <a href="{{ route('admin.laporan.index', ['status_histori' => 'selesai']) }}" class="text-sm text-indigo-600 hover:underline">Lihat Riwayat Penyewa Selesai</a> --}}
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
            <div>
                <label for="akan_berakhir" class="block text-sm font-medium text-gray-700">Sewa Akan Berakhir</label>
                <select name="akan_berakhir" id="akan_berakhir" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2.5 px-3 text-sm">
                    <option value="">Semua</option>
                    <option value="7" {{ request('akan_berakhir') == '7' ? 'selected' : '' }}>Dalam 7 Hari</option>
                    {{-- Tambah opsi lain jika perlu, misal 14 hari, 30 hari --}}
                </select>
            </div>
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
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kamar Ditempati</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Periode Sewa</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Bayar (Verified)</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sisa Hari</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($penyewaAktif as $pemesanan)
                        @php
                            $tanggalSelesai = null;
                            $sisaHari = null;
                            if ($pemesanan->tanggal_selesai) {
                                $tanggalSelesai = \Carbon\Carbon::parse($pemesanan->tanggal_selesai);
                                $sisaHari = \Carbon\Carbon::now()->diffInDays($tanggalSelesai, false); // false agar bisa negatif
                            }
                            $totalDibayar = $pemesanan->pembayaran->where('status', 'diterima')->sum('jumlah');
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $pemesanan->user->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $pemesanan->user->jenis_kelamin ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-700">{{ $pemesanan->user->email ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $pemesanan->user->no_hp ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">Kamar {{ $pemesanan->kos->nomor_kamar ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">Lantai {{ $pemesanan->kos->lantai ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $pemesanan->tanggal_masuk ? \Carbon\Carbon::parse($pemesanan->tanggal_masuk)->format('d M y') : '-' }}
                                <span class="text-gray-400 mx-1">&rarr;</span>
                                {{ $tanggalSelesai ? $tanggalSelesai->format('d M y') : '-' }}
                                <div class="text-xs text-gray-400">({{ $pemesanan->lama_sewa }} bulan)</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">
                                Rp{{ number_format($totalDibayar, 0, ',', '.') }}
                            </td>
                             <td class="px-4 py-4 whitespace-nowrap text-sm font-medium
                                @if(!is_null($sisaHari) && $sisaHari < 0) 
                                @elseif(!is_null($sisaHari) && $sisaHari <= 7) 
                                @else text-gray-700
                                @endif">
                                @if(!is_null($sisaHari))
                                    {{ $sisaHari < 0 ? 'Lewat ' . abs($sisaHari) . ' hari' : $sisaHari . ' hari lagi' }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.pemesanan.show', $pemesanan->id) }}" title="Detail Pemesanan" class="text-blue-600 hover:text-blue-800 p-1.5 hover:bg-blue-100 rounded-md transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9 6a1 1 0 012 0v2a1 1 0 11-2 0V6zm1 3a1 1 0 100 2h.01a1 1 0 100-2H10z" clip-rule="evenodd" /></svg>
                                    </a>
                                    <form action="{{ route('admin.penyewa.complete', $pemesanan->id) }}" method="POST" onsubmit="return confirm('Tandai penyewa \'{{ $pemesanan->user->name }}\' untuk kamar \'{{ $pemesanan->kos->nomor_kamar ?? '-' }}\' sebagai selesai sewa?')">
                                        @csrf
                                        <button type="submit" title="Tandai Selesai Sewa" class="text-green-600 hover:text-green-800 p-1.5 hover:bg-green-100 rounded-md transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                 <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <p class="text-base font-semibold text-gray-600 mb-1">Tidak Ada Penyewa Aktif</p>
                                    <p class="text-sm text-gray-400">Semua kamar mungkin tersedia atau data tidak cocok filter.</p>
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