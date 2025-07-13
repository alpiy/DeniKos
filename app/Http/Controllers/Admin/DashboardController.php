<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kos;
use App\Models\Notification;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Svg\Tag\Rect;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $jumlahKos = Kos::count();
        $totalPemesananAll = Pemesanan::count(); // Nama variabel diubah untuk kejelasan
        $pending = Pemesanan::where('status_pemesanan', 'pending')->count();
        $diterima = Pemesanan::where('status_pemesanan', 'diterima')->count();
        $ditolak = Pemesanan::where('status_pemesanan', 'ditolak')
                        ->orWhere('status_pemesanan', 'batal')->count(); // Gabung ditolak & batal
        $selesai = Pemesanan::where('status_pemesanan', 'selesai')->count();
        
        // Ambil 5 pemesanan terbaru untuk ditampilkan di dashboard
        $pemesananTerbaru = Pemesanan::with(['user', 'kos'])
                                ->orderByDesc('created_at')
                                ->take(5)
                                ->get();

        $pendapatan = DB::table('pemesanan')
            ->join('pembayarans', 'pemesanan.id', '=', 'pembayarans.pemesanan_id')
            ->selectRaw('MONTH(pemesanan.tanggal_pesan) as bulan, SUM(pembayarans.jumlah) as total')
            ->whereIn('pemesanan.status_pemesanan', ['diterima', 'selesai'])
            ->where('pembayarans.status', 'diterima')
            ->groupBy(DB::raw('MONTH(pemesanan.tanggal_pesan)'))
            ->orderBy(DB::raw('MONTH(pemesanan.tanggal_pesan)'))
            ->get();
       
        $labels = [];
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = date('F', mktime(0, 0, 0, $i, 10));
            $bulanData = $pendapatan->firstWhere('bulan', $i);
            $data[] = $bulanData ? $bulanData->total : 0;
        }

        // Return with cache control headers
        return response()->view('admin.dashboard', compact(
            'jumlahKos', 'totalPemesananAll', 'pending', 'diterima', 'ditolak', 'selesai', 
            'pemesananTerbaru', 'labels', 'data'
        ))
        ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
    }
}
