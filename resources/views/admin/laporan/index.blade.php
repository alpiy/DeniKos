@extends('admin.layout')

@section('title', 'Histori & Laporan Pemesanan')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <h1 class="text-3xl font-bold text-gray-800">Histori & Laporan Pemesanan</h1>
        <div class="mt-4 md:mt-0 flex space-x-2">
            <a href="{{ route('admin.laporan.exportExcel', request()->query()) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 100 4v2a2 2 0 01-2 2H4a2 2 0 01-2-2V6zm4.293 5.293a1 1 0 011.414 0L10 13.586l2.293-2.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" /></svg>
                Export Excel
            </a>
            <a href="{{ route('admin.laporan.exportPDF', request()->query()) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 10-2 0v3a1 1 0 102 0v-3zm2-3a1 1 0 011 1v5a1 1 0 11-2 0v-5a1 1 0 011-1zm4-1a1 1 0 10-2 0v7a1 1 0 102 0V8z" clip-rule="evenodd" /></svg>
                Export PDF
            </a>
        </div>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-xl">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold text-gray-700">Filter Data Pemesanan</h2>
             <p class="text-lg font-semibold text-gray-800 mt-2 md:mt-0">
                Total Pendapatan (Sesuai Filter): <span class="text-green-600">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</span>
            </p>
        </div>

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="mb-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-4 items-end">
            <div>
                <label for="search_histori" class="block text-sm font-medium text-gray-700">Cari (Nama/Email/No.Kamar)</label>
                <input type="text" name="search_histori" id="search_histori" value="{{ request('search_histori') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2.5 px-3 text-sm" placeholder="Ketik pencarian...">
            </div>
            <div>
                <label for="status_histori" class="block text-sm font-medium text-gray-700">Status Pemesanan</label>
                <select name="status_histori" id="status_histori" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2.5 px-3 text-sm">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status_histori') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="diterima" {{ request('status_histori') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="ditolak" {{ request('status_histori') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="batal" {{ request('status_histori') == 'batal' ? 'selected' : '' }}>Batal</option>
                    <option value="selesai" {{ request('status_histori') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div>
                <label for="bulan_histori" class="block text-sm font-medium text-gray-700">Bulan Pesan</label>
                <select name="bulan_histori" id="bulan_histori" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2.5 px-3 text-sm">
                    <option value="">Semua Bulan</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('bulan_histori') == $i ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label for="tahun_histori" class="block text-sm font-medium text-gray-700">Tahun Pesan</label>
                 <select name="tahun_histori" id="tahun_histori" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2.5 px-3 text-sm">
                    <option value="">Semua Tahun</option>
                    @for ($y = date('Y'); $y >= date('Y') - 5; $y--) {{-- Ambil 5 tahun terakhir --}}
                        <option value="{{ $y }}" {{ request('tahun_histori') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="lg:col-span-4 flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0">
                <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Terapkan Filter
                </button>
                 @if(request()->hasAny(['search_histori', 'status_histori', 'bulan_histori', 'tahun_histori']))
                    <a href="{{ route('admin.laporan.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Reset Filter
                    </a>
                @endif
            </div>
        </form>

        {{-- Tabel Histori --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Penyewa</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kamar</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Periode Sewa</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Bayar (Verified)</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($historiPemesanan as $pesanan)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $pesanan->user->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $pesanan->user->email ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">Kamar {{ $pesanan->kos->nomor_kamar ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">Lantai {{ $pesanan->kos->lantai ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $pesanan->tanggal_masuk ? \Carbon\Carbon::parse($pesanan->tanggal_masuk)->isoFormat('D MMM YYYY') : '-' }}
                            <span class="text-gray-400 mx-1">&rarr;</span>
                            {{ $pesanan->tanggal_selesai ? \Carbon\Carbon::parse($pesanan->tanggal_selesai)->isoFormat('D MMM YYYY') : '-' }}
                            <div class="text-xs text-gray-400">({{ $pesanan->lama_sewa }} bulan)</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($pesanan->status_pemesanan == 'diterima') 
                                @elseif($pesanan->status_pemesanan == 'pending')                                 @elseif($pesanan->status_pemesanan == 'ditolak') 
                                @elseif($pesanan->status_pemesanan == 'batal') 
                                @elseif($pesanan->status_pemesanan == 'selesai') 
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($pesanan->status_pemesanan) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">
                            Rp{{ number_format($pesanan->pembayaran()->where('status', 'diterima')->sum('jumlah'), 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $pesanan->is_perpanjangan ? 'bg-purple-100 text-purple-800' : 'bg-teal-100 text-teal-800' }}">
                                {{ $pesanan->is_perpanjangan ? 'Perpanjangan' : 'Awal' }}
                            </span>
                        </td>
                         <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.pemesanan.show', $pesanan->id) }}" class="text-indigo-600 hover:text-indigo-800 transition-colors duration-150">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 10h.01M13 10h.01M7 10h.01"></path></svg>
                                <p class="text-base font-semibold text-gray-600 mb-1">Tidak Ada Data Pemesanan</p>
                                <p class="text-sm text-gray-400">Coba ubah filter Anda atau belum ada pemesanan yang tercatat.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            {{ $historiPemesanan->links() }}
        </div>
    </div>
</div>
@endsection