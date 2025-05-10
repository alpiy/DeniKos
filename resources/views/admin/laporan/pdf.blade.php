<h2>Laporan Sewa</h2>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Kamar</th>
            <th>Tanggal Masuk</th>
            <th>Jumlah Pembayaran</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $item->user->name }}</td>
                <td>{{ $item->user->email }}</td>   
                <td>{{ $item->kos->nomor_kamar }}</td>
                <td>{{ $item->tanggal_pesan }}</td>
                <td>Rp{{ number_format($item->total_pembayaran, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
