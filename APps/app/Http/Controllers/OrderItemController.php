<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        return response()->json(OrderItem::latest()->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|integer|exists:orders,order_id',
            'product_id' => 'required|integer|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric',
            'subtotal' => 'required|numeric',
        ]);

        $item = OrderItem::create($validated);
        return response()->json($item, 201);
    }

    public function show(string $orderItemId)
    {
        $item = OrderItem::where('order_item_id', $orderItemId)->firstOrFail();
        return response()->json($item);
    }

    public function update(Request $request, string $orderItemId)
    {
        $item = OrderItem::where('order_item_id', $orderItemId)->firstOrFail();
        $validated = $request->validate([
            'order_id' => 'sometimes|required|integer|exists:orders,order_id',
            'product_id' => 'sometimes|required|integer|exists:products,product_id',
            'quantity' => 'sometimes|required|integer|min:1',
            'unit_price' => 'sometimes|required|numeric',
            'subtotal' => 'sometimes|required|numeric',
        ]);
        $item->update($validated);
        return response()->json($item);
    }

    public function destroy(string $orderItemId)
    {
        $item = OrderItem::where('order_item_id', $orderItemId)->firstOrFail();
        $item->delete();
        return response()->json(null, 204);
    }
}


