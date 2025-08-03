@extends('app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-6">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Compact Header Section -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mb-3">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">🏠 Pesan Kamar Kos</h1>
            <p class="text-gray-600">Lengkapi data pemesanan terlebih dahulu</p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-3 rounded-lg mb-4">
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <h3 class="text-red-800 font-medium text-sm">Terdapat kesalahan dalam form:</h3>
                </div>
                <ul class="mt-1 text-red-700 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.pesan.store') }}" method="POST" enctype="multipart/form-data" id="bookingForm">
        @csrf
        
        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Left Column -->
            <div class="space-y-4">
                <!-- Room Selection Card -->
                <div class="bg-white rounded-xl shadow-md p-4 border border-gray-100">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-2">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Kamar yang Dipilih</h2>
                    </div>
                    <div class="space-y-2">
                        @foreach($kamarTersedia as $kamar)
                            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-lg p-3">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h3 class="font-semibold text-gray-800 text-sm">Kamar {{ $kamar->nomor_kamar }}</h3>
                                        <p class="text-xs text-gray-600">Lantai {{ $kamar->lantai }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-lg font-bold text-indigo-600">Rp{{ number_format($kamar->harga_bulanan, 0, ',', '.') }}</span>
                                        <p class="text-xs text-gray-500">/bulan</p>
                                    </div>
                                </div>
                                <input type="hidden" name="kamar[]" value="{{ $kamar->id }}">
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Booking Details Card -->
                <div class="bg-white rounded-xl shadow-md p-4 border border-gray-100">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-2">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Detail Pemesanan</h2>
                    </div>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1" for="tanggal_masuk">
                                📅 Tanggal Masuk
                            </label>
                            <input type="date" id="tanggal_masuk" name="tanggal_masuk" required min="{{ date('Y-m-d') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm">
                        </div>
                        
                        <div>
                            <label for="lama_sewa" class="block text-sm font-semibold text-gray-700 mb-1">
                                ⏰ Lama Sewa (bulan)
                            </label>
                            <input type="number" id="lama_sewa" name="lama_sewa" min="1" max="12" required value="1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm"
                                   placeholder="Masukkan jumlah bulan">
                        </div>
                    </div>
                </div>

                <!-- Payment Summary Card -->
                <div class="bg-white rounded-xl shadow-md p-4 border border-gray-100">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-amber-500 to-orange-600 rounded-lg flex items-center justify-center mr-2">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Ringkasan Biaya</h2>
                    </div>

                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-3 mb-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm">Total Biaya Sewa:</span>
                            <span id="total_pembayaran_display" class="text-xl font-bold text-gray-800">Rp 0</span>
                        </div>
                     <div id="payment-breakdown" class="text-xs text-gray-500 mt-1">
                            <p>* Pembayaran akan dilakukan setelah pemesanan dibuat</p>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="text-sm text-blue-700">
                                <p class="font-medium">Info Pembayaran:</p>
                                <p class="text-xs mt-1">Setelah pemesanan dibuat, Anda akan diarahkan ke halaman pembayaran untuk melakukan DP atau pembayaran lunas.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
                <!-- Booking Terms Card -->
                <div class="bg-white rounded-xl shadow-md p-4 border border-gray-100">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-2">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Syarat & Ketentuan</h2>
                    </div>

                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex items-start">
                            <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Pemesanan akan direview oleh admin dalam 1x24 jam</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Pembayaran dilakukan setelah pemesanan disetujui</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>DP minimal 30% dari total biaya sewa</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Kamar dapat dibatalkan jika pembayaran belum lunas</span>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="text-sm text-yellow-700">
                                <p class="font-medium">Penting!</p>
                                <p class="text-xs mt-1">Pastikan data yang diisi sudah benar. Perubahan setelah pemesanan memerlukan persetujuan admin.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button Card -->
                <div class="bg-white rounded-xl shadow-md p-4 border border-gray-100">
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">✅ Konfirmasi Pemesanan</h3>
                        <p class="text-sm text-gray-600 mb-4">Pastikan semua data sudah benar sebelum membuat pemesanan</p>
                        <button type="submit" id="submitBtn" 
                                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 inline-flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <span class="submit-text">Buat Pemesanan</span>
                            <div class="loading-spinner hidden ml-2">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                            </div>
                        </button>
                        <p class="text-xs text-gray-500 mt-2">Setelah pemesanan dibuat, Anda akan diarahkan ke halaman pembayaran</p>
                    </div>
                </div>
            </div>
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
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('bookingForm');

    // Calculate total payment
    function calculateTotal() {
        console.log('Calculating total...'); // Debug log
        
        const months = parseInt(lamaSewa.value) || 0;
        const totalHarga = hargaKamarList.reduce((sum, harga) => sum + harga, 0) * months;
        
        console.log('Months:', months, 'Total harga:', totalHarga); // Debug log
        
        // Update display (for user information only)
        totalPembayaranDisplay.textContent = `Rp ${totalHarga.toLocaleString('id-ID')}`;

        // Show breakdown info
        updatePaymentBreakdown(totalHarga);

        // Animate the display
        if (totalHarga > 0) {
            totalPembayaranDisplay.classList.add('animate-pulse');
            setTimeout(() => {
                totalPembayaranDisplay.classList.remove('animate-pulse');
            }, 500);
        }
    }

    // Update payment breakdown information
    function updatePaymentBreakdown(totalHarga) {
        const breakdownContainer = document.getElementById('payment-breakdown');
        
        if (!breakdownContainer) return;
        
        let breakdownHTML = '';
        
        if (totalHarga > 0) {
            breakdownHTML = `
                <div class="text-xs text-gray-600 mt-2 p-2 bg-gray-50 rounded">
                    <div class="flex justify-between">
                        <span>Total Biaya Sewa:</span>
                        <span class="font-semibold">Rp ${totalHarga.toLocaleString('id-ID')}</span>
                    </div>
                    <div class="text-center text-blue-600 mt-1">
                        <span class="text-xs">Pembayaran akan dilakukan di halaman berikutnya</span>
                    </div>
                </div>
            `;
        } else {
            breakdownHTML = '<p class="text-xs text-gray-500">* Pembayaran akan dilakukan setelah pemesanan dibuat</p>';
        }
        
        breakdownContainer.innerHTML = breakdownHTML;
    }

    // File upload handling - removed (not needed for booking form)
    
    // Form validation - simplified for booking only
    function handleSubmit(e) {
        const submitText = submitBtn.querySelector('.submit-text');
        const loadingSpinner = submitBtn.querySelector('.loading-spinner');
        
        // Show loading
        submitText.textContent = 'Mengirim...';
        loadingSpinner.classList.remove('hidden');
        submitBtn.disabled = true;
        
        // Basic validation
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
    }

    function resetSubmitButton() {
        const submitText = submitBtn.querySelector('.submit-text');
        const loadingSpinner = submitBtn.querySelector('.loading-spinner');
        
        submitText.textContent = 'Buat Pemesanan';
        loadingSpinner.classList.add('hidden');
        submitBtn.disabled = false;
    }

    // Event listeners - simplified
    lamaSewa.addEventListener('input', calculateTotal);
    form.addEventListener('submit', handleSubmit);

    // Initialize - ensure calculation runs when page loads
    console.log('DOM loaded, initializing...'); // Debug log
    console.log('Room prices:', hargaKamarList); // Debug log
    
    // Make sure all elements exist
    if (lamaSewa && totalPembayaranDisplay) {
        calculateTotal();
        
        // Backup calculation to ensure
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
/* Styling untuk layout horizontal yang compact */
.transition-all {
    transition: all 0.3s ease;
}

/* Focus states untuk accessibility */
input:focus, select:focus {
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

/* Optimasi untuk screenshot horizontal */
@media (min-width: 1024px) {
    .max-w-7xl {
        max-width: 90rem;
    }
    
    .grid.grid-cols-1.lg\\:grid-cols-2 {
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
}

/* Compact card styling */
.rounded-xl {
    border-radius: 0.75rem;
}

.shadow-md {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Mobile responsive - stack vertically */
@media (max-width: 1023px) {
    .text-3xl {
        font-size: 2rem;
    }
    
    .w-40 {
        width: 8rem;
    }
    
    .grid.grid-cols-1.lg\\:grid-cols-2 {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .max-w-7xl {
        max-width: 42rem;
    }
}

/* Tablet responsive */
@media (min-width: 768px) and (max-width: 1023px) {
    .grid.grid-cols-1.lg\\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
}

/* Optimasi untuk QRIS image */
.w-40 {
    width: 10rem;
}

/* Button hover effects */
button:hover {
    transform: translateY(-1px);
}

button:active {
    transform: translateY(0);
}

/* Card hover effects */
.bg-white:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Loading animation */
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Gradient backgrounds */
.bg-gradient-to-br {
    background-image: linear-gradient(to bottom right, var(--tw-gradient-stops));
}

.bg-gradient-to-r {
    background-image: linear-gradient(to right, var(--tw-gradient-stops));
}

/* Text sizing for compact design */
.text-lg {
    font-size: 1.125rem;
    line-height: 1.75rem;
}

.text-sm {
    font-size: 0.875rem;
    line-height: 1.25rem;
}

.text-xs {
    font-size: 0.75rem;
    line-height: 1rem;
}

/* Form input styling */
input[type="date"], input[type="number"], input[type="file"], select {
    background-color: #ffffff;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

input[type="date"]:focus, input[type="number"]:focus, select:focus {
    border-color: #6366f1;
    ring: 2px;
    ring-color: #6366f1;
    ring-opacity: 0.5;
}

/* Space optimization */
.space-y-4 > * + * {
    margin-top: 1rem;
}

.space-y-3 > * + * {
    margin-top: 0.75rem;
}

.space-y-2 > * + * {
    margin-top: 0.5rem;
}
</style>
@endsection
