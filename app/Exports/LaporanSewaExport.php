<?php

namespace App\Exports;


use App\Models\Pemesanan;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Http\Request;

class LaporanSewaExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Pemesanan::with(['user', 'kos', 'pembayaran'])
            ->whereIn('status_pemesanan', ['diterima', 'selesai']); // Filter status

        if ($this->request->filled('bulan')) {
            $query->whereMonth('tanggal_pesan', $this->request->bulan);
        }
        if ($this->request->filled('tahun')) {
            $query->whereYear('tanggal_pesan', $this->request->tahun);
        }
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nama Penyewa',
            'Email Penyewa',
            'Nomor Kamar',
            'Lantai',
            'Tanggal Pesan',
            'Tanggal Masuk',
            'Tanggal Selesai',
            'Lama Sewa (bulan)',
            'Total Tagihan Seharusnya',
            'Total Telah Dibayar (Terverifikasi)',
            'Status Pemesanan',
            'Jenis Pemesanan'
        ];
    }

    public function map($item): array
    {
        $totalTagihanSeharusnya = $item->lama_sewa * ($item->kos->harga_bulanan ?? 0);
        $totalTelahDibayar = $item->pembayaran->where('status', 'diterima')->sum('jumlah');

        return [
            $item->user->name ?? '-',
            $item->user->email ?? '-',
            $item->kos->nomor_kamar ?? '-',
            $item->kos->lantai ?? '-',
            \Carbon\Carbon::parse($item->tanggal_pesan)->format('d-m-Y'),
            $item->tanggal_masuk ? \Carbon\Carbon::parse($item->tanggal_masuk)->format('d-m-Y') : '-',
            $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') : '-',
            $item->lama_sewa,
            $totalTagihanSeharusnya,
            $totalTelahDibayar,
            ucfirst($item->status_pemesanan),
            $item->is_perpanjangan ? 'Perpanjangan' : 'Awal',
        ];
    }
}
