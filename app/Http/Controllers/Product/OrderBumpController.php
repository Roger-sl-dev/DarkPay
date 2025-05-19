<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderBump;
class OrderBumpController extends Controller
{
    public function index()
    {
        return response()->json(OrderBump::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'active' => 'boolean',
        ]);

        $offer = OrderBump::create($data);

        return response()->json($offer, 201);
    }

    public function show($id)
    {
        $offer = OrderBump::findOrFail($id);
        return response()->json($offer);
    }

    public function update(Request $request, $id)
    {
        $offer = OrderBump::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'active' => 'boolean',
        ]);

        $offer->update($data);

        return response()->json($offer);
    }

    public function destroy($id)
    {
        $offer = OrderBump::findOrFail($id);
        $offer->delete();

        return response()->json(['message' => 'Order Bump deletado com sucesso']);
    }
}
