<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;

/**
 * @group Categorias
 *
 * Endpoints para gerenciamento de categorias de produtos.
 */
class CategoriaController extends Controller
{
    /**
     * Listar categorias
     *
     * Retorna uma lista paginada de categorias.
     *
     * @queryParam per_page int Quantidade de itens por página. Default: 10. Example: 15
     *
     * @response 200 {
     *   "current_page": 1,
     *   "data": [
     *     {
     *       "id": 1,
     *       "nome": "Cursos",
     *       "descricao": "Categoria para cursos digitais",
     *       ...
     *     }
     *   ],
     *   ...
     * }
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        return response()->json(Categoria::paginate($perPage));
    }

    /**
     * Criar categoria
     *
     * Cria uma nova categoria.
     *
     * @bodyParam nome string required Nome da categoria. Example: Cursos
     * @bodyParam descricao string Descrição da categoria. Example: Categoria para cursos online
     *
     * @response 201 {
     *   "id": 1,
     *   "nome": "Cursos",
     *   "descricao": "Categoria para cursos online",
     *   ...
     * }
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $categoria = Categoria::create($data);
        return response()->json($categoria, 201);
    }

    /**
     * Visualizar categoria
     *
     * Exibe os detalhes de uma categoria específica.
     *
     * @urlParam categoria int required ID da categoria. Example: 1
     *
     * @response 200 {
     *   "id": 1,
     *   "nome": "Cursos",
     *   "descricao": "Categoria para cursos online"
     * }
     */
    public function show(Categoria $categoria)
    {
        return response()->json($categoria);
    }

    /**
     * Atualizar categoria
     *
     * Atualiza uma categoria existente.
     *
     * @urlParam categoria int required ID da categoria. Example: 1
     * @bodyParam nome string required Nome da categoria. Example: Cursos Avançados
     * @bodyParam descricao string Descrição da categoria. Example: Cursos de nível avançado
     *
     * @response 200 {
     *   "id": 1,
     *   "nome": "Cursos Avançados",
     *   "descricao": "Cursos de nível avançado"
     * }
     */
    public function update(Request $request, Categoria $categoria)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $categoria->update($data);
        return response()->json($categoria);
    }

    /**
     * Deletar categoria
     *
     * Remove uma categoria do sistema.
     *
     * @urlParam categoria int required ID da categoria. Example: 1
     *
     * @response 204 {}
     */
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return response()->json(null, 204);
    }
}
