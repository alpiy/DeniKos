<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PemesananController extends Controller
{
    public function index()
    {
        $pemesanan = Pemesanan::with('kos', 'user')->get();
        return view('admin.pemesanan.index', compact('pemesanan'));
    }
}
