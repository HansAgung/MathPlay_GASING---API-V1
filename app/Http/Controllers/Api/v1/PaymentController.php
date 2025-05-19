<?php

use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function createTransaction(Request $request)
    {
        // Konfigurasi Midtrans
        Config::$serverKey = 'SB-Mid-server-r4gNqqnEClaCtc_4yuMwEkMX';
        Config::$isProduction = false;

        $params = [
            'transaction_details' => [
                'order_id' => uniqid(),
                'gross_amount' => 16000,
            ],
            'customer_details' => [
                'first_name' => 'User',
                'email' => 'user@example.com',
            ],
            'enabled_payments' => ['qris'],
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json([
            'snap_token' => $snapToken
        ]);
    }
}
