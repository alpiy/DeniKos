@extends('app')

@section('title', 'Pengajuan Perpanjangan Berhasil')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow-2xl text-center">
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
            <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-4">Pengajuan Perpanjangan Terkirim!</h1>

        <p class="text-gray-600 mb-3">
            Pengajuan perpanjangan sewa Anda untuk kamar <strong class="font-semibold">{{ $pemesanan->kos->nomor_kamar ?? '-' }}</strong> selama <strong class="font-semibold">{{ $pemesanan->lama_sewa }} bulan</strong> telah berhasil dikirim.
        </p>
        <p class="text-gray-600 mb-6">
            Total biaya perpanjangan: <strong class="font-semibold text-indigo-700">Rp{{ number_format($pemesanan->total_pembayaran, 0, ',', '.') }}</strong>.
        </p>
        <p class="text-gray-600 mb-3 text-sm">
            Mohon tunggu <strong class="font-semibold">1x24 jam</strong> untuk proses verifikasi oleh Admin. Anda akan menerima notifikasi setelah pengajuan Anda diverifikasi.
        </p>
        <p class="text-gray-500 text-xs mb-8">
            (ID Pengajuan Perpanjangan: PERPANJANG-{{ $pemesanan->id }})
        </p>

        <div class="mt-8 space-y-3 sm:space-y-0 sm:flex sm:justify-center sm:space-x-4">
            <a href="{{ route('user.riwayat') }}"
               class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                Lihat Riwayat Pemesanan
            </a>
            <a href="{{ route('landing') }}"
               class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection