<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        return response()->json(Shipping::latest()->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|integer|exists:orders,order_id',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip_code' => 'required|string',
            'country' => 'required|string',
            'tracking_number' => 'nullable|string',
            'shipping_status' => 'required|in:pending,shipped,delivered,cancelled',
        ]);

        $shipping = Shipping::create($validated);
        return response()->json($shipping, 201);
    }

    public function show(string $shippingId)
    {
        $shipping = Shipping::where('shipping_id', $shippingId)->firstOrFail();
        return response()->json($shipping);
    }

    public function update(Request $request, string $shippingId)
    {
        $shipping = Shipping::where('shipping_id', $shippingId)->firstOrFail();
        $validated = $request->validate([
            'order_id' => 'sometimes|required|integer|exists:orders,order_id',
            'address' => 'sometimes|required|string',
            'city' => 'sometimes|required|string',
            'state' => 'sometimes|required|string',
            'zip_code' => 'sometimes|required|string',
            'country' => 'sometimes|required|string',
            'tracking_number' => 'nullable|string',
            'shipping_status' => 'sometimes|required|in:pending,shipped,delivered,cancelled',
        ]);
        $shipping->update($validated);
        return response()->json($shipping);
    }

    public function destroy(string $shippingId)
    {
        $shipping = Shipping::where('shipping_id', $shippingId)->firstOrFail();
        $shipping->delete();
        return response()->json(null, 204);
    }
}


