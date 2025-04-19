<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PemesananController extends Controller
{
    public function index()
    {
        $pemesanan = Pemesanan::with('kos')->latest()->get();
        return view('admin.pemesanan.index', compact('pemesanan'));
    }

    public function show($id)
    {
        $pemesanan = Pemesanan::with('kos')->findOrFail($id);
        return view('admin.pemesanan.show', compact('pemesanan'));
    }

    public function destroy($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);

        if ($pemesanan->bukti_pembayaran) {
            Storage::disk('public')->delete($pemesanan->bukti_pembayaran);
        }

        $pemesanan->delete();

        return redirect()->route('admin.pemesanan.index')->with('success', 'Pemesanan berhasil dihapus.');
    }
}
