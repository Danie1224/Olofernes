<?php

namespace App\Http\Controllers;

use App\Models\AddToCart;
use App\Models\Customer;
use Illuminate\Http\Request;

class AddToCartController extends Controller
{
    public function index(Request $request)
    {
        // Get authenticated user's email to find their customer_id
        $user = $request->user();
        if ($user) {
            $customer = Customer::where('email', $user->email)->first();
            if ($customer) {
                return response()->json(AddToCart::where('customer_id', $customer->customer_id)
                    ->with('product')
                    ->latest()
                    ->paginate(15));
            }
        }
        return response()->json(['data' => []]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Get customer_id from authenticated user
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $customer = Customer::where('email', $user->email)->first();
        if (!$customer) {
            return response()->json(['message' => 'Customer profile not found'], 404);
        }

        // Check if item already exists in cart
        $existingCart = AddToCart::where('customer_id', $customer->customer_id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existingCart) {
            // Update quantity if already exists
            $existingCart->update([
                'quantity' => $existingCart->quantity + $validated['quantity']
            ]);
            return response()->json($existingCart->load('product'), 200);
        }

        $cart = AddToCart::create([
            'customer_id' => $customer->customer_id,
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
        ]);
        
        return response()->json($cart->load('product'), 201);
    }

    public function show(string $cartId)
    {
        $cart = AddToCart::where('cart_id', $cartId)->with('product')->firstOrFail();
        return response()->json($cart);
    }

    public function update(Request $request, string $cartId)
    {
        $cart = AddToCart::where('cart_id', $cartId)->firstOrFail();
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        $cart->update($validated);
        return response()->json($cart->load('product'));
    }

    public function destroy(string $cartId)
    {
        $cart = AddToCart::where('cart_id', $cartId)->firstOrFail();
        $cart->delete();
        return response()->json(null, 204);
    }
}


