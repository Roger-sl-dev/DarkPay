<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produto;

class SolucõesController extends Controller
{
   

    public function show(Produto $produto)
    {
        return response()->json($produto);
    }

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

    public function destroy(Produto $produto)
    {
        $produto->delete();

        return response()->json(null, 204);
    }
}
