@extends('app')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow-md text-center">
        <h1 class="text-2xl font-bold text-green-600 mb-4">Pemesanan Berhasil!</h1>
        <p class="mb-4">Terima kasih, pemesanan Anda sedang kami proses dan akan segera dicek oleh admin.</p>

        <div class="text-left mt-6">
            <h2 class="text-lg font-semibold mb-2">Detail Pemesanan:</h2>
            <ul class="list-disc list-inside space-y-1">
                <li><strong>No.Kamar:</strong> {{ $pemesanan->kos->nomor_kamar }}</li>
                <li><strong>Tanggal Masuk:</strong> {{ $pemesanan->tanggal_pesan }}</li>
                <li><strong>Lama Sewa:</strong> {{ $pemesanan->lama_sewa }} bulan</li>
                <li><strong>Total Bayar:</strong> Rp{{ number_format($pemesanan->total_pembayaran, 0, ',', '.') }}</li>
                <li><strong>Status:</strong> <span class="text-yellow-600">{{ ucfirst($pemesanan->status_pemesanan) }}</span></li>
            </ul>
        </div>
    </div>
@endsection
