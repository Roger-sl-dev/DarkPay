<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use Stripe\Stripe;
use Stripe\Refund;

class RefundController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Realiza o reembolso de uma transação.
     */
    public function refund(Request $request, $id)
    {
        $request->validate([
            'amount' => 'nullable|numeric|min:0.5',
            'reason' => 'nullable|in:duplicate,fraudulent,requested_by_customer',
        ]);

        $transaction = Transaction::findOrFail($id);

        try {
            $params = [
                'payment_intent' => $transaction->stripe_id,
            ];

            if ($request->filled('amount')) {
                $params['amount'] = intval($request->amount * 100); // Stripe espera em centavos
            }

            if ($request->filled('reason')) {
                $params['reason'] = $request->reason;
            }

            $refund = Refund::create($params);

            // Opcional: você pode salvar o status do reembolso na tabela de transações ou em uma tabela separada
            $transaction->update([
                'status' => 'refunded'
            ]);

            return response()->json([
                'message' => 'Reembolso realizado com sucesso.',
                'refund' => $refund,
                'transaction' => $transaction,
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao reembolsar transação: ' . $e->getMessage());
            return response()->json(['error' => 'Não foi possível realizar o reembolso.'], 500);
        }
    }
}
