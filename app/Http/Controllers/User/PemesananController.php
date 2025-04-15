<?php

namespace App\Http\Controllers\User;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PemesananController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'kos_id' => 'required|exists:kos,id',
            'tanggal_masuk' => 'required|date',
        ]);

        Pemesanan::create([
            'user_id' => Auth::id(),
            'kos_id' => $request->kos_id,
            'tanggal_masuk' => $request->tanggal_masuk,
            'status' => 'Menunggu',
        ]);

        return redirect()->route('user.riwayat')->with('success', 'Pemesanan berhasil dikirim!');
    }
}
