<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kos extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'alamat', 'harga', 'fasilitas', 'foto'];

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class);
    }
}
