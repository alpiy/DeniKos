<?php


namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotifikasiUserBaru implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $title;
    public $message;

    public function __construct($userId, $title, $message)
    {
        $this->userId = $userId;
        $this->title = $title;
        $this->message = $message;
    }

    public function broadcastOn()
    {
       
         return ['user.' . $this->userId];
    }

    public function broadcastAs()
    {
        return 'notifikasi-user';
    }
}