<?php

namespace App\Exports;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LaporanSewaExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnWidths,
    WithColumnFormatting,
    WithEvents
{
    protected $request;
    protected $totalPendapatan;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->totalPendapatan = 0; // Inisialisasi
    }

    public function collection()
    {
        $query = Pemesanan::with(['user', 'kos', 'pembayaran']);

        // --- Logika Filter (Sama seperti di Controller) ---
        if ($this->request->filled('tanggal_pesan')) {
            $query->whereDate('tanggal_pesan', $this->request->tanggal_pesan);
        } else {
            if ($this->request->filled('bulan_histori')) {
                $query->whereMonth('tanggal_pesan', $this->request->bulan_histori);
            }
            if ($this->request->filled('tahun_histori')) {
                $query->whereYear('tanggal_pesan', $this->request->tahun_histori);
            }
        }

        if ($this->request->filled('status_histori')) {
            $query->where('status_pemesanan', $this->request->status_histori);
        } else {
            $query->whereIn('status_pemesanan', ['diterima', 'selesai']);
        }

        if ($this->request->filled('search_histori')) {
            $searchTerm = $this->request->search_histori;
            $query->where(function ($q) use ($searchTerm) {
                // ... logika search Anda ...
            });
        }
        // --- Akhir Logika Filter ---

        $data = $query->get();

        // Hitung total pendapatan untuk ditampilkan di header
        $this->totalPendapatan = $data->sum(function($item) {
            return $item->pembayaran->where('status', 'diterima')->sum('jumlah');
        });

        return $data;
    }

    public function headings(): array
    {
        // Kolom header untuk tabel data
        return [
            'Penyewa',
            'Email',
            'No. Kamar',
            'Lantai',
            'Tgl. Pesan',
            'Tgl. Masuk',
            'Tgl. Selesai',
            'Lama Sewa (Bln)',
            'Status',
            'Total Bayar (Verified)',
        ];
    }

    public function map($pemesanan): array
    {
        // Memetakan setiap baris data
        return [
            $pemesanan->user->name ?? 'N/A',
            $pemesanan->user->email ?? 'N/A',
            $pemesanan->kos->nomor_kamar ?? 'N/A',
            $pemesanan->kos->lantai ?? 'N/A',
            $pemesanan->tanggal_pesan ? \Carbon\Carbon::parse($pemesanan->tanggal_pesan)->format('d-m-Y') : '-',
            $pemesanan->tanggal_masuk ? \Carbon\Carbon::parse($pemesanan->tanggal_masuk)->format('d-m-Y') : '-',
            $pemesanan->tanggal_selesai ? \Carbon\Carbon::parse($pemesanan->tanggal_selesai)->format('d-m-Y') : '-',
            $pemesanan->lama_sewa,
            ucfirst($pemesanan->status_pemesanan),
            $pemesanan->pembayaran->where('status', 'diterima')->sum('jumlah'),
        ];
    }

    public function columnWidths(): array
    {
        // Mengatur lebar kolom agar tidak terpotong
        return [
            'A' => 25, 'B' => 30, 'C' => 12, 'D' => 8,
            'E' => 15, 'F' => 15, 'G' => 15, 'H' => 18,
            'I' => 15, 'J' => 22,
        ];
    }

    public function columnFormats(): array
    {
        // Format kolom J (Total Bayar) menjadi format Rupiah
        return [
            'J' => '"Rp "#,##0',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Memberi style pada baris header (baris ke-5 setelah info tambahan)
        return [
            5 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // --- Menambahkan Judul dan Info di Atas Tabel ---
                $sheet->insertNewRowBefore(1, 4); // Menambah 4 baris kosong di atas

                // Judul Laporan
                $sheet->mergeCells('A1:J1');
                $sheet->setCellValue('A1', 'LAPORAN PEMESANAN DENIKOS');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // Tanggal Generate
                $sheet->mergeCells('A2:J2');
                $sheet->setCellValue('A2', 'Dibuat pada: ' . now()->isoFormat('D MMMM YYYY, HH:mm:ss'));
                $sheet->getStyle('A2')->getFont()->setItalic(true);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                // Total Pendapatan
                $sheet->setCellValue('A4', 'Total Pendapatan (Sesuai Filter):');
                $sheet->getStyle('A4')->getFont()->setBold(true);
                $sheet->setCellValue('B4', $this->totalPendapatan);
                $sheet->getStyle('B4')->getNumberFormat()->setFormatCode('"Rp "#,##0');
                $sheet->getStyle('B4')->getFont()->setBold(true);

                // --- Memberi Border pada Seluruh Tabel ---
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ];
                $sheet->getStyle('A5:' . $lastColumn . $lastRow)->applyFromArray($styleArray);
            },
        ];
    }
}