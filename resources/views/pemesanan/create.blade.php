@extends('app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Pesan Kamar Kos</h1>
            <p class="text-gray-600 text-lg">Lengkapi formulir di bawah untuk memesan kamar kos impian Anda</p>
        </div>



        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <h3 class="text-red-800 font-medium">Terdapat kesalahan dalam form:</h3>
                </div>
                <ul class="mt-2 text-red-700 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.pesan.store') }}" method="POST" enctype="multipart/form-data" id="bookingForm" class="space-y-6">
        @csrf
        
        <!-- Room Selection Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-800">Kamar yang Dipilih</h2>
            </div>
            <div class="space-y-3">
                @foreach($kamarTersedia as $kamar)
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-semibold text-gray-800">Kamar {{ $kamar->nomor_kamar }}</h3>
                                <p class="text-sm text-gray-600">Lantai {{ $kamar->lantai }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-indigo-600">Rp{{ number_format($kamar->harga_bulanan, 0, ',', '.') }}</span>
                                <p class="text-sm text-gray-500">/bulan</p>
                            </div>
                        </div>
                        <input type="hidden" name="kamar[]" value="{{ $kamar->id }}">
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Booking Details Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-800">Detail Pemesanan</h2>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="tanggal_masuk">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"></path>
                        </svg>
                        Tanggal Masuk
                    </label>
                    <input type="date" id="tanggal_masuk" name="tanggal_masuk" required min="{{ date('Y-m-d') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>
                
                <div>
                    <label for="lama_sewa" class="block text-sm font-semibold text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Lama Sewa (bulan)
                    </label>
                    <input type="number" id="lama_sewa" name="lama_sewa" min="1" max="12" required value="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                           placeholder="Masukkan jumlah bulan">
                </div>
            </div>
        </div>

        <!-- Payment Summary Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-gradient-to-r from-amber-500 to-orange-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-800">Ringkasan Pembayaran</h2>
            </div>

            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-4 mb-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Pembayaran:</span>
                    <span id="total_pembayaran_display" class="text-2xl font-bold text-gray-800">Rp 0</span>
                </div>
                <div id="payment-breakdown"></div>
            </div>

            <div>
                <label for="jenis_pembayaran" class="block text-sm font-semibold text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Jenis Pembayaran
                </label>
                <select name="jenis_pembayaran" id="jenis_pembayaran" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    <option value="dp" selected>DP (Minimal 30% dari total pembayaran)</option>
                    <option value="lunas">Lunas (Bayar penuh)</option>
                </select>
                <p class="text-sm text-gray-500 mt-1">💡 Pilih "Lunas" jika ingin membayar penuh langsung.</p>
            </div>
            <input type="hidden" id="total_pembayaran" name="total_pembayaran">
        </div>

        <!-- Payment Method Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-800">Pembayaran</h2>
            </div>

            <div class="text-center mb-6">
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg p-4 mb-4">
                    <h3 class="font-semibold text-gray-800 mb-2">Scan QRIS untuk Pembayaran</h3>
                    <div class="inline-block bg-white p-4 rounded-lg shadow-sm">
                        <img src="{{ asset('qris/qris.jpg') }}" alt="QRIS Pembayaran" class="w-48 h-auto mx-auto">
                    </div>
                    <p class="text-sm text-gray-600 mt-3">📱 Scan kode QR di atas dengan aplikasi mobile banking atau e-wallet Anda</p>
                </div>
            </div>

            <div>
                <label for="bukti_pembayaran" class="block text-sm font-semibold text-gray-700 mb-2">
                    📎 Upload Bukti Pembayaran <span class="text-red-500">*</span>
                </label>
                <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" required accept="image/*" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <p class="text-xs text-gray-500 mt-1">Format: PNG, JPG, JPEG (maksimal 5MB)</p>
                @error('bukti_pembayaran')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <!-- Submit Button -->
        <div class="text-center pt-6">
            <button type="submit" id="submitBtn" 
                    class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold px-8 py-4 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
                <span class="submit-text">Kirim Pemesanan</span>
                <div class="loading-spinner hidden ml-2">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                </div>
            </button>
        </div>
    </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Room prices from server
    const hargaKamarList = [
        @foreach($kamarTersedia as $kamar)
            {{ $kamar->harga_bulanan }},
        @endforeach
    ];

    // DOM elements
    const lamaSewa = document.getElementById('lama_sewa');
    const totalPembayaranDisplay = document.getElementById('total_pembayaran_display');
    const totalPembayaran = document.getElementById('total_pembayaran');
    const jenisPembayaran = document.getElementById('jenis_pembayaran');
    const buktiPembayaran = document.getElementById('bukti_pembayaran');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('bookingForm');

    // Calculate total payment
    function calculateTotal() {
        console.log('Calculating total...'); // Debug log
        
        const months = parseInt(lamaSewa.value) || 0;
        const totalHarga = hargaKamarList.reduce((sum, harga) => sum + harga, 0) * months;
        
        console.log('Months:', months, 'Total harga:', totalHarga); // Debug log
        
        // Calculate based on payment type
        let finalTotal = totalHarga;
        const jenisPembayaranValue = jenisPembayaran.value;
        
        if (jenisPembayaranValue === 'dp') {
            finalTotal = Math.ceil(totalHarga * 0.3); // DP 30%
        }
        
        console.log('Final total:', finalTotal, 'Payment type:', jenisPembayaranValue); // Debug log
        
        // Update display
        totalPembayaranDisplay.textContent = `Rp ${finalTotal.toLocaleString('id-ID')}`;
        totalPembayaran.value = finalTotal;

        // Show breakdown info
        updatePaymentBreakdown(totalHarga, finalTotal, jenisPembayaranValue);

        // Animate the display
        if (finalTotal > 0) {
            totalPembayaranDisplay.classList.add('animate-pulse');
            setTimeout(() => {
                totalPembayaranDisplay.classList.remove('animate-pulse');
            }, 500);
        }
    }

    // Update payment breakdown information
    function updatePaymentBreakdown(totalHarga, finalTotal, jenisPembayaran) {
        const breakdownContainer = document.getElementById('payment-breakdown');
        
        if (!breakdownContainer) return;
        
        let breakdownHTML = '';
        
        if (jenisPembayaran === 'dp' && totalHarga > 0) {
            const sisaPembayaran = totalHarga - finalTotal;
            breakdownHTML = `
                <div class="text-xs text-gray-600 mt-2 p-2 bg-blue-50 rounded">
                    <div class="flex justify-between">
                        <span>Total Sewa:</span>
                        <span>Rp ${totalHarga.toLocaleString('id-ID')}</span>
                    </div>
                    <div class="flex justify-between font-semibold text-blue-700">
                        <span>DP (30%):</span>
                        <span>Rp ${finalTotal.toLocaleString('id-ID')}</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Sisa Pembayaran:</span>
                        <span>Rp ${sisaPembayaran.toLocaleString('id-ID')}</span>
                    </div>
                </div>
            `;
        } else if (jenisPembayaran === 'lunas' && totalHarga > 0) {
            breakdownHTML = `
                <div class="text-xs text-gray-600 mt-2 p-2 bg-green-50 rounded">
                    <div class="flex justify-between font-semibold text-green-700">
                        <span>Pembayaran Lunas:</span>
                        <span>Rp ${finalTotal.toLocaleString('id-ID')}</span>
                    </div>
                </div>
            `;
        }
        
        breakdownContainer.innerHTML = breakdownHTML;
    }

    // File upload handling - sederhana
    function handleFileUpload() {
        const file = buktiPembayaran.files[0];
        
        if (file) {
            // Validasi ukuran file max 5MB
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file maksimal 5MB');
                buktiPembayaran.value = '';
                return;
            }
            
            // Tampilkan info file terpilih
            const existingInfo = document.querySelector('.file-upload-info');
            if (existingInfo) {
                existingInfo.remove();
            }
            
            const fileInfo = document.createElement('div');
            fileInfo.className = 'file-upload-info mt-2 p-2 bg-green-100 border border-green-300 rounded text-sm text-green-800';
            fileInfo.innerHTML = `✓ File terpilih: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            
            buktiPembayaran.parentNode.appendChild(fileInfo);
        }
    }

    // Form validation - sederhana dan mudah dipahami
    function handleSubmit(e) {
        const submitText = submitBtn.querySelector('.submit-text');
        const loadingSpinner = submitBtn.querySelector('.loading-spinner');
        
        // Tampilkan loading
        submitText.textContent = 'Mengirim...';
        loadingSpinner.classList.remove('hidden');
        submitBtn.disabled = true;
        
        // Validasi form
        if (!buktiPembayaran.files[0]) {
            e.preventDefault();
            alert('Mohon upload bukti pembayaran');
            resetSubmitButton();
            return;
        }
        
        if (!lamaSewa.value || lamaSewa.value < 1) {
            e.preventDefault();
            alert('Mohon masukkan lama sewa yang valid');
            resetSubmitButton();
            return;
        }

        const tanggalMasuk = document.getElementById('tanggal_masuk').value;
        if (!tanggalMasuk) {
            e.preventDefault();
            alert('Mohon pilih tanggal masuk');
            resetSubmitButton();
            return;
        }

        // Pastikan total pembayaran sudah dihitung
        if (!totalPembayaran.value || totalPembayaran.value <= 0) {
            e.preventDefault();
            calculateTotal();
            if (!totalPembayaran.value || totalPembayaran.value <= 0) {
                alert('Terjadi kesalahan dalam kalkulasi pembayaran');
                resetSubmitButton();
                return;
            }
        }
    }

    function resetSubmitButton() {
        const submitText = submitBtn.querySelector('.submit-text');
        const loadingSpinner = submitBtn.querySelector('.loading-spinner');
        
        submitText.textContent = 'Kirim Pemesanan';
        loadingSpinner.classList.add('hidden');
        submitBtn.disabled = false;
    }

    // Event listeners - sederhana
    lamaSewa.addEventListener('input', calculateTotal);
    jenisPembayaran.addEventListener('change', calculateTotal);
    buktiPembayaran.addEventListener('change', handleFileUpload);
    form.addEventListener('submit', handleSubmit);

    // Inisialisasi - pastikan kalkulasi berjalan saat halaman dimuat
    console.log('DOM loaded, initializing...'); // Debug log
    console.log('Room prices:', hargaKamarList); // Debug log
    
    // Pastikan semua element sudah ada
    if (lamaSewa && totalPembayaranDisplay && jenisPembayaran) {
        calculateTotal();
        
        // Backup calculation untuk memastikan
        setTimeout(function() {
            console.log('Backup calculation triggered'); // Debug log
            calculateTotal();
        }, 500);
    } else {
        console.error('Required elements not found!'); // Debug log
    }
});
</script>

<style>
/* Styling sederhana dan mudah dipahami */
.transition-all {
    transition: all 0.3s ease;
}

/* Focus states untuk accessibility */
input:focus, select:focus {
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

/* Mobile responsive */
@media (max-width: 768px) {
    .text-4xl {
        font-size: 2.5rem;
    }
    
    .w-48 {
        width: 10rem;
    }
    
    .grid.md\\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
