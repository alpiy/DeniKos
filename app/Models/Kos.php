<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kos extends Model
{
    use HasFactory;

    protected $fillable = ['nama_kos', 'alamat', 'harga', 'deskripsi','fasilitas', 'foto'];
    protected $casts = [
        'fasilitas' => 'array',
    ];

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class);
    }
}
