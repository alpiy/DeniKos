<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanSewaExport;

class LaporanSewaController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemesanan::with(['user', 'kos'])
            ->where('status_pemesanan', 'diterima');

        if ($request->filled('bulan')) {
            $query->whereMonth('created_at', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }

        $all = $query->orderByDesc('id')->get();
        // Filter: hanya satu data per user per kamar (ambil yang terbaru)
        $data = $all->unique(function($item) {
            return $item->user_id.'-'.$item->kos_id;
        });
        // Total pendapatan: semua pembayaran diterima (tanpa unique)
        $totalPendapatan = $all->sum('total_pembayaran');

        // Untuk detail, kirim juga semua histori diterima (group by user+kos)
        $detailHistori = $all->groupBy(function($item) {
            return $item->user_id.'-'.$item->kos_id;
        });

        return view('admin.laporan.index', compact('data', 'totalPendapatan', 'detailHistori'));
    }
    public function exportExcel()
    {
        return Excel::download(new LaporanSewaExport, 'laporan_sewa.xlsx');
    }
    public function exportPDF()
    {
        $data = Pemesanan::with('user', 'kos')
            ->where('status_pemesanan', 'diterima')
            ->get();

        $pdf = app('dompdf.wrapper')->loadView('admin.laporan.pdf', compact('data'));
        return $pdf->download('laporan_sewa.pdf');
    }
}
