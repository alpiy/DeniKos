@extends('admin.layout')

@section('title', 'Detail Pemesanan')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Header Section --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Detail Pemesanan</h1>
                <p class="text-gray-600 mt-1">ID Pemesanan: #{{ $pemesanan->id }}</p>
            </div>
            <div class="flex items-center space-x-3">
                {{-- Status Badge --}}
                @php
                    $statusClasses = '';
                    switch($pemesanan->status_pemesanan) {
                        case 'pending':
                            $statusClasses = 'bg-yellow-100 text-yellow-800';
                            break;
                        case 'diterima':
                            $statusClasses = 'bg-green-100 text-green-800';
                            break;
                        case 'ditolak':
                            $statusClasses = 'bg-red-100 text-red-800';
                            break;
                        case 'batal':
                            $statusClasses = 'bg-gray-100 text-gray-800';
                            break;
                        case 'selesai':
                            $statusClasses = 'bg-blue-100 text-blue-800';
                            break;
                        default:
                            $statusClasses = 'bg-gray-100 text-gray-800';
                    }
                @endphp
                <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $statusClasses }}">
                    {{ ucfirst($pemesanan->status_pemesanan) }}
                </span>
                
                {{-- Payment Status Badge --}}
                @if($sisaTagihan == 0)
                    <span class="px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i>Lunas
                    </span>
                @else
                    <span class="px-4 py-2 rounded-full text-sm font-semibold bg-orange-100 text-orange-800">
                        <i class="fas fa-exclamation-circle mr-1"></i>Belum Lunas
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column: Main Information --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Customer Information Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">Informasi Penyewa</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nama Lengkap</label>
                        <p class="text-gray-900 font-medium">{{ $pemesanan->user->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900">{{ $pemesanan->user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">No. Handphone</label>
                        <p class="text-gray-900">{{ $pemesanan->user->no_hp ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Tanggal Pemesanan</label>
                        <p class="text-gray-900">{{ \Carbon\Carbon::parse($pemesanan->tanggal_pesan)->isoFormat('dddd, D MMMM Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Room Information Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-home text-purple-600"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">Informasi Kamar</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nomor Kamar</label>
                        <p class="text-gray-900 font-medium">Kamar {{ $pemesanan->kos->nomor_kamar ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Lantai</label>
                        <p class="text-gray-900">Lantai {{ $pemesanan->kos->lantai ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Tanggal Masuk</label>
                        <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($pemesanan->tanggal_masuk)->isoFormat('dddd, D MMMM Y') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Lama Sewa</label>
                        <p class="text-gray-900 font-medium">{{ $pemesanan->lama_sewa }} bulan</p>
                    </div>
                </div>
            </div>

            {{-- Payment History Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-credit-card text-green-600"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">Riwayat Pembayaran</h2>
                </div>
                
                @if($pemesanan->pembayaran->count() > 0)
                    <div class="space-y-3">
                        @foreach($pemesanan->pembayaran as $bayar)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            @php
                                                $jenisClasses = '';
                                                switch($bayar->jenis) {
                                                    case 'dp':
                                                        $jenisClasses = 'bg-blue-100 text-blue-800';
                                                        break;
                                                    case 'pelunasan':
                                                        $jenisClasses = 'bg-purple-100 text-purple-800';
                                                        break;
                                                    default:
                                                        $jenisClasses = 'bg-gray-100 text-gray-800';
                                                }

                                                $statusClasses = '';
                                                switch($bayar->status) {
                                                    case 'verified':
                                                        $statusClasses = 'bg-green-100 text-green-800';
                                                        break;
                                                    case 'pending':
                                                        $statusClasses = 'bg-yellow-100 text-yellow-800';
                                                        break;
                                                    default:
                                                        $statusClasses = 'bg-red-100 text-red-800';
                                                }
                                            @endphp
                                            <span class="px-2 py-1 rounded text-xs font-medium {{ $jenisClasses }}">
                                                {{ ucfirst($bayar->jenis) }}
                                            </span>
                                            <span class="px-2 py-1 rounded text-xs font-medium {{ $statusClasses }}">
                                                {{ ucfirst($bayar->status) }}
                                            </span>
                                        </div>
                                        <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</p>
                                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($bayar->created_at)->isoFormat('D MMMM Y, HH:mm') }}</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if($bayar->bukti_pembayaran)
                                            <a href="{{ asset('storage/'.$bayar->bukti_pembayaran) }}" target="_blank" 
                                               class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-md text-xs font-medium hover:bg-blue-200 transition-colors">
                                                <i class="fas fa-eye mr-1"></i>Lihat Bukti
                                            </a>
                                        @endif
                                        @if($bayar->status == 'pending')
                                            <form action="{{ route('admin.pembayaran.verifikasi', $bayar->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-green-600 text-white rounded-md text-xs font-medium hover:bg-green-700 transition-colors">
                                                    <i class="fas fa-check mr-1"></i>Verifikasi
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-receipt text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">Belum ada riwayat pembayaran</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right Column: Summary & Actions --}}
        <div class="space-y-6">
            {{-- Payment Summary Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Pembayaran</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Tagihan</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Dibayar</span>
                        <span class="font-semibold text-green-600">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</span>
                    </div>
                    <hr class="border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-800 font-medium">Sisa Tagihan</span>
                        <span class="font-bold text-lg {{ $sisaTagihan > 0 ? 'text-red-600' : 'text-green-600' }}">
                            Rp {{ number_format($sisaTagihan, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Action Buttons Card --}}
            @if($pemesanan->status_pemesanan === 'pending')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Pemesanan</h3>
                    <div class="space-y-3">
                        <form action="{{ route('admin.pemesanan.approve', $pemesanan->id) }}" method="POST">
                            @csrf
                            @if(request()->has('is_perpanjangan') || old('is_perpanjangan') || $pemesanan->is_perpanjangan ?? false)
                                <input type="hidden" name="is_perpanjangan" value="1">
                            @endif
                            <button type="submit" class="w-full bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-colors font-medium">
                                <i class="fas fa-check mr-2"></i>Setujui Pemesanan
                            </button>
                        </form>

                        <form action="{{ route('admin.pemesanan.reject', $pemesanan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-red-600 text-white px-4 py-3 rounded-lg hover:bg-red-700 transition-colors font-medium"
                                    onclick="return confirm('Apakah Anda yakin ingin menolak pemesanan ini?')">
                                <i class="fas fa-times mr-2"></i>Tolak Pemesanan
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Navigation Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Navigasi</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.pemesanan.index') }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                    </a>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
