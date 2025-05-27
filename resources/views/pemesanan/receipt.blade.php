<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tanda Terima Pembayaran - DeniKos</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f6fa;
            margin: 0;
            padding: 0;
        }
        .receipt-container {
            max-width: 480px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(80, 112, 255, 0.08);
            padding: 32px 28px 24px 28px;
        }
        .receipt-title {
            color: #4338ca;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 18px;
            text-align: center;
        }
        .receipt-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 1rem;
        }
        .receipt-label {
            font-weight: 600;
            color: #374151;
        }
        .receipt-value {
            color: #22223b;
        }
        .status-lunas {
            color: #16a34a;
            font-weight: bold;
        }
        .divider {
            border-bottom: 1px dashed #c7d2fe;
            margin: 18px 0 18px 0;
        }
        .footer {
            text-align: center;
            margin-top: 24px;
            color: #64748b;
            font-size: 0.95rem;
        }
        .brand {
            text-align: center;
            margin-bottom: 18px;
        }
        .brand img {
            width: 60px;
            margin-bottom: 6px;
        }
        .download-btn {
            display: inline-block;
            background: #4338ca;
            color: #fff;
            padding: 10px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 18px;
            transition: background 0.2s;
        }
        .download-btn:hover {
            background: #3730a3;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="brand">
            <img src="{{ public_path('images/landing/kos-slide1.jpeg') }}" alt="Logo DeniKos">
            <div style="font-weight:bold; color:#4338ca; font-size:1.1rem;">DeniKos</div>
        </div>
        <div class="receipt-title">Tanda Terima Pembayaran</div>
        <div class="divider"></div>
        <div class="receipt-row"><span class="receipt-label">Nama :</span><span class="receipt-value">{{ $pemesanan->user->name }}</span></div>
        <div class="receipt-row"><span class="receipt-label">Tanggal Pembayaran :</span><span class="receipt-value">{{ $pembayaran->created_at->format('d M Y H:i') }}</span></div>
        <div class="receipt-row"><span class="receipt-label">Nomor Pemesanan :</span><span class="receipt-value">#{{ $pemesanan->id }}</span></div>
        <div class="receipt-row"><span class="receipt-label">Kamar :</span>
            <span class="receipt-value">
                @if(isset($pemesanan->kos) && $pemesanan->kos)
                    Kamar {{ is_object($pemesanan->kos) ? $pemesanan->kos->nomor_kamar : $pemesanan->kos['nomor_kamar'] ?? '-' }}
                @elseif(isset($pemesanan->kamar) && is_iterable($pemesanan->kamar))
                    @foreach($pemesanan->kamar as $kamar)
                        Kamar {{ $kamar->nomor_kamar }}@if(!$loop->last), @endif
                    @endforeach
                @else
                    -
                @endif
            </span>
        </div>
        <div class="receipt-row"><span class="receipt-label">Periode Sewa :</span>
            <span class="receipt-value">
                @php
                    $tanggalMasuk = $pemesanan->tanggal_masuk instanceof \Carbon\Carbon ? $pemesanan->tanggal_masuk : \Carbon\Carbon::parse($pemesanan->tanggal_masuk);
                    $tanggalSelesai = $pemesanan->tanggal_selesai instanceof \Carbon\Carbon ? $pemesanan->tanggal_selesai : \Carbon\Carbon::parse($pemesanan->tanggal_selesai);
                @endphp
                {{ $tanggalMasuk->format('d M Y') }} - {{ $tanggalSelesai->format('d M Y') }}
            </span>
        </div>
        <div class="receipt-row"><span class="receipt-label">Total Pembayaran :</span><span class="receipt-value">Rp{{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span></div>
        <div class="receipt-row"><span class="receipt-label">Status :</span><span class="status-lunas">Lunas</span></div>
        <div class="divider"></div>
        <div class="footer">
            Terima kasih telah melakukan pembayaran di DeniKos.<br>
            Tanda terima ini sah tanpa tanda tangan.<br>
            @php
                $now = \Carbon\Carbon::now()->setTimezone('Asia/Jakarta');
            @endphp
            <span style="font-size:0.9em;">Dicetak pada: {{ $now->format('d M Y H:i') }} WIB</span>
        </div>
    </div>
</body>
</html>
