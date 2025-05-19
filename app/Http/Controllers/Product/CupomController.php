<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cupom;

/**
 * @group Cupons
 *
 * APIs para gerenciamento de cupons de desconto.
 */
class CupomController extends Controller
{
    /**
     * Listar cupons
     *
     * Retorna uma lista paginada de cupons cadastrados.
     *
     * @response 200 {
     *   "current_page": 1,
     *   "data": [
     *     {
     *       "id": 1,
     *       "codigo": "DESCONTO10",
     *       "tipo": "percentual",
     *       "valor": 10,
     *       "ativo": true,
     *       "validade": "2025-12-31",
     *       ...
     *     }
     *   ],
     *   "last_page": 1,
     *   ...
     * }
     */
    public function index()
{
    $cupons = Cupom::all();

    return response()->json([
        'data' => $cupons
    ]);
}

    /**
     * Criar um novo cupom
     *
     * Registra um novo cupom de desconto.
     *
     * @bodyParam codigo string required Código único do cupom. Example: DESCONTO10
     * @bodyParam descricao string Descrição do cupom. Example: 10% de desconto em toda a loja
     * @bodyParam tipo string required Tipo do cupom: percentual ou valor_fixo. Example: percentual
     * @bodyParam valor number required Valor do desconto. Example: 10
     * @bodyParam ativo boolean Define se o cupom está ativo. Example: true
     * @bodyParam validade date Data de validade do cupom. Example: 2025-12-31
     *
     * @response 201 {
     *   "id": 1,
     *   "codigo": "DESCONTO10",
     *   "tipo": "percentual",
     *   "valor": 10,
     *   "ativo": true,
     *   ...
     * }
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => 'required|string|unique:cupons,codigo',
            'descricao' => 'nullable|string',
            'tipo' => 'required|in:percentual,valor_fixo',
            'valor' => 'required|numeric|min:0',
            'ativo' => 'boolean',
            'validade' => 'nullable|date',
        ]);

        $cupom = Cupom::create($data);
        return response()->json($cupom, 201);
    }

    /**
     * Mostrar cupom
     *
     * Retorna os dados de um cupom específico.
     *
     * @urlParam cupom int required ID do cupom. Example: 1
     *
     * @response 200 {
     *   "id": 1,
     *   "codigo": "DESCONTO10",
     *   "tipo": "percentual",
     *   "valor": 10,
     *   ...
     * }
     */
    public function show(Cupom $cupom)
    {
        return response()->json($cupom);
    }

    /**
     * Atualizar cupom
     *
     * Atualiza os dados de um cupom existente.
     *
     * @urlParam cupom int required ID do cupom. Example: 1
     * @bodyParam codigo string Código do cupom. Example: DESCONTO20
     * @bodyParam descricao string Descrição do cupom. Example: 20% de desconto
     * @bodyParam tipo string Tipo do cupom. Example: percentual
     * @bodyParam valor number Valor do desconto. Example: 20
     * @bodyParam ativo boolean Define se o cupom está ativo. Example: false
     * @bodyParam validade date Data de validade. Example: 2025-12-31
     *
     * @response 200 {
     *   "id": 1,
     *   "codigo": "DESCONTO20",
     *   ...
     * }
     */
    public function update(Request $request, Cupom $cupom)
    {
        $data = $request->validate([
            'codigo' => 'sometimes|required|string|unique:cupons,codigo,' . $cupom->id,
            'descricao' => 'nullable|string',
            'tipo' => 'in:percentual,valor_fixo',
            'valor' => 'numeric|min:0',
            'ativo' => 'boolean',
            'validade' => 'nullable|date',
        ]);

        $cupom->update($data);
        return response()->json($cupom);
    }

    /**
     * Deletar cupom
     *
     * Remove um cupom existente do sistema.
     *
     * @urlParam cupom int required ID do cupom. Example: 1
     *
     * @response 204 {}
     */
    public function destroy(Cupom $cupom)
    {
        $cupom->delete();
        return response()->json(null, 204);
    }
}
