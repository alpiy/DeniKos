<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use App\Events\NotifikasiUserBaru;
use App\Http\Controllers\Controller;

class PenyewaController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemesanan::with(['user', 'kos', 'pembayaran'])
            ->where('status_pemesanan', 'diterima') // Hanya yang statusnya 'diterima' (aktif)
            ->whereHas('kos'); // Pastikan relasi kos ada (kos tidak soft deleted, misalnya)

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

        // Filter Lantai
        if ($request->filled('lantai')) {
            $query->whereHas('kos', function($kosQuery) use ($request) {
                $kosQuery->where('lantai', $request->lantai);
            });
        }
        
        // Filter Berdasarkan Tanggal Selesai Sewa (Contoh: akan berakhir dalam 7 hari)
        if ($request->filled('akan_berakhir')) {
            $targetTanggal = Carbon::now()->addDays(7)->toDateString();
            // Perlu kalkulasi tanggal_selesai di query, bisa lebih kompleks
            // Untuk sekarang, kita bisa filter setelah get(), atau buat scope di model
            // $query->whereDate(DB::raw("DATE_ADD(tanggal_masuk, INTERVAL lama_sewa MONTH)"), '<=', $targetTanggal);
            // Atau bisa juga dengan whereDate('tanggal_selesai', '<=', $targetTanggal) jika tanggal_selesai sudah pasti benar.
            $query->whereNotNull('tanggal_selesai')->whereDate('tanggal_selesai', '<=', $targetTanggal);
        }


        // Urutkan berdasarkan tanggal selesai sewa yang paling dekat
        $penyewaAktif = $query->orderBy('tanggal_selesai', 'asc') 
                              ->paginate(15)
                              ->withQueryString();
        
        $totalPenyewaAktif = (clone $query)->count(); // Hitung total sebelum paginasi untuk statistik

        return view('admin.dataPenyewa.index', compact('penyewaAktif', 'totalPenyewaAktif'));
    }

    // Method markAsCompleted tetap sama seperti yang sudah diimprove sebelumnya
    public function markAsCompleted($id) 
    {
        // ... (kode dari respons sebelumnya)
        $pemesanan = Pemesanan::with('kos', 'user')->findOrFail($id);

        if ($pemesanan->status_pemesanan === 'diterima') {
            $pemesanan->status_pemesanan = 'selesai';
            $pemesanan->save();

            if ($pemesanan->kos) {
                 $adaPenyewaAktifLain = Pemesanan::where('kos_id', $pemesanan->kos_id)
                                           ->where('status_pemesanan', 'diterima')
                                           ->where('id', '!=', $pemesanan->id)
                                           ->exists();
                if (!$adaPenyewaAktifLain) {
                    $pemesanan->kos->update(['status_kamar' => 'tersedia']);
                }
            }
            
            event(new NotifikasiUserBaru( // Namespace lengkap jika belum di-use
                $pemesanan->user_id,
                'Masa Sewa Selesai',
                'Masa sewa Anda untuk kamar ' . ($pemesanan->kos->nomor_kamar ?? '-') . ' telah ditandai selesai oleh admin. Terima kasih.'
            ));

            return redirect()->route('admin.penyewa.index')->with('success', 'Penyewa ' . $pemesanan->user->name . ' untuk kamar ' . ($pemesanan->kos->nomor_kamar ?? '-') . ' telah ditandai selesai.');
        }

        return redirect()->route('admin.penyewa.index')->with('error', 'Status penyewa tidak dapat diubah.');
    }
}
