<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UpsellOffer;


class UpsellController extends Controller
{
    public function index()
    {
        return response()->json(UpsellOffer::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'active' => 'boolean',
        ]);

        $upsell = UpsellOffer::create($data);

        return response()->json($upsell, 201);
    }

    public function show($id)
    {
        $upsell = UpsellOffer::findOrFail($id);
        return response()->json($upsell);
    }

    public function update(Request $request, $id)
    {
        $upsell = UpsellOffer::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'active' => 'boolean',
        ]);

        $upsell->update($data);

        return response()->json($upsell);
    }

    public function destroy($id)
    {
        $upsell = UpsellOffer::findOrFail($id);
        $upsell->delete();

        return response()->json(['message' => 'Upsell deletado com sucesso']);
    }
}
