<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pemesanan extends Model
{
    use HasFactory;

    protected $fillable = ['kos_id', 'nama', 'email', 'no_hp', 'tgl_masuk', 'bukti_pembayaran'];
    protected $table = 'pemesanan';


    public function kos()
    {
        return $this->belongsTo(Kos::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
