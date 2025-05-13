<?php

namespace App\Events;

use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PemesananBaru implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
       public $message;

    /**
     * Create a new event instance.
     *
     * @param mixed $message
     */
    public function __construct($message)
    {
         $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
     public function broadcastOn()
  {
      return ['notifikasi-admin'];
  }
    public function broadcastAs()
    {
        return 'pemesanan-baru';
    }
    public function handle()
{
    Log::info('Event diproses!'); // Cek di storage/logs/laravel.log
}
}
