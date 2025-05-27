@extends('app')

@section('content')
@if ($errors->any())
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <h1 class="text-2xl font-bold mb-6">Form Pemesanan Kos</h1>

    <form action="{{ route('user.pesan.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow-md space-y-6">
        @csrf
        <div>
            <label class="block font-semibold mb-1">Kamar yang Dipesan</label>
            <ul class="mb-2">
                @foreach($kamarTersedia as $kamar)
                    <li>Kamar {{ $kamar->nomor_kamar }} (Lantai {{ $kamar->lantai }}) - Rp{{ number_format($kamar->harga_bulanan, 0, ',', '.') }}/bulan
                        <input type="hidden" name="kamar[]" value="{{ $kamar->id }}">
                    </li>
                @endforeach
            </ul>
        </div>
        <div>
            <label class="block font-semibold mb-1" for="tanggal_masuk">Tanggal Masuk</label>
            <input type="date" id="tanggal_masuk" name="tanggal_masuk" required min="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border rounded-lg">
        </div>
        <div>
            <label for="lama_sewa" class="block font-semibold mb-1">Lama Sewa (bulan)</label>
            <input type="number" id="lama_sewa" name="lama_sewa" min="1" max="12" required class="w-full px-4 py-2 border rounded-lg">
        </div>
        <div>
            <label for="total_pembayaran_display" class="block font-semibold mb-1">Total Pembayaran</label>
            <input type="text" id="total_pembayaran_display" readonly class="w-full px-4 py-2 border rounded-lg bg-gray-100">
        </div>
        <div>
            <label for="jenis_pembayaran" class="block font-semibold mb-1">Jenis Pembayaran</label>
            <select name="jenis_pembayaran" id="jenis_pembayaran" class="w-full border rounded-lg px-4 py-2 mb-2">
                <option value="dp" selected>DP (Minimal 30% dari total pembayaran)</option>
                <option value="lunas">Lunas (Bayar penuh)</option>
            </select>
            <small class="text-gray-500">Pilih "Lunas" jika ingin membayar penuh langsung.</small>
        </div>
        <input type="hidden" id="total_pembayaran" name="total_pembayaran">
        <div class="mb-4 text-center">
            <label class="block text-sm font-medium text-gray-700 mb-2">Scan QRIS untuk Pembayaran</label>
            <img src="{{ asset('qris/qris.jpg') }}" alt="QRIS Pembayaran" class="w-64 h-auto mx-auto mb-2">
            <p class="text-sm text-gray-600">Setelah pembayaran, unggah bukti transfer di bawah ini.</p>
        </div>
        <div class="mb-4">
            <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700">Upload Bukti Pembayaran <span class="text-red-500">*</span></label>
            <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" required class="w-full border rounded-lg px-4 py-2">
            @error('bukti_pembayaran')
                <div class="text-red-500 text-sm">{{ $message }}</div>
            @enderror
        </div>
        
        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
            Kirim Pemesanan
        </button>
    </form>
    <script>
        window.hargaKamarList = [
            @foreach($kamarTersedia as $kamar)
                {{ $kamar->harga_bulanan }},
            @endforeach
        ];
    </script>
@endsection
