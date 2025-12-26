<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\UserVoucher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminVoucherController extends Controller
{
    /**
     * Display a listing of all vouchers
     */
    public function index()
    {
        $vouchers = Voucher::withCount('userVouchers')
            ->withCount('voucherUsages')
            ->latest('created_at')
            ->paginate(15);

        return view('admin.vouchers.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new voucher
     */
    public function create()
    {
        return view('admin.vouchers.create');
    }

    /**
     * Store a newly created voucher in database and distribute to all users
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:vouchers,code|max:50',
            'description' => 'nullable|string|max:500',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            DB::beginTransaction();

            // Create the voucher
            $voucher = Voucher::create($validated);

            // Get all users (customers)
            $users = User::all();

            // Distribute voucher to all users
            $userVoucherData = $users->map(function ($user) use ($voucher) {
                return [
                    'user_id' => $user->id,
                    'voucher_id' => $voucher->voucher_id,
                    'status' => 'available',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            // Batch insert for efficiency
            if (count($userVoucherData) > 0) {
                UserVoucher::insert($userVoucherData);
            }

            DB::commit();

            return redirect()->route('admin.vouchers.index')
                ->with('success', "Voucher '{$voucher->code}' created successfully and distributed to " . count($users) . ' users!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create voucher: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified voucher with distribution details
     */
    public function show(string $voucherId)
    {
        $voucher = Voucher::where('voucher_id', $voucherId)
            ->with(['userVouchers' => function ($query) {
                $query->with('user')->paginate(20);
            }])
            ->withCount('userVouchers')
            ->withCount('voucherUsages')
            ->firstOrFail();

        $distributedUsers = $voucher->userVouchers()
            ->with('user')
            ->paginate(20);

        return view('admin.vouchers.show', compact('voucher', 'distributedUsers'));
    }

    /**
     * Show the form for editing the specified voucher
     */
    public function edit(string $voucherId)
    {
        $voucher = Voucher::where('voucher_id', $voucherId)->firstOrFail();
        return view('admin.vouchers.edit', compact('voucher'));
    }

    /**
     * Update the specified voucher
     */
    public function update(Request $request, string $voucherId)
    {
        $voucher = Voucher::where('voucher_id', $voucherId)->firstOrFail();

        $validated = $request->validate([
            'code' => 'sometimes|required|string|unique:vouchers,code,' . $voucher->voucher_id . ',voucher_id|max:50',
            'description' => 'nullable|string|max:500',
            'discount_type' => 'sometimes|required|in:percentage,fixed',
            'discount_value' => 'sometimes|required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
            'status' => 'sometimes|required|in:active,inactive',
        ]);

        $voucher->update($validated);

        return redirect()->route('admin.vouchers.index')
            ->with('success', "Voucher '{$voucher->code}' updated successfully!");
    }

    /**
     * Remove the specified voucher (soft delete)
     */
    public function destroy(string $voucherId)
    {
        $voucher = Voucher::where('voucher_id', $voucherId)->firstOrFail();
        $code = $voucher->code;
        
        try {
            DB::beginTransaction();
            
            // Delete associated user vouchers
            UserVoucher::where('voucher_id', $voucher->voucher_id)->delete();
            
            // Delete the voucher
            $voucher->delete();
            
            DB::commit();
            
            return redirect()->route('admin.vouchers.index')
                ->with('success', "Voucher '{$code}' and all its distributions have been deleted!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete voucher: ' . $e->getMessage()]);
        }
    }

    /**
     * Distribute an existing voucher to new users (who didn't have it)
     */
    public function redistribute(Request $request, string $voucherId)
    {
        $voucher = Voucher::where('voucher_id', $voucherId)->firstOrFail();

        try {
            DB::beginTransaction();

            // Get all users
            $allUsers = User::where('role', '!=', 'admin')->pluck('id')->toArray();

            // Get users who already have this voucher
            $usersWithVoucher = UserVoucher::where('voucher_id', $voucher->voucher_id)
                ->pluck('user_id')
                ->toArray();

            // Find users who don't have the voucher yet
            $newUsers = array_diff($allUsers, $usersWithVoucher);

            // Distribute to new users
            $newUserVoucherData = array_map(function ($userId) use ($voucher) {
                return [
                    'user_id' => $userId,
                    'voucher_id' => $voucher->voucher_id,
                    'status' => 'available',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $newUsers);

            if (count($newUserVoucherData) > 0) {
                UserVoucher::insert($newUserVoucherData);
            }

            DB::commit();

            $message = count($newUsers) > 0 
                ? "Voucher redistributed to " . count($newUsers) . ' new users!'
                : 'All users already have this voucher!';

            return redirect()->route('admin.vouchers.show', $voucher->voucher_id)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to redistribute voucher: ' . $e->getMessage()]);
        }
    }

    /**
     * Get distribution statistics for a voucher
     */
    public function stats(string $voucherId)
    {
        $voucher = Voucher::where('voucher_id', $voucherId)->firstOrFail();

        $totalDistributed = $voucher->userVouchers()->count();
        $usageCount = $voucher->voucherUsages()->count();
        $availableCount = $voucher->userVouchers()->where('status', 'available')->count();
        $usedCount = $voucher->userVouchers()->where('status', 'used')->count();

        return response()->json([
            'voucher_id' => $voucher->voucher_id,
            'code' => $voucher->code,
            'total_distributed' => $totalDistributed,
            'usage_count' => $usageCount,
            'available' => $availableCount,
            'used' => $usedCount,
            'percentage_used' => $totalDistributed > 0 ? round(($usedCount / $totalDistributed) * 100, 2) : 0,
            'usage_limit' => $voucher->usage_limit,
            'remaining_uses' => $voucher->usage_limit ? $voucher->usage_limit - $usageCount : 'Unlimited',
        ]);
    }
}
