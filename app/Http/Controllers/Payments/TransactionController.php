<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Transaction;
use App\Services\Payments\CardPaymentService;
use App\Services\Payments\PixPaymentService;

/**
 * @group Pagamentos
 *
 * Gerenciamento de transações com Stripe.
 */
class TransactionController extends Controller
{
    protected $pixService;
    protected $cardService;

    public function __construct(PixPaymentService $pixService, CardPaymentService $cardService)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $this->pixService = $pixService;
        $this->cardService = $cardService;
    }
    /**
     * Criar uma nova transação.
     * 
     * Cria uma transação com Stripe usando os dados fornecidos. Retorna o `client_secret`
     * para completar o pagamento no frontend. Se o método de pagamento for `pix`,
     * retorna também o QR Code.
     * 
     * @bodyParam amount float required Valor da transação. Exemplo: 49.90
     * @bodyParam currency string required Moeda no formato ISO 4217 (3 letras). Exemplo: BRL
     * @bodyParam payment_method_type string required Tipo de pagamento. Valores possíveis: card, pix. Exemplo: pix
     * @bodyParam description string Descrição da transação. Exemplo: Pagamento de produto X
     * @bodyParam metadata object Informações adicionais em forma de chave/valor. Exemplo: {"pedido_id": "123"}
     * 
     * @response 201 {
     *   "transaction": {
     *     "id": 1,
     *     "stripe_id": "pi_1234567890",
     *     "amount": 49.9,
     *     "currency": "brl",
     *     "status": "requires_payment_method",
     *     "description": "Pagamento de produto X",
     *     "payment_method": "pix",
     *     "created_at": "2025-05-08T12:00:00Z",
     *     "updated_at": "2025-05-08T12:00:00Z"
     *   },
     *   "client_secret": "pi_1234567890_secret_abc123",
     *   "pix_qr_code": "data:image/png;base64,..."
     * }
     * 
     * @response 500 {
     *   "error": "Erro ao criar transação."
     * }
     */
    
     public function store(Request $request)
     {
         $this->validateRequest($request);
 
         try {
             $method = $request->payment_method_type;
 
             return match ($method) {
                 'pix' => response()->json($this->pixService->create($request->all()), 201),
                 'card' => response()->json($this->cardService->create($request->all()), 201),
             };
         } catch (\Exception $e) {
             Log::error("Erro ao criar transação [$request->payment_method_type]: " . $e->getMessage());
             return response()->json(['error' => 'Erro ao criar transação.'], 500);
         }
     }
 
     /**
     * Listar transações.
     * 
     * Retorna a lista de transações ordenadas pela mais recente.
     * 
     * @response 200 [
     *   {
     *     "id": 1,
     *     "stripe_id": "pi_1234567890",
     *     "amount": 49.9,
     *     "currency": "brl",
     *     "status": "succeeded",
     *     "description": "Pagamento de produto X",
     *     "payment_method": "card",
     *     "created_at": "2025-05-08T12:00:00Z",
     *     "updated_at": "2025-05-08T12:00:00Z"
     *   }
     * ]
     */
    public function index()
    {
        return response()->json(Transaction::latest()->get());
    }

    /**
     * Exibir uma transação específica.
     * 
     * Retorna os detalhes de uma transação pelo ID.
     * 
     * @urlParam id integer required ID da transação. Exemplo: 1
     * 
     * @response 200 {
     *   "id": 1,
     *   "stripe_id": "pi_1234567890",
     *   "amount": 49.9,
     *   "currency": "brl",
     *   "status": "succeeded",
     *   "description": "Pagamento de produto X",
     *   "payment_method": "card",
     *   "created_at": "2025-05-08T12:00:00Z",
     *   "updated_at": "2025-05-08T12:00:00Z"
     * }
     */

    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);
        return response()->json($transaction);
    }
     /**
     * Cancelar uma transação.
     * 
     * Cancela uma transação existente via Stripe e atualiza seu status no banco de dados.
     * 
     * @urlParam id integer required ID da transação. Exemplo: 1
     * 
     * @response 200 {
     *   "message": "Transação cancelada.",
     *   "transaction": {
     *     "id": 1,
     *     "stripe_id": "pi_1234567890",
     *     "status": "canceled",
     *     ...
     *   }
     * }
     * 
     * @response 500 {
     *   "error": "Não foi possível cancelar a transação."
     * }
     */

    public function cancel($id)
    {
        $transaction = Transaction::findOrFail($id);

        try {
            $intent = PaymentIntent::retrieve($transaction->stripe_id);
            $canceled = $intent->cancel();

            $transaction->status = $canceled->status;
            $transaction->save();

            return response()->json([
                'message' => 'Transação cancelada.',
                'transaction' => $transaction
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao cancelar: ' . $e->getMessage());
            return response()->json(['error' => 'Não foi possível cancelar a transação.'], 500);
        }
    }

    private function validateRequest(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string|size:3',
            'payment_method_type' => 'required|in:card,pix',
            'description' => 'nullable|string',
            'metadata' => 'nullable|array',
        ]);
    }
}
