<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use App\Events\NotifikasiUserBaru;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


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
        
public function approve($id, Request $request)
{
    $pemesanan = Pemesanan::findOrFail($id);

    // Cek context dari request
    $isPerpanjangan = $request->input('is_perpanjangan') == 1;

    $pemesanan->status_pemesanan = 'diterima';
    $pemesanan->save();

    if ($pemesanan->kos) {
        $pemesanan->kos->status_kamar = 'terpesan';
        $pemesanan->kos->save();
    }

    // Kirim notifikasi realtime ke user dengan pesan berbeda
    if ($isPerpanjangan) {
        event(new NotifikasiUserBaru(
            $pemesanan->user_id,
            'Perpanjangan Disetujui',
            'Perpanjangan sewa kamar ' . $pemesanan->kos->nomor_kamar . ' Anda telah disetujui oleh admin.'
        ));
    } else {
        event(new NotifikasiUserBaru(
            $pemesanan->user_id,
            'Pemesanan Diterima',
            'Pemesanan kamar ' . $pemesanan->kos->nomor_kamar . ' Anda telah diterima.'
        ));
    }

    return redirect()->route('admin.pemesanan.index')->with('success', $isPerpanjangan ? 'Perpanjangan berhasil disetujui.' : 'Pemesanan berhasil disetujui.');
}

    public function reject($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $pemesanan->status_pemesanan = 'ditolak';
        $pemesanan->save();
        // Kirim notifikasi ke user (opsional) // Kirim notifikasi realtime ke user
    event(new NotifikasiUserBaru(
        $pemesanan->user_id,
        'Pemesanan Ditolak',
        'Pemesanan kamar ' . $pemesanan->kos->nomor_kamar . ' Anda ditolak.'
    ));

        return redirect()->route('admin.pemesanan.index')->with('success', 'Pemesanan telah ditolak.');
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
