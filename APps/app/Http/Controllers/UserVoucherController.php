<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\UserVoucher;
use App\Models\VoucherUsage;
use Carbon\Carbon;

class UserVoucherController extends Controller
{
    /**
     * Display all available and claimed vouchers for the user
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get user's claimed vouchers with their details
        $claimedVouchers = UserVoucher::where('user_id', $user->id)
            ->with('voucher')
            ->orderBy('created_at', 'desc')
            ->get();

        // Check each voucher's validity
        $voucherDetails = $claimedVouchers->map(function($userVoucher) use ($user) {
            $voucher = $userVoucher->voucher;
            $isExpired = Carbon::parse($voucher->end_date)->isPast();
            $notStarted = Carbon::parse($voucher->start_date)->isFuture();
            $isActive = !$isExpired && !$notStarted && $voucher->status === 'active';
            
            // Check if voucher has been used
            $isUsed = VoucherUsage::where('voucher_id', $voucher->voucher_id)
                ->where('user_id', $user->id)
                ->exists();
            
            // Days until expiration
            $daysUntilExpiry = Carbon::parse($voucher->end_date)->diffInDays(now(), false);
            $daysUntilStart = Carbon::parse($voucher->start_date)->diffInDays(now(), false);
            
            return [
                'user_voucher_id' => $userVoucher->user_voucher_id,
                'voucher_id' => $voucher->voucher_id,
                'code' => $voucher->code,
                'description' => $voucher->description,
                'discount_type' => $voucher->discount_type,
                'discount_value' => $voucher->discount_value,
                'start_date' => $voucher->start_date,
                'end_date' => $voucher->end_date,
                'status' => $voucher->status,
                'is_active' => $isActive,
                'is_expired' => $isExpired,
                'is_used' => $isUsed,
                'not_started' => $notStarted,
                'days_until_expiry' => $daysUntilExpiry,
                'days_until_start' => $daysUntilStart,
                'claimed_at' => $userVoucher->created_at,
            ];
        });

        return view('vouchers.index', compact('voucherDetails'));
    }

    /**
     * Claim a voucher by code (for available vouchers)
     */
    public function claim(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
        ]);

        $user = $request->user();
        $code = strtoupper(trim($request->voucher_code));

        // Find voucher by code
        $voucher = Voucher::where('code', $code)
            ->where('status', 'active')
            ->first();

        if (!$voucher) {
            return redirect()->route('vouchers.index')
                ->with('error', '❌ Voucher code not found or inactive.');
        }

        // Check if user already claimed this voucher
        $alreadyClaimed = UserVoucher::where('user_id', $user->id)
            ->where('voucher_id', $voucher->voucher_id)
            ->exists();

        if ($alreadyClaimed) {
            return redirect()->route('vouchers.index')
                ->with('error', '⚠️ You have already claimed this voucher.');
        }

        // Check if voucher is within valid date range
        $now = now();
        if ($now->lt(Carbon::parse($voucher->start_date))) {
            return redirect()->route('vouchers.index')
                ->with('error', '⏳ This voucher is not yet active. It starts on ' . $voucher->start_date->format('M d, Y'));
        }

        if ($now->gt(Carbon::parse($voucher->end_date))) {
            return redirect()->route('vouchers.index')
                ->with('error', '⏰ This voucher has expired.');
        }

        // Check usage limit
        if ($voucher->usage_limit && $voucher->usage_count >= $voucher->usage_limit) {
            return redirect()->route('vouchers.index')
                ->with('error', '📉 This voucher has reached its usage limit.');
        }

        // Claim the voucher
        UserVoucher::create([
            'user_id' => $user->id,
            'voucher_id' => $voucher->voucher_id,
            'status' => 'available',
        ]);

        return redirect()->route('vouchers.index')
            ->with('success', '🎉 Voucher claimed successfully! Discount: ' . 
                ($voucher->discount_type === 'percentage' ? $voucher->discount_value . '%' : '$' . $voucher->discount_value));
    }

    /**
     * Get user's available vouchers as JSON (for checkout)
     */
    public function getAvailable(Request $request)
    {
        $user = $request->user();
        $now = now();

        // Get only active, unexpired vouchers that user has claimed and haven't used
        $availableVouchers = UserVoucher::where('user_id', $user->id)
            ->with('voucher')
            ->get()
            ->filter(function($userVoucher) use ($now, $user) {
                $voucher = $userVoucher->voucher;
                
                // Check validity
                if ($voucher->status !== 'active') return false;
                if ($now->lt(Carbon::parse($voucher->start_date))) return false;
                if ($now->gt(Carbon::parse($voucher->end_date))) return false;
                
                // Check if already used
                $isUsed = VoucherUsage::where('voucher_id', $voucher->voucher_id)
                    ->where('user_id', $user->id)
                    ->exists();
                
                return !$isUsed;
            })
            ->map(function($userVoucher) {
                $v = $userVoucher->voucher;
                return [
                    'voucher_id' => $v->voucher_id,
                    'code' => $v->code,
                    'description' => $v->description,
                    'discount_type' => $v->discount_type,
                    'discount_value' => (float) $v->discount_value,
                    'start_date' => $v->start_date->format('Y-m-d'),
                    'end_date' => $v->end_date->format('Y-m-d'),
                ];
            })
            ->values();

        return response()->json($availableVouchers);
    }

    /**
     * Validate a voucher before applying
     */
    public function validate(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required|integer|exists:vouchers,voucher_id',
        ]);

        $user = $request->user();
        $voucherId = $request->voucher_id;
        $now = now();

        // Get voucher
        $voucher = Voucher::find($voucherId);

        if (!$voucher || $voucher->status !== 'active') {
            return response()->json([
                'valid' => false,
                'message' => 'Voucher is inactive.',
            ], 400);
        }

        // Check if user claimed it
        $claimed = UserVoucher::where('user_id', $user->id)
            ->where('voucher_id', $voucherId)
            ->exists();

        if (!$claimed) {
            return response()->json([
                'valid' => false,
                'message' => 'You have not claimed this voucher.',
            ], 400);
        }

        // Check dates
        if ($now->lt(Carbon::parse($voucher->start_date))) {
            return response()->json([
                'valid' => false,
                'message' => 'Voucher not yet active.',
            ], 400);
        }

        if ($now->gt(Carbon::parse($voucher->end_date))) {
            return response()->json([
                'valid' => false,
                'message' => 'Voucher has expired.',
            ], 400);
        }

        // Check if already used
        $isUsed = VoucherUsage::where('voucher_id', $voucherId)
            ->where('user_id', $user->id)
            ->exists();

        if ($isUsed) {
            return response()->json([
                'valid' => false,
                'message' => 'Voucher has already been used.',
            ], 400);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Voucher is valid.',
            'voucher' => [
                'voucher_id' => $voucher->voucher_id,
                'code' => $voucher->code,
                'discount_type' => $voucher->discount_type,
                'discount_value' => $voucher->discount_value,
            ],
        ]);
    }

    /**
     * Revoke/remove a voucher (mark as used if needed)
     */
    public function revoke(Request $request, $userVoucherId)
    {
        $user = $request->user();

        $userVoucher = UserVoucher::where('user_voucher_id', $userVoucherId)
            ->where('user_id', $user->id)
            ->first();

        if (!$userVoucher) {
            return redirect()->route('vouchers.index')
                ->with('error', 'Voucher not found.');
        }

        // Delete the claim
        $userVoucher->delete();

        return redirect()->route('vouchers.index')
            ->with('success', '✓ Voucher removed from your collection.');
    }
}


