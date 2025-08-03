<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckCancellationTimeLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $pemesananId = $request->route('id');
        $pemesanan = Pemesanan::where('user_id', Auth::id())->findOrFail($pemesananId);
        
        // Business Rule: Can only cancel before check-in date
        $today = Carbon::now()->startOfDay();
        $tanggalMasuk = Carbon::parse($pemesanan->tanggal_masuk)->startOfDay();
        
        if ($tanggalMasuk->lte($today)) {
            return redirect()->route('user.riwayat')
                ->with('error', 'Pembatalan tidak dapat dilakukan pada atau setelah tanggal check-in.');
        }
        
        // Additional business rule: No cancellation if status is not pending/diterima
        if (!in_array($pemesanan->status_pemesanan, ['pending', 'diterima'])) {
            return redirect()->route('user.riwayat')
                ->with('error', 'Pemesanan dengan status ini tidak dapat dibatalkan.');
        }
        
        return $next($request);
    }
}
