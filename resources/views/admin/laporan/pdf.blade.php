<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pemesanan DeniKos</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; margin: 0; padding: 0; color: #333; }
        .container { padding: 20px; }
        h1, h2 { text-align: center; color: #2c3e50; margin-bottom: 5px; }
        h1 { font-size: 18px; margin-bottom: 20px;}
        h2 { font-size: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #bdc3c7; padding: 6px; text-align: left; }
        th { background-color: #ecf0f1; font-weight: bold; font-size: 10px; }
        td { font-size: 9px; }
        .total-section { margin-top: 20px; text-align: right; }
        .total-section p { font-size: 12px; font-weight: bold; color: #2980b9; }
        .footer { text-align: center; font-size: 8px; color: #7f8c8d; position: fixed; bottom: 10px; width:100%; }
        .status-diterima { color: green; }
        .status-selesai { color: blue; }
        .status-pending { color: orange; }
        .status-batal, .status-ditolak { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Laporan Pemesanan DeniKos</h1>
        @if(request()->filled('status_histori') || request()->filled('bulan_histori') || request()->filled('tahun_histori') || request()->filled('search_histori'))
            <p style="font-size:9px; text-align:center; margin-bottom:15px;">
                Filter Aktif:
                @if(request()->filled('search_histori')) Pencarian "{{ request('search_histori') }}"; @endif
                @if(request()->filled('status_histori')) Status "{{ ucfirst(request('status_histori')) }}"; @endif
                @if(request()->filled('bulan_histori')) Bulan {{ DateTime::createFromFormat('!m', request('bulan_histori'))->format('F') }}; @endif
                @if(request()->filled('tahun_histori')) Tahun {{ request('tahun_histori') }}; @endif
            </p>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Penyewa</th>
                    <th>Kamar</th>
                    <th>Tgl. Pesan</th>
                    <th>Periode Sewa</th>
                    <th>Lama</th>
                    <th>Status</th>
                    {{-- <th>Jenis</th> --}}
                    <th>Total Dibayar (Verified)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                    <tr>
                        <td>{{ $item->user->name ?? '-' }} <br><small>{{ $item->user->email ?? '-' }}</small></td>
                        <td>Km {{ $item->kos->nomor_kamar ?? '-' }} (Lt {{ $item->kos->lantai ?? '-' }})</td>
                        <td>{{ $item->tanggal_pesan ? \Carbon\Carbon::parse($item->tanggal_pesan)->format('d/m/y') : '-'}}</td>
                        <td>
                            {{ $item->tanggal_masuk ? \Carbon\Carbon::parse($item->tanggal_masuk)->format('d/m/y') : '-'}}
                            -
                            {{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/y') : '-'}}
                        </td>
                        <td>{{ $item->lama_sewa }} bln</td>
                        <td class="status-{{ strtolower($item->status_pemesanan) }}">{{ ucfirst($item->status_pemesanan) }}</td>
                        {{-- <td>{{ $item->is_perpanjangan ? 'Perpanjangan' : 'Awal' }}</td> --}}
                        <td>Rp{{ number_format($item->pembayaran->where('status', 'diterima')->sum('jumlah'), 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center;">Tidak ada data yang cocok dengan filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="total-section">
            <p>Total Pendapatan Terverifikasi (Sesuai Filter): Rp{{ number_format($totalPendapatanTerverifikasi, 0, ',', '.') }}</p>
        </div>
    </div>
    <div class="footer">
        Laporan ini dibuat pada: {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }} - DeniKos
    </div>
</body>
</html>