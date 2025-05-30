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
        $queryHistori = Pemesanan::with(['user', 'kos', 'pembayaran'])
                            ->orderByDesc('created_at'); // Urutkan berdasarkan tanggal pembuatan pemesanan

        // Filter Pencarian
        if ($request->filled('search_histori')) {
            $searchTerm = $request->search_histori;
            $queryHistori->where(function($q) use ($searchTerm) {
                $q->whereHas('user', function($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'like', "%{$searchTerm}%")
                              ->orWhere('email', 'like', "%{$searchTerm}%");
                })->orWhereHas('kos', function($kosQuery) use ($searchTerm) {
                    $kosQuery->where('nomor_kamar', 'like', "%{$searchTerm}%");
                });
            });
        }

        // Filter Status
        if ($request->filled('status_histori')) {
            $queryHistori->where('status_pemesanan', $request->status_histori);
        }

        // Filter Bulan Pemesanan (berdasarkan tanggal_pesan)
        if ($request->filled('bulan_histori')) {
            $queryHistori->whereMonth('tanggal_pesan', $request->bulan_histori);
        }

        // Filter Tahun Pemesanan (berdasarkan tanggal_pesan)
        if ($request->filled('tahun_histori')) { // Tambahkan filter tahun jika belum ada
            $queryHistori->whereYear('tanggal_pesan', $request->tahun_histori);
        }

        $historiPemesanan = $queryHistori->paginate(15)->withQueryString(); // Paginasi, misal 15 item

        // Hitung Total Pendapatan berdasarkan filter yang aktif untuk data yang dipaginasi
        // Ini akan menghitung dari semua data yang cocok filter, bukan hanya yang di halaman saat ini
        $queryTotalPendapatan = Pemesanan::query();
        if ($request->filled('search_histori')) {
             $searchTerm = $request->search_histori;
            $queryTotalPendapatan->where(function($q) use ($searchTerm) {
                $q->whereHas('user', function($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'like', "%{$searchTerm}%")
                              ->orWhere('email', 'like', "%{$searchTerm}%");
                })->orWhereHas('kos', function($kosQuery) use ($searchTerm) {
                    $kosQuery->where('nomor_kamar', 'like', "%{$searchTerm}%");
                });
            });
        }
        if ($request->filled('status_histori')) {
            $queryTotalPendapatan->where('status_pemesanan', $request->status_histori);
        } else {
            // Jika tidak ada filter status khusus, laporan pendapatan biasanya dari yang diterima & selesai
            $queryTotalPendapatan->whereIn('status_pemesanan', ['diterima', 'selesai']);
        }
        if ($request->filled('bulan_histori')) {
            $queryTotalPendapatan->whereMonth('tanggal_pesan', $request->bulan_histori);
        }
        if ($request->filled('tahun_histori')) {
            $queryTotalPendapatan->whereYear('tanggal_pesan', $request->tahun_histori);
        }
        
        $totalPendapatan = $queryTotalPendapatan->join('pembayarans', 'pemesanan.id', '=', 'pembayarans.pemesanan_id')
                                            ->where('pembayarans.status', 'diterima')
                                            ->sum('pembayarans.jumlah');


        return view('admin.laporan.index', compact('historiPemesanan', 'totalPendapatan'));
    }

    public function exportExcel(Request $request) // Terima Request untuk filter
    {
        return Excel::download(new LaporanSewaExport($request), 'laporan_pemesanan_denikos.xlsx');
    }

    public function exportPDF(Request $request) // Terima Request untuk filter
    {
        $query = Pemesanan::with(['user', 'kos', 'pembayaran'])
            ->orderByDesc('created_at');

        if ($request->filled('search_histori')) {
             $searchTerm = $request->search_histori;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('user', function($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'like', "%{$searchTerm}%")
                              ->orWhere('email', 'like', "%{$searchTerm}%");
                })->orWhereHas('kos', function($kosQuery) use ($searchTerm) {
                    $kosQuery->where('nomor_kamar', 'like', "%{$searchTerm}%");
                });
            });
        }
        if ($request->filled('status_histori')) {
            $query->where('status_pemesanan', $request->status_histori);
        }
        if ($request->filled('bulan_histori')) {
            $query->whereMonth('tanggal_pesan', $request->bulan_histori);
        }
        if ($request->filled('tahun_histori')) {
            $query->whereYear('tanggal_pesan', $request->tahun_histori);
        }

        $data = $query->get(); // Ambil semua data yang cocok filter untuk PDF

        // Hitung total pendapatan terverifikasi untuk data yang diekspor
        $totalPendapatanTerverifikasi = 0;
        foreach ($data as $item) {
            $totalPendapatanTerverifikasi += $item->pembayaran->where('status', 'diterima')->sum('jumlah');
        }

        $pdf = app('dompdf.wrapper')->loadView('admin.laporan.pdf', compact('data', 'totalPendapatanTerverifikasi'));
        return $pdf->download('laporan_pemesanan_denikos.pdf');
    }
}
