<?php

namespace App\Services\Payments;

use Stripe\PaymentIntent;
use App\Models\Transaction;

class PixPaymentService
{
    public function create(array $data): array
    {
        $paymentIntent = PaymentIntent::create([
            'amount' => intval($data['amount'] * 100),
            'currency' => strtolower($data['currency']),
            'payment_method_types' => ['pix'],
            'description' => $data['description'] ?? null,
            'metadata' => $data['metadata'] ?? [],
        ]);

        $transaction = Transaction::create([
            'stripe_id' => $paymentIntent->id,
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'status' => $paymentIntent->status,
            'description' => $data['description'] ?? null,
            'payment_method' => 'pix',
        ]);

        return [
            'transaction' => $transaction,
            'client_secret' => $paymentIntent->client_secret,
            'pix_qr_code' => $paymentIntent->next_action['pix_display_qr_code']['data'] ?? null,
        ];
    }
}
