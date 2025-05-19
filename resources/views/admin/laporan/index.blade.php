@extends('admin.layout')

@section('content')
<h1 class="text-2xl font-bold mb-4">Laporan Penerimaan Sewa</h1>

<form method="GET" class="mb-4 space-x-2">
    <select name="bulan" class="border rounded p-2">
        <option value="">-- Pilih Bulan --</option>
        @for ($i = 1; $i <= 12; $i++)
            <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                {{ DateTime::createFromFormat('!m', $i)->format('F') }}
            </option>
        @endfor
    </select>

    <select name="tahun" class="border rounded p-2">
        <option value="">-- Pilih Tahun --</option>
        @for ($y = 2023; $y <= now()->year; $y++)
            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
        @endfor
    </select>

    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Filter</button>
</form>

<table class="w-full table-auto border-collapse mb-4">
    <div class="mb-4 space-x-2">
        <a href="{{ route('admin.laporan.exportExcel') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Export Excel</a>
        <a href="{{ route('admin.laporan.exportPDF') }}" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Export PDF</a>
    </div>
    <thead>
        <tr class="bg-gray-100">
            <th class="p-2 border">Nama</th>
            <th class="p-2 border">Kamar</th>
            <th class="p-2 border">Tanggal Masuk</th>
            <th class="p-2 border">Jumlah Bayar</th>
            <th class="p-2 border">Detail</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td class="p-2 border">{{ $item->user->name }}</td>
                <td class="p-2 border">Kamar {{ $item->kos->nomor_kamar }}</td>
                <td class="p-2 border">{{ $item->tanggal_pesan }}</td>
                <td class="p-2 border">Rp{{ number_format($item->total_pembayaran, 0, ',', '.') }}</td>
                <td class="p-2 border">
                    <button type="button" onclick="document.getElementById('detail-{{ $item->user_id }}-{{ $item->kos_id }}').classList.toggle('hidden')" class="bg-indigo-500 text-white px-3 py-1 rounded text-xs">Lihat</button>
                </td>
            </tr>
            <tr id="detail-{{ $item->user_id }}-{{ $item->kos_id }}" class="hidden">
                <td colspan="5" class="bg-gray-50 p-3">
                    <div class="font-semibold mb-2">Histori Pembayaran User Ini di Kamar Ini:</div>
                    <ul class="list-disc ml-6 text-sm">
                        @foreach(($detailHistori[$item->user_id.'-'.$item->kos_id] ?? []) as $hist)
                            <li>
                                Tanggal: {{ $hist->tanggal_pesan }} | Jumlah: Rp{{ number_format($hist->total_pembayaran, 0, ',', '.') }} | Status: <span class="{{ $hist->is_perpanjangan ? 'text-blue-600' : 'text-green-600' }}">{{ $hist->is_perpanjangan ? 'Perpanjangan' : 'Awal' }}</span>
                            </li>
                        @endforeach
                    </ul>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<p class="text-lg font-bold">
    Total Pendapatan: Rp{{ number_format($totalPendapatan, 0, ',', '.') }}
</p>
@endsection
