@extends('app')

@section('title', 'Perpanjang Sewa Kamar Kos')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white p-6 sm:p-8 rounded-xl shadow-2xl">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-indigo-700">Perpanjang Sewa Kamar</h1>
            <p class="text-gray-600">Kamar <span class="font-semibold">{{ $pemesanan->kos->nomor_kamar ?? '-' }}</span> (Lantai {{ $pemesanan->kos->lantai ?? '-' }})</p>
        </div>

        {{-- Notifikasi Kebijakan Perpanjangan --}}
        <div class="mb-6 p-4 bg-yellow-50 rounded-lg border border-yellow-300 text-yellow-700 text-sm">
            <h3 class="font-semibold mb-1">Kebijakan Perpanjangan:</h3>
            <ul class="list-disc list-inside text-xs">
                <li>Anda dapat mengajukan perpanjangan paling lambat <strong class="font-semibold">{{ $batasAkhirPengajuan->format('d F Y') }}</strong> (7 hari setelah masa sewa berakhir).</li>
                <li>Pengajuan setelah tanggal tersebut mungkin tidak dapat diproses. Silakan hubungi admin.</li>
                <li>Perpanjangan dimulai H+1 dari tanggal selesai sewa saat ini: <strong class="font-semibold">{{ \Carbon\Carbon::parse($tanggalSelesaiSewaSaatIni, 'UTC')->locale('id')->isoFormat('D MMMM YYYY') }}</strong>.</li>
            </ul>
        </div>


        @if ($errors->any())
            {{-- ... (kode error handling tetap sama) ... --}}
        @endif

        @if(!$bisaPerpanjang)
            <div class="mb-6 p-4 bg-red-100 rounded-lg border border-red-300 text-red-700 text-center">
                <p class="font-semibold text-lg">Masa Pengajuan Perpanjangan Telah Berakhir</p>
                <p class="text-sm mt-1">Anda sudah tidak dapat mengajukan perpanjangan untuk sewa ini melalui sistem. Silakan hubungi admin untuk bantuan lebih lanjut.</p>
                <a href="{{ route('user.riwayat') }}" class="mt-4 inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                    Kembali ke Riwayat
                </a>
            </div>
        @else
            {{-- Informasi Sewa Saat Ini --}}
            <div class="mb-6 p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                {{-- ... (kode informasi sewa saat ini tetap sama) ... --}}
                 <h2 class="text-lg font-semibold text-indigo-700 mb-2">Informasi Sewa Saat Ini</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                    <p><span class="text-gray-600">Nomor Kamar:</span> <strong class="text-gray-800">{{ $pemesanan->kos->nomor_kamar ?? '-' }}</strong></p>
                    <p><span class="text-gray-600">Harga per Bulan:</span> <strong class="text-gray-800">Rp{{ number_format($pemesanan->kos->harga_bulanan ?? 0, 0, ',', '.') }}</strong></p>
                    <p><span class="text-gray-600">Tanggal Masuk Awal:</span> <strong class="text-gray-800">{{ \Carbon\Carbon::parse($pemesanan->tanggal_masuk)->format('d F Y') }}</strong></p>
                    <p><span class="text-gray-600">Sewa Saat Ini Berakhir:</span> <strong class="text-red-600">{{ $tanggalSelesaiSewaSaatIni }}</strong></p>
                </div>
            </div>

            <form id="formPerpanjang" method="POST" action="{{ route('user.pesan.perpanjang.store', $pemesanan->id) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Tanggal Mulai Perpanjangan (Info) --}}
                <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                     {{-- ... (kode tanggal mulai perpanjangan info tetap sama) ... --}}
                    <label class="block font-semibold text-green-700 mb-1">Tanggal Mulai Perpanjangan</label>
                    @if($disarankanTanggalMulaiPerpanjang)
                    <p class="text-green-800 text-lg font-medium">
                        {{ \Carbon\Carbon::parse($disarankanTanggalMulaiPerpanjang)->format('d F Y') }}
                    </p>
                    <p class="text-xs text-green-600 mt-1">Perpanjangan akan dimulai secara otomatis setelah masa sewa Anda saat ini berakhir.</p>
                    @else
                    <p class="text-red-600">Tidak dapat menentukan tanggal mulai perpanjangan. Silakan hubungi admin.</p>
                    @endif
                </div>

                {{-- ... (sisa form tetap sama: lama sewa, total biaya, bukti bayar, tombol) ... --}}
                 {{-- Tambah Lama Sewa --}}
                <div>
                    <label for="tambah_lama_sewa" class="block text-sm font-semibold text-gray-700 mb-1.5">Tambah Lama Sewa (bulan)</label>
                    <input type="number" id="tambah_lama_sewa" name="tambah_lama_sewa" min="1"
                        data-harga="{{ $pemesanan->kos->harga_bulanan ?? 0 }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                        required value="{{ old('tambah_lama_sewa', 1) }}">
                    @error('tambah_lama_sewa')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Total Biaya Perpanjangan --}}
                <div>
                    <label for="total_biaya_perpanjangan_display" class="block text-sm font-semibold text-gray-700 mb-1.5">Total Biaya Perpanjangan</label>
                    <input type="text" id="total_biaya_perpanjangan_display" readonly
                        class="w-full border-gray-300 rounded-lg shadow-sm px-4 py-2.5 bg-gray-100 text-gray-700 font-medium"
                        placeholder="Akan terhitung otomatis">
                    <input type="hidden" id="total_biaya_perpanjangan" name="total_biaya_perpanjangan" value="{{ old('total_biaya_perpanjangan') }}">
                    @error('total_biaya_perpanjangan')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Upload Bukti Pembayaran --}}
                <div class="mb-4 text-center bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Scan QRIS untuk Pembayaran</label>
                    <img src="{{ asset('qris/qris.jpg') }}" alt="QRIS Pembayaran" class="w-48 h-auto mx-auto mb-2 border rounded-md">
                    <p class="text-xs text-gray-600">Setelah pembayaran, unggah bukti transfer di bawah ini.</p>
                </div>

                <div>
                    <label for="bukti_pembayaran" class="block text-sm font-semibold text-gray-700 mb-1.5">Upload Bukti Pembayaran <span class="text-red-500">*</span></label>
                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran"
                        class="w-full text-sm text-gray-500 border-gray-300 rounded-lg shadow-sm
                                file:mr-4 file:py-2.5 file:px-4
                                file:rounded-l-lg file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                    @error('bukti_pembayaran')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <input type="hidden" name="is_perpanjangan" value="true">

                <div class="pt-2">
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold px-6 py-3.5 rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 transition-all duration-200 shadow-lg hover:shadow-xl active:scale-[0.98]">
                        Ajukan Perpanjangan Sewa
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tambahLamaSewaInput = document.getElementById('tambah_lama_sewa');
    const totalPerpanjangDisplay = document.getElementById('total_biaya_perpanjangan_display');
    const totalPerpanjangHidden = document.getElementById('total_biaya_perpanjangan');
    
    // Cek apakah elemen ada sebelum mengakses dataset atau menambahkan event listener
    if (tambahLamaSewaInput && totalPerpanjangDisplay && totalPerpanjangHidden) {
        const hargaBulanan = parseInt(tambahLamaSewaInput.dataset.harga || 0);

        const hitungTotalPerpanjang = () => {
            const lamaSewa = parseInt(tambahLamaSewaInput.value) || 0;
            const total = hargaBulanan * lamaSewa;

            totalPerpanjangDisplay.value = total > 0 ? `Rp${total.toLocaleString('id-ID')}` : '';
            totalPerpanjangHidden.value = total || '0';
        };

        tambahLamaSewaInput.addEventListener('input', hitungTotalPerpanjang);
        hitungTotalPerpanjang(); // Hitung saat pertama kali halaman dibuka
    }

    // Konfirmasi Sebelum Submit
    const formPerpanjang = document.getElementById('formPerpanjang');
    if (formPerpanjang) {
        formPerpanjang.addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah submit langsung

            const lamaSewa = document.getElementById('tambah_lama_sewa').value;
            const totalBiayaDisplay = document.getElementById('total_biaya_perpanjangan_display').value;
            const kamar = "{{ $pemesanan->kos->nomor_kamar ?? '-' }}";
            
            const message = `Anda akan mengajukan perpanjangan sewa untuk:\n` +
                          `Kamar: ${kamar}\n` +
                          `Selama: ${lamaSewa} bulan\n` +
                          `Total Biaya: ${totalBiayaDisplay}\n\n` +
                          `Pastikan data dan bukti pembayaran sudah benar. Lanjutkan?`;

            if (confirm(message)) {
                this.submit(); // Lanjutkan submit jika user konfirmasi
            }
        });
    }
});
</script>
@endpush