<?php

namespace App\Http\Controllers\User;

use App\Models\Kos;
use App\Models\Pemesanan;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Events\PemesananBaru;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PemesananController extends Controller
{

    public function index()
{
    $pesanans = Pemesanan::with('kos')->latest()->get(); // ambil semua dengan relasi kos
    return view('pemesanan.index', compact('pesanans'));
}
    public function create($id)
    {
        $kos = Kos::findOrFail($id);
        return view('pemesanan.create', compact('kos'));
    }
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login.form')->with('error', 'Silakan login untuk memesan kos.');
        }
        
        $validated = $request->validate([
        'kos_id' => 'required|exists:kos,id',
        'tanggal_pesan' => 'required|date',
        'lama_sewa' => 'required|integer|min:1',
        'total_pembayaran' => 'required|integer',
        'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $validated['user_id'] = Auth::id();
   
    $validated['status_pemesanan'] = 'pending'; // default status

    // Simpan bukti pembayaran
    if ($request->hasFile('bukti_pembayaran')) {
        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
        $validated['bukti_pembayaran'] = $path;
    }

    // Simpan ke database
    $pemesanan = Pemesanan::create($validated)->load('kos');
    // Buat notifikasi untuk admin
        Notification::create([
            'user_id' => null, // karena untuk admin umum
            'title' => 'Pemesanan Baru',
        'message' => 'User ' . Auth::user()->name . ' telah memesan kamar kos "' . $pemesanan->kos->nomor_kamar . '".',
    ]);
    event(new PemesananBaru('User ' . Auth::user()->name . ' telah memesan kamar kos "' . $pemesanan->kos->nomor_kamar . '".'));
   
    

    return redirect()->route('user.pesan.success', $pemesanan->id);
    }
    public function success($id)
    {
        $pemesanan = Pemesanan::with('kos')->findOrFail($id);

        // Pastikan hanya user yang memesan bisa melihat
        if ($pemesanan->user_id !== Auth::user()->id) {
            abort(403);
        }

        return view('pemesanan.success', compact('pemesanan'));
    }

}
