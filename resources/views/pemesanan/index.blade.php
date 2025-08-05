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
                        <div class="text-green-800 font-medium">{!! session('success') !!}</div>
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
                    
                    // Check payment status
                    $hasApprovedDP = $p->pembayaran->where('jenis', 'dp')->where('status', 'diterima')->count() > 0;
                    $hasPelunasan = $p->pembayaran->where('jenis', 'pelunasan')->count() > 0;
                    $showPelunasanForm = $p->status_pemesanan == 'diterima' && $hasApprovedDP && !$hasPelunasan && $sisaTagihan > 0;
                    
                    // Check rejected payments
                    $hasRejectedPayment = $p->pembayaran->where('status', 'ditolak')->count() > 0;
                    $latestRejectedPayment = $p->pembayaran->where('status', 'ditolak')->sortByDesc('ditolak_pada')->first();
                    
                    // Check if there's any accepted payment - if yes, no need for upload ulang
                    $hasAcceptedPayment = $p->pembayaran->where('status', 'diterima')->count() > 0;
                    
                    // Only show upload ulang if there's rejected payment AND no accepted payment AND status is not 'batal'
                    $showUploadUlang = $hasRejectedPayment && !$hasAcceptedPayment && $p->status_pemesanan !== 'batal';
                    
                    // Check if user can make new payment (exclude 'batal' status)
                    $canMakeNewPayment = $p->status_pemesanan == 'pending' && ($hasRejectedPayment || !$p->has_payment);
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
                                @elseif($p->status_pemesanan == 'selesai')
                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">Selesai</span>
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
                                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Pesanan Dibatalkan</span>
                                    </div>
                                @else
                                    <!-- Riwayat Pembayaran -->
                                    <div class="space-y-2 mb-4">
                                        @foreach($p->pembayaran as $bayar)
                                            <div class="flex justify-between items-center text-sm bg-gray-50 p-2 rounded">
                                                <span class="font-medium">{{ ucfirst($bayar->jenis) }}:</span>
                                                <div class="text-right">
                                                    <span class="font-bold">Rp{{ number_format($bayar->jumlah, 0, ',', '.') }}</span>
                                                    <div class="text-xs mt-1">
                                                        @if($bayar->status == 'pending')
                                                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full">Menunggu Verifikasi</span>
                                                        @elseif($bayar->status == 'diterima')
                                                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full">Diterima</span>
                                                        @elseif($bayar->status == 'ditolak')
                                                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full">Ditolak</span>
                                                        @endif
                                                    </div>
                                                    @if($bayar->bukti_pembayaran)
                                                        <a href="{{ asset('storage/'.$bayar->bukti_pembayaran) }}" target="_blank" 
                                                           class="text-blue-600 hover:underline text-xs">Lihat Bukti</a>
                                                    @endif
                                                    
                                                    {{-- Tampilkan alasan penolakan --}}
                                                    @if($bayar->status == 'ditolak' && $bayar->alasan_tolak)
                                                        <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded text-xs">
                                                            <div class="font-medium text-red-800 mb-1">Alasan Penolakan:</div>
                                                            <div class="text-red-700">{{ $bayar->alasan_tolak }}</div>
                                                            <div class="mt-1 text-red-600 font-medium">
                                                                💡 Anda bisa upload ulang bukti pembayaran yang sesuai
                                                            </div>
                                                        </div>
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

                        <!-- Pelunasan Button (jika diperlukan) -->
                        @if($showPelunasanForm)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="bg-orange-50 p-4 rounded-lg">
                                    <h5 class="font-semibold text-gray-800 mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Pelunasan Sisa Tagihan
                                    </h5>
                                    
                                    <!-- Info Pelunasan -->
                                    <div class="mb-4 p-3 bg-white rounded-lg border border-orange-200">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-gray-700">Sisa Tagihan yang Harus Dilunasi:</span>
                                            <span class="text-lg font-bold text-orange-600">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            💡 Lakukan pelunasan untuk menyelesaikan pembayaran sepenuhnya.
                                        </p>
                                    </div>
                                    
                                    <a href="{{ route('user.pesan.pelunasan', $p->id) }}" 
                                       class="inline-flex items-center justify-center w-full bg-orange-600 text-white px-4 py-3 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Lakukan Pelunasan
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Card Footer - Actions -->
                    <div class="bg-gray-50 px-6 py-4">
                        <!-- Payment Deadline Alert for Pending Bookings -->
                        @if ($p->status_pemesanan == 'pending' && !$p->has_payment && !$p->is_payment_expired)
                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-orange-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div>
                                            <h5 class="font-medium text-orange-800">Batas Waktu Pembayaran</h5>
                                            <p class="text-sm text-orange-700">
                                                Deadline: {{ $p->payment_deadline->format('d M Y, H:i') }} WIB
                                                <span class="font-semibold block" id="countdown-{{ $p->id }}"></span>
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ route('user.pembayaran.show', $p->id) }}" 
                                       class="bg-green-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                                        💳 Bayar Sekarang
                                    </a>
                                </div>
                            </div>
                        @elseif ($p->status_pemesanan == 'pending' && !$p->has_payment && $p->is_payment_expired)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <h5 class="font-medium text-red-800">Pemesanan Expired</h5>
                                        <p class="text-sm text-red-700">Batas waktu pembayaran telah terlewati. Pemesanan akan dibatalkan otomatis.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="flex justify-between items-center">
                            <div class="flex space-x-3">
                                @if ($p->status_pemesanan == 'pending' && !$p->is_payment_expired)
                                    @if(!$p->has_payment && !$hasAcceptedPayment)
                                        <!-- Belum ada pembayaran sama sekali -->
                                        <a href="{{ route('user.pembayaran.show', $p->id) }}" 
                                           class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                                            💳 Lakukan Pembayaran
                                        </a>
                                    @elseif($hasAcceptedPayment)
                                        <!-- Sudah ada pembayaran yang diterima - show status -->
                                        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded-lg text-sm font-medium">
                                            <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Pembayaran Diterima
                                        </div>
                                    @endif
                                    
                                    <!-- Cancellation logic -->
                                    @php
                                        $totalPaidVerified = $p->pembayaran->where('status', 'diterima')->sum('jumlah');
                                        $totalPaidPending = $p->pembayaran->where('status', 'pending')->sum('jumlah');
                                    @endphp
                                    
                                    <div class="flex items-center space-x-2">
                                        @if($totalPaidPending > 0)
                                            <!-- Ada pembayaran pending -->
                                            <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-2 rounded-lg text-sm font-medium">
                                                <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                </svg>
                                                Menunggu Verifikasi Admin
                                            </div>
                                        @elseif($totalPaidVerified > 0)
                                            <!-- Sudah ada pembayaran verified -->
                                            <div class="bg-blue-100 border border-blue-300 text-blue-800 px-4 py-2 rounded-lg text-sm font-medium">
                                                <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                </svg>
                                                Pembayaran Sudah Diterima
                                            </div>
                                            <div class="text-xs text-gray-600 bg-gray-50 px-3 py-2 rounded-lg border">
                                                💡 Hubungi admin jika ada keperluan mendesak
                                            </div>
                                        @elseif($hasRejectedPayment)
                                            <!-- Ada pembayaran ditolak - bisa upload ulang atau batalkan -->
                                            <a href="{{ route('user.pembayaran.show', $p->id) }}" 
                                               class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">
                                                🔄 Upload Ulang Bukti Bayar
                                            </a>
                                            <form action="{{ route('user.pesan.batal', $p->id) }}" method="POST" 
                                                  onsubmit="return confirm('Batalkan pemesanan ini?\n\nTindakan ini tidak dapat dibatalkan.')" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition-colors">
                                                    Batalkan Pesanan
                                                </button>
                                            </form>
                                        @else
                                            <!-- Belum ada pembayaran sama sekali -->
                                            <form action="{{ route('user.pesan.batal', $p->id) }}" method="POST" 
                                                  onsubmit="return confirm('Batalkan pemesanan ini?\n\nTindakan ini tidak dapat dibatalkan.')" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition-colors">
                                                    Batalkan Pesanan
                                                </button>
                                            </form>
                                            {{-- <div class="text-xs text-green-600 bg-green-50 px-3 py-2 rounded-lg border border-green-200">
                                                ✅ Pembatalan gratis sampai deadline pembayaran
                                            </div> --}}
                                        @endif
                                    </div>
                            @elseif ($p->status_pemesanan == 'diterima')
                                <div class="text-xs text-gray-600 mt-2">
        💡 Masa sewa berakhir: {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}
    </div>
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
                    <a href="{{ route('landing') }}" 
                       class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Mulai Pesan Kamar
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Cancellation Policy Info - SIMPLIFIED -->
        {{-- <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-blue-900 mb-2">📋 Kebijakan Pembatalan Pemesanan</h3>
                    <div class="text-sm text-blue-800 space-y-2">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white rounded-lg p-3 border border-blue-200">
                                <div class="flex items-center mb-2">
                                    <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                                    <span class="font-semibold">Belum Bayar</span>
                                </div>
                                <p class="text-xs">Gratis pembatalan sampai deadline pembayaran (24 jam setelah booking)</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-blue-200">
                                <div class="flex items-center mb-2">
                                    <span class="w-3 h-3 bg-orange-500 rounded-full mr-2"></span>
                                    <span class="font-semibold">Pembayaran Ditolak</span>
                                </div>
                                <p class="text-xs">Bisa batalkan pesanan atau upload ulang bukti pembayaran yang benar</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-blue-200">
                                <div class="flex items-center mb-2">
                                    <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                                    <span class="font-semibold">Sudah Bayar</span>
                                </div>
                                <p class="text-xs">Pembatalan tidak tersedia. Hubungi admin untuk keperluan mendesak</p>
                            </div>
                        </div>
                        <p class="text-xs mt-3 text-blue-700">
                            <strong>Catatan:</strong> Jika pembayaran ditolak admin, Anda akan mendapat notifikasi dengan alasan penolakan. 
                            Silakan perbaiki dan upload ulang bukti pembayaran yang sesuai, atau batalkan pemesanan jika tidak jadi melanjutkan.
                        </p>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</div>

<script>
// Countdown timers for all pending bookings
@foreach($pesanans as $p)
    @if($p->status_pemesanan == 'pending' && !$p->has_payment && !$p->is_payment_expired)
        (function() {
            function updateCountdown{{ $p->id }}() {
                const deadline = new Date('{{ $p->payment_deadline->toISOString() }}');
                const now = new Date();
                const timeDiff = deadline - now;
                
                const countdownElement = document.getElementById('countdown-{{ $p->id }}');
                if (!countdownElement) return;
                
                if (timeDiff <= 0) {
                    countdownElement.innerHTML = '<span class="text-red-600 font-bold">EXPIRED!</span>';
                    setTimeout(() => location.reload(), 2000);
                    return;
                }
                
                const hours = Math.floor(timeDiff / (1000 * 60 * 60));
                const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);
                
                countdownElement.innerHTML = 
                    `Sisa waktu: <span class="font-mono font-bold text-red-600">${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}</span>`;
            }
            
            updateCountdown{{ $p->id }}();
            setInterval(updateCountdown{{ $p->id }}, 1000);
        })();
    @endif
@endforeach

// Function for back button in warning message
function goBack() {
    window.history.back();
}
</script>

        <!-- Cancellation Policy Info - SIMPLIFIED -->
        {{-- <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-blue-900 mb-2">📋 Kebijakan Pembatalan Pemesanan</h3>
                    <div class="text-sm text-blue-800 space-y-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white rounded-lg p-3 border border-blue-200">
                                <div class="flex items-center mb-2">
                                    <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                                    <span class="font-semibold">Belum Bayar</span>
                                </div>
                                <p class="text-xs">Gratis pembatalan kapan saja sebelum deadline pembayaran</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-blue-200">
                                <div class="flex items-center mb-2">
                                    <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                                    <span class="font-semibold">Sudah Bayar</span>
                                </div>
                                <p class="text-xs">Pembatalan tidak tersedia. Hubungi admin untuk keperluan mendesak</p>
                            </div>
                        </div>
                        <p class="text-xs mt-3 text-blue-700">
                            <strong>Catatan:</strong> Dengan melakukan pembayaran, Anda setuju untuk menempati kamar yang telah dipesan. 
                            Untuk informasi lebih lanjut, hubungi customer service kami.
                        </p>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</div>

@endsection