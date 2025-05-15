@extends('app')
@section('content')
<h1 class="text-2xl font-bold mb-6">Perpanjang Sewa Kamar {{ $pemesanan->kos->nomor_kamar }}</h1>
<form method="POST" action="{{ route('user.pesan.perpanjang.store', $pemesanan->id) }}" enctype="multipart/form-data" class="max-w-lg mx-auto bg-white p-6 rounded-xl shadow">
    @csrf
    <div class="mb-4">
        <label class="block font-semibold mb-1">Tanggal Mulai Perpanjangan</label>
        <input type="date" name="tanggal_mulai" class="w-full border rounded px-3 py-2" required>
    </div>
    <div class="mb-4">
        <label class="block font-semibold mb-1">Tambah Lama Sewa (bulan)</label>
        <input type="number" id="tambah_lama_sewa" name="tambah_lama_sewa" min="1"
               data-harga="{{ $pemesanan->kos->harga_bulanan }}"
               class="w-full border rounded px-3 py-2" required>
    </div>
    <div class="mb-4">
        <label class="block font-semibold mb-1">Estimasi Biaya Perpanjangan</label>
        <input type="text" id="estimasi_biaya_perpanjangan" value="Rp{{ number_format($pemesanan->kos->harga_bulanan,0,',','.') }} x bulan"
               class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
    </div>
    <div class="mb-4">
        <label class="block font-semibold mb-1">Total Biaya Perpanjangan</label>
        <input type="text" id="total_biaya_perpanjangan_display" readonly
               class="w-full border rounded px-3 py-2 bg-gray-100">
        <input type="hidden" id="total_biaya_perpanjangan" name="total_biaya_perpanjangan">
    </div>
    <div class="mb-4">
        <label class="block font-semibold mb-1">Upload Bukti Pembayaran</label>
        <input type="file" name="bukti_pembayaran" class="w-full border rounded px-3 py-2" required>
    </div>
    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">Ajukan Perpanjangan</button>
</form>
@endsection