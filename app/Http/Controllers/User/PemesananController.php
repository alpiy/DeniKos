<?php

namespace App\Http\Controllers\User;

use App\Models\Kos;
use App\Models\Pemesanan;
use App\Models\Notification;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Events\PemesananBaru;
use App\Events\NotifikasiUserBaru;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PemesananController extends Controller
{

    public function index()
    {
        $pesanans = Pemesanan::with(['kos', 'pembayaran'])->where('user_id', Auth::id())->latest()->get();
        return view('pemesanan.index', compact('pesanans'));
    }
    public function create($id, Request $request)
    {
        // Jika id == 'multi', ambil kamar dari query string
        if ($id === 'multi') {
            $kamarIds = $request->query('kamar', []);
            $kamarTersedia = Kos::whereIn('id', $kamarIds)->get();
            if ($kamarTersedia->isEmpty()) {
                return redirect()->route('user.kos.index')->with('error', 'Pilih minimal satu kamar.');
            }
            return view('pemesanan.create', [
                'kamarTersedia' => $kamarTersedia,
                'multi' => true
            ]);
        }
        // Default: satu kamar
        $kos = Kos::findOrFail($id);
        return view('pemesanan.create', [
            'kamarTersedia' => collect([$kos]),
            'multi' => false
        ]);
    }
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login.form')->with('error', 'Silakan login untuk memesan kos.');
        }
        $kamarIds = $request->input('kamar', []);
        if (!is_array($kamarIds) || count($kamarIds) === 0) {
            return redirect()->back()->with('error', 'Pilih minimal satu kamar.');
        }
        $validated = $request->validate([
            'tanggal_masuk' => 'required|date|after_or_equal:today',
            'lama_sewa' => 'required|integer|min:1',
            'total_pembayaran' => 'required|integer',
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        $validated['user_id'] = Auth::id();
        $validated['status_pemesanan'] = 'pending';
        $validated['tanggal_pesan'] = now();
        $successIds = [];
        if ($request->hasFile('bukti_pembayaran')) {
            $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
        } else {
            $path = null;
        }
        $jenisPembayaran = $request->input('jenis_pembayaran', 'dp');
        foreach ($kamarIds as $kosId) {
            $kos = Kos::find($kosId);
            if (!$kos || $kos->status_kamar !== 'tersedia') continue;
            $sudahPesan = Pemesanan::where('user_id', Auth::id())
                ->where('kos_id', $kosId)
                ->whereIn('status_pemesanan', ['pending', 'diterima'])
                ->exists();
            if ($sudahPesan) continue;
            $validated['kos_id'] = $kosId;
            // Hitung tanggal selesai otomatis
            $lamaSewaInt = (int) $request->lama_sewa;
            $tanggalSelesai = \Carbon\Carbon::parse($request->tanggal_masuk)->addMonths($lamaSewaInt)->toDateString();
            $validated['tanggal_selesai'] = $tanggalSelesai;
            $pemesanan = Pemesanan::create($validated);
            // Hitung jumlah pembayaran sesuai jenis
            $jumlahBayar = $request->input('total_pembayaran');
            if ($jenisPembayaran === 'dp') {
                $jumlahBayar = ceil($jumlahBayar * 0.3); // minimal 30% DP
            }
            Pembayaran::create([
                'pemesanan_id' => $pemesanan->id,
                'tanggal_bayar' => now(),
                'jenis' => $jenisPembayaran,
                'jumlah' => $jumlahBayar,
                'bukti_pembayaran' => $path,
                'status' => 'pending',
            ]);
            Notification::create([
                'user_id' => null,
                'title' => 'Pemesanan Baru',
                'message' => 'User ' . Auth::user()->name . ' telah memesan kamar kos "' . $kos->nomor_kamar . '".',
            ]);
            event(new PemesananBaru('User ' . Auth::user()->name . ' telah memesan kamar kos "' . $kos->nomor_kamar . '".'));
            $successIds[] = $pemesanan->id;
        }
        if (count($successIds) === 0) {
            return redirect()->route('user.kos.index')->with('error', 'Tidak ada kamar yang berhasil dipesan.');
        }
        // Redirect ke halaman sukses untuk pemesanan pertama
        return redirect()->route('user.pesan.success', $successIds[0]);
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
    
public function perpanjangForm($id)
{
    $pemesanan = Pemesanan::with('kos')->where('user_id', Auth::id())->findOrFail($id);
    if ($pemesanan->status_pemesanan !== 'diterima') {
        abort(403, 'Hanya bisa perpanjang sewa pada pemesanan aktif.');
    }
    return view('pemesanan.perpanjang', compact('pemesanan'));
}

public function perpanjangStore(Request $request, $id)
{
    $pemesananLama = Pemesanan::with('kos')->where('user_id', Auth::id())->findOrFail($id);
    if ($pemesananLama->status_pemesanan !== 'diterima') {
        return redirect()->route('user.pemesanan.index')->with('error', 'Hanya bisa perpanjang sewa pada pemesanan aktif.');
    }
    $request->validate([
        'tambah_lama_sewa' => 'required|integer|min:1',
        'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);
    $buktiBaru = null;
    if ($request->hasFile('bukti_pembayaran')) {
        $buktiBaru = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
    }
    $pemesananBaru = Pemesanan::create([
        'kos_id' => $pemesananLama->kos_id,
        'user_id' => Auth::id(),
        'tanggal_pesan' => now(),
        'lama_sewa' => $request->tambah_lama_sewa,
        'status_pemesanan' => 'pending',
        'is_perpanjangan' => true,
        'status_refund' => 'belum',
    ]);
    // Buat pembayaran perpanjangan
    Pembayaran::create([
        'pemesanan_id' => $pemesananBaru->id,
        'tanggal_bayar' => now(),
        'jenis' => 'lainnya',
        'jumlah' => $request->tambah_lama_sewa * $pemesananLama->kos->harga_bulanan,
        'bukti_pembayaran' => $buktiBaru,
        'status' => 'pending',
    ]);
    Notification::create([
        'user_id' => null,
        'title' => 'Perpanjangan Sewa',
        'message' => 'User ' . Auth::user()->name . ' mengajukan perpanjangan sewa kamar "' . $pemesananLama->kos->nomor_kamar . '".',
    ]);
    event(new PemesananBaru('User ' . Auth::user()->name . ' mengajukan perpanjangan sewa kamar "' . $pemesananLama->kos->nomor_kamar . '".'));
    event(new NotifikasiUserBaru(
        Auth::id(),
        'Perpanjangan Sewa Berhasil',
        'Pengajuan perpanjangan sewa kamar ' . $pemesananLama->kos->nomor_kamar . ' berhasil dikirim. Menunggu verifikasi admin.'
    ));
    return redirect()->route('user.riwayat')->with('success', 'Pengajuan perpanjangan berhasil, menunggu verifikasi admin.');
}

public function batal($id)
{
    $pemesanan = Pemesanan::with('kos')->where('user_id', Auth::id())->findOrFail($id);
    if ($pemesanan->status_pemesanan !== 'pending') {
        return redirect()->route('user.pemesanan.index')->with('error', 'Hanya bisa membatalkan pemesanan yang masih pending.');
    }

    $pemesanan->status_pemesanan = 'batal';
    $pemesanan->status_refund = 'proses'; // Jika ingin langsung proses refund
    $pemesanan->save();

    // Notifikasi realtime ke admin
    event(new PemesananBaru(
        'User ' . Auth::user()->name . ' membatalkan pemesanan kamar "' . $pemesanan->kos->nomor_kamar . '".'
    ));

    // Notifikasi ke user
    event(new NotifikasiUserBaru(
        Auth::id(),
        'Pemesanan Dibatalkan',
        'Pemesanan kamar ' . $pemesanan->kos->nomor_kamar . ' telah dibatalkan.'
    ));

    return redirect()->route('user.riwayat')->with('success', 'Pemesanan berhasil dibatalkan. Untuk pengembalian dana, silakan hubungi admin.');
}

public function pelunasan(Request $request, $id)
{
    $pemesanan = Pemesanan::where('user_id', Auth::id())->findOrFail($id);
    $request->validate([
        'jumlah' => 'required|integer|min:1',
        'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);
    $bukti = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
    Pembayaran::create([
        'pemesanan_id' => $pemesanan->id,
        'tanggal_bayar' => now(),
        'jenis' => 'pelunasan',
        'jumlah' => $request->jumlah,
        'bukti_pembayaran' => $bukti,
        'status' => 'pending',
    ]);
    return redirect()->route('user.riwayat')->with('success', 'Bukti pelunasan berhasil diupload, menunggu verifikasi admin.');
}

/**
 * Download PDF receipt for a verified pemesanan
 */
public function downloadReceipt($id)
{
    $pemesanan = Pemesanan::with(['user', 'kos', 'pembayaran'])->findOrFail($id);
    // Only allow if status diterima & ada pembayaran terverifikasi (status: 'diterima')
    $hasVerifiedPayment = $pemesanan->pembayaran && $pemesanan->pembayaran->where('status','diterima')->count() > 0;
    if ($pemesanan->status_pemesanan !== 'diterima' || !$hasVerifiedPayment) {
        abort(403, 'Tanda terima hanya tersedia setelah pembayaran diverifikasi.');
    }
    // Ambil pembayaran terakhir yang terverifikasi (atau pertama jika hanya satu)
    $pembayaran = $pemesanan->pembayaran->where('status','diterima')->sortByDesc('created_at')->first();
    $pdf = Pdf::loadView('pemesanan.receipt', [
        'pemesanan' => $pemesanan,
        'pembayaran' => $pembayaran
    ]);
    return $pdf->download('TandaTerima-DeniKos-'.$pemesanan->id.'.pdf');
}
}
