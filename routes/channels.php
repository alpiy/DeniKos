<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('notifikasi-admin', function ($user) {
    Log::info("Auth attempt by user: " . ($user ? $user->id : 'null'));
    return $user && $user->role === 'admin';
});
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});