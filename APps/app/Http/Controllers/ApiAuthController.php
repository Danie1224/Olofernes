<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\Voucher;
use App\Models\UserVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ApiAuthController extends Controller
{
    private function ensureDefaultVoucherForUser(int $userId): void
    {
        if (UserVoucher::where('user_id', $userId)->exists()) {
            return;
        }

        $percents = [10, 20, 30, 50, 70];
        $percent = $percents[array_rand($percents)];
        $code = 'UV-'.$userId.'-'.Str::upper(Str::random(6));

        $voucher = Voucher::create([
            'code' => $code,
            'description' => 'Personal '.$percent.'% off voucher',
            'discount_type' => 'percentage',
            'discount_value' => $percent,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addYears(10)->toDateString(),
            'status' => 'active',
        ]);

        UserVoucher::create([
            'user_id' => $userId,
            'voucher_id' => $voucher->voucher_id,
            'status' => 'available',
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->only(['name', 'email', 'password', 'password_confirmation', 'phone']);

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Create customer profile
        $customer = Customer::create([
            'customer_id' => (string) Str::uuid(),
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $data['phone'] ?? '',
            'address' => '',
            'city' => '',
            'state' => '',
            'zip_code' => '',
            'country' => 'USA',
            'status' => 'active',
        ]);

        // Ensure a voucher is assigned
        $this->ensureDefaultVoucherForUser($user->id);

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->only(['email', 'password']);

        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('api')->plainTextToken;

        // Ensure customer profile exists (for users created before customer auto-creation was added)
        $customer = Customer::where('email', $user->email)->first();
        if (!$customer) {
            Customer::create([
                'customer_id' => (string) Str::uuid(),
                'name' => $user->name,
                'email' => $user->email,
                'phone' => '',
                'address' => '',
                'city' => '',
                'state' => '',
                'zip_code' => '',
                'country' => 'USA',
                'status' => 'active',
            ]);
        }

        // Ensure default voucher exists
        $this->ensureDefaultVoucherForUser($user->id);

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function adminLogin(Request $request)
    {
        $data = $request->only(['email', 'password']);

        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $admin = Admin::where('email', $data['email'])->first();

        if (!$admin || !Hash::check($data['password'], $admin->password)) {
            return response()->json(['message' => 'Invalid admin credentials'], 401);
        }

        $token = $admin->createToken('admin-api')->plainTextToken;

        return response()->json([
            'admin' => $admin,
            'token' => $token,
            'role' => $admin->role
        ], 200);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            // Revoke current access token
            $token = $request->bearerToken();
            if ($token && $request->user()->currentAccessToken()) {
                $request->user()->currentAccessToken()->delete();
            } else {
                // Revoke all tokens
                $request->user()->tokens()->delete();
            }
        }

        return response()->json(['message' => 'Logged out'], 200);
    }

    public function registerAdmin(Request $request)
    {
        $data = $request->only(['name', 'email', 'password', 'password_confirmation', 'role']);

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|string|in:admin,super_admin'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $admin = Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        $token = $admin->createToken('admin-api')->plainTextToken;

        return response()->json([
            'admin' => $admin,
            'token' => $token,
        ], 201);
    }
}
