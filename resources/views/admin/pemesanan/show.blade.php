@extends('admin.layout')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Detail Pemesanan</h1>

    <div class="space-y-4">
        <p><strong>Nama:</strong> {{ $pemesanan->user->name ?? '-' }}</p>
        <p><strong>Email:</strong> {{ $pemesanan->user->email ?? '-' }}</p>
        <p><strong>No HP:</strong> {{ $pemesanan->user->no_hp ?? '-' }}</p>
        <p><strong>Kos:</strong> Kamar {{ $pemesanan->kos->nomor_kamar ?? '-' }} (Lantai {{ $pemesanan->kos->lantai ?? '-' }})</p>
        <p><strong>Tanggal Masuk:</strong> {{ $pemesanan->tanggal_masuk }}</p>
        <p><strong>Lama Sewa:</strong> {{ $pemesanan->lama_sewa }} bulan</p>
        <p><strong>Total Tagihan:</strong> Rp{{ number_format($totalTagihan,0,',','.') }}</p>
        <p><strong>Total Dibayar:</strong> Rp{{ number_format($totalDibayar,0,',','.') }}</p>
        <p><strong>Sisa Tagihan:</strong> <span class="{{ $sisaTagihan > 0 ? 'text-red-600 font-bold' : 'text-green-600 font-bold' }}">Rp{{ number_format($sisaTagihan,0,',','.') }}</span></p>
        <p><strong>Status Pembayaran:</strong>
            @if($sisaTagihan == 0)
                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Lunas</span>
            @else
                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">Belum Lunas</span>
            @endif
        </p>
        <div>
            <strong>Histori Pembayaran:</strong>
            <ul class="text-xs mt-1">
                @foreach($pemesanan->pembayaran as $bayar)
                    <li class="mb-1">
                        <span class="font-semibold">{{ ucfirst($bayar->jenis) }}:</span> Rp{{ number_format($bayar->jumlah,0,',','.') }}
                        ({{ $bayar->status }})
                        @if($bayar->bukti_pembayaran)
                            <a href="{{ asset('storage/'.$bayar->bukti_pembayaran) }}" target="_blank" class="text-blue-600 underline ml-1">Bukti</a>
                        @endif
                        @if($bayar->status == 'pending')
                            <form action="{{ route('admin.pembayaran.verifikasi', $bayar->id) }}" method="POST" class="inline ml-2">
                                @csrf
                                <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded text-xs">Verifikasi</button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @if($pemesanan->status_pemesanan === 'pending')
    <div class="space-x-2">
        <form action="{{ route('admin.pemesanan.approve', $pemesanan->id) }}" method="POST" class="inline">
            @csrf
             @if(request()->has('is_perpanjangan') || old('is_perpanjangan') || $pemesanan->is_perpanjangan ?? false)
        <input type="hidden" name="is_perpanjangan" value="1">
    @endif
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Setujui</button>
        </form>

        <form action="{{ route('admin.pemesanan.reject', $pemesanan->id) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Tolak</button>
        </form>
    </div>
@endif
    <div class="mt-4">
        <a href="{{ route('admin.pemesanan.index') }}" class="text-blue-600 hover:underline">Kembali ke Daftar Pemesanan</a>
    </div>
@endsection
