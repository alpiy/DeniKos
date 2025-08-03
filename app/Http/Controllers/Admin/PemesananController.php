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
    $pemesanan = Pemesanan::with(['pembayaran', 'kos'])->findOrFail($id);

    // SECURITY: Prevent double approval
    if ($pemesanan->status_pemesanan === 'diterima') {
        return redirect()->route('admin.pemesanan.index')->with('error', 'Pemesanan sudah disetujui sebelumnya.');
    }
    
    // SECURITY: Only approve pending bookings
    if ($pemesanan->status_pemesanan !== 'pending') {
        return redirect()->route('admin.pemesanan.index')->with('error', 'Hanya dapat menyetujui pemesanan dengan status pending.');
    }

    // Validasi: Pastikan ada pembayaran yang sudah diverifikasi
    $pembayaranDiterima = $pemesanan->pembayaran->where('status', 'diterima');
    if ($pembayaranDiterima->isEmpty()) {
        return redirect()->route('admin.pemesanan.index')->with('error', 'Tidak dapat menyetujui pemesanan. Belum ada pembayaran yang diverifikasi.');
    }
    
    // SECURITY: Check if room is still available
    if ($pemesanan->kos && $pemesanan->kos->status_kamar !== 'tersedia') {
        return redirect()->route('admin.pemesanan.index')->with('error', 'Kamar tidak tersedia untuk pemesanan ini.');
    }

    // Update status pemesanan
    $pemesanan->status_pemesanan = 'diterima';
    $pemesanan->save();

    // Update status kamar menjadi terpesan
    if ($pemesanan->kos) {
        $pemesanan->kos->status_kamar = 'terpesan';
        $pemesanan->kos->save();
    }

    // Otomatis approve semua pembayaran pending yang terkait pemesanan ini
    $pemesanan->pembayaran()
        ->where('status', 'pending')
        ->update(['status' => 'diterima']);

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
        $pemesanan = Pemesanan::with(['pembayaran', 'kos'])->findOrFail($id);
        
        // Cek apakah ada pembayaran yang sudah diterima sebelumnya
        $adaPembayaranDiterima = $pemesanan->pembayaran()
            ->where('status', 'diterima')
            ->exists();
        
        // Update status pemesanan
        $pemesanan->status_pemesanan = 'ditolak';
        $pemesanan->save();

        // Update semua pembayaran terkait menjadi ditolak
        $pemesanan->pembayaran()
            ->whereIn('status', ['pending', 'diterima'])
            ->update(['status' => 'ditolak']);

        // Jika kamar sudah terpesan, kembalikan ke tersedia
        if ($pemesanan->kos && $pemesanan->kos->status_kamar === 'terpesan') {
            $pemesanan->kos->status_kamar = 'tersedia';
            $pemesanan->kos->save();
        }

        // Set status refund jika ada pembayaran yang sudah diterima sebelumnya
        if ($adaPembayaranDiterima && $pemesanan->status_refund === 'belum') {
            $pemesanan->status_refund = 'belum';
            $pemesanan->save();
        }

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
    $pemesanan = Pemesanan::with(['pembayaran', 'kos', 'user'])->findOrFail($id);
    
    // ENHANCED: Calculate both verified and all payments for better decision making
    $totalPembayaranDiterima = $pemesanan->pembayaran
        ->where('status', 'diterima')
        ->sum('jumlah');
    
    $totalSemuaPembayaran = $pemesanan->pembayaran
        ->whereIn('status', ['diterima', 'pending', 'ditolak'])
        ->sum('jumlah');
    
    // ENHANCED: Get payment history for context
    $pembayaranHistory = $pemesanan->pembayaran->map(function($payment) {
        return [
            'jenis' => $payment->jenis,
            'jumlah' => $payment->jumlah,
            'status' => $payment->status,
            'created_at' => $payment->created_at->format('d/m/Y H:i')
        ];
    });
    
    if ($pemesanan->status_refund === 'proses') {
        // Selesaikan refund yang sedang diproses
        $pemesanan->status_refund = 'selesai';
        $pemesanan->save();
        
        event(new NotifikasiUserBaru(
            $pemesanan->user_id,
            'Refund Selesai',
            'Pengembalian dana sebesar Rp ' . number_format($totalPembayaranDiterima, 0, ',', '.') . 
            ' untuk pemesanan kamar ' . ($pemesanan->kos->nomor_kamar ?? '-') . ' telah selesai diproses.'
        ));
        
        return redirect()->route('admin.pemesanan.index')
            ->with('success', 'Refund sebesar Rp ' . number_format($totalPembayaranDiterima, 0, ',', '.') . ' berhasil diselesaikan.');
        
    } elseif ($pemesanan->status_refund === 'belum') {
        // ENHANCED VALIDATION: More flexible refund conditions
        $statusValid = in_array($pemesanan->status_pemesanan, ['ditolak', 'batal']);
        
        if (!$statusValid) {
            return redirect()->route('admin.pemesanan.index')
                ->with('error', 'Refund hanya dapat diproses untuk pemesanan yang ditolak atau dibatalkan. Status saat ini: ' . $pemesanan->status_pemesanan);
        }
        
        // ENHANCED: Handle different refund scenarios
        if ($totalPembayaranDiterima > 0) {
            // Scenario 1: Ada pembayaran yang sudah diverifikasi - PROCESS REFUND
            $pemesanan->status_refund = 'proses';
            $pemesanan->save();
            
            event(new NotifikasiUserBaru(
                $pemesanan->user_id,
                'Refund Diproses',
                'Pengembalian dana sebesar Rp ' . number_format($totalPembayaranDiterima, 0, ',', '.') . 
                ' untuk pemesanan kamar ' . ($pemesanan->kos->nomor_kamar ?? '-') . ' sedang diproses. ' .
                'Silakan hubungi admin untuk detail lebih lanjut.'
            ));
            
            return redirect()->route('admin.pemesanan.index')
                ->with('success', 'Refund sebesar Rp ' . number_format($totalPembayaranDiterima, 0, ',', '.') . 
                       ' untuk ' . $pemesanan->user->name . ' sedang diproses.');
                       
        } elseif ($totalSemuaPembayaran > 0) {
            // Scenario 2: Ada pembayaran pending/ditolak tapi belum diverifikasi - MARK AS COMPLETED (No actual refund)
            $pemesanan->status_refund = 'selesai';
            $pemesanan->save();
            
            // Detail notification for clarity
            $paymentSummary = "Total pembayaran: Rp " . number_format($totalSemuaPembayaran, 0, ',', '.') . " (belum diverifikasi)";
            
            event(new NotifikasiUserBaru(
                $pemesanan->user_id,
                'Status Refund Update',
                'Pemesanan kamar ' . ($pemesanan->kos->nomor_kamar ?? '-') . ' telah dibatalkan. ' .
                $paymentSummary . '. Tidak ada dana yang perlu dikembalikan karena pembayaran belum diverifikasi admin.'
            ));
            
            return redirect()->route('admin.pemesanan.index')
                ->with('success', 'Status refund diperbarui. Tidak ada dana yang perlu dikembalikan karena belum ada pembayaran yang diverifikasi.');
                
        } else {
            // Scenario 3: Tidak ada pembayaran sama sekali - MARK AS COMPLETED
            $pemesanan->status_refund = 'selesai';
            $pemesanan->save();
            
            return redirect()->route('admin.pemesanan.index')
                ->with('success', 'Status refund diperbarui. Tidak ada pembayaran yang dilakukan untuk pemesanan ini.');
        }
        
    } elseif ($pemesanan->status_refund === 'selesai') {
        return redirect()->route('admin.pemesanan.index')
            ->with('info', 'Refund sudah selesai diproses sebelumnya. Total yang sudah di-refund: Rp ' . number_format($totalPembayaranDiterima, 0, ',', '.'));
    }
    
    return redirect()->route('admin.pemesanan.index')
        ->with('error', 'Status refund tidak valid: ' . $pemesanan->status_refund);
}

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

    public function cancelBooking($id)
    {
        $pemesanan = Pemesanan::with(['pembayaran', 'kos'])->findOrFail($id);
        
        // SECURITY: Hanya bisa membatalkan pemesanan yang statusnya diterima
        if ($pemesanan->status_pemesanan !== 'diterima') {
            return redirect()->route('admin.pemesanan.index')->with('error', 'Hanya dapat membatalkan pemesanan yang sudah diterima.');
        }
        
        // SECURITY: Check if there are verified payments
        $totalPembayaranDiterima = $pemesanan->pembayaran
            ->where('status', 'diterima')
            ->sum('jumlah');
        
        // Update status pemesanan menjadi batal
        $pemesanan->status_pemesanan = 'batal';
        
        // REFUND LOGIC: Set status refund hanya jika ada pembayaran diterima
        if ($totalPembayaranDiterima > 0) {
            $pemesanan->status_refund = 'belum'; // Set untuk proses refund
            $refundMessage = ' Dana sebesar Rp ' . number_format($totalPembayaranDiterima, 0, ',', '.') . ' akan diproses refund.';
        } else {
            $pemesanan->status_refund = 'belum'; // Default state
            $refundMessage = ' Tidak ada dana yang perlu dikembalikan.';
        }
        
        $pemesanan->save();

        // Kembalikan status kamar ke tersedia
        if ($pemesanan->kos) {
            $pemesanan->kos->status_kamar = 'tersedia';
            $pemesanan->kos->save();
        }

        // Notifikasi ke user
        event(new NotifikasiUserBaru(
            $pemesanan->user_id,
            'Pemesanan Dibatalkan oleh Admin',
            'Pemesanan kamar ' . ($pemesanan->kos->nomor_kamar ?? '-') . ' Anda telah dibatalkan oleh admin.' . $refundMessage . ' Silakan hubungi admin untuk informasi lebih lanjut.',
            'warning'
        ));

        return redirect()->route('admin.pemesanan.index')
            ->with('success', 'Pemesanan berhasil dibatalkan.' . $refundMessage);
    }
}
