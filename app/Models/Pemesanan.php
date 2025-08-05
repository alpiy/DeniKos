<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pemesanan extends Model
{
    use HasFactory;

    protected $fillable = ['kos_id', 'user_id', 'tanggal_pesan', 'tanggal_masuk', 'tanggal_selesai', 'status_pemesanan','bukti_pembayaran', 'lama_sewa', 'total_pembayaran', 'status_refund', 'payment_deadline'];
    protected $table = 'pemesanan';
    
    protected $casts = [
        'tanggal_pesan' => 'datetime',
        'tanggal_masuk' => 'date',
        'tanggal_selesai' => 'date',
        'payment_deadline' => 'datetime',
    ];


    public function kos()
    {
        return $this->belongsTo(Kos::class, 'kos_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }
    
    // Accessor untuk payment deadline dengan fallback
    public function getPaymentDeadlineAttribute($value)
    {
        if ($value) {
            return \Carbon\Carbon::parse($value);
        }
        
        // Fallback ke 24 jam setelah tanggal pesan untuk data lama
        return \Carbon\Carbon::parse($this->tanggal_pesan)->addHours(24);
    }
    
    // Accessor untuk checking apakah payment expired
    public function getIsPaymentExpiredAttribute()
    {
        return \Carbon\Carbon::now()->gt($this->payment_deadline);
    }
    
    // Accessor untuk checking apakah ada pembayaran
    public function getHasPaymentAttribute()
    {
        return $this->pembayaran()->exists();
    }
}
