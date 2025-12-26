<?php

namespace App\Http\Controllers;

use App\Models\ReturnRequest;
use App\Models\ReturnRequestItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class UserReturnRequestController extends Controller
{
    /**
     * Display user's return requests with items
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get customer associated with user (using email)
        $customer = Customer::where('email', $user->email)->first();
        
        if (!$customer) {
            return abort(403, 'Customer profile not found');
        }

        // Filter return requests by status if provided
        $status = $request->query('status');
        
        $query = ReturnRequest::where('customer_id', $customer->customer_id)
            ->with(['order', 'product', 'items.orderItem.product'])
            ->orderByDesc('created_at');

        if ($status && in_array($status, ['pending', 'approved', 'rejected', 'refunded'])) {
            $query->where('status', $status);
        }

        $returnRequests = $query->paginate(10);

        // Calculate statistics
        $stats = [
            'total' => ReturnRequest::where('customer_id', $customer->customer_id)->count(),
            'pending' => ReturnRequest::where('customer_id', $customer->customer_id)->where('status', 'pending')->count(),
            'approved' => ReturnRequest::where('customer_id', $customer->customer_id)->where('status', 'approved')->count(),
            'refunded' => ReturnRequest::where('customer_id', $customer->customer_id)->where('status', 'refunded')->count(),
        ];

        return view('returns.index', compact('returnRequests', 'stats', 'status'));
    }

    /**
     * Show return request creation form
     */
    public function create()
    {
        $user = Auth::user();
        $customer = Customer::where('email', $user->email)->first();

        if (!$customer) {
            return abort(403, 'Customer profile not found');
        }

        // Get user's completed orders with items for returning
        $orders = Order::where('customer_id', $customer->customer_id)
            ->with('orderItems.product')
            ->where('status', 'completed')
            ->orderByDesc('order_date')
            ->get();

        return view('returns.create', compact('orders'));
    }

    /**
     * Store a new return request
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $customer = Customer::where('email', $user->email)->first();

        if (!$customer) {
            return abort(403, 'Customer profile not found');
        }

        // Validate order_id
        $request->validate([
            'order_id' => 'required|exists:orders,order_id',
        ]);

        $order_id = $request->input('order_id');

        // Verify order belongs to user
        $order = Order::findOrFail($order_id);
        if ($order->customer_id !== $customer->customer_id) {
            return abort(403, 'Unauthorized');
        }

        // Get all items from request, filter only those with data
        $items_input = $request->input('items', []);
        $items = [];
        
        // Get the checked items from the form data
        foreach ($items_input as $index => $item) {
            // Skip items that don't have both order_item_id and quantity
            // (quantity will only be present if the item was checked and inputs were shown)
            if (!isset($item['order_item_id']) || !isset($item['quantity']) || $item['quantity'] === '') {
                continue;
            }

            $items[] = [
                'order_item_id' => (int) $item['order_item_id'],
                'product_id' => (int) $item['product_id'],
                'quantity' => (int) ($item['quantity'] ?? 1),
                'reason' => isset($item['reason']) ? trim($item['reason']) : null,
                'notes' => isset($item['notes']) ? trim($item['notes']) : null,
            ];
        }

        // Validate we have at least one item
        if (empty($items)) {
            return back()->withErrors(['error' => 'Please select at least one item to return']);
        }

        // Validate each item
        $validated_items = [];
        foreach ($items as $item) {
            // Check that all required fields are present and not empty
            if (empty($item['order_item_id']) || empty($item['product_id']) || empty($item['quantity'])) {
                return back()->withErrors(['error' => 'All selected items must have quantity specified']);
            }

            // Validate reason is provided
            if (!isset($item['reason']) || is_null($item['reason']) || trim($item['reason']) === '') {
                return back()->withErrors(['error' => 'All selected items must have a reason specified']);
            }

            // Validate quantity and product exist
            $order_item = OrderItem::where('order_item_id', $item['order_item_id'])->first();
            if (!$order_item) {
                return back()->withErrors(['error' => 'Invalid order item']);
            }

            if ($item['quantity'] > $order_item->quantity) {
                return back()->withErrors(['error' => "Return quantity for " . $order_item->product->name . " cannot exceed ordered quantity"]);
            }

            $validated_items[] = $item;
        }

        try {
            DB::beginTransaction();

            // Create return request
            $returnRequest = ReturnRequest::create([
                'order_id' => $order_id,
                'customer_id' => $customer->customer_id,
                'product_id' => $validated_items[0]['product_id'],
                'reason' => 'Return request submitted by customer',
                'status' => 'pending',
            ]);

            // Create return request items
            foreach ($validated_items as $item) {
                $orderItem = OrderItem::findOrFail($item['order_item_id']);
                $refundAmount = $orderItem->unit_price * $item['quantity'];

                ReturnRequestItem::create([
                    'request_id' => $returnRequest->request_id,
                    'order_item_id' => $item['order_item_id'],
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'reason' => $item['reason'],
                    'refund_amount' => $refundAmount,
                    'status' => 'pending',
                    'notes' => $item['notes'],
                ]);
            }

            DB::commit();

            // Get admin emails and send notification
            $totalRefund = $returnRequest->items->sum('refund_amount');
            $admins = Admin::all();
            
            if ($admins->count() > 0) {
                $adminEmails = $admins->pluck('email')->toArray();
                try {
                    Mail::send('emails.return-request-notification', [
                        'returnRequest' => $returnRequest,
                        'customer' => $customer,
                        'totalRefund' => $totalRefund,
                        'itemsCount' => count($validated_items),
                    ], function ($message) use ($adminEmails) {
                        $message->to($adminEmails)
                            ->subject('🔄 New Return Request from Customer - Action Required');
                    });
                } catch (\Exception $e) {
                    Log::error('Failed to send admin notification email: ' . $e->getMessage());
                    // Don't fail the request if email fails
                }
            }

            return redirect()->route('returns.index')
                ->with('success', '✅ Return request submitted successfully! Admins have been notified. We\'ll review it within 24-48 hours.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting return request: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error submitting request: ' . $e->getMessage()]);
        }
    }

    /**
     * Show detailed return request
     */
    public function show($requestId)
    {
        $user = Auth::user();
        $customer = Customer::where('email', $user->email)->first();

        if (!$customer) {
            return abort(403, 'Customer profile not found');
        }

        $returnRequest = ReturnRequest::with([
            'order',
            'product',
            'items.orderItem.product',
            'admin',
        ])->findOrFail($requestId);

        // Verify ownership
        if ($returnRequest->customer_id !== $customer->customer_id) {
            return abort(403, 'Unauthorized');
        }

        // Calculate total refund
        $totalRefund = $returnRequest->items->sum('refund_amount');

        return view('returns.show', compact('returnRequest', 'totalRefund'));
    }

    /**
     * Cancel a pending return request
     */
    public function cancel(Request $request, $requestId)
    {
        $user = Auth::user();
        $customer = Customer::where('email', $user->email)->first();

        if (!$customer) {
            return abort(403, 'Customer profile not found');
        }

        $returnRequest = ReturnRequest::findOrFail($requestId);

        // Verify ownership
        if ($returnRequest->customer_id !== $customer->customer_id) {
            return abort(403, 'Unauthorized');
        }

        // Only allow cancellation of pending requests
        if ($returnRequest->status !== 'pending') {
            return back()->withErrors(['error' => 'Can only cancel pending return requests']);
        }

        // Delete associated items and request
        ReturnRequestItem::where('request_id', $returnRequest->request_id)->delete();
        $returnRequest->delete();

        return redirect()->route('returns.index')
            ->with('success', '✅ Return request cancelled successfully');
    }

    /**
     * Get return request statistics for dashboard
     */
    public function stats()
    {
        $user = Auth::user();
        $customer = Customer::where('email', $user->email)->first();

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 403);
        }

        $stats = [
            'total_returns' => ReturnRequest::where('customer_id', $customer->customer_id)->count(),
            'pending' => ReturnRequest::where('customer_id', $customer->customer_id)->where('status', 'pending')->count(),
            'approved' => ReturnRequest::where('customer_id', $customer->customer_id)->where('status', 'approved')->count(),
            'refunded' => ReturnRequest::where('customer_id', $customer->customer_id)->where('status', 'refunded')->count(),
            'total_refund_amount' => ReturnRequestItem::whereIn('request_id', 
                ReturnRequest::where('customer_id', $customer->customer_id)
                    ->where('status', 'refunded')
                    ->pluck('request_id')
            )->sum('refund_amount'),
        ];

        return response()->json($stats);
    }
}
