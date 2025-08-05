<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use App\Events\NotifikasiUserBaru;
use App\Http\Controllers\Controller;
use App\Models\User;

class PenyewaController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemesanan::with(['user', 'kos', 'pembayaran'])
            ->where('status_pemesanan', 'diterima')
            ->whereHas('kos');

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('kos', function ($q) use ($search) {
                    $q->where('nomor_kamar', 'like', "%{$search}%");
                });
            });
        }

        // Filter lantai
        if ($request->filled('lantai')) {
            $query->whereHas('kos', function ($q) use ($request) {
                $q->where('lantai', $request->lantai);
            });
        }

        // Filter akan berakhir (dalam 1 bulan)
        if ($request->filled('akan_berakhir')) {
            $query->whereNotNull('tanggal_selesai')
                  ->whereDate('tanggal_selesai', '<=', now()->addMonth());
        }

        // Sederhana: ambil data langsung dengan pagination
        $penyewaAktif = $query->orderBy('tanggal_selesai', 'asc')
                             ->paginate(15)
                             ->withQueryString();

        $totalPenyewaAktif = $query->count();

        return view('admin.dataPenyewa.index', compact('penyewaAktif', 'totalPenyewaAktif'));
    }

    // public function allUsers(Request $request)
    // {
    //     $query = User::where('role', 'user');

    //     if ($request->filled('search')) {
    //         $search = $request->search;
    //         $query->where(function ($q) use ($search) {
    //             $q->where('name', 'like', "%{$search}%")
    //               ->orWhere('email', 'like', "%{$search}%")
    //               ->orWhere('no_hp', 'like', "%{$search}%");
    //         });
    //     }

    //     $users = $query->withCount([
    //             'pemesanans as total_pemesanan',
    //             'pemesanans as pemesanan_aktif' => function($q) {
    //                 $q->where('status_pemesanan', 'diterima');
    //             }
    //         ])
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(20)
    //         ->withQueryString();

    //     return view('admin.dataPenyewa.all-users', compact('users'));
    // }
    
    public function complete(Request $request, $id)
    {
        try {
            $pemesanan = Pemesanan::findOrFail($id);
            
            // Update status pemesanan menjadi selesai
            $pemesanan->update([
                'status_pemesanan' => 'selesai'
            ]);
            
            // Update status kamar menjadi tersedia lagi
            if ($pemesanan->kos) {
                $pemesanan->kos->update(['status_kamar' => 'tersedia']);
            }
            
            // Kirim notifikasi ke user (opsional)
            // event(new NotifikasiUserBaru([
            //     'user_id' => $pemesanan->user_id,
            //     'title' => 'Sewa Kamar Selesai',
            //     'message' => "Sewa kamar {$pemesanan->kos->nomor_kamar} Anda telah selesai.",
            //     'type' => 'info'
            // ]));
            
            return redirect()->route('admin.penyewa.index')
                           ->with('success', "Penyewa {$pemesanan->user->name} kamar {$pemesanan->kos->nomor_kamar} berhasil ditandai sebagai selesai sewa. Kamar sekarang tersedia untuk disewakan kembali.");
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal menyelesaikan sewa: ' . $e->getMessage());
        }
    }
}
