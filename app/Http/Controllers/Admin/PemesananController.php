<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pemesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Events\NotifikasiUserBaru;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


class PemesananController extends Controller
{
    public function index(Request $request) // Tambahkan Request $request
    {
        $query = Pemesanan::with(['kos', 'user', 'pembayaran']) // Eager load relasi
                           ->orderByDesc('created_at'); // Urutkan berdasarkan terbaru

        // Filter Pencarian (Nama User, Email User, No Kamar)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('user', function($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'like', "%{$searchTerm}%")
                              ->orWhere('email', 'like', "%{$searchTerm}%");
                })->orWhereHas('kos', function($kosQuery) use ($searchTerm) {
                    $kosQuery->where('nomor_kamar', 'like', "%{$searchTerm}%");
                });
            });
        }

        // Filter Status Pemesanan
        if ($request->filled('status_pemesanan')) {
            $query->where('status_pemesanan', $request->status_pemesanan);
        }

        // Filter Jenis Pemesanan (Awal / Perpanjangan)
        if ($request->filled('jenis_pemesanan')) {
            if ($request->jenis_pemesanan === 'awal') {
                $query->where('is_perpanjangan', false);
            } elseif ($request->jenis_pemesanan === 'perpanjangan') {
                $query->where('is_perpanjangan', true);
            }
        }
        
        // Filter Bulan & Tahun (Berdasarkan tanggal_pesan)
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_pesan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_pesan', $request->tahun);
        }


        $semuaPemesanan = $query->paginate(15)->withQueryString(); // Paginasi

        return view('admin.pemesanan.index', compact('semuaPemesanan'));
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
// public function perpanjangIndex()
//     {
//         $pemesananPerpanjang = Pemesanan::with('kos', 'user')->where('is_perpanjangan', true)->latest()->get();
//         return view('admin.pemesanan.perpanjang', compact('pemesananPerpanjang'));
//     }
public function verifikasiPembayaran($id)
    {
        $pembayaran = Pembayaran::with('pemesanan')->findOrFail($id);
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
