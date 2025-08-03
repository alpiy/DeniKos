@extends('app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Success Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-500 rounded-full mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-green-600 mb-2">Pemesanan Berhasil!</h1>
            <p class="text-gray-600 text-lg">Terima kasih! {{ count($pemesanans) }} kamar berhasil dipesan.</p>
        </div>

        <!-- Payment Deadline Alert -->
        @if(!$isExpired && $unpaidCount > 0)
        <div class="bg-orange-50 border-l-4 border-orange-400 p-4 mb-6 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-orange-800">Batas Waktu Pembayaran</h3>
                    <div class="mt-2 text-sm text-orange-700">
                        <p>Lakukan pembayaran untuk {{ $unpaidCount }} kamar sebelum: <strong id="deadline-display">{{ $paymentDeadline->format('d F Y, H:i') }} WIB</strong></p>
                        <p class="mt-1 font-semibold">Sisa waktu: <span id="countdown" class="text-red-600"></span></p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($unpaidCount == 0 && count($pemesanans) > 0)
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Status Pembayaran</h3>
                    <p class="mt-1 text-sm text-blue-700">Semua pembayaran telah dikirim dan menunggu verifikasi admin.</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Multiple Booking Cards -->
        <div class="space-y-6 mb-6">
            @foreach($pemesanans as $index => $pemesanan)
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 {{ $loop->first ? 'border-green-500' : 'border-blue-500' }}">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Kamar {{ $pemesanan->kos->nomor_kamar }} - {{ $pemesanan->kos->lantai }}
                    </h2>
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        @if($pemesanan->status_pemesanan == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($pemesanan->status_pemesanan == 'approved') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($pemesanan->status_pemesanan) }}
                    </span>
                </div>
                
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <div class="flex justify-between py-1 text-sm">
                            <span class="text-gray-600">Tanggal Masuk:</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($pemesanan->tanggal_pesan)->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between py-1 text-sm">
                            <span class="text-gray-600">Lama Sewa:</span>
                            <span class="font-medium">{{ $pemesanan->lama_sewa }} bulan</span>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between py-1 text-sm">
                            <span class="text-gray-600">Total:</span>
                            <span class="font-semibold text-green-600">Rp{{ number_format($pemesanan->total_pembayaran, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 text-sm">
                            <span class="text-gray-600">Pembayaran:</span>
                            <span class="font-medium {{ $pemesanan->pembayaran->count() > 0 ? 'text-blue-600' : 'text-red-600' }}">
                                {{ $pemesanan->pembayaran->count() > 0 ? 'Sudah Upload' : 'Belum Bayar' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex justify-end items-center">
                        @if($pemesanan->pembayaran->count() == 0 && !$isExpired)
                            <a href="{{ route('user.pembayaran.show', $pemesanan->id) }}" 
                               class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                                💳 Bayar
                            </a>
                        @else
                            <span class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium">
                                ✓ Selesai
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Summary -->
        <div class="bg-gradient-to-r from-blue-50 to-green-50 border border-blue-200 rounded-xl p-6 mb-6">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Ringkasan Pemesanan
            </h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-700"><span class="font-medium">Total Kamar:</span> {{ count($pemesanans) }} kamar</p>
                    <p class="text-gray-700"><span class="font-medium">Kamar yang dipesan:</span> 
                        @foreach($pemesanans as $pemesanan)
                            No. {{ $pemesanan->kos->nomor_kamar }}{{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    </p>
                </div>
                <div>
                    <p class="text-gray-700"><span class="font-medium">Total Pembayaran:</span> 
                        <span class="font-bold text-green-600 text-lg">
                            Rp{{ number_format($totalPembayaran, 0, ',', '.') }}
                        </span>
                    </p>
                    <p class="text-gray-700"><span class="font-medium">Status Pembayaran:</span> 
                        <span class="font-medium {{ $unpaidCount > 0 ? 'text-orange-600' : 'text-green-600' }}">
                            {{ $unpaidCount > 0 ? $unpaidCount . ' belum bayar' : 'Semua sudah upload' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Informasi Selanjutnya -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
            <h3 class="font-semibold text-blue-800 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Langkah Selanjutnya
            </h3>
            @if($unpaidCount > 0 && !$isExpired)
            <ul class="text-blue-700 space-y-2">
                <li class="flex items-start">
                    <span class="font-semibold mr-2">1.</span>
                    <span>Lakukan pembayaran untuk {{ $unpaidCount }} kamar dalam waktu 24 jam</span>
                </li>
                <li class="flex items-start">
                    <span class="font-semibold mr-2">2.</span>
                    <span>Anda bisa membayar satu per satu atau sekaligus melalui halaman riwayat</span>
                </li>
                <li class="flex items-start">
                    <span class="font-semibold mr-2">3.</span>
                    <span>Admin akan memverifikasi bukti pembayaran dalam 1x24 jam</span>
                </li>
                <li class="flex items-start">
                    <span class="font-semibold mr-2">4.</span>
                    <span>Anda akan mendapat notifikasi melalui email setelah verifikasi selesai</span>
                </li>
            </ul>
            @else
            <ul class="text-blue-700 space-y-2">
                <li class="flex items-start">
                    <span class="font-semibold mr-2">1.</span>
                    <span>Admin akan memverifikasi semua bukti pembayaran dalam 1x24 jam</span>
                </li>
                <li class="flex items-start">
                    <span class="font-semibold mr-2">2.</span>
                    <span>Anda akan mendapat notifikasi melalui email setelah verifikasi selesai</span>
                </li>
                <li class="flex items-start">
                    <span class="font-semibold mr-2">3.</span>
                    <span>Silakan hubungi admin jika ada pertanyaan lebih lanjut</span>
                </li>
            </ul>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('user.riwayat') }}" 
               class="px-8 py-4 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors text-center shadow-lg">
                📋 Lihat Semua Riwayat & Kelola Pembayaran
            </a>
            <a href="{{ route('user.kos.index') }}" 
               class="px-6 py-3 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-700 transition-colors text-center">
                🏠 Pesan Kamar Lain
            </a>
        </div>
    </div>
</div>

@if(!$isExpired && $unpaidCount > 0)
<script>
// Countdown Timer
function updateCountdown() {
    const deadline = new Date('{{ $paymentDeadline->toISOString() }}');
    const now = new Date();
    const timeDiff = deadline - now;
    
    if (timeDiff <= 0) {
        document.getElementById('countdown').innerHTML = '<span class="font-bold">EXPIRED!</span>';
        // Optionally reload page to update status
        setTimeout(() => location.reload(), 2000);
        return;
    }
    
    const hours = Math.floor(timeDiff / (1000 * 60 * 60));
    const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);
    
    document.getElementById('countdown').innerHTML = 
        `<span class="font-mono font-bold">${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}</span>`;
}

// Update countdown every second
updateCountdown();
setInterval(updateCountdown, 1000);
</script>
@endif

@endsection
