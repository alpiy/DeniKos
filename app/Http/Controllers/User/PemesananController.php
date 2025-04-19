<?php

namespace App\Http\Controllers\User;

use App\Models\Kos;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
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
        $validated = $request->validate([
        'kos_id' => 'required|exists:kos,id',
        'nama' => 'required|string|max:255',
        'email' => 'required|email',
        'no_hp' => 'required|string|max:20',
        'tgl_masuk' => 'required|date',
        'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    // Simpan bukti pembayaran
    if ($request->hasFile('bukti_pembayaran')) {
        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
        $validated['bukti_pembayaran'] = $path;
    }

    // Simpan ke database
    Pemesanan::create($validated);

    return redirect()->route('user.kos.index')->with('success', 'Pemesanan berhasil dikirim!');
    }

}
