<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kos extends Model
{
    use HasFactory;

    protected $fillable = ['nomor_kamar', 'alamat', 'harga_bulanan', 'deskripsi','fasilitas', 'foto', "status_kamar", 'denah_kamar', 'lantai'];
    protected $casts = [
        'fasilitas' => 'array',
        'foto' => 'array',
    ];
    

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class);
    }
}
