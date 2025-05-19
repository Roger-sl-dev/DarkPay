<?php

namespace App\Services\Payments;

use Stripe\PaymentIntent;
use App\Models\Transaction;

class CardPaymentService
{
    public function create(array $data): array
    {
        $paymentIntent = PaymentIntent::create([
            'amount' => intval($data['amount'] * 100),
            'currency' => strtolower($data['currency']),
            'payment_method_types' => ['card'],
            'description' => $data['description'] ?? null,
            'metadata' => $data['metadata'] ?? [],
        ]);

        $transaction = Transaction::create([
            'stripe_id' => $paymentIntent->id,
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'status' => $paymentIntent->status,
            'description' => $data['description'] ?? null,
            'payment_method' => 'card',
        ]);

        return [
            'transaction' => $transaction,
            'client_secret' => $paymentIntent->client_secret,
        ];
    }
}
