<?php

namespace App\Console;

use App\Models\Pemesanan;
use App\Models\Notification;
use App\Events\NotifikasiUserBaru;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\CancelExpiredBookings::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        Log::info('Schedule method called, registering all scheduled tasks...');
        
        // Check for expired bookings every 5 minutes
        $schedule->command('bookings:cancel-expired')
            ->everyFiveMinutes()
            ->withoutOverlapping();
        
        //         $schedule->call(function () {
        //     Log::info('SCHEDULER WORKS! Time: ' . now());
        // })->everyMinute();
        $schedule->call(function () {
            Log::info('Scheduler closure is running...');
            $targetDate = Carbon::now()->addDays(3)->toDateString();
            Log::info('[Scheduler][Notif H-3] Menjalankan pengecekan notifikasi masa sewa. Target date: ' . $targetDate);

            $pemesanans = Pemesanan::with('kos')
                ->where('status_pemesanan', 'diterima')
                ->get();

            foreach ($pemesanans as $pemesanan) {
                $tanggalSelesai = Carbon::parse($pemesanan->tanggal_masuk)
                    ->addMonths($pemesanan->lama_sewa)
                    ->toDateString();

                Log::info("[Scheduler][Notif H-3] Cek user_id {$pemesanan->user_id}, tanggal selesai: {$tanggalSelesai}");

                if ($tanggalSelesai === $targetDate) {
                    $alreadyNotified = Notification::where('user_id', $pemesanan->user_id)
                        ->where('title', 'Masa Sewa Segera Habis')
                        ->whereDate('created_at', Carbon::today())
                        ->exists();

                    if (!$alreadyNotified) {
                        $message = 'Masa sewa kamar ' . ($pemesanan->kos->nomor_kamar ?? '-') .
                            ' akan berakhir pada ' . $tanggalSelesai . '. Silakan lakukan perpanjangan jika ingin melanjutkan.';

                        Notification::create([
                            'user_id' => $pemesanan->user_id,
                            'title' => 'Masa Sewa Segera Habis',
                            'message' => $message,
                        ]);

                        event(new NotifikasiUserBaru(
                            $pemesanan->user_id,
                            'Masa Sewa Segera Habis',
                            $message
                        ));

                        Log::info("[Scheduler][Notif H-3] Notifikasi dikirim ke user_id {$pemesanan->user_id}, kamar: " . ($pemesanan->kos->nomor_kamar ?? '-') . ", tanggal selesai: {$tanggalSelesai}");
                    } else {
                        Log::info("[Scheduler][Notif H-3] Sudah ada notifikasi hari ini untuk user_id {$pemesanan->user_id}, skip.");
                    }
                }
            }
        })->everyMinute();
    }
}