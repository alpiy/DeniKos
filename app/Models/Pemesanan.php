<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pemesanan extends Model
{
    use HasFactory;

    protected $fillable = ['kos_id', 'user_id', 'tanggal_pesan', 'status_pemesanan','bukti_pembayaran', 'lama_sewa', 'total_pembayaran', 'is_perpanjangan', 'status_refund'];
    protected $table = 'pemesanan';


    public function kos()
    {
        return $this->belongsTo(Kos::class, 'kos_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
