@extends('app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Pelunasan Pembayaran</h2>
            
            <!-- Booking Information -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Informasi Pemesanan</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Kamar:</span>
                        <span class="font-medium">{{ $pemesanan->kos->nomor_kamar }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Lama Sewa:</span>
                        <span class="font-medium">{{ $pemesanan->lama_sewa }} bulan</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Total Tagihan:</span>
                        <span class="font-medium">Rp {{ number_format($pemesanan->kos->harga_bulanan * $pemesanan->lama_sewa, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">DP Terbayar:</span>
                        <span class="font-medium">Rp {{ number_format($pembayaranDP->jumlah, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-semibold mb-2 text-blue-800">Ringkasan Pelunasan</h3>
                <div class="text-lg">
                    <span class="text-gray-600">Sisa yang harus dibayar:</span>
                    <span class="font-bold text-blue-600">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Payment Form -->
            <form action="{{ route('user.pesan.pelunasan.process', $pemesanan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Payment Method Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Metode Pembayaran</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($paymentMethods as $method)
                        <div class="relative">
                            <input type="radio" 
                                   name="payment_method" 
                                   value="{{ $method->id }}" 
                                   id="payment_{{ $method->id }}" 
                                   class="sr-only peer"
                                   required>
                            <label for="payment_{{ $method->id }}" 
                                   class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:bg-blue-50 peer-checked:border-blue-500">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900">{{ $method->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $method->account_number }}</div>
                                    @if($method->description)
                                    <div class="text-xs text-gray-400 mt-1">{{ $method->description }}</div>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <div class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-blue-500 peer-checked:bg-blue-500"></div>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('payment_method')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Upload Payment Proof -->
                <div class="mb-6">
                    <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                        Bukti Pembayaran
                    </label>
                    <input type="file" 
                           name="bukti_pembayaran" 
                           id="bukti_pembayaran"
                           accept=".jpg,.jpeg,.png"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg"
                           required>
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG. Maksimal 5MB</p>
                    @error('bukti_pembayaran')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Important Notes -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-yellow-800 mb-2">Penting!</h4>
                    <ul class="text-sm text-yellow-700 space-y-1">
                        <li>• Pastikan jumlah yang ditransfer sesuai dengan sisa tagihan</li>
                        <li>• Upload bukti pembayaran yang jelas dan dapat dibaca</li>
                        <li>• Pelunasan akan diverifikasi oleh admin dalam 1x24 jam</li>
                        <li>• Setelah pelunasan diverifikasi, pemesanan akan otomatis aktif</li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <div class="flex space-x-4">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200">
                        Kirim Pelunasan
                    </button>
                    <a href="{{ route('user.riwayat') }}" 
                       class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-3 px-6 rounded-lg text-center transition duration-200">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // File input preview
    const fileInput = document.getElementById('bukti_pembayaran');
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Check file size (5MB limit)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 5MB.');
                this.value = '';
                return;
            }
            
            // Check file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format file tidak didukung. Gunakan JPG, JPEG, atau PNG.');
                this.value = '';
                return;
            }
        }
    });
});
</script>
@endpush
@endsection
