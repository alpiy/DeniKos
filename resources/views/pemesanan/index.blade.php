@extends('app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Riwayat Pemesanan</h1>
                    <p class="text-gray-600">Kelola dan pantau status pemesanan kamar kos Anda</p>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                        <p class="text-sm text-green-600 mt-1">
                            Untuk pengembalian dana, silakan hubungi admin di 
                            <a href="https://wa.me/628xxxxxxx" class="underline hover:text-green-700">WhatsApp</a> 
                            atau email: admin@email.com
                        </p>
                    </div>
                </div>
            </div>
        @endif
        <!-- Pemesanan Cards -->
        <div class="space-y-6">
            @forelse ($pesanans as $no => $p)
                @php
                    $totalTagihan = $p->lama_sewa * ($p->kos->harga_bulanan ?? 0);
                    $totalDibayar = $p->pembayaran->whereIn('status', ['pending','diterima'])->sum('jumlah');
                    $sisaTagihan = max($totalTagihan - $totalDibayar, 0);
                @endphp
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Kamar {{ $p->kos->nomor_kamar ?? '-' }}</h3>
                                <p class="text-sm text-gray-600">Pemesanan #{{ $no + 1 }}</p>
                            </div>
                            <div class="text-right">
                                <!-- Status Badge -->
                                @if($p->status_pemesanan == 'diterima')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">Diterima</span>
                                @elseif($p->status_pemesanan == 'pending')
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-medium">Pending</span>
                                @elseif($p->status_pemesanan == 'ditolak')
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-medium">Ditolak</span>
                                @elseif($p->status_pemesanan == 'batal')
                                    <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-medium">Dibatalkan</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Detail Pemesanan -->
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Detail Pemesanan
                                </h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tanggal Masuk:</span>
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($p->tanggal_masuk)->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Lama Sewa:</span>
                                        <span class="font-medium">{{ $p->lama_sewa }} bulan</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Total Tagihan:</span>
                                        <span class="font-bold text-gray-800">Rp{{ number_format($totalTagihan, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Pembayaran -->
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    Info Pembayaran
                                </h4>
                                
                                @if($p->status_pemesanan == 'batal')
                                    <div class="text-center py-4">
                                        <span class="text-gray-500 text-sm">Pesanan Dibatalkan</span>
                                        <div class="mt-2">
                                            @if($p->status_refund == 'proses')
                                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">Refund: Proses</span>
                                            @elseif($p->status_refund == 'selesai')
                                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">Refund: Selesai</span>
                                            @else
                                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Refund: Belum</span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <!-- Riwayat Pembayaran -->
                                    <div class="space-y-2 mb-4">
                                        @foreach($p->pembayaran as $bayar)
                                            <div class="flex justify-between items-center text-sm bg-gray-50 p-2 rounded">
                                                <span class="font-medium">{{ ucfirst($bayar->jenis) }}:</span>
                                                <div class="text-right">
                                                    <span class="font-bold">Rp{{ number_format($bayar->jumlah, 0, ',', '.') }}</span>
                                                    <span class="block text-xs text-gray-500">({{ $bayar->status }})</span>
                                                    @if($bayar->bukti_pembayaran)
                                                        <a href="{{ asset('storage/'.$bayar->bukti_pembayaran) }}" target="_blank" 
                                                           class="text-blue-600 hover:underline text-xs">Lihat Bukti</a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Sisa Tagihan -->
                                    <div class="border-t pt-3">
                                        <div class="flex justify-between items-center">
                                            <span class="font-semibold text-gray-700">Sisa Tagihan:</span>
                                            <div class="text-right">
                                                <span class="font-bold text-lg {{ $sisaTagihan > 0 ? 'text-red-600' : 'text-green-600' }}">
                                                    Rp{{ number_format($sisaTagihan, 0, ',', '.') }}
                                                </span>
                                                <div class="mt-1">
                                                    @if($sisaTagihan == 0)
                                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">Lunas</span>
                                                    @else
                                                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs">Belum Lunas</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Form Pelunasan (jika diperlukan) -->
                        @if($p->status_pemesanan == 'diterima' && $sisaTagihan > 0)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h5 class="font-semibold text-gray-800 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Upload Pelunasan
                                </h5>
                                <form action="{{ route('user.pembayaran.pelunasan', $p->id) }}" method="POST" enctype="multipart/form-data" 
                                      class="bg-orange-50 p-4 rounded-lg">
                                    @csrf
                                    <div class="grid md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Nominal Pelunasan</label>
                                            <input type="number" name="jumlah" min="1" max="{{ $sisaTagihan }}" value="{{ $sisaTagihan }}" 
                                                   placeholder="Nominal" class="w-full border rounded-lg px-3 py-2 text-sm" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Pembayaran</label>
                                            <input type="file" name="bukti_pembayaran" accept="image/*"
                                                   class="w-full border rounded-lg px-3 py-2 text-sm" required>
                                        </div>
                                        <div class="flex items-end">
                                            <button type="submit" 
                                                    class="w-full bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">
                                                Upload Pelunasan
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Card Footer - Actions -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                        <div class="flex space-x-3">
                            @if ($p->status_pemesanan == 'pending')
                                <form action="{{ route('user.pesan.batal', $p->id) }}" method="POST" 
                                      onsubmit="return confirm('Batalkan pemesanan ini?')" class="inline">
                                    @csrf
                                    <button class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition-colors">
                                        Batalkan Pesanan
                                    </button>
                                </form>
                            @elseif ($p->status_pemesanan == 'diterima')
                                <a href="{{ route('user.pesan.perpanjang', $p->id) }}" 
                                   class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors">
                                    Ajukan Perpanjangan
                                </a>
                            @else
                                <a href="{{ asset('storage/'.$p->bukti_pembayaran) }}" target="_blank" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Lihat Bukti Pembayaran
                                </a>
                            @endif
                        </div>

                        <div>
                            @if($p->status_pemesanan == 'diterima' && $sisaTagihan == 0 && $p->pembayaran->where('status','diterima')->count() > 0)
                                <a href="{{ route('user.pemesanan.downloadReceipt', $p->id) }}" 
                                   class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors inline-flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download Tanda Terima
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Belum Ada Pemesanan</h3>
                    <p class="text-gray-600 mb-4">Anda belum memiliki riwayat pemesanan kamar kos.</p>
                    <a href="{{ route('user.dashboard') }}" 
                       class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Mulai Pesan Kamar
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection