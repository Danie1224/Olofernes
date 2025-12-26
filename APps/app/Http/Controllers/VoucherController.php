<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\UserVoucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        // Only show vouchers that have started and not expired
        $vouchers = Voucher::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->latest()
            ->paginate(15);
            
        return response()->json($vouchers);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:vouchers,code',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer',
            'usage_count' => 'nullable|integer',
            'status' => 'required|in:active,inactive,expired',
        ]);

        $voucher = Voucher::create($validated);
        return response()->json($voucher, 201);
    }

    public function show(string $voucherId)
    {
        $voucher = Voucher::where('voucher_id', $voucherId)->firstOrFail();
        return response()->json($voucher);
    }

    public function update(Request $request, string $voucherId)
    {
        $voucher = Voucher::where('voucher_id', $voucherId)->firstOrFail();
        $validated = $request->validate([
            'code' => 'sometimes|required|string|unique:vouchers,code,' . $voucher->voucher_id . ',voucher_id',
            'description' => 'nullable|string',
            'discount_type' => 'sometimes|required|in:percentage,fixed',
            'discount_value' => 'sometimes|required|numeric',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer',
            'usage_count' => 'nullable|integer',
            'status' => 'sometimes|required|in:active,inactive,expired',
        ]);
        $voucher->update($validated);
        return response()->json($voucher);
    }

    public function destroy(string $voucherId)
    {
        $voucher = Voucher::where('voucher_id', $voucherId)->firstOrFail();
        $voucher->delete();
        return response()->json(null, 204);
    }

    /**
     * Get user's vouchers
     */
    public function getUserVouchers(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userVouchers = UserVoucher::where('user_id', $user->id)
            ->with(['voucher' => function($query) {
                // Only include vouchers that have started and not expired
                $query->where('start_date', '<=', now())
                      ->where('end_date', '>=', now());
            }])
            ->get()
            ->filter(function($userVoucher) {
                // Remove user vouchers where the voucher is null (coming soon or expired)
                return $userVoucher->voucher !== null;
            })
            ->values();

        return response()->json($userVouchers);
    }

    /**
     * Remove user voucher
     */
    public function removeUserVoucher(Request $request, string $userVoucherId)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userVoucher = UserVoucher::where('user_voucher_id', $userVoucherId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $userVoucher->delete();
        return response()->json(['message' => 'Voucher removed successfully'], 200);
    }

    /**
     * Get users who have this voucher (for admin)
     */
    public function getVoucherUsers(string $voucherId)
    {
        $voucher = Voucher::where('voucher_id', $voucherId)->first();
        
        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        $userVouchers = UserVoucher::where('voucher_id', $voucherId)
            ->with('user:id,name,email')
            ->get();

        return response()->json([
            'data' => $userVouchers,
            'voucher' => $voucher
        ]);
    }

    /**
     * Claim a voucher by code
     */
    public function claim(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Find the voucher by code
        $voucher = Voucher::where('code', strtoupper($validated['code']))->first();
        
        if (!$voucher) {
            return response()->json(['message' => 'Voucher code not found'], 404);
        }

        // Check if voucher is active
        if ($voucher->status !== 'active') {
            return response()->json(['message' => 'This voucher is no longer active'], 400);
        }

        // Check if voucher has expired
        if ($voucher->end_date && now()->gt($voucher->end_date)) {
            return response()->json(['message' => 'This voucher has expired'], 400);
        }

        // Check if voucher hasn't started yet
        if ($voucher->start_date && now()->lt($voucher->start_date)) {
            return response()->json(['message' => 'This voucher is not yet available'], 400);
        }

        // Check if user already has this voucher
        $existingUserVoucher = UserVoucher::where('user_id', $user->id)
            ->where('voucher_id', $voucher->voucher_id)
            ->first();

        if ($existingUserVoucher) {
            return response()->json(['message' => 'You have already claimed this voucher'], 400);
        }

        // Check usage limit
        if ($voucher->usage_limit !== null && $voucher->usage_count >= $voucher->usage_limit) {
            return response()->json(['message' => 'This voucher has reached its usage limit'], 400);
        }

        // Create user voucher association
        UserVoucher::create([
            'user_id' => $user->id,
            'voucher_id' => $voucher->voucher_id,
            'status' => 'available',
        ]);

        return response()->json([
            'message' => 'Voucher claimed successfully!',
            'voucher' => $voucher
        ], 200);
    }
}


