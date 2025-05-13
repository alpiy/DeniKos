@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Dashboard Adamin</h1>
  
      <!-- Section Notifikasi Realtime -->
  
        <div id="realtime-notifikasi" class="space-y-3">
          
        </div>
    

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow text-center">
            <h2 class="text-lg font-semibold">Total Kos</h2>
            <p class="text-3xl font-bold text-indigo-600">{{ $jumlahKos }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow text-center">
            <h2 class="text-lg font-semibold">Total Pemesanan</h2>
            <p class="text-3xl font-bold text-indigo-600">{{ $totalPemesanan }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow text-center">
            <h2 class="text-lg font-semibold">Pending</h2>
            <p class="text-3xl font-bold text-yellow-500">{{ $pending }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow text-center">
            <h2 class="text-lg font-semibold">Diterima</h2>
            <p class="text-3xl font-bold text-green-500">{{ $diterima }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow text-center">
            <h2 class="text-lg font-semibold">Ditolak</h2>
            <p class="text-3xl font-bold text-red-500">{{ $ditolak }}</p>
        </div>
        <!-- Section Notifikasi Database -->
    {{-- <div class="bg-white p-6 rounded-xl shadow mb-8">
        <h2 class="text-xl font-bold mb-4">Riwayat Notifikasi</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 text-left">Judul</th>
                        <th class="py-2 px-4 text-left">Pesan</th>
                        <th class="py-2 px-4 text-left">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($notifications as $notif)
                        <tr class="border-b">
                            <td class="py-3 px-4">{{ $notif->title }}</td>
                            <td class="py-3 px-4">{{ $notif->message }}</td>
                            <td class="py-3 px-4 text-sm text-gray-500">
                                {{ $notif->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500">
                                Tidak ada notifikasi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div> --}}
       

        
       
    </div>
    <div class="mt-10">
        <h2 class="text-xl font-bold mb-4">Grafik Pendapatan</h2>
        <div class="bg-white p-6 rounded-xl shadow">
            <canvas id="grafikPendapatan"
                height="70a"
                data-labels='@json($labels)'
                data-data='@json($data)'
            ></canvas>
        </div>
    </div>
@endsection
