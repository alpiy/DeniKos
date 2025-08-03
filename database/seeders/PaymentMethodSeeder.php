<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'type' => 'bank_transfer',
                'name' => 'Bank BRI',
                'account_number' => '1234-5678-9012-3456',
                'account_name' => 'Deni Kos',
                'instructions' => 'Transfer ke rekening BRI atas nama Deni Kos. Jangan lupa screenshot bukti transfer dan upload sebagai bukti pembayaran.',
                'logo_path' => null,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'type' => 'qris',
                'name' => 'QRIS DeniKos',
                'account_number' => null,
                'account_name' => 'Deni Kos',
                'instructions' => 'Scan QR Code dengan aplikasi mobile banking atau e-wallet. Masukkan nominal sesuai tagihan.',
                'logo_path' => 'qris/qris.jpg',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'type' => 'bank_transfer',
                'name' => 'Bank Mandiri',
                'account_number' => '987-654-321-012',
                'account_name' => 'Deni Kos',
                'instructions' => 'Transfer ke rekening Mandiri atas nama Deni Kos. Simpan bukti transfer untuk diupload.',
                'logo_path' => null,
                'is_active' => false, // Nonaktif sebagai contoh
                'sort_order' => 3
            ]
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }
    }
}
