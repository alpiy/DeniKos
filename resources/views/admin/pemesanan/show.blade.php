@extends('admin.layout')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Detail Pemesanan</h1>

    <div class="space-y-4">
        <p><strong>Nama:</strong> {{ $pemesanan->nama }}</p>
        <p><strong>Email:</strong> {{ $pemesanan->email }}</p>
        <p><strong>No HP:</strong> {{ $pemesanan->no_hp }}</p>
        <p><strong>Kos:</strong> {{ $pemesanan->kos->nama ?? '-' }}</p>
        <p><strong>Tanggal Masuk:</strong> {{ $pemesanan->tgl_masuk }}</p>

        <p><strong>Bukti Pembayaran:</strong><br>
            @if($pemesanan->bukti_pembayaran)
                <img src="{{ asset('storage/'.$pemesanan->bukti_pembayaran) }}" class="w-64 rounded shadow">
            @else
                Tidak ada bukti.
            @endif
        </p>
    </div>
@endsection
