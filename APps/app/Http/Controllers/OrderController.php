<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Check if user is authenticated
        $user = Auth::guard('sanctum')->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required',
                'data' => []
            ], 401);
        }
        
        // Check if user is admin (has admin_id property)
        $isAdmin = isset($user->admin_id) || isset($user->role);
        
        if ($isAdmin) {
            // Admin sees ALL orders
            $orders = Order::with(['orderItems.product', 'customer'])
                ->latest()
                ->get();
                
            return response()->json([
                'success' => true,
                'data' => $orders,
                'count' => $orders->count()
            ]);
        }
        
        // Regular user - find their customer account
        $customer = Customer::whereRaw('LOWER(email) = ?', [strtolower($user->email)])->first();
        
        if ($customer) {
            // Return only this customer's orders with items
            $orders = Order::with(['orderItems.product'])
                ->where('customer_id', $customer->customer_id)
                ->latest()
                ->get();
                
            return response()->json([
                'success' => true,
                'data' => $orders,
                'count' => $orders->count()
            ]);
        }
        
        // Customer not found - return empty
        return response()->json([
            'success' => true,
            'data' => [],
            'count' => 0
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|string|exists:customers,customer_id',
            'product_id' => 'required|integer|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric',
            'payment_method' => 'required|in:cash,card,gcash,paypal',
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
            'order_date' => 'nullable|date',
        ]);

        $order = Order::create($validated);
        return response()->json($order, 201);
    }

    public function show(string $orderId)
    {
        $order = Order::with('orderItems')->where('order_id', $orderId)->firstOrFail();
        return response()->json($order);
    }

    public function update(Request $request, string $orderId)
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();

        $validated = $request->validate([
            'customer_id' => 'sometimes|required|string|exists:customers,customer_id',
            'product_id' => 'sometimes|required|integer|exists:products,product_id',
            'quantity' => 'sometimes|required|integer|min:1',
            'total_price' => 'sometimes|required|numeric',
            'payment_method' => 'sometimes|required|in:cash,card,gcash,paypal',
            'status' => 'sometimes|required|in:pending,processing,shipped,completed,cancelled',
            'order_date' => 'nullable|date',
        ]);

        $order->update($validated);
        return response()->json($order);
    }

    public function destroy(string $orderId)
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();
        $order->delete();
        return response()->json(null, 204);
    }

    public function cancel(string $orderId)
    {
        // Check if user is authenticated
        $user = Auth::guard('sanctum')->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $order = Order::where('order_id', $orderId)->firstOrFail();

        // Verify the order belongs to the user (unless admin)
        $isAdmin = isset($user->admin_id) || isset($user->role);
        
        if (!$isAdmin) {
            $customer = Customer::whereRaw('LOWER(email) = ?', [strtolower($user->email)])->first();
            
            if (!$customer || $order->customer_id !== $customer->customer_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to cancel this order'
                ], 403);
            }
        }

        // Only allow canceling pending orders
        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending orders can be cancelled'
            ], 400);
        }

        // Check if order is older than 24 hours
        $orderCreatedAt = $order->created_at;
        $twentyFourHoursAgo = now()->subHours(24);
        
        if ($orderCreatedAt->lt($twentyFourHoursAgo)) {
            return response()->json([
                'success' => false,
                'message' => 'Orders can only be cancelled within 24 hours of placement'
            ], 400);
        }

        // Update order status to cancelled
        $order->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully',
            'data' => $order
        ]);
    }
}


