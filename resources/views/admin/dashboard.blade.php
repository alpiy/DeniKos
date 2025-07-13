@extends('admin.layout')

@section('title', 'Dashboard Admin DeniKos')

@section('content')
    <h1 class="text-3xl font-bold mb-8 text-gray-800">Dashboard Admin</h1>
  
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6 mb-8"> {{-- Ubah jadi 3 kolom untuk statistik utama --}}
        <div class="bg-white p-6 rounded-xl shadow-lg text-center transform hover:scale-105 transition-transform duration-200 border-l-4 border-indigo-500">
            <h2 class="text-lg font-semibold text-gray-700">Total Kamar Kos</h2>
            <p class="text-4xl font-bold text-indigo-600 mt-1">{{ $jumlahKos }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-lg text-center transform hover:scale-105 transition-transform duration-200 border-l-4 border-purple-500">
            <h2 class="text-lg font-semibold text-gray-700">Total Transaksi</h2>
            <p class="text-4xl font-bold text-purple-600 mt-1">{{ $totalPemesananAll }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-lg text-center transform hover:scale-105 transition-transform duration-200 border-l-4 border-yellow-500">
            <h2 class="text-lg font-semibold text-gray-700">Pemesanan Pending</h2>
            <p class="text-4xl font-bold text-yellow-500 mt-1">{{ $pending }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-lg text-center transform hover:scale-105 transition-transform duration-200 border-l-4 border-green-500">
            <h2 class="text-lg font-semibold text-gray-700">Penyewa Aktif</h2>
            <p class="text-4xl font-bold text-green-500 mt-1">{{ $diterima }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-lg text-center transform hover:scale-105 transition-transform duration-200 border-l-4 border-red-500">
            <h2 class="text-lg font-semibold text-gray-700">Ditolak / Batal</h2>
            <p class="text-4xl font-bold text-red-500 mt-1">{{ $ditolak }}</p>
        </div>
         <div class="bg-white p-6 rounded-xl shadow-lg text-center transform hover:scale-105 transition-transform duration-200 border-l-4 border-blue-500">
            <h2 class="text-lg font-semibold text-gray-700">Sewa Selesai</h2>
            <p class="text-4xl font-bold text-blue-500 mt-1">{{ $selesai }}</p>
        </div>
    </div>

    {{-- Ringkasan Pemesanan Terbaru --}}
    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-xl mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Pemesanan Terbaru (5 Terakhir)</h2>
            <a href="{{ route('admin.laporan.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">
                Lihat Semua Histori &rarr;
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penyewa</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kamar</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl. Pesan</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($pemesananTerbaru as $pesanan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $pesanan->user->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $pesanan->user->email ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">Kamar {{ $pesanan->kos->nomor_kamar ?? 'N/A' }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($pesanan->tanggal_pesan)->isoFormat('LL') }}</td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($pesanan->status_pemesanan == 'diterima') 
                                @elseif($pesanan->status_pemesanan == 'pending')  
                                @elseif($pesanan->status_pemesanan == 'ditolak') 
                                @elseif($pesanan->status_pemesanan == 'batal') 
                                @elseif($pesanan->status_pemesanan == 'selesai') 
                                @endif">
                                {{ ucfirst($pesanan->status_pemesanan) }}
                            </span>
                        </td>
                         <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.pemesanan.show', $pesanan->id) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada pemesanan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    {{-- Grafik Pendapatan --}}
    <div class="mt-10">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Grafik Pendapatan Bulanan (Pembayaran Terverifikasi)</h2>
        <div class="bg-white p-6 rounded-xl shadow-xl">
            <canvas id="grafikPendapatan"
                height="100"
                data-labels='@json($labels)'
                data-data='@json($data)'
            ></canvas>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Additional protection specifically for dashboard
    document.addEventListener('DOMContentLoaded', function() {
        // Clear any existing back attempts when dashboard loads
        if (window.backAttempts !== undefined) {
            window.backAttempts = 0;
        }
        
        // Force replace state to ensure clean history
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        
        console.log('Dashboard loaded with clean state');
    });
</script>
@endpush