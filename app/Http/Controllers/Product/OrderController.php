<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Produto;

/**
 * @group Pedidos
 *
 * APIs para gerenciamento de pedidos de produtos.
 */
class OrderController extends Controller
{
    /**
     * Listar pedidos
     *
     * Retorna uma lista paginada de pedidos com seus respectivos produtos.
     *
     * @response 200 {
     *  "current_page": 1,
     *  "data": [
     *    {
     *      "id": 1,
     *      "cliente_nome": "João Silva",
     *      "status": "pendente",
     *      "produtos": [
     *        {
     *          "id": 2,
     *          "nome": "Produto A",
     *          "quantidade": 3
     *        }
     *      ]
     *    }
     *  ],
     *  "last_page": 1,
     *  "per_page": 10,
     *  ...
     * }
     */
    public function index()
    {
        return response()->json(Order::with('produtos')->paginate(10));
    }

    /**
     * Criar um novo pedido
     *
     * Cria um novo pedido com produtos vinculados.
     *
     * @bodyParam cliente_nome string required Nome do cliente. Example: João da Silva
     * @bodyParam status string Status do pedido (ex: pendente, enviado). Example: pendente
     * @bodyParam endereco_rua string required Rua de entrega. Example: Rua das Flores
     * @bodyParam endereco_numero string required Número. Example: 123
     * @bodyParam endereco_bairro string Bairro. Example: Centro
     * @bodyParam endereco_cidade string required Cidade. Example: São Paulo
     * @bodyParam endereco_estado string required Estado. Example: SP
     * @bodyParam endereco_cep string required CEP. Example: 01000-000
     * @bodyParam produtos array required Lista de produtos com quantidade.
     * @bodyParam produtos[].id integer required ID do produto. Example: 1
     * @bodyParam produtos[].quantidade integer required Quantidade do produto. Example: 2
     *
     * @response 201 {
     *   "id": 1,
     *   "cliente_nome": "João da Silva",
     *   "status": "pendente",
     *   "produtos": [...]
     * }
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_nome' => 'required|string',
            'status' => 'nullable|string',
            'endereco_rua' => 'required|string',
            'endereco_numero' => 'required|string',
            'endereco_bairro' => 'nullable|string',
            'endereco_cidade' => 'required|string',
            'endereco_estado' => 'required|string',
            'endereco_cep' => 'required|string',
            'produtos' => 'required|array',
            'produtos.*.id' => 'required|exists:produtos,id',
            'produtos.*.quantidade' => 'required|integer|min:1'
        ]);

        $order = Order::create([
            'cliente_nome' => $data['cliente_nome'],
            'status' => $data['status'] ?? 'pendente',
            'endereco_rua' => $data['endereco_rua'],
            'endereco_numero' => $data['endereco_numero'],
            'endereco_bairro' => $data['endereco_bairro'] ?? null,
            'endereco_cidade' => $data['endereco_cidade'],
            'endereco_estado' => $data['endereco_estado'],
            'endereco_cep' => $data['endereco_cep'],
        ]);

        $produtosSync = [];
        foreach ($data['produtos'] as $p) {
            $produtosSync[$p['id']] = ['quantidade' => $p['quantidade']];
        }

        $order->produtos()->sync($produtosSync);

        return response()->json($order->load('produtos'), 201);
    }

    /**
     * Mostrar um pedido
     *
     * Retorna os dados de um pedido específico.
     *
     * @urlParam order int required ID do pedido. Example: 1
     *
     * @response 200 {
     *   "id": 1,
     *   "cliente_nome": "João da Silva",
     *   "status": "pendente",
     *   "produtos": [...]
     * }
     */
    public function show(Order $order)
    {
        return response()->json($order->load('produtos'));
    }

    /**
     * Atualizar um pedido
     *
     * Atualiza os dados de um pedido existente.
     *
     * @urlParam order int required ID do pedido. Example: 1
     * @bodyParam cliente_nome string Nome do cliente. Example: João da Silva
     * @bodyParam status string Status do pedido. Example: enviado
     * @bodyParam produtos array Lista de produtos.
     * @bodyParam produtos[].id integer ID do produto. Example: 1
     * @bodyParam produtos[].quantidade integer Quantidade do produto. Example: 2
     *
     * @response 200 {
     *   "id": 1,
     *   "cliente_nome": "João da Silva",
     *   "status": "enviado",
     *   "produtos": [...]
     * }
     */
    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'cliente_nome' => 'nullable|string',
            'status' => 'nullable|string',
            'produtos' => 'nullable|array',
            'produtos.*.id' => 'required_with:produtos|exists:produtos,id',
            'produtos.*.quantidade' => 'required_with:produtos|integer|min:1'
        ]);

        $order->update([
            'cliente_nome' => $data['cliente_nome'] ?? $order->cliente_nome,
            'status' => $data['status'] ?? $order->status,
        ]);

        if (isset($data['produtos'])) {
            $produtosSync = [];
            foreach ($data['produtos'] as $p) {
                $produtosSync[$p['id']] = ['quantidade' => $p['quantidade']];
            }
            $order->produtos()->sync($produtosSync);
        }

        return response()->json($order->load('produtos'));
    }

    /**
     * Excluir pedido
     *
     * Remove um pedido existente do sistema.
     *
     * @urlParam order int required ID do pedido. Example: 1
     *
     * @response 204 {}
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(null, 204);
    }
}
