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
        $pending = Pemesanan::where('status', 'pending')->count();
        $diterima = Pemesanan::where('status', 'diterima')->count();
        $ditolak = Pemesanan::where('status', 'ditolak')->count();

        return view('admin.dashboard', compact(
            'jumlahKos', 'totalPemesanan', 'pending', 'diterima', 'ditolak'
        ));
    }
}
