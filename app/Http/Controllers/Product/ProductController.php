<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Produto;

/**
 * @group Produtos
 *
 * Gerenciamento de produtos disponíveis.
 */
class ProductController extends Controller
{
    /**
     * Listar produtos
     *
     * Retorna uma lista paginada de produtos.
     *
     * @queryParam per_page int Quantidade de itens por página. Default: 10. Example: 20
     *
     * @response 200 {
     *   "current_page": 1,
     *   "data": [
     *     {
     *       "id": 1,
     *       "nome": "Produto Exemplo",
     *       "descricao": "Descrição detalhada",
     *       "tipo_produto": "digital",
     *       ...
     *     }
     *   ],
     *   "last_page": 1,
     *   ...
     * }
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $produtos = Produto::paginate($perPage);
        return response()->json($produtos);
    }

    /**
     * Criar produto
     *
     * Cria um novo produto.
     *
     * @bodyParam nome string required Nome do produto. Example: Curso de PHP
     * @bodyParam descricao string Descrição do produto. Example: Curso completo de PHP para iniciantes
     * @bodyParam tipo_produto string required Tipo do produto (ex: digital, físico). Example: digital
     * @bodyParam url_da_pl string URL da página de vendas. Example: https://exemplo.com/landing
     * @bodyParam tipo_entrega string Tipo de entrega. Example: e-mail
     * @bodyParam informacoes_email string Informações adicionais para envio por e-mail. Example: Enviar em até 24 horas
     * @bodyParam imagem string URL da imagem do produto. Example: https://exemplo.com/imagem.jpg
     * @bodyParam garantia string Política de garantia. Example: 7 dias de garantia
     *
     * @response 201 {
     *   "id": 1,
     *   "nome": "Curso de PHP",
     *   "tipo_produto": "digital",
     *   ...
     * }
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo_produto' => 'required|string',
            'url_da_pl' => 'nullable|string',
            'tipo_entrega' => 'nullable|string',
            'informacoes_email' => 'nullable|string',
            'imagem' => 'nullable|string',
            'garantia' => 'nullable|string',
        ]);

        $produto = Produto::create($data);
        return response()->json($produto, 201);
    }

    /**
     * Visualizar produto
     *
     * Retorna os dados de um produto específico.
     *
     * @urlParam produto int required ID do produto. Example: 1
     *
     * @response 200 {
     *   "id": 1,
     *   "nome": "Curso de PHP",
     *   ...
     * }
     */
    public function show(Produto $produto)
    {
        return response()->json($produto);
    }

    /**
     * Atualizar produto
     *
     * Atualiza os dados de um produto.
     *
     * @urlParam produto int required ID do produto. Example: 1
     * @bodyParam nome string required Nome do produto. Example: Curso atualizado de PHP
     * @bodyParam descricao string Descrição do produto. Example: Versão 2025 do curso
     * @bodyParam tipo_produto string required Tipo do produto. Example: digital
     * @bodyParam url_da_pl string URL da página de vendas. Example: https://exemplo.com/novo
     * @bodyParam tipo_entrega string Tipo de entrega. Example: e-mail
     * @bodyParam informacoes_email string Informações para envio. Example: Enviar em até 12 horas
     * @bodyParam imagem string URL da imagem do produto. Example: https://exemplo.com/imagem_nova.jpg
     * @bodyParam garantia string Garantia. Example: 15 dias
     *
     * @response 200 {
     *   "id": 1,
     *   "nome": "Curso atualizado de PHP",
     *   ...
     * }
     */
    public function update(Request $request, Produto $produto)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo_produto' => 'required|string',
            'url_da_pl' => 'nullable|string',
            'tipo_entrega' => 'nullable|string',
            'informacoes_email' => 'nullable|string',
            'imagem' => 'nullable|string',
            'garantia' => 'nullable|string',
        ]);

        $produto->update($data);
        return response()->json($produto);
    }

    /**
     * Deletar produto
     *
     * Remove um produto do sistema.
     *
     * @urlParam produto int required ID do produto. Example: 1
     *
     * @response 204 {}
     */
    public function destroy(Produto $produto)
    {
        $produto->delete();
        return response()->json(null, 204);
    }
}
