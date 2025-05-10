<?php

namespace App\Exports;


use App\Models\Pemesanan;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class LaporanSewaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Pemesanan::with('user', 'kos')
            ->where('status_pemesanan', 'diterima')
            ->get()
            ->map(function ($item) {
                return [
                    'Nama' => $item->user->name,
                    'Email' => $item->user->email,
                    'Nomor Kamar' => $item->kos->nomor_kamar,
                    'Tanggal Masuk' => $item->tanggal_pesan,
                    'Jumlah Pembayaran' => $item->total_pembayaran,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'Nomor Kamar',
            'Tanggal Masuk',
            'Jumlah Pembayaran',
        ];
    }
   
   

}
