<?php

namespace App\Http\Controllers;

use App\Models\ReturnRequest;
use App\Models\ReturnRequestItem;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AdminReturnRequestController extends Controller
{
    /**
     * Display all return requests for admin review
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        
        $query = ReturnRequest::with([
            'customer',
            'order',
            'product',
            'items.orderItem.product',
            'admin'
        ])->orderByDesc('created_at');

        if ($status && in_array($status, ['pending', 'approved', 'rejected', 'refunded'])) {
            $query->where('status', $status);
        }

        $returns = $query->paginate(15);

        // Calculate statistics
        $stats = [
            'total' => ReturnRequest::count(),
            'pending' => ReturnRequest::where('status', 'pending')->count(),
            'approved' => ReturnRequest::where('status', 'approved')->count(),
            'rejected' => ReturnRequest::where('status', 'rejected')->count(),
            'refunded' => ReturnRequest::where('status', 'refunded')->count(),
            'total_pending_refund' => ReturnRequestItem::whereIn('request_id',
                ReturnRequest::where('status', 'approved')->pluck('request_id')
            )->sum('refund_amount'),
        ];

        return view('admin.returns.index', compact('returns', 'stats', 'status'));
    }

    /**
     * Show return request details for admin review
     */
    public function show($requestId)
    {
        $returnRequest = ReturnRequest::with([
            'customer',
            'order',
            'product',
            'items.orderItem.product',
            'admin'
        ])->findOrFail($requestId);

        $totalRefund = $returnRequest->items->sum('refund_amount');

        return view('admin.returns.show', compact('returnRequest', 'totalRefund'));
    }

    /**
     * Approve a return request
     */
    public function approve(Request $request, $requestId)
    {
        $returnRequest = ReturnRequest::findOrFail($requestId);

        if ($returnRequest->status !== 'pending') {
            return back()->withErrors(['error' => 'Can only approve pending requests']);
        }

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $admin = Auth::guard('admin')->user();
        
        $returnRequest->update([
            'status' => 'approved',
            'processed_by' => $admin->id ?? null,
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        return redirect()->route('admin.returns.show', $requestId)
            ->with('success', '✅ Return request approved successfully!');
    }

    /**
     * Reject a return request
     */
    public function reject(Request $request, $requestId)
    {
        $returnRequest = ReturnRequest::findOrFail($requestId);

        if ($returnRequest->status !== 'pending') {
            return back()->withErrors(['error' => 'Can only reject pending requests']);
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $admin = Auth::guard('admin')->user();

        $returnRequest->update([
            'status' => 'rejected',
            'processed_by' => $admin->id ?? null,
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        return redirect()->route('admin.returns.show', $requestId)
            ->with('success', '✅ Return request rejected!');
    }

    /**
     * Mark as refunded
     */
    public function refund(Request $request, $requestId)
    {
        $returnRequest = ReturnRequest::findOrFail($requestId);

        if ($returnRequest->status !== 'approved') {
            return back()->withErrors(['error' => 'Can only refund approved requests']);
        }

        $validated = $request->validate([
            'refund_notes' => 'nullable|string|max:500',
        ]);

        $returnRequest->update([
            'status' => 'refunded',
            'admin_notes' => $validated['refund_notes'] ?? null,
        ]);

        return redirect()->route('admin.returns.show', $requestId)
            ->with('success', '✅ Refund processed successfully!');
    }

    /**
     * Get statistics for dashboard
     */
    public function stats()
    {
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

        return response()->json($stats);
    }
}
