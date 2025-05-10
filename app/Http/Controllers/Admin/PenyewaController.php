<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PenyewaController extends Controller
{
    public function index()
    {
        // Ambil semua pemesanan yang sudah disetujui (status = 'disetujui')
        $penyewa = Pemesanan::with(['user', 'kos'])
            ->where('status_pemesanan', 'diterima')
            ->get();

        return view('admin.dataPenyewa.index', compact('penyewa'));
    }

    public function destroy($id)
    {
        $penyewa = Pemesanan::findOrFail($id);

        // Kembalikan status kamar menjadi 'tersedia'
        $penyewa->kos->update(['status_kamar' => 'tersedia']);

        $penyewa->delete();

        return redirect()->route('admin.penyewa.index')->with('success', 'Data penyewa berhasil dihapus.');
    }
}
