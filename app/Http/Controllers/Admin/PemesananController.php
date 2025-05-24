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
        // Ambil pemesanan awal (bukan perpanjangan)
        $pemesananAwal = Pemesanan::with('kos', 'user')->where('is_perpanjangan', false)->latest()->get();
        // Ambil pemesanan perpanjangan
        $pemesananPerpanjang = Pemesanan::with('kos', 'user')->where('is_perpanjangan', true)->latest()->get();
        return view('admin.pemesanan.index', compact('pemesananAwal', 'pemesananPerpanjang'));
    }

    public function show($id)
    {
        $pemesanan = Pemesanan::with(['kos', 'user', 'pembayaran'])->findOrFail($id);
        // Hitung total tagihan dan total pembayaran diterima
        $totalTagihan = $pemesanan->lama_sewa * ($pemesanan->kos->harga_bulanan ?? 0);
        $totalDibayar = $pemesanan->pembayaran->where('status','diterima')->sum('jumlah');
        $sisaTagihan = max($totalTagihan - $totalDibayar, 0);
        return view('admin.pemesanan.show', compact('pemesanan', 'totalTagihan', 'totalDibayar', 'sisaTagihan'));
    }
        
public function approve($id, Request $request)
{
    $pemesanan = Pemesanan::findOrFail($id);

    $pemesanan->status_pemesanan = 'diterima';
    $pemesanan->save();

    if ($pemesanan->kos) {
        $pemesanan->kos->status_kamar = 'terpesan';
        $pemesanan->kos->save();
    }

    // Kirim notifikasi realtime ke user dengan pesan berbeda
    if ($pemesanan->is_perpanjangan) {
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

    return redirect()->route('admin.pemesanan.index')->with('success', $pemesanan->is_perpanjangan ? 'Perpanjangan berhasil disetujui.' : 'Pemesanan berhasil disetujui.');
}

    public function reject($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $pemesanan->status_pemesanan = 'ditolak';
        $pemesanan->save();

        // Kirim notifikasi realtime ke user dengan pesan berbeda
        if ($pemesanan->is_perpanjangan) {
            event(new NotifikasiUserBaru(
                $pemesanan->user_id,
                'Perpanjangan Ditolak',
                'Pengajuan perpanjangan sewa kamar ' . $pemesanan->kos->nomor_kamar . ' Anda ditolak oleh admin.',
                'danger'
            ));
        } else {
            event(new NotifikasiUserBaru(
                $pemesanan->user_id,
                'Pemesanan Ditolak',
                'Pemesanan kamar ' . $pemesanan->kos->nomor_kamar . ' Anda ditolak.',
                'danger'
            ));
        }

        return redirect()->route('admin.pemesanan.index')->with('success', $pemesanan->is_perpanjangan ? 'Perpanjangan berhasil ditolak.' : 'Pemesanan telah ditolak.');
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
public function refund($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        if ($pemesanan->status_refund === 'proses') {
            $pemesanan->status_refund = 'selesai';
            $pemesanan->save();
            event(new NotifikasiUserBaru(
                $pemesanan->user_id,
                'Refund Selesai',
                'Pengembalian dana untuk pemesanan kamar ' . ($pemesanan->kos->nomor_kamar ?? '-') . ' telah selesai diproses oleh admin.'
            ));
            return redirect()->route('admin.pemesanan.index')->with('success', 'Status refund diubah menjadi selesai.');
        } elseif ($pemesanan->status_refund === 'belum') {
            $pemesanan->status_refund = 'proses';
            $pemesanan->save();
            event(new NotifikasiUserBaru(
                $pemesanan->user_id,
                'Refund Diproses',
                'Pengembalian dana untuk pemesanan kamar ' . ($pemesanan->kos->nomor_kamar ?? '-') . ' sedang diproses oleh admin.'
            ));
            return redirect()->route('admin.pemesanan.index')->with('success', 'Status refund diubah menjadi proses.');
        }
        return redirect()->route('admin.pemesanan.index')->with('error', 'Status refund sudah selesai atau tidak valid.');
    }
public function perpanjangIndex()
    {
        $pemesananPerpanjang = Pemesanan::with('kos', 'user')->where('is_perpanjangan', true)->latest()->get();
        return view('admin.pemesanan.perpanjang', compact('pemesananPerpanjang'));
    }
public function verifikasiPembayaran($id)
    {
        $pembayaran = \App\Models\Pembayaran::with('pemesanan')->findOrFail($id);
        $pembayaran->status = 'diterima';
        $pembayaran->save();
        // Notifikasi ke user
        event(new NotifikasiUserBaru(
            $pembayaran->pemesanan->user_id,
            'Pembayaran Diverifikasi',
            'Pembayaran ' . $pembayaran->jenis . ' untuk kamar ' . ($pembayaran->pemesanan->kos->nomor_kamar ?? '-') . ' telah diverifikasi admin.'
        ));
        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }
}
