@extends('app')

@section('title', 'Pembayaran Pemesanan')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                💳 Pembayaran Pemesanan
            </h1>
            <p class="text-gray-600 mt-2">
                Lengkapi pembayaran untuk menyelesaikan pemesanan Anda
            </p>
        </div>

        <!-- Booking Summary Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-100">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-800">Ringkasan Pemesanan</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <!-- Left Side -->
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 text-sm">🏠 Nomor Kamar:</span>
                        <span class="font-semibold text-gray-800">{{ $pemesanan->kos->nomor_kamar }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 text-sm">📅 Tanggal Masuk:</span>
                        <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($pemesanan->tanggal_masuk)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 text-sm">📅 Tanggal Selesai:</span>
                        <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($pemesanan->tanggal_selesai)->format('d/m/Y') }}</span>
                    </div>
                </div>

                <!-- Right Side -->
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 text-sm">⏰ Lama Sewa:</span>
                        <span class="font-semibold text-gray-800">{{ $pemesanan->lama_sewa }} bulan</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 text-sm">💰 Harga/Bulan:</span>
                        <span class="font-semibold text-gray-800">Rp {{ number_format($pemesanan->kos->harga_bulanan, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 text-sm">💵 Total Biaya:</span>
                        <span class="font-bold text-lg text-green-600">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <form action="{{ route('user.pembayaran.process', $pemesanan->id) }}" method="POST" enctype="multipart/form-data" id="paymentForm">
            @csrf
            <div class="grid lg:grid-cols-3 gap-6">
                <!-- Left Column - Payment Options -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Payment Type Selection -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-lg flex items-center justify-center mr-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Pilih Jenis Pembayaran</h3>
                        </div>

                        <div class="space-y-4">
                            <!-- DP Option -->
                            <div class="relative">
                                <input type="radio" id="dp" name="jenis_pembayaran" value="dp" checked 
                                       class="peer sr-only" onchange="updatePaymentAmount()">
                                <label for="dp" class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <span class="font-semibold text-gray-800">💳 Bayar DP (Down Payment)</span>
                                            <span class="text-sm text-blue-600">50% Fixed</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">
                                            Bayar 50% dari total biaya terlebih dahulu
                                        </p>
                                        <div class="mt-2">
                                            <span class="font-bold text-lg text-blue-600">Rp {{ number_format($totalTagihan * 0.5, 0, ',', '.') }}</span>
                                            <span class="text-sm text-gray-500 ml-2">(50% dari total)</span>
                                        </div>
                                        <div class="mt-1 text-sm text-gray-500">
                                            Sisa pelunasan: <span class="font-semibold text-orange-600">Rp {{ number_format($totalTagihan * 0.5, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-blue-500 peer-checked:bg-blue-500"></div>
                                    </div>
                                </label>
                            </div>

                            <!-- Lunas Option -->
                            <div class="relative">
                                <input type="radio" id="lunas" name="jenis_pembayaran" value="lunas" 
                                       class="peer sr-only" onchange="updatePaymentAmount()">
                                <label for="lunas" class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-green-500 peer-checked:bg-green-50 transition-all">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <span class="font-semibold text-gray-800">✅ Bayar Lunas</span>
                                            <span class="text-sm text-green-600">Hemat waktu</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">
                                            Bayar seluruh biaya sewa sekaligus
                                        </p>
                                        <div class="mt-2">
                                            <span class="font-bold text-lg text-green-600">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-green-500 peer-checked:bg-green-500"></div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Card -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg flex items-center justify-center mr-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Pilih Metode Pembayaran</h3>
                        </div>

                        <!-- Dynamic Payment Methods -->
                        <div class="space-y-4 mb-6">
                            @foreach($paymentMethods as $method)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                                    <div class="flex items-start space-x-3">
                                        <input type="radio" name="payment_method" value="{{ $method->id }}" 
                                               id="payment_{{ $method->id }}" 
                                               class="mt-1"
                                               @if($loop->first) checked @endif>
                                        <div class="flex-1">
                                            <label for="payment_{{ $method->id }}" class="cursor-pointer">
                                                <div class="flex items-center justify-between mb-2">
                                                    <h4 class="font-semibold text-gray-800">{{ $method->name }}</h4>
                                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                                                        {{ ucfirst(str_replace('_', ' ', $method->type)) }}
                                                    </span>
                                                </div>
                                                
                                                @if($method->account_number)
                                                    <div class="text-sm text-gray-600 mb-2">
                                                        <span class="font-medium">No. Rekening:</span> 
                                                        <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $method->account_number }}</span>
                                                    </div>
                                                    <div class="text-sm text-gray-600 mb-2">
                                                        <span class="font-medium">Atas Nama:</span> {{ $method->account_name }}
                                                    </div>
                                                @endif
                                                
                                                @if($method->type === 'qris' && $method->logo_path)
                                                    <div class="text-center my-3">
                                                        <div class="inline-block bg-gray-50 p-3 rounded-lg">
                                                            <img src="{{ asset($method->logo_path) }}" alt="QRIS" class="w-32 h-auto mx-auto">
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                @if($method->instructions)
                                                    <p class="text-xs text-gray-500 mt-2">{{ $method->instructions }}</p>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div>
                            <label for="bukti_pembayaran" class="block text-sm font-semibold text-gray-700 mb-2">
                                📎 Upload Bukti Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" required accept="image/*" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <p class="text-xs text-gray-500 mt-1">Format: PNG, JPG, JPEG (maksimal 5MB)</p>
                            @error('bukti_pembayaran')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Syarat dan Ketentuan -->
                        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-yellow-800 mb-2">⚠️ Syarat dan Ketentuan Pembayaran</h4>
                                    <ul class="text-xs text-yellow-700 space-y-1">
                                        <li>• <strong>Dengan melakukan pembayaran, Anda setuju untuk menempati kamar yang telah dipesan</strong></li>
                                        <li>• Pembayaran yang sudah diverifikasi admin <strong>tidak dapat dibatalkan</strong></li>
                                        <li>• Pembatalan hanya tersedia sebelum melakukan pembayaran apapun</li>
                                        <li>• Pastikan detail pemesanan sudah benar sebelum melakukan pembayaran</li>
                                        <li>• Untuk keperluan mendesak setelah pembayaran, hubungi admin langsung</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Checkbox Agreement -->
                        <div class="flex items-start mt-4">
                            <input type="checkbox" id="agreement" name="agreement" required 
                                   class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="agreement" class="ml-2 text-sm text-gray-700">
                                Saya telah membaca dan <strong>menyetujui syarat dan ketentuan</strong> di atas. 
                                Saya memahami bahwa pembayaran yang sudah diverifikasi tidak dapat dibatalkan.
                            </label>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Summary & Submit -->
                <div class="space-y-6">
                    <!-- Payment Summary -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Ringkasan Pembayaran</h3>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 text-sm">Total Tagihan:</span>
                                <span class="font-semibold text-gray-800">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 text-sm">Jenis Pembayaran:</span>
                                <span id="paymentType" class="font-semibold text-blue-600">DP</span>
                            </div>
                            <div class="flex justify-between items-center py-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg px-3">
                                <span class="text-gray-700 font-medium">Yang Dibayar Sekarang:</span>
                                <span id="currentPayment" class="font-bold text-lg text-green-600">Rp {{ number_format($totalTagihan * 0.5, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Instructions -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-lg flex items-center justify-center mr-2">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Panduan Pembayaran</h3>
                        </div>

                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-start">
                                <span class="text-blue-500 mr-2 mt-0.5">1.</span>
                                <span>Scan kode QRIS dengan aplikasi pembayaran Anda</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-blue-500 mr-2 mt-0.5">2.</span>
                                <span>Masukkan nominal sesuai pilihan pembayaran</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-blue-500 mr-2 mt-0.5">3.</span>
                                <span>Screenshot bukti pembayaran yang berhasil</span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-blue-500 mr-2 mt-0.5">4.</span>
                                <span>Upload bukti pembayaran di form ini</span>
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-xs text-yellow-700">
                                <span class="font-medium">Penting!</span> 
                                Pastikan nominal yang dibayar sesuai dengan yang dipilih di form ini.
                            </p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                        <button type="submit" id="submitBtn" 
                                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold px-6 py-4 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 inline-flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <span class="submit-text">Kirim Pembayaran</span>
                            <div class="loading-spinner hidden ml-2">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                            </div>
                        </button>
                        <p class="text-xs text-gray-500 mt-2 text-center">
                            Pembayaran akan diverifikasi oleh admin dalam 1x24 jam
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
const totalTagihan = {{ $totalTagihan }};
const dpAmount = totalTagihan * 0.5; // Fixed 50% DP

function updatePaymentAmount() {
    const dpRadio = document.getElementById('dp');
    const lunasRadio = document.getElementById('lunas');
    const paymentType = document.getElementById('paymentType');
    const currentPayment = document.getElementById('currentPayment');
    
    if (dpRadio.checked) {
        paymentType.textContent = 'DP';
        currentPayment.textContent = 'Rp ' + dpAmount.toLocaleString('id-ID');
    } else {
        paymentType.textContent = 'Lunas';
        currentPayment.textContent = 'Rp ' + totalTagihan.toLocaleString('id-ID');
    }
}

// Initialize display
document.addEventListener('DOMContentLoaded', function() {
    updatePaymentAmount();
    
    // Form submission handling
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        const submitText = submitBtn.querySelector('.submit-text');
        const spinner = submitBtn.querySelector('.loading-spinner');
        
        submitBtn.disabled = true;
        submitText.textContent = 'Mengirim...';
        spinner.classList.remove('hidden');
    });
});
</script>
@endsection
