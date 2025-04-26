<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kos;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlahKos = Kos::count();
        $totalPemesanan = Pemesanan::count();
        $pending = Pemesanan::where('status_pemesanan', 'pending')->count();
        $diterima = Pemesanan::where('status_pemesanan', 'diterima')->count();
        $ditolak = Pemesanan::where('status_pemesanan', 'ditolak')->count();

        return view('admin.dashboard', compact(
            'jumlahKos', 'totalPemesanan', 'pending', 'diterima', 'ditolak'
        ));
    }
}
