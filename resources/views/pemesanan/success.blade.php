@extends('app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 py-8">
    <div class="max-w-3xl mx-auto px-4">
        <!-- Success Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-500 rounded-full mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-green-600 mb-2">Pemesanan Berhasil!</h1>
            <p class="text-gray-600 text-lg">Terima kasih! Pemesanan Anda sedang diproses oleh admin.</p>
        </div>

        <!-- Detail Pemesanan Card -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Detail Pemesanan
            </h2>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-600">No. Kamar:</span>
                        <span class="font-semibold text-gray-800">{{ $pemesanan->kos->nomor_kamar }}</span>
                    </div>
                    
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-600">Tanggal Masuk:</span>
                        <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($pemesanan->tanggal_pesan)->format('d F Y') }}</span>
                    </div>
                    
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-600">Lama Sewa:</span>
                        <span class="font-semibold text-gray-800">{{ $pemesanan->lama_sewa }} bulan</span>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-600">Total Pembayaran:</span>
                        <span class="font-bold text-green-600 text-lg">Rp{{ number_format($pemesanan->total_pembayaran, 0, ',', '.') }}</span>
                    </div>
                    
                    <!-- <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-600">Jenis Pembayaran:</span>
                        <span class="font-semibold text-gray-800">{{ ucfirst($pemesanan->jenis_pembayaran) }}</span>
                    </div> -->
                    
                    <div class="flex justify-between py-2">
                        <span class="font-medium text-gray-600">Status:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($pemesanan->status_pemesanan == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($pemesanan->status_pemesanan == 'approved') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($pemesanan->status_pemesanan) }}
                        </span>
                    </div>
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
            <ul class="text-blue-700 space-y-2">
                <li class="flex items-start">
                    <span class="font-semibold mr-2">1.</span>
                    <span>Admin akan memverifikasi bukti pembayaran Anda dalam 1x24 jam</span>
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
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('landing') }}" 
               class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors text-center">
                Beranda
            </a>
            <a href="{{ route('user.kos.index') }}" 
               class="px-6 py-3 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-700 transition-colors text-center">
                Pesan Kamar Lain
            </a>
        </div>
    </div>
</div>
@endsection
