<?php

namespace App\Http\Controllers;

use App\Models\ReturnRequest;
use App\Models\ReturnRequestItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ReturnRequestController extends Controller
{
    /**
     * Get all return requests (API - public read)
     */
    public function index()
    {
        $returnRequests = ReturnRequest::with([
            'customer',
            'order',
            'items.orderItem.product'
        ])->latest()->paginate(15);

        // Calculate refund_amount for each return request from items if not set
        $returnRequests->getCollection()->transform(function ($returnRequest) {
            if (!$returnRequest->refund_amount || $returnRequest->refund_amount == 0) {
                $returnRequest->refund_amount = $returnRequest->items->sum('refund_amount');
            }
            return $returnRequest;
        });

        return response()->json([
            'success' => true,
            'data' => $returnRequests
        ]);
    }

    /**
     * Get a specific return request (API - public read)
     */
    public function show($requestId)
    {
        $returnRequest = ReturnRequest::with([
            'customer',
            'order',
            'items.orderItem.product'
        ])->findOrFail($requestId);

        $totalRefund = $returnRequest->items->sum('refund_amount');

        return response()->json([
            'success' => true,
            'data' => $returnRequest,
            'total_refund' => $totalRefund
        ]);
    }

    /**
     * Create a new return request (API - authenticated)
     */
    public function store(Request $request)
    {
        // Get authenticated user
        $user = Auth::guard('sanctum')->user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Authentication required'], 401);
        }

        // Get customer associated with user
        $customer = Customer::where('email', $user->email)->first();
        
        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Customer profile not found'], 403);
        }

        try {
            $validated = $request->validate([
                'order_id' => 'required|exists:orders,order_id',
                'items' => 'required|array|min:1',
                'items.*.order_item_id' => 'required|exists:order_items,order_item_id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.reason' => 'required|in:Damaged During Shipment,Defective Product,Wrong Item Received,Not as Described,Other',
                'items.*.notes' => 'nullable|string|max:500',
            ]);

            $order_id = $validated['order_id'];

            // Verify order belongs to customer
            $order = Order::where('order_id', $order_id)
                         ->where('customer_id', $customer->customer_id)
                         ->firstOrFail();

            // Process and validate items
            $validated_items = [];
            foreach ($validated['items'] as $item) {
                $order_item = OrderItem::where('order_item_id', $item['order_item_id'])->firstOrFail();
                
                if ($item['quantity'] > $order_item->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Return quantity for {$order_item->product->name} exceeds ordered quantity"
                    ], 422);
                }

                $validated_items[] = $item;
            }

            DB::beginTransaction();

            // Create return request
            // Collect all reasons from items for the main return request
            $reasons = array_column($validated_items, 'reason');
            $mainReason = implode(', ', array_unique($reasons));
            
            // Get the first order item's product_id (required field)
            $firstOrderItem = OrderItem::findOrFail($validated_items[0]['order_item_id']);

            $returnRequest = ReturnRequest::create([
                'order_id' => $order_id,
                'customer_id' => $customer->customer_id,
                'product_id' => $firstOrderItem->product_id,
                'reason' => $mainReason, // Required field in database
                'status' => 'pending',
            ]);

            // Create return items
            $totalRefund = 0;
            foreach ($validated_items as $item) {
                $order_item = OrderItem::findOrFail($item['order_item_id']);
                $refund_amount = $order_item->unit_price * $item['quantity'];
                $totalRefund += $refund_amount;

                ReturnRequestItem::create([
                    'request_id' => $returnRequest->request_id,
                    'order_item_id' => $item['order_item_id'],
                    'product_id' => $order_item->product_id,
                    'quantity' => $item['quantity'],
                    'reason' => $item['reason'],
                    'refund_amount' => $refund_amount,
                    'status' => 'pending',
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            // Update the return request with total refund amount
            $returnRequest->update([
                'refund_amount' => $totalRefund
            ]);

            DB::commit();

            // Send admin notification email
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
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Return request created successfully',
                'data' => $returnRequest->load(['customer', 'order', 'items.orderItem.product']),
                'total_refund' => $totalRefund
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating return request: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating return request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a return request (API - admin)
     */
    public function approve(Request $request, $requestId)
    {
        try {
            $validated = $request->validate([
                'admin_notes' => 'nullable|string|max:500',
            ]);

            $returnRequest = ReturnRequest::findOrFail($requestId);

            if ($returnRequest->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending requests can be approved'
                ], 400);
            }

            $admin = Auth::guard('sanctum')->user();
            
            $returnRequest->update([
                'status' => 'approved',
                'processed_by' => $admin->id ?? null,
                'admin_notes' => $validated['admin_notes'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Return request approved',
                'data' => $returnRequest
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error approving request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a return request (API - admin)
     */
    public function reject(Request $request, $requestId)
    {
        try {
            $validated = $request->validate([
                'admin_notes' => 'required|string|min:10|max:500',
            ]);

            $returnRequest = ReturnRequest::findOrFail($requestId);

            if ($returnRequest->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending requests can be rejected'
                ], 400);
            }

            $admin = Auth::guard('sanctum')->user();

            $returnRequest->update([
                'status' => 'rejected',
                'processed_by' => $admin->id ?? null,
                'admin_notes' => $validated['admin_notes'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Return request rejected',
                'data' => $returnRequest
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark return request as refunded (API - admin)
     */
    public function refund(Request $request, $requestId)
    {
        try {
            $returnRequest = ReturnRequest::findOrFail($requestId);

            if ($returnRequest->status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only approved requests can be refunded'
                ], 400);
            }

            $returnRequest->update(['status' => 'refunded']);

            return response()->json([
                'success' => true,
                'message' => 'Return request marked as refunded',
                'data' => $returnRequest
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error refunding request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel a return request (API - customer)
     */
    public function cancel(Request $request, $requestId)
    {
        try {
            $returnRequest = ReturnRequest::findOrFail($requestId);

            if ($returnRequest->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending requests can be cancelled'
                ], 400);
            }

            $returnRequest->delete();

            return response()->json([
                'success' => true,
                'message' => 'Return request cancelled successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error cancelling request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get return request statistics (API)
     */
    public function stats()
    {
        try {
            $stats = [
                'total_returns' => ReturnRequest::count(),
                'pending' => ReturnRequest::where('status', 'pending')->count(),
                'approved' => ReturnRequest::where('status', 'approved')->count(),
                'rejected' => ReturnRequest::where('status', 'rejected')->count(),
                'refunded' => ReturnRequest::where('status', 'refunded')->count(),
                'total_refund_pending' => ReturnRequestItem::whereIn('request_id',
                    ReturnRequest::where('status', 'approved')->pluck('request_id')
                )->sum('refund_amount'),
                'total_refunded' => ReturnRequestItem::whereIn('request_id',
                    ReturnRequest::where('status', 'refunded')->pluck('request_id')
                )->sum('refund_amount'),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}

