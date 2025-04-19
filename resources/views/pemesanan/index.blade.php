@extends('app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Daftar Pemesanan Anda</h1>

    @forelse ($pesanans as $pesanan)
        <div class="bg-white p-4 rounded-lg shadow mb-4">
            <h2 class="text-lg font-semibold text-indigo-600">{{ $pesanan->kos->nama ?? '-' }}</h2>
            <p class="text-gray-700">Tanggal Masuk: {{ $pesanan->tgl_masuk }}</p>
            <p class="text-gray-700">Status: 
                <span class="px-2 py-1 text-sm rounded 
                    @if($pesanan->status == 'pending') bg-status-pending
                    @elseif($pesanan->status == 'diterima') bg-status-accepted
                    @else bg-status-rejected @endif">
                    {{ ucfirst($pesanan->status) }}
                </span>
            </p>
        </div>
    @empty
        <p class="text-gray-500">Belum ada pemesanan.</p>
    @endforelse
@endsection
