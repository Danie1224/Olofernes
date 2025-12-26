<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Voucher;
use App\Models\UserVoucher;

class AuthController extends Controller
{
    private function ensureDefaultVoucherForUser(int $userId): void
    {
        // If user already has a voucher, do nothing
        if (UserVoucher::where('user_id', $userId)->exists()) {
            return;
        }

        // Create a unique voucher for this user with random percent
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
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find user in database
        $user = User::where('email', $validated['email'])->first();

        if ($user && Hash::check($validated['password'], $user->password)) {
            // Login successful
            Auth::login($user);
            
            // Regenerate session after successful login
            $request->session()->regenerate();

            // Ensure default voucher exists and is assigned to this user
            $this->ensureDefaultVoucherForUser($user->id);

            return redirect()->intended('/')->with('success', 'Login successful!');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'These credentials do not match our records.',
            ]);
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string',
            'password' => 'required|min:6|confirmed',
        ]);

        // Create user in database
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Also create customer profile
        // Assign default voucher to new user
        $this->ensureDefaultVoucherForUser($user->id);
        $customer = Customer::create([
            'customer_id' => Str::uuid(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? '',
            'address' => '',
            'city' => '',
            'state' => '',
            'zip_code' => '',
            'country' => 'USA',
            'status' => 'active',
        ]);

        // Auto login
        Auth::login($user);
        
        // Regenerate session after successful login
        $request->session()->regenerate();

        return redirect()->intended('/')->with('success', 'Registration successful! You are now logged in.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully!');
    }
}

