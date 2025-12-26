<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('checkout');
    }

    public function processCheckout(Request $request)
    {
        $data = $request->json()->all();
        
        // Log incoming data for debugging
        \Illuminate\Support\Facades\Log::info('Checkout Request Data:', $data);
        
        // Validate the data manually since it's coming as JSON
        $validated = [
            'name' => $data['name'] ?? '',
            'email' => $data['email'] ?? '',
            'phone' => $data['phone'] ?? '',
            'address' => $data['address'] ?? '',
            'city' => $data['city'] ?? '',
            'zip' => $data['zip'] ?? '',
            'payment_method' => $data['payment_method'] ?? 'cash',
            'items' => $data['items'] ?? [],
            'total' => $data['total'] ?? 0,
            'vouchers' => $data['vouchers'] ?? [],
        ];
        
        // Validate required fields
        $errors = [];
        if (empty($validated['name'])) {
            $errors['name'] = ['Name is required'];
        }
        if (empty($validated['email'])) {
            $errors['email'] = ['Email is required'];
        }
        if (empty($validated['items']) || !is_array($validated['items']) || count($validated['items']) === 0) {
            $errors['items'] = ['Cart items are required'];
        }
        
        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ], 422);
        }

        // Get authenticated user - IMPORTANT: use authenticated user's email for customer matching
        $authUser = \Illuminate\Support\Facades\Auth::guard('sanctum')->user();
        $customerEmail = $authUser ? $authUser->email : $validated['email'];
        
        \Illuminate\Support\Facades\Log::info('Checkout - Customer lookup:', [
            'auth_user_email' => $authUser ? $authUser->email : 'not authenticated',
            'form_email' => $validated['email'],
            'using_email' => $customerEmail
        ]);

        // Get or create customer using authenticated user's email
        $customer = Customer::whereRaw('LOWER(email) = ?', [strtolower($customerEmail)])->first();
        
        if (!$customer) {
            $customer = Customer::create([
                'customer_id' => \Illuminate\Support\Str::uuid(),
                'name' => $validated['name'],
                'email' => $customerEmail, // Use authenticated email
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'state' => '',
                'zip_code' => $validated['zip'],
                'country' => 'USA',
                'status' => 'active',
            ]);
            \Illuminate\Support\Facades\Log::info('Checkout - Created new customer:', ['customer_id' => $customer->customer_id]);
        } else {
            // Update customer info
            $customer->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'zip_code' => $validated['zip'],
            ]);
            \Illuminate\Support\Facades\Log::info('Checkout - Using existing customer:', ['customer_id' => $customer->customer_id]);
        }

        // Get cart from JSON data since it's coming as JSON
        $cartItems = $validated['items'];
        $totalPrice = $validated['total'];
        $voucherSelections = $validated['vouchers'];
        
        \Illuminate\Support\Facades\Log::info('Checkout Data:', [
            'customer' => $customer->toArray(),
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice
        ]);

        try {
            // Wrap order creation, stock updates, and order items in a transaction
            $order = DB::transaction(function () use ($customer, $validated, $cartItems, $totalPrice, $voucherSelections) {
                // Get a random admin to handle the order (or use a specific admin based on your business logic)
                $admin = Admin::first();
                \Illuminate\Support\Facades\Log::info('Selected Admin:', ['admin' => $admin ? $admin->toArray() : null]);
                
                if (!$admin) {
                    throw new \RuntimeException('No admin available to process the order.');
                }

                $firstProduct = collect($cartItems)->first();
                $productId = $firstProduct['product_id'] ?? null;

                if (empty($productId)) {
                    throw new \RuntimeException('Invalid product in cart.');
                }

                $order = Order::create([
                    'customer_id' => $customer->customer_id,
                    'product_id' => $productId,
                    'admin_id' => $admin->admin_id, // Assign an admin to handle the order
                    'quantity' => array_sum(array_map(fn($i) => (int)($i['quantity'] ?? 0), $cartItems)),
                    'total_price' => $totalPrice,
                    'payment_method' => $validated['payment_method'],
                    'status' => 'pending',
                    'order_date' => now(),
                ]);

                foreach ($cartItems as $item) {
                    $productId = $item['product_id'] ?? null;
                    $quantity = max(1, (int)($item['quantity'] ?? 1));
                    $unitPrice = (float)($item['price'] ?? 0);

                    if (!$productId) {
                        continue;
                    }

                    // Lock the product row for update to prevent race conditions
                    $product = Product::where('product_id', $productId)->lockForUpdate()->first();
                    if (!$product) {
                        continue;
                    }

                    // Ensure sufficient stock
                    if ($product->stock_quantity < $quantity) {
                        throw new \RuntimeException('Insufficient stock for product: ' . $product->name);
                    }

                    // Decrement stock
                    $product->decrement('stock_quantity', $quantity);

                    // Create order item
                    OrderItem::create([
                        'order_id' => $order->order_id,
                        'product_id' => $product->product_id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'subtotal' => $unitPrice * $quantity,
                    ]);

                    // Handle voucher usage - ONE-TIME USE ONLY
                    $sel = collect($voucherSelections)->firstWhere('product_id', (string)$product->product_id);
                    if ($sel && !empty($sel['voucher_id'])) {
                        // Verify voucher exists and is active and within date
                        $voucher = \App\Models\Voucher::where('voucher_id', $sel['voucher_id'])
                            ->where('status', 'active')
                            ->whereDate('start_date', '<=', now())
                            ->whereDate('end_date', '>=', now())
                            ->first();
                        
                        if ($voucher) {
                            // Verify user claimed this voucher
                            $userVoucher = \App\Models\UserVoucher::where('user_id', auth()->id() ?? 0)
                                ->where('voucher_id', $voucher->voucher_id)
                                ->first();

                            if (!$userVoucher) {
                                throw new \RuntimeException('You have not claimed this voucher: ' . $voucher->code);
                            }

                            // Check if voucher has already been used (one-time use only)
                            if ($userVoucher->status === 'used') {
                                throw new \RuntimeException('This voucher has already been used and cannot be reused.');
                            }

                            // Mark voucher as used in UserVoucher
                            $userVoucher->update([
                                'status' => 'used',
                                'used_at' => now(),
                            ]);

                            // Increment usage count on voucher
                            $voucher->increment('usage_count');
                        } else {
                            throw new \RuntimeException('Voucher is invalid, inactive, or has expired.');
                        }
                    }
                }

                return $order;
            });
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            report($e);
            \Illuminate\Support\Facades\Log::error('Order Creation Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'detail' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }

        // Clear cart (client controls localStorage; we still clear any server cart)
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'order_id' => $order->order_id,
            'message' => 'Order placed successfully!'
        ]);
    }

    /**
     * Display GCash payment details page
     */
    public function showGCashDetails()
    {
        return view('checkout-gcash');
    }

    /**
     * Display Credit Card payment details page
     */
    public function showCreditCardDetails()
    {
        return view('checkout-credit-card');
    }

    /**
     * Display PayPal payment details page
     */
    public function showPayPalDetails()
    {
        return view('checkout-paypal');
    }
}


