@extends('app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Riwayat Pemesanan Anda</h1>
    @if (session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-4">
            {{ session('success') }}
             <br>
        <span class="text-sm text-gray-600">
            Untuk pengembalian dana, silakan hubungi admin di <a href="https://wa.me/628xxxxxxx" class="text-blue-600 underline">WhatsApp</a> atau email: admin@email.com
        </span>
        </div>
    @endif
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-300 text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">#</th>
                    <th class="p-3 border">Kamar</th>
                    <th class="p-3 border">Tanggal Masuk</th>
                    <th class="p-3 border">Lama Sewa</th>
                    <th class="p-3 border">Status</th>
                    <th class="p-3 border">Refund</th>
                    <th class="p-3 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pesanans as $no => $p)
                    <tr>
                        <td class="p-3 border">{{ $no+1 }}</td>
                        <td class="p-3 border">{{ $p->kos->nomor_kamar ?? '-' }}</td>
                        <td class="p-3 border">{{ $p->tanggal_masuk }}</td>
                        <td class="p-3 border">{{ $p->lama_sewa }} bulan</td>
                        <td class="p-3 border capitalize">
                            @if($p->status_pemesanan == 'diterima')
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Diterima</span>
                            @elseif($p->status_pemesanan == 'pending')
                                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">Pending</span>
                            @elseif($p->status_pemesanan == 'ditolak')
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Ditolak</span>
                            @elseif($p->status_pemesanan == 'batal')
                                <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs">Dibatalkan</span>
                            @endif
                        </td>
                        <td class="p-3 border">
                            @if($p->status_pemesanan == 'batal')
                                @if($p->status_refund == 'proses')
                                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">Proses</span>
                                @elseif($p->status_refund == 'selesai')
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Selesai</span>
                                @else
                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">Belum</span>
                                @endif
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="p-3 border">
                            @if ($p->status_pemesanan == 'pending')
                                <form action="{{ route('user.pesan.batal', $p->id) }}" method="POST" onsubmit="return confirm('Batalkan pemesanan ini?')">
                                    @csrf
                                    <button class="bg-red-500 text-white px-3 py-1 rounded text-xs">Batalkan</button>
                                </form>
                            @elseif ($p->status_pemesanan == 'diterima')
                                <a href="{{ route('user.pesan.perpanjang', $p->id) }}" class="text-blue-600 hover:underline">Ajukan Perpanjangan</a>
                            @else
                                <a href="{{ asset('storage/'.$p->bukti_pembayaran) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Bukti Pembayaran</a>
                            @endif
                        </td>
                        @php
                            $totalTagihan = $p->lama_sewa * ($p->kos->harga_bulanan ?? 0);
                            $totalDibayar = $p->pembayaran->whereIn('status', ['pending','diterima'])->sum('jumlah');
                            $sisaTagihan = max($totalTagihan - $totalDibayar, 0);
                        @endphp
                        <td class="p-3 border">
                            <ul class="text-xs">
                                @foreach($p->pembayaran as $bayar)
                                    <li>
                                        <span class="font-semibold">{{ ucfirst($bayar->jenis) }}:</span> Rp{{ number_format($bayar->jumlah,0,',','.') }}
                                        ({{ $bayar->status }})
                                        @if($bayar->bukti_pembayaran)
                                            <a href="{{ asset('storage/'.$bayar->bukti_pembayaran) }}" target="_blank" class="text-blue-600 underline ml-1">Bukti</a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                            @if($p->status_pemesanan == 'batal')
                                <div class="mt-1">
                                    <span class="font-semibold text-gray-500">Pesanan Dibatalkan</span>
                                    <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs ml-2">Tidak Berlaku</span>
                                </div>
                            @else
                                <div class="mt-1">
                                    <span class="font-semibold">Sisa Tagihan:</span> <span class="{{ $sisaTagihan > 0 ? 'text-red-600 font-bold' : 'text-green-600 font-bold' }}">Rp{{ number_format($sisaTagihan,0,',','.') }}</span>
                                    <span class="ml-2">
                                        @if($sisaTagihan == 0)
                                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Lunas</span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">Belum Lunas</span>
                                        @endif
                                    </span>
                                </div>
                                @if($p->status_pemesanan == 'diterima' && $sisaTagihan > 0)
                                    <form action="{{ route('user.pembayaran.pelunasan', $p->id) }}" method="POST" enctype="multipart/form-data" class="mt-2 flex flex-col gap-2">
                                        @csrf
                                        <input type="number" name="jumlah" min="1" max="{{ $sisaTagihan }}" value="{{ $sisaTagihan }}" placeholder="Nominal Pelunasan" class="border rounded px-2 py-1 text-xs" required>
                                        <input type="file" name="bukti_pembayaran" class="border rounded px-2 py-1 text-xs" required>
                                        <button type="submit" class="bg-indigo-600 text-white px-2 py-1 rounded text-xs">Upload Pelunasan</button>
                                    </form>
                                @endif
                            @endif
                        </td>
                        <td class="p-3 border">
                            @if($p->status_pemesanan == 'diterima' && $sisaTagihan == 0 && $p->pembayaran->where('status','diterima')->count() > 0)
                                <a href="{{ route('user.pemesanan.downloadReceipt', $p->id) }}" class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700 transition">Download Tanda Terima</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-3 border text-center text-gray-500">Belum ada pemesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection