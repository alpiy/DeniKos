<?php

namespace App\Http\Controllers\User;

use App\Models\Kos;
use App\Models\Pemesanan;
use App\Models\Notification;
use App\Models\Pembayaran;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Events\PemesananBaru;
use App\Events\NotifikasiUserBaru;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PemesananController extends Controller
{

    public function index()
    {
        $pesanans = Pemesanan::with(['kos', 'pembayaran'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get()
            ->map(function($pemesanan) {
                // Calculate payment deadline for each booking (don't save to DB)
                $paymentDeadline = Carbon::parse($pemesanan->tanggal_pesan)->addHours(24);
                $isPaymentExpired = Carbon::now()->gt($paymentDeadline);
                
                // Auto-cancel expired pending bookings
                if ($isPaymentExpired && $pemesanan->status_pemesanan === 'pending') {
                    // Use update() to only modify specific fields
                    $pemesanan->update(['status_pemesanan' => 'batal']);
                }
                
                // Add temporary properties after potential save operation
                $pemesanan->payment_deadline = $paymentDeadline;
                $pemesanan->is_payment_expired = $isPaymentExpired;
                $pemesanan->has_payment = $pemesanan->pembayaran->count() > 0;
                
                return $pemesanan;
            });
        
        // Get active payment methods for pelunasan forms    
        $paymentMethods = PaymentMethod::active()->ordered()->get();
            
        return view('pemesanan.index', compact('pesanans', 'paymentMethods'));
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
            
            // SECURITY: Filter out rooms user already booked
            if (Auth::check()) {
                $userBookedRooms = Pemesanan::where('user_id', Auth::id())
                    ->whereIn('status_pemesanan', ['pending', 'diterima'])
                    ->pluck('kos_id')
                    ->toArray();
                
                $kamarTersedia = $kamarTersedia->reject(function($kamar) use ($userBookedRooms) {
                    return in_array($kamar->id, $userBookedRooms);
                });
                
                if ($kamarTersedia->isEmpty()) {
                    return redirect()->route('user.riwayat')->with('error', 
                        'Semua kamar yang dipilih sudah Anda pesan sebelumnya. Silakan cek riwayat pemesanan Anda.');
                }
            }
            
            return view('pemesanan.create', [
                'kamarTersedia' => $kamarTersedia,
                'multi' => true
            ]);
        }
        
        // Default: satu kamar
        $kos = Kos::findOrFail($id);
        
        // SECURITY: Check if user already booked this room
        if (Auth::check()) {
            $existingBooking = Pemesanan::where('user_id', Auth::id())
                ->where('kos_id', $id)
                ->whereIn('status_pemesanan', ['pending', 'diterima'])
                ->first();
                
            if ($existingBooking) {
                return redirect()->route('user.riwayat')->with('error', 
                    "Anda sudah memiliki pemesanan untuk kamar {$kos->nomor_kamar}. Silakan cek riwayat pemesanan Anda.");
            }
        }
        
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
        
        // Simplified validation for booking only (no payment fields)
        $validated = $request->validate([
            'tanggal_masuk' => 'required|date|after_or_equal:today',
            'lama_sewa' => 'required|integer|min:1|max:12',
        ]);

        // Set additional fields for booking creation
        $validated['user_id'] = Auth::id();
        $validated['status_pemesanan'] = 'pending';
        $validated['tanggal_pesan'] = now();
        $successIds = [];
        
        // REMOVED: Payment logic moved to separate method
        foreach ($kamarIds as $kosId) {
            $kos = Kos::find($kosId);
            if (!$kos || $kos->status_kamar !== 'tersedia') {
                continue; // Skip unavailable rooms
            }
            
            // SECURITY: Prevent double booking - check for any active booking by same user
            $sudahPesan = Pemesanan::where('user_id', Auth::id())
                ->where('kos_id', $kosId)
                ->whereIn('status_pemesanan', ['pending', 'diterima'])
                ->exists();
            if ($sudahPesan) {
                continue; // Skip already booked rooms
            }
         
            $validated['kos_id'] = $kosId;
            // Hitung tanggal selesai otomatis
            $lamaSewaInt = (int) $request->lama_sewa;
            $tanggalSelesai = Carbon::parse($request->tanggal_masuk)->addMonths($lamaSewaInt)->toDateString();
            $validated['tanggal_selesai'] = $tanggalSelesai;
            
            // Calculate total_pembayaran automatically based on room price and duration
            $validated['total_pembayaran'] = $kos->harga_bulanan * $lamaSewaInt;
            
            // Set initial payment deadline (24 hours from booking)
            $validated['payment_deadline'] = now()->addHours(24);
            
            $pemesanan = Pemesanan::create($validated);
            
            // Create notification for admin about new booking
            Notification::create([
                'user_id' => null,
                'title' => 'Pemesanan Baru',
                'message' => 'User ' . Auth::user()->name . ' telah membuat pemesanan kamar kos "' . $kos->nomor_kamar . '".',
            ]);
            event(new PemesananBaru('User ' . Auth::user()->name . ' telah membuat pemesanan kamar kos "' . $kos->nomor_kamar . '".'));
            $successIds[] = $pemesanan->id;
        }
        if (count($successIds) === 0) {
            // Check user existing bookings for detailed message
            $userExistingBookings = Pemesanan::with('kos')
                ->where('user_id', Auth::id())
                ->whereIn('kos_id', $kamarIds)
                ->whereIn('status_pemesanan', ['pending', 'diterima'])
                ->get();
            
            if ($userExistingBookings->count() > 0) {
                $roomNumbers = $userExistingBookings->map(function($booking) {
                    return $booking->kos->nomor_kamar ?? $booking->kos_id;
                })->implode(', ');
                
                return redirect()->route('user.riwayat')->with('error', 
                    "Tidak dapat membuat pemesanan baru. Anda sudah memiliki pemesanan aktif untuk kamar: {$roomNumbers}. " .
                    "Silakan lihat status pemesanan Anda atau hubungi admin jika ada masalah.");
            }
            
            // Check if rooms are available
            $unavailableRooms = Kos::whereIn('id', $kamarIds)
                ->where('status_kamar', '!=', 'tersedia')
                ->pluck('nomor_kamar')
                ->toArray();
                
            if (!empty($unavailableRooms)) {
                return redirect()->route('user.kos.index')->with('error', 
                    'Kamar yang dipilih sudah tidak tersedia: ' . implode(', ', $unavailableRooms) . '. Silakan pilih kamar lain.');
            }
            
            return redirect()->route('user.kos.index')->with('error', 'Tidak ada kamar yang berhasil dipesan. Silakan coba lagi atau hubungi admin.');
        }
        // Redirect ke halaman success dengan semua IDs yang berhasil dibuat
        if (count($successIds) === 1) {
            // Single booking - redirect to individual success page
            return redirect()->route('user.pesan.success', $successIds[0])
                ->with('success', 'Pemesanan berhasil dibuat! Anda memiliki waktu 24 jam untuk melakukan pembayaran.');
        } else {
            // Multiple bookings - redirect to multiple success page
            return redirect()->route('user.pesan.success.multiple')
                ->with('success_ids', $successIds)
                ->with('success', count($successIds) . ' pemesanan berhasil dibuat! Anda memiliki waktu 24 jam untuk melakukan pembayaran.');
        }
    }
    public function success($id)
    {
        $pemesanan = Pemesanan::with('kos')->findOrFail($id);

        // Pastikan hanya user yang memesan bisa melihat
        if ($pemesanan->user_id !== Auth::user()->id) {
            abort(403);
        }
        
        // Calculate payment deadline (24 hours from booking)
        $paymentDeadline = Carbon::parse($pemesanan->tanggal_pesan)->addHours(24);
        $isExpired = Carbon::now()->gt($paymentDeadline);
        
        // Auto-cancel if expired
        if ($isExpired && $pemesanan->status_pemesanan === 'pending') {
            $pemesanan->status_pemesanan = 'batal';
            $pemesanan->save();
            
            return redirect()->route('user.riwayat')->with('error', 
                'Pemesanan telah dibatalkan otomatis karena melewati batas waktu pembayaran (24 jam). Silakan buat pemesanan baru.');
        }
        
        // Check if payment already exists
        $existingPembayaran = Pembayaran::where('pemesanan_id', $id)->first();

        return view('pemesanan.success', compact('pemesanan', 'paymentDeadline', 'isExpired', 'existingPembayaran'));
    }

    public function successMultiple()
    {
        $successIds = session('success_ids', []);
        if (empty($successIds)) {
            return redirect()->route('user.riwayat')->with('error', 'Data pemesanan tidak ditemukan.');
        }

        $pemesanans = Pemesanan::with(['kos', 'pembayaran'])->whereIn('id', $successIds)
            ->where('user_id', Auth::user()->id)
            ->get();

        if ($pemesanans->isEmpty()) {
            return redirect()->route('user.riwayat')->with('error', 'Pemesanan tidak ditemukan.');
        }

        // Calculate payment deadline for all bookings (assuming same booking time)
        $paymentDeadline = Carbon::parse($pemesanans->first()->tanggal_pesan)->addHours(24);
        $isExpired = Carbon::now()->gt($paymentDeadline);

        // Auto-cancel expired bookings
        if ($isExpired) {
            $pemesanans->each(function($pemesanan) {
                if ($pemesanan->status_pemesanan === 'pending') {
                    $pemesanan->status_pemesanan = 'batal';
                    $pemesanan->save();
                }
            });
            
            return redirect()->route('user.riwayat')->with('error', 
                'Pemesanan telah dibatalkan otomatis karena melewati batas waktu pembayaran (24 jam). Silakan buat pemesanan baru.');
        }

        // Calculate statistics for multiple bookings
        $totalPembayaran = $pemesanans->sum('total_pembayaran');
        $unpaidCount = $pemesanans->filter(function($pemesanan) {
            return $pemesanan->pembayaran->count() == 0;
        })->count();

        return view('pemesanan.success-multiple', compact('pemesanans', 'paymentDeadline', 'isExpired', 'totalPembayaran', 'unpaidCount'));
    }
    
public function perpanjangForm($id)
{
    $pemesanan = Pemesanan::with('kos')->where('user_id', Auth::id())->findOrFail($id);
    if ($pemesanan->status_pemesanan !== 'diterima') {
        abort(403, 'Hanya bisa perpanjang sewa pada pemesanan aktif.');
    }

    $tanggalSelesaiSewaSaatIniCarbon = Carbon::parse($pemesanan->tanggal_selesai);
    $tanggalSelesaiSewaSaatIni = $tanggalSelesaiSewaSaatIniCarbon->format('d F Y');

    // Kebijakan: Batas maksimal pengajuan perpanjangan adalah 7 hari setelah masa sewa berakhir
    $batasAkhirPengajuan = $tanggalSelesaiSewaSaatIniCarbon->copy()->addDays(7);
    $bisaPerpanjang = Carbon::now()->lte($batasAkhirPengajuan);

    $disarankanTanggalMulaiPerpanjang = null;
    if ($pemesanan->tanggal_selesai) {
        try {
            $disarankanTanggalMulaiPerpanjang = Carbon::parse($pemesanan->tanggal_selesai)->addDay()->toDateString();
        } catch (\Exception $e) {
            //
        }
    }

    return view('pemesanan.perpanjang', compact('pemesanan', 'tanggalSelesaiSewaSaatIni', 'disarankanTanggalMulaiPerpanjang', 'bisaPerpanjang', 'batasAkhirPengajuan'));
}

public function perpanjangStore(Request $request, $id)
{
    $pemesananLama = Pemesanan::with('kos')->where('user_id', Auth::id())->findOrFail($id);
    if ($pemesananLama->status_pemesanan !== 'diterima') {
        return redirect()->route('user.riwayat')->with('error', 'Hanya bisa perpanjang sewa pada pemesanan aktif.');
    }

    // Validasi Batas Waktu Pengajuan Perpanjangan
    $tanggalSelesaiSewaSaatIniCarbon = Carbon::parse($pemesananLama->tanggal_selesai);
    $batasAkhirPengajuan = $tanggalSelesaiSewaSaatIniCarbon->copy()->addDays(7); // Misal, 7 hari setelah selesai
    if (Carbon::now()->gt($batasAkhirPengajuan)) {
        return redirect()->route('user.riwayat')->with('error', 'Masa pengajuan perpanjangan telah berakhir. Silakan hubungi admin jika ada pertanyaan.');
    }
    
    // SECURITY: Check if user already has pending extension for this room
    $existingExtension = Pemesanan::where('user_id', Auth::id())
        ->where('kos_id', $pemesananLama->kos_id)
        ->where('is_perpanjangan', true)
        ->where('status_pemesanan', 'pending')
        ->exists();
    
    if ($existingExtension) {
        return redirect()->route('user.riwayat')->with('error', 'Anda sudah memiliki pengajuan perpanjangan yang pending untuk kamar ini.');
    }

    $validatedData = $request->validate([ // Simpan hasil validasi
        'tambah_lama_sewa' => 'required|integer|min:1|max:12', // Max 12 bulan
        'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'total_biaya_perpanjangan' => 'required|numeric|min:0'
    ], [
        'tambah_lama_sewa.max' => 'Maksimal perpanjangan adalah 12 bulan',
        'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB',
        'bukti_pembayaran.mimes' => 'File harus berformat JPG, JPEG, PNG, atau PDF'
    ]);
    
    // SECURITY: Validate calculated cost
    $lamaSewaTambahan = (int) $validatedData['tambah_lama_sewa'];
    $expectedCost = $pemesananLama->kos->harga_bulanan * $lamaSewaTambahan;
    
    if (abs($validatedData['total_biaya_perpanjangan'] - $expectedCost) > 1) {
        return redirect()->back()->with('error', 'Total biaya tidak sesuai dengan perhitungan sistem.');
    }

    $buktiBaru = null;
    if ($request->hasFile('bukti_pembayaran')) {
        $buktiBaru = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
    }

    $tanggalMulaiPerpanjangan = Carbon::parse($pemesananLama->tanggal_selesai)->addDay();
    // Gunakan variabel yang sudah di-cast ke integer
    $tanggalSelesaiPerpanjangan = $tanggalMulaiPerpanjangan->copy()->addMonths($lamaSewaTambahan);


    $pemesananBaru = Pemesanan::create([
        'kos_id' => $pemesananLama->kos_id,
        'user_id' => Auth::id(),
        'tanggal_pesan' => now(),
        'tanggal_masuk' => $tanggalMulaiPerpanjangan->toDateString(),
        'tanggal_selesai' => $tanggalSelesaiPerpanjangan->toDateString(),
        'lama_sewa' => $lamaSewaTambahan, // Gunakan variabel yang sudah di-cast
        'total_pembayaran' => $validatedData['total_biaya_perpanjangan'], // Gunakan dari hasil validasi
        'status_pemesanan' => 'pending',
        'is_perpanjangan' => true,
        'status_refund' => 'belum',
    ]);

    Pembayaran::create([
        'pemesanan_id' => $pemesananBaru->id,
        'tanggal_bayar' => now(),
        'jenis' => 'lainnya',
        'jumlah' => $validatedData['total_biaya_perpanjangan'], // Gunakan dari hasil validasi
        'bukti_pembayaran' => $buktiBaru,
        'status' => 'pending',
    ]);

    Notification::create([
        'user_id' => null,
        'title' => 'Pengajuan Perpanjangan Sewa',
        'message' => 'User ' . Auth::user()->name . ' mengajukan perpanjangan sewa untuk kamar "' . ($pemesananLama->kos->nomor_kamar ?? '-') . '".',
    ]);
    event(new PemesananBaru('User ' . Auth::user()->name . ' mengajukan perpanjangan sewa kamar "' . ($pemesananLama->kos->nomor_kamar ?? '-') . '".'));

    event(new NotifikasiUserBaru(
        Auth::id(),
        'Pengajuan Perpanjangan Terkirim',
        'Pengajuan perpanjangan sewa kamar ' . ($pemesananLama->kos->nomor_kamar ?? '-') . ' telah berhasil dikirim dan menunggu verifikasi admin.'
    ));

    return redirect()->route('user.pesan.perpanjang.success', $pemesananBaru->id);
}
public function perpanjangSuccess($id)
{
    $pemesanan = Pemesanan::with(['kos', 'user'])->findOrFail($id);
    // Pastikan hanya user yang memesan bisa melihat dan ini adalah perpanjangan
    if ($pemesanan->user_id !== Auth::id() || !$pemesanan->is_perpanjangan) {
        abort(403, 'Akses ditolak atau bukan pemesanan perpanjangan.');
    }
    return view('pemesanan.perpanjang_success', compact('pemesanan'));
}

public function batal($id)
{
    try {
        $pemesanan = Pemesanan::with(['kos', 'pembayaran'])->where('user_id', Auth::id())->findOrFail($id);
        
        Log::info('Pembatalan dimulai untuk pemesanan ID: ' . $id . ' oleh user: ' . Auth::id());
        
        // SECURITY: Hanya bisa membatalkan jika status pending
        if ($pemesanan->status_pemesanan !== 'pending') {
            Log::warning('Pembatalan ditolak - status bukan pending: ' . $pemesanan->status_pemesanan);
            return redirect()->route('user.riwayat')->with('error', 'Pemesanan tidak dapat dibatalkan karena status sudah berubah.');
        }
        
        // SIMPLE LOGIC: Hanya bisa batalkan jika belum ada pembayaran yang diterima
        $totalPembayaranDiterima = $pemesanan->pembayaran->where('status', 'diterima')->sum('jumlah');
        
        if ($totalPembayaranDiterima > 0) {
            Log::warning('Pembatalan ditolak - sudah ada pembayaran diterima');
            return redirect()->route('user.riwayat')->with('error', 'Pemesanan tidak dapat dibatalkan karena pembayaran sudah diterima. Silakan hubungi admin jika diperlukan.');
        }
        
        // BUSINESS LOGIC: User bisa batalkan sampai deadline pembayaran (24 jam setelah booking)
        $paymentDeadline = Carbon::parse($pemesanan->tanggal_pesan)->addHours(24);
        $today = Carbon::now();
        
        // Cek apakah sudah melewati deadline pembayaran
        if ($today->gt($paymentDeadline)) {
            Log::warning('Pembatalan ditolak - sudah melewati deadline pembayaran (24 jam)');
            return redirect()->route('user.riwayat')->with('error', 'Pemesanan tidak dapat dibatalkan karena sudah melewati batas waktu pembayaran (24 jam). Pemesanan akan dibatalkan otomatis oleh sistem.');
        }
        
        // Update status pemesanan
        $pemesanan->update([
            'status_pemesanan' => 'batal',
            'status_refund' => 'selesai' // Selalu selesai karena tidak ada refund
        ]);
        
        Log::info('Status pemesanan diupdate ke batal');
        
        // Update room status back to available
        if ($pemesanan->kos && $pemesanan->kos->status_kamar === 'terpesan') {
            $pemesanan->kos->update(['status_kamar' => 'tersedia']);
            Log::info('Status kamar diupdate ke tersedia');
        }
        
        // Cancel any pending payments
        $totalPembayaranPending = $pemesanan->pembayaran->where('status', 'pending')->sum('jumlah');
        if ($totalPembayaranPending > 0) {
            $pemesanan->pembayaran()->where('status', 'pending')->update(['status' => 'ditolak']);
            Log::info('Pembayaran pending dibatalkan');
        }
        
        // SIMPLE NOTIFICATION
        Notification::create([
            'user_id' => null,
            'title' => 'Pembatalan Pemesanan',
            'message' => 'User ' . Auth::user()->name . ' membatalkan pemesanan kamar ' . $pemesanan->kos->nomor_kamar . ' (belum ada pembayaran diterima)',
        ]);
        
        // User notification
        event(new NotifikasiUserBaru(
            Auth::id(),
            'Pemesanan Dibatalkan',
            'Pemesanan kamar ' . $pemesanan->kos->nomor_kamar . ' telah berhasil dibatalkan.'
        ));
        
        Log::info('Pembatalan berhasil untuk pemesanan ID: ' . $id);
        
        return redirect()->route('user.riwayat')->with('success', 'Pemesanan berhasil dibatalkan.');
        
    } catch (\Exception $e) {
        Log::error('Error dalam pembatalan pemesanan: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return redirect()->route('user.riwayat')->with('error', 'Terjadi kesalahan saat membatalkan pemesanan. Silakan coba lagi.');
    }
}


/**
 * Process pelunasan payment - SIMPLIFIED
 */
public function processPelunasan(Request $request, $id)
{
    $pemesanan = Pemesanan::with(['kos', 'pembayaran'])
        ->where('user_id', Auth::id())
        ->findOrFail($id);
    
    // SIMPLIFIED: Only check if booking is accepted
    if ($pemesanan->status_pemesanan !== 'diterima') {
        return redirect()->back()->with('error', 'Pelunasan hanya bisa dilakukan setelah pemesanan disetujui admin.');
    }
    
    // Check if there's an approved DP payment
    $pembayaranDP = $pemesanan->pembayaran->where('jenis', 'dp')->where('status', 'diterima')->first();
    
    if (!$pembayaranDP) {
        return redirect()->back()->with('error', 'Pelunasan hanya dapat dilakukan untuk DP yang sudah disetujui admin.');
    }
    
    // Check if pelunasan already exists
    $pelunasanExists = $pemesanan->pembayaran->where('jenis', 'pelunasan')->first();
    if ($pelunasanExists) {
        return redirect()->back()->with('error', 'Pelunasan untuk pemesanan ini sudah dilakukan.');
    }
    
    // SIMPLIFIED: Calculate remaining amount
    $totalTagihan = $pemesanan->lama_sewa * $pemesanan->kos->harga_bulanan;
    $sisaTagihan = $totalTagihan - $pembayaranDP->jumlah;
    
    if ($sisaTagihan <= 0) {
        return redirect()->back()->with('error', 'Pembayaran sudah lunas.');
    }
    
    // SIMPLIFIED validation
    $request->validate([
        'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png|max:5120',
        'payment_method' => 'required|exists:payment_methods,id',
    ]);
    
    $bukti = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
    $paymentMethod = PaymentMethod::find($request->input('payment_method'));
    
    // Create pelunasan payment
    Pembayaran::create([
        'pemesanan_id' => $pemesanan->id,
        'tanggal_bayar' => now(),
        'jenis' => 'pelunasan',
        'jumlah' => $sisaTagihan,
        'bukti_pembayaran' => $bukti,
        'status' => 'pending',
        'payment_method_id' => $paymentMethod->id,
    ]);
    
    // Simple notification
    Notification::create([
        'user_id' => null,
        'title' => 'Pelunasan Baru',
        'message' => 'User ' . Auth::user()->name . ' melakukan pelunasan sebesar Rp ' . number_format($sisaTagihan, 0, ',', '.') . ' untuk kamar "' . $pemesanan->kos->nomor_kamar . '" via ' . $paymentMethod->name . '.',
    ]);
    
    return redirect()->back()->with('success', 'Pelunasan berhasil dikirim! Menunggu verifikasi admin.');
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

/**
 * Show payment form for a booking
 */
public function showPembayaran($id)
{
    $pemesanan = Pemesanan::with(['kos', 'pembayaran'])->findOrFail($id);
    
    // Pastikan hanya user yang memesan bisa melihat
    if ($pemesanan->user_id !== Auth::user()->id) {
        abort(403);
    }
    
    // SECURITY: Only allow payment for pending bookings
    if ($pemesanan->status_pemesanan !== 'pending') {
        return redirect()->route('user.riwayat')
            ->with('error', 'Pembayaran hanya dapat dilakukan untuk pemesanan dengan status pending.');
    }
    
    // Check for rejected payments - this now creates fresh deadline
    $hasRejectedPayment = $pemesanan->pembayaran->where('status', 'ditolak')->count() > 0;
    
    // Use payment_deadline from database (set when payment is rejected or initial booking)
    $paymentDeadline = $pemesanan->payment_deadline ?? Carbon::parse($pemesanan->tanggal_pesan)->addHours(24);
    $isExpired = Carbon::now()->gt($paymentDeadline);
    
    if ($isExpired) {
        // Auto-cancel expired booking
        $pemesanan->status_pemesanan = 'batal';
        $pemesanan->save();
        
        // Different message for rejected vs initial payment
        $message = $hasRejectedPayment 
            ? 'Batas waktu upload ulang pembayaran telah habis. Pemesanan dibatalkan otomatis.'
            : 'Pemesanan telah kadaluarsa (24 jam). Silakan buat pemesanan baru.';
        
        return redirect()->route('user.riwayat')->with('error', $message);
    }
    
    // Check if payment already exists and is pending/approved
    $activePembayaran = $pemesanan->pembayaran->whereIn('status', ['pending', 'diterima'])->first();
    if ($activePembayaran && !$hasRejectedPayment) {
        return redirect()->route('user.pesan.success', $id)
            ->with('info', 'Pembayaran untuk pemesanan ini sudah dilakukan.');
    }
    
    // Calculate total payment amount
    $totalTagihan = $pemesanan->kos->harga_bulanan * $pemesanan->lama_sewa;
    
    // Get active payment methods
    $paymentMethods = PaymentMethod::active()->ordered()->get();
    
    // Get latest rejected payment for context
    $latestRejectedPayment = $pemesanan->pembayaran->where('status', 'ditolak')->sortByDesc('ditolak_pada')->first();
    
    return view('user.pembayaran.create', compact(
        'pemesanan', 
        'totalTagihan', 
        'paymentDeadline', 
        'paymentMethods', 
        'hasRejectedPayment',
        'latestRejectedPayment'
    ));
}

/**
 * Process payment for a booking - SIMPLIFIED (LUNAS or DP 50%)
 */
public function processPembayaran(Request $request, $id)
{
    $pemesanan = Pemesanan::with(['kos', 'pembayaran'])->findOrFail($id);
    
    // Pastikan hanya user yang memesan bisa melakukan pembayaran
    if ($pemesanan->user_id !== Auth::user()->id) {
        abort(403);
    }
    
    // SECURITY: Only allow payment for pending bookings
    if ($pemesanan->status_pemesanan !== 'pending') {
        return redirect()->route('user.pesan.success', $id)
            ->with('error', 'Pembayaran hanya dapat dilakukan untuk pemesanan dengan status pending.');
    }
    
    // Check for rejected payments and use new deadline logic
    $hasRejectedPayment = $pemesanan->pembayaran->where('status', 'ditolak')->count() > 0;
    
    // Use payment_deadline from database (reset when payment rejected)
    $paymentDeadline = $pemesanan->payment_deadline ?? Carbon::parse($pemesanan->tanggal_pesan)->addHours(24);
    
    // SECURITY: Check payment deadline (supports reset deadline for rejected payments)
    if (Carbon::now()->gt($paymentDeadline)) {
        // Auto-cancel expired booking
        $pemesanan->update(['status_pemesanan' => 'batal']);
        
        $message = $hasRejectedPayment 
            ? 'Batas waktu upload ulang pembayaran telah habis. Pemesanan dibatalkan otomatis.'
            : 'Pemesanan telah kadaluarsa (24 jam). Silakan buat pemesanan baru.';
        
        return redirect()->route('user.kos.index')->with('error', $message);
    }
    
    // Check if active payment already exists (but allow re-upload for rejected payments)
    $activePembayaran = $pemesanan->pembayaran->whereIn('status', ['pending', 'diterima'])->first();
    if ($activePembayaran && !$hasRejectedPayment) {
        return redirect()->route('user.pesan.success', $id)
            ->with('error', 'Pembayaran untuk pemesanan ini sudah dilakukan.');
    }
    
    // SIMPLIFIED VALIDATION - only LUNAS or DP 50%
    $request->validate([
        'jenis_pembayaran' => 'required|in:dp,lunas',
        'bukti_pembayaran' => 'required|file|mimes:png,jpg,jpeg|max:5120', // 5MB max
        'payment_method' => 'required|exists:payment_methods,id',
    ], [
        'bukti_pembayaran.max' => 'Ukuran file maksimal 5MB',
        'bukti_pembayaran.mimes' => 'File harus berformat PNG, JPG, atau JPEG',
        'payment_method.required' => 'Silakan pilih metode pembayaran',
        'payment_method.exists' => 'Metode pembayaran tidak valid'
    ]);
    
    // SIMPLIFIED PAYMENT CALCULATION
    $totalTagihan = $pemesanan->kos->harga_bulanan * $pemesanan->lama_sewa;
    $jenisPembayaran = $request->input('jenis_pembayaran');
    
    if ($jenisPembayaran === 'dp') {
        $jumlahBayar = $totalTagihan * 0.5; // Fixed 50% DP
    } else {
        $jumlahBayar = $totalTagihan; // Full payment
    }
    
    // Handle file upload for payment proof
    $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
    
    // Get selected payment method
    $paymentMethod = PaymentMethod::find($request->input('payment_method'));
    
    // Create payment record
    Pembayaran::create([
        'pemesanan_id' => $pemesanan->id,
        'tanggal_bayar' => now(),
        'jenis' => $jenisPembayaran,
        'jumlah' => $jumlahBayar,
        'bukti_pembayaran' => $path,
        'status' => 'pending', // Admin harus verifikasi
        'payment_method_id' => $paymentMethod->id,
    ]);
    
    // Create notification for admin about payment
    Notification::create([
        'user_id' => null,
        'title' => 'Pembayaran Baru',
        'message' => 'User ' . Auth::user()->name . ' telah melakukan pembayaran ' . $jenisPembayaran . ' sebesar Rp ' . number_format($jumlahBayar, 0, ',', '.') . ' untuk kamar "' . $pemesanan->kos->nomor_kamar . '" via ' . $paymentMethod->name . '.',
    ]);
    
    return redirect()->route('user.pesan.success', $id)
        ->with('success', 'Pembayaran berhasil dikirim! Menunggu verifikasi admin.');
}

/**
 * Show pelunasan form - SIMPLIFIED
 */
public function pelunasan($id)
{
    $pemesanan = Pemesanan::with(['kos', 'pembayaran', 'pembayaran.paymentMethod'])->findOrFail($id);
    
    // Pastikan hanya user yang memesan bisa mengakses
    if ($pemesanan->user_id !== Auth::user()->id) {
        abort(403);
    }
    
    // Check if there's an existing DP payment that's approved
    $pembayaranDP = $pemesanan->pembayaran->where('jenis', 'dp')->where('status', 'diterima')->first();
    
    if (!$pembayaranDP) {
        return redirect()->route('user.riwayat')
            ->with('error', 'Pelunasan hanya dapat dilakukan untuk DP yang sudah disetujui admin.');
    }
    
    // Check if pelunasan already exists
    $pelunasanExists = $pemesanan->pembayaran->where('jenis', 'pelunasan')->first();
    if ($pelunasanExists) {
        return redirect()->route('user.riwayat')
            ->with('info', 'Pelunasan untuk pemesanan ini sudah dilakukan.');
    }
    
    // Calculate remaining amount (50% of total)
    $totalTagihan = $pemesanan->kos->harga_bulanan * $pemesanan->lama_sewa;
    $sisaTagihan = $totalTagihan - $pembayaranDP->jumlah;
    
    // Get all payment methods
    $paymentMethods = PaymentMethod::where('is_active', true)->get();
    
    return view('pemesanan.pelunasan', compact('pemesanan', 'pembayaranDP', 'sisaTagihan', 'paymentMethods'));
}
}
