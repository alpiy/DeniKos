@extends('admin.layout')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Detail Pemesanan</h1>

    <div class="space-y-4">
        <p><strong>Nama:</strong> {{ $pemesanan->nama }}</p>
        <p><strong>Email:</strong> {{ $pemesanan->email }}</p>
        <p><strong>No HP:</strong> {{ $pemesanan->no_hp }}</p>
        <p><strong>Kos:</strong> {{ $pemesanan->kos->nama ?? '-' }}</p>
        <p><strong>Tanggal Masuk:</strong> {{ $pemesanan->tgl_masuk }}</p>
        <p><strong>Status Refund:</strong>
            @if($pemesanan->status_pemesanan === 'batal')
                @if($pemesanan->status_refund === 'belum')
                    <form action="{{ route('admin.pemesanan.refund', $pemesanan->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-yellow-500 text-white px-2 py-1 rounded text-xs">Proses Refund</button>
                    </form>
                @elseif($pemesanan->status_refund === 'proses')
                    <form action="{{ route('admin.pemesanan.refund', $pemesanan->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded text-xs">Tandai Selesai</button>
                    </form>
                @elseif($pemesanan->status_refund === 'selesai')
                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Selesai</span>
                @endif
            @else
                <span class="text-gray-400 text-xs">-</span>
            @endif
        </p>

        <p><strong>Bukti Pembayaran:</strong><br>
            @if($pemesanan->bukti_pembayaran)
                <img src="{{ asset('storage/'.$pemesanan->bukti_pembayaran) }}" class="w-64 rounded shadow">
            @else
                Tidak ada bukti.
            @endif
        </p>
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
