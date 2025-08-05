@extends('admin.layout')

@section('title', 'Manajemen Pemesanan')

@section('content')
<div class="space-y-8">
    <h1 class="text-3xl font-bold text-gray-800">Manajemen Pemesanan</h1>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
            <div class="flex">
                <div class="py-1"><svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                <div>
                    <p class="font-bold">Berhasil!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif
     @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
             <div class="flex">
                <div class="py-1"><svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                <div>
                    <p class="font-bold">Gagal!</p>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-xl">
        <h2 class="text-xl font-semibold text-gray-700 mb-6">Daftar Semua Pemesanan</h2>

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('admin.pemesanan.index') }}" class="mb-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-6 gap-y-4 items-end">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Cari (Nama/Email/No.Kamar)</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2.5 px-3 text-sm" placeholder="Ketik pencarian...">
            </div>
            <div>
                <label for="status_pemesanan" class="block text-sm font-medium text-gray-700">Status Pemesanan</label>
                <select name="status_pemesanan" id="status_pemesanan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2.5 px-3 text-sm">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status_pemesanan') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="diterima" {{ request('status_pemesanan') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="ditolak" {{ request('status_pemesanan') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="batal" {{ request('status_pemesanan') == 'batal' ? 'selected' : '' }}>Batal</option>
                    <option value="selesai" {{ request('status_pemesanan') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            {{-- <div>
                <label for="jenis_pemesanan" class="block text-sm font-medium text-gray-700">Jenis Pemesanan</label>
                <select name="jenis_pemesanan" id="jenis_pemesanan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2.5 px-3 text-sm">
                    <option value="">Semua Jenis</option>
                    <option value="awal" {{ request('jenis_pemesanan') == 'awal' ? 'selected' : '' }}>Awal</option>
                    <option value="perpanjangan" {{ request('jenis_pemesanan') == 'perpanjangan' ? 'selected' : '' }}>Perpanjangan</option>
                </select>
            </div> --}}
             {{-- Filter Bulan & Tahun --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="bulan" class="block text-sm font-medium text-gray-700">Bulan Pesan</label>
                    <select name="bulan" id="bulan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2.5 px-3 text-sm">
                        <option value="">--</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun Pesan</label>
                    <select name="tahun" id="tahun" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2.5 px-3 text-sm">
                        <option value="">--</option>
                        @for ($y = date('Y'); $y >= date('Y') - 3; $y--)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="xl:col-span-4 flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0 mt-2 sm:mt-0">
                <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Filter
                </button>
                 @if(request()->hasAny(['search', 'status_pemesanan', 'jenis_pemesanan', 'bulan', 'tahun']))
                    <a href="{{ route('admin.pemesanan.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- Tabel Pemesanan --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Penyewa</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kamar</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tgl. Pesan</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Periode Sewa</th>
                        {{-- <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis</th> --}}
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($semuaPemesanan as $pesanan)
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
    <div>{{ \Carbon\Carbon::parse($pesanan->tanggal_pesan)->format('d M Y') }}</div>
    <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($pesanan->tanggal_pesan)->diffForHumans() }}</div>
</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $pesanan->tanggal_masuk ? \Carbon\Carbon::parse($pesanan->tanggal_masuk)->format('d M y') : '-' }}
                            <span class="text-gray-400 mx-1">&rarr;</span>
                            {{ $pesanan->tanggal_selesai ? \Carbon\Carbon::parse($pesanan->tanggal_selesai)->format('d M y') : '-' }}
                            <div class="text-xs text-gray-400">({{ $pesanan->lama_sewa }} bulan)</div>
                        </td>
                        {{-- <td class="px-4 py-4 whitespace-nowrap">
                             <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $pesanan->is_perpanjangan ? 'bg-purple-100 text-purple-800' : 'bg-teal-100 text-teal-800' }}">
                                {{ $pesanan->is_perpanjangan ? 'Perpanjangan' : 'Awal' }}
                            </span>
                        </td> --}}
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($pesanan->status_pemesanan == 'diterima') bg-green-100 text-green-800
                                @elseif($pesanan->status_pemesanan == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($pesanan->status_pemesanan == 'ditolak') bg-red-100 text-red-800
                                @elseif($pesanan->status_pemesanan == 'batal') bg-gray-100 text-gray-800
                                @elseif($pesanan->status_pemesanan == 'selesai') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($pesanan->status_pemesanan) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.pemesanan.show', $pesanan->id) }}" 
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
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
            {{ $semuaPemesanan->links() }}
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Tambahan style jika diperlukan, misal untuk ikon SVG agar lebih konsisten */
    .table-action-icon {
        @apply h-5 w-5 inline text-gray-500 hover:text-gray-700 transition-colors duration-150;
    }
    .table-action-icon.text-green-600:hover { @apply text-green-800; }
    .table-action-icon.text-red-600:hover { @apply text-red-800; }
    .table-action-icon.text-yellow-600:hover { @apply text-yellow-800; }
    .table-action-icon.text-indigo-600:hover { @apply text-indigo-800; }
</style>
@endpush