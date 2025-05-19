<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkout;

class CheckoutController extends Controller
{
    public function index()
    {
        return response()->json(Checkout::with(['user', 'product'])->latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'bump_offer_id' => 'nullable|exists:offers,id',
            'upsell_offer_id' => 'nullable|exists:offers,id',
            'total_amount' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $checkout = Checkout::create($data);

        return response()->json($checkout, 201);
    }

    public function show($id)
    {
        $checkout = Checkout::with(['user', 'product'])->findOrFail($id);
        return response()->json($checkout);
    }

    public function update(Request $request, $id)
    {
        $checkout = Checkout::findOrFail($id);

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'bump_offer_id' => 'nullable|exists:offers,id',
            'upsell_offer_id' => 'nullable|exists:offers,id',
            'total_amount' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $checkout->update($data);

        return response()->json($checkout);
    }

    public function destroy($id)
    {
        $checkout = Checkout::findOrFail($id);
        $checkout->delete();

        return response()->json(['message' => 'Checkout deletado com sucesso']);
    }
}

