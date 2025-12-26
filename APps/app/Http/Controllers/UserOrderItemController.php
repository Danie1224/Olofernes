<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOrderItemController extends Controller
{
    /**
     * Display all orders with items for the authenticated customer
     */
    public function index(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // Get customer record linked to this user (by email)
        $customer = Customer::where('email', $user->email)->first();
        
        if (!$customer) {
            return view('orders.user-orders', ['orders' => collect()]);
        }

        // Fetch all orders for this customer
        $orders = Order::where('customer_id', $customer->customer_id)
            ->with(['orderItems.product', 'customer'])
            ->latest('order_date')
            ->paginate(10);

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $orders = Order::where('customer_id', $customer->customer_id)
                ->where('status', $request->status)
                ->with(['orderItems.product', 'customer'])
                ->latest('order_date')
                ->paginate(10);
        }

        $statuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];

        return view('orders.user-orders', compact('orders', 'statuses'));
    }

    /**
     * Display items for a specific order
     */
    public function show(string $orderId)
    {
        $user = Auth::user();
        $customer = Customer::where('email', $user->email)->first();

        if (!$customer) {
            abort(404, 'Customer not found');
        }

        // Get order, ensuring it belongs to the authenticated customer
        $order = Order::where('order_id', $orderId)
            ->where('customer_id', $customer->customer_id)
            ->with(['orderItems.product', 'customer'])
            ->firstOrFail();

        $orderItems = OrderItem::where('order_id', $orderId)
            ->with('product')
            ->get();

        return view('orders.order-details', compact('order', 'orderItems'));
    }

    /**
     * Get order statistics for the authenticated user
     */
    public function stats()
    {
        $user = Auth::user();
        $customer = Customer::where('email', $user->email)->first();

        if (!$customer) {
            return response()->json([
                'total_orders' => 0,
                'total_spent' => 0,
                'completed_orders' => 0,
                'pending_orders' => 0,
            ]);
        }

        $totalOrders = Order::where('customer_id', $customer->customer_id)->count();
        $totalSpent = Order::where('customer_id', $customer->customer_id)->sum('total_price');
        $completedOrders = Order::where('customer_id', $customer->customer_id)
            ->where('status', 'completed')
            ->count();
        $pendingOrders = Order::where('customer_id', $customer->customer_id)
            ->where('status', 'pending')
            ->count();

        return response()->json([
            'total_orders' => $totalOrders,
            'total_spent' => $totalSpent,
            'completed_orders' => $completedOrders,
            'pending_orders' => $pendingOrders,
        ]);
    }
}
