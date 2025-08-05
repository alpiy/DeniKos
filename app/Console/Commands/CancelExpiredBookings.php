<?php

namespace App\Console\Commands;

use App\Models\Pemesanan;
use App\Events\NotifikasiUserBaru;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CancelExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:cancel-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel bookings that have exceeded their payment deadline';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        
        // Find all pending bookings where payment deadline has passed
        $expiredBookings = Pemesanan::with(['kos', 'user', 'pembayaran'])
            ->where('status_pemesanan', 'pending')
            ->where(function($query) use ($now) {
                $query->where('payment_deadline', '<', $now)
                      ->orWhere(function($subQuery) use ($now) {
                          // Fallback for old data without payment_deadline
                          $subQuery->whereNull('payment_deadline')
                                   ->whereRaw('DATE_ADD(tanggal_pesan, INTERVAL 24 HOUR) < ?', [$now]);
                      });
            })
            ->get();
        
        $cancelledCount = 0;
        
        foreach ($expiredBookings as $booking) {
            // Check if there are any active payments (pending/approved)
            $hasActivePayments = $booking->pembayaran()
                ->whereIn('status', ['pending', 'diterima'])
                ->exists();
            
            // Only cancel if no active payments exist
            if (!$hasActivePayments) {
                $booking->update(['status_pemesanan' => 'batal']);
                
                // Free up the room if it was reserved
                if ($booking->kos && $booking->kos->status_kamar === 'terpesan') {
                    $booking->kos->update(['status_kamar' => 'tersedia']);
                }
                
                // Check if this was due to rejected payment
                $hasRejectedPayment = $booking->pembayaran()
                    ->where('status', 'ditolak')
                    ->exists();
                
                $notificationTitle = $hasRejectedPayment 
                    ? 'Pemesanan Dibatalkan - Deadline Upload Ulang Habis'
                    : 'Pemesanan Dibatalkan - Deadline Pembayaran Habis';
                
                $notificationMessage = $hasRejectedPayment
                    ? 'Pemesanan kamar ' . ($booking->kos->nomor_kamar ?? '-') . ' dibatalkan karena Anda tidak upload ulang bukti pembayaran dalam waktu yang ditentukan.'
                    : 'Pemesanan kamar ' . ($booking->kos->nomor_kamar ?? '-') . ' dibatalkan karena pembayaran tidak dilakukan dalam 24 jam.';
                
                // Send notification to user
                event(new NotifikasiUserBaru(
                    $booking->user_id,
                    $notificationTitle,
                    $notificationMessage,
                    'warning'
                ));
                
                $cancelledCount++;
                
                $roomNumber = $booking->kos->nomor_kamar ?? 'Unknown';
                $this->info("Cancelled booking ID {$booking->id} for user {$booking->user->name} (Room: {$roomNumber})");
            } else {
                $this->warn("Skipped booking ID {$booking->id} - has active payments");
            }
        }
        
        $this->info("Process completed. Cancelled {$cancelledCount} expired bookings.");
        
        return 0;
    }
}
