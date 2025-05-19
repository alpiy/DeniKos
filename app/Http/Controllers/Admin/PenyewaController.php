<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PenyewaController extends Controller
{
    public function index()
    {
        // Ambil semua pemesanan diterima, urutkan terbaru
        $all = Pemesanan::with(['user', 'kos'])
            ->where('status_pemesanan', 'diterima')
            ->orderByDesc('id')
            ->get();
        // Filter: hanya satu data aktif per user per kamar (ambil yang terbaru)
        $penyewa = $all->unique(function($item) {
            return $item->user_id.'-'.$item->kos_id;
        });
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
