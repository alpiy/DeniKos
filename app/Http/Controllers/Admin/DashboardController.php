<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kos;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlahKos = Kos::count();
        $totalPemesanan = Pemesanan::count();
        $pending = Pemesanan::where('status_pemesanan', 'pending')->count();
        $diterima = Pemesanan::where('status_pemesanan', 'diterima')->count();
        $ditolak = Pemesanan::where('status_pemesanan', 'ditolak')->count();
          // Data untuk grafik pendapatan
        $pendapatan = DB::table('pemesanan')
        ->selectRaw('MONTH(tanggal_pesan) as bulan, SUM(total_pembayaran) as total')
        ->where('status_pemesanan', 'diterima')
        ->groupBy(DB::raw('MONTH(tanggal_pesan)'))
        ->orderBy(DB::raw('MONTH(tanggal_pesan)'))
        ->get();
       

        $labels = [];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = date('F', mktime(0, 0, 0, $i, 10)); // Nama bulan
            $bulanData = $pendapatan->firstWhere('bulan', $i);
            $data[] = $bulanData ? $bulanData->total : 0; // Jika tidak ada data, isi 0
        }

        return view('admin.dashboard', compact(
            'jumlahKos', 'totalPemesanan', 'pending', 'diterima', 'ditolak',
            'labels', 'data'
        ));

        
    }
}
