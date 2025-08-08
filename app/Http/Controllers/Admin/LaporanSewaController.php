<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanSewaExport;
use Illuminate\Support\Facades\Auth;

class LaporanSewaController extends Controller
{
    public function index(Request $request)
    {
        $queryHistori = Pemesanan::with(['user', 'kos', 'pembayaran'])
                            ->orderByDesc('created_at');

        // Filter Pencarian (Tidak berubah)
        if ($request->filled('search_histori')) {
            // ... logika pencarian Anda ...
        }

        // Filter Status (Tidak berubah)
        if ($request->filled('status_histori')) {
            $queryHistori->where('status_pemesanan', $request->status_histori);
        }

        // --- PERUBAHAN LOGIKA FILTER TANGGAL DIMULAI DI SINI ---
        // Prioritaskan filter tanggal spesifik
        if ($request->filled('tanggal_pesan')) {
            $queryHistori->whereDate('tanggal_pesan', $request->tanggal_pesan);
        } else {
            // Jika tidak ada tanggal spesifik, baru terapkan filter bulan dan tahun
            if ($request->filled('bulan_histori')) {
                $queryHistori->whereMonth('tanggal_pesan', $request->bulan_histori);
            }
            if ($request->filled('tahun_histori')) {
                $queryHistori->whereYear('tanggal_pesan', $request->tahun_histori);
            }
        }
        // --- AKHIR PERUBAHAN ---

        $historiPemesanan = $queryHistori->paginate(15)->withQueryString();

        // Duplikasi logika filter untuk query Total Pendapatan
        $queryTotalPendapatan = Pemesanan::query();

        if ($request->filled('search_histori')) {
            // ... logika pencarian Anda untuk total pendapatan ...
        }
        if ($request->filled('status_histori')) {
            $queryTotalPendapatan->where('status_pemesanan', $request->status_histori);
        } else {
            $queryTotalPendapatan->whereIn('status_pemesanan', ['diterima', 'selesai']);
        }
        
        // --- PERUBAHAN LOGIKA FILTER TANGGAL UNTUK TOTAL PENDAPATAN ---
        if ($request->filled('tanggal_pesan')) {
            $queryTotalPendapatan->whereDate('tanggal_pesan', $request->tanggal_pesan);
        } else {
            if ($request->filled('bulan_histori')) {
                $queryTotalPendapatan->whereMonth('tanggal_pesan', $request->bulan_histori);
            }
            if ($request->filled('tahun_histori')) {
                $queryTotalPendapatan->whereYear('tanggal_pesan', $request->tahun_histori);
            }
        }
        // --- AKHIR PERUBAHAN ---
        
        $totalPendapatan = $queryTotalPendapatan->join('pembayarans', 'pemesanan.id', '=', 'pembayarans.pemesanan_id')
                                            ->where('pembayarans.status', 'diterima')
                                            ->sum('pembayarans.jumlah');


        return view('admin.laporan.index', compact('historiPemesanan', 'totalPendapatan'));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new LaporanSewaExport($request), 'laporan_pemesanan_denikos.xlsx');
    }

    public function exportPDF(Request $request)
    {
        $query = Pemesanan::with(['user', 'kos', 'pembayaran'])
            ->orderByDesc('created_at');

        // ... (Filter 'search_histori' dan 'status_histori' tetap sama) ...

        // --- PERUBAHAN LOGIKA FILTER TANGGAL UNTUK PDF ---
        if ($request->filled('tanggal_pesan')) {
            $query->whereDate('tanggal_pesan', $request->tanggal_pesan);
        } else {
            if ($request->filled('bulan_histori')) {
                $query->whereMonth('tanggal_pesan', $request->bulan_histori);
            }
            if ($request->filled('tahun_histori')) {
                $query->whereYear('tanggal_pesan', $request->tahun_histori);
            }
        }
        // --- AKHIR PERUBAHAN ---

        $data = $query->get();

        $totalPendapatanTerverifikasi = 0;
        foreach ($data as $item) {
            $totalPendapatanTerverifikasi += $item->pembayaran->where('status', 'diterima')->sum('jumlah');
        }
        
        $penanggungJawab = 'Deni Arya';

        $pdf = app('dompdf.wrapper')->loadView('admin.laporan.pdf', compact('data', 'totalPendapatanTerverifikasi', 'penanggungJawab'));
        return $pdf->download('laporan_pemesanan_denikos.pdf');
    }
}