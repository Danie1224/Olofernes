<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Brand; // <-- NEW: Import the Brand model
use App\Models\Order; // <-- Make sure Order model is imported for clarity
use App\Models\User; // <-- Make sure User model is imported for clarity
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // --- Dashboard ---
    public function dashboard()
    {
        return view('admin.dashboard');
    }
    
    // --- Product Management ---
    
    public function productsIndex(Request $request)
    {
        $filter = $request->query('filter');
        
        $query = Product::latest();

        if ($filter === 'in_stock') {
            $query->where('stock_quantity', '>', 10);
        } elseif ($filter === 'low_stock') {
            $query->whereBetween('stock_quantity', [1, 10]);
        } elseif ($filter === 'out_of_stock') {
            $query->where('stock_quantity', 0);
        }

        $products = $query->paginate(20);
        return view('admin.products.index', compact('products', 'filter'));
    }
    
    public function productsCreate()
    {
        // Fetch brands for the dropdown list in the create form
        $brands = Brand::orderBy('name')->get(); 
        
        return view('admin.products.create', compact('brands')); // <-- Passing brands to the view
    }
    
    public function productsStore(Request $request)
    {
        // 1. Validate all required data, including the image file
        $validated = $request->validate([
            'product_code' => 'required|string|unique:products,product_code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'brand_id' => 'nullable|integer|exists:brands,id',
        ]);
        
        // 2. Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'product_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('products', $filename, 'public');
            $validated['image'] = 'storage/' . $path;
        }
        
        // 3. Add the required 'admin_id' to the data array
        $dataToCreate = array_merge($validated, [
            'admin_id' => Auth::id(),
        ]);
        
        // 4. Create the product with ALL required fields including image
        Product::create($dataToCreate);
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }
    
    public function productsEdit(Product $product)
    {
        // You might need brands here if you allow editing the brand
        $brands = Brand::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'brands'));
    }
    
    public function productsUpdate(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock_quantity' => 'sometimes|required|integer|min:0',
            'category' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'brand_id' => 'sometimes|required|integer|exists:brands,id',
        ]);
        
        // Handle image upload/replacement
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($product->image) {
                $oldImagePath = str_replace('storage/', '', $product->image);
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldImagePath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldImagePath);
                }
            }
            
            // Store new image
            $file = $request->file('image');
            $filename = 'product_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('products', $filename, 'public');
            $validated['image'] = 'storage/' . $path;
        }
        
        // Note: admin_id is typically NOT updated here, but other fields are fine.
        $product->update($validated);
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }
    
    public function productsDestroy(Product $product)
    {
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
    
    // --- Customer Management ---

    public function customersIndex(Request $request)
    {
        $userStatus = $request->query('user_status');
        $customerStatus = $request->query('customer_status');

        // Get all registered users with filtering
        $usersQuery = User::latest();
        if ($userStatus === 'verified') {
            $usersQuery->where('email_verified_at', '!=', null);
        } elseif ($userStatus === 'unverified') {
            $usersQuery->where('email_verified_at', null);
        }
        $users = $usersQuery->paginate(20);

        // Get all customer profiles with filtering
        $customersQuery = Customer::latest();
        if ($customerStatus === 'active') {
            $customersQuery->where('status', 'active');
        } elseif ($customerStatus === 'inactive') {
            $customersQuery->where('status', 'inactive');
        }
        $customers = $customersQuery->paginate(20);

        return view('admin.customers.index', compact('users', 'customers', 'userStatus', 'customerStatus'));
    }
    
    // --- Order Management ---

    public function ordersIndex(Request $request)
    {
        $status = $request->query('status');
        
        $query = Order::with(['customer'])->latest();

        if ($status && in_array($status, ['pending', 'processing', 'shipped', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(20);
        return view('admin.orders.index', compact('orders', 'status'));
    }

    public function ordersShow(Order $order) // Using model type-hinting for Order
    {
        // Eager load customer, order items, and the product for each item
        $order->load(['customer', 'orderItems.product']); 
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Mark order as completed
     */
    public function completeOrder(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        // Validate that only non-completed orders can be completed
        if ($order->isCompleted()) {
            return response()->json([
                'success' => false,
                'message' => 'Order is already completed.',
            ], 400);
        }

        // Get the current admin ID (assuming authenticated user is an admin)
        $adminId = auth('admin')->id() ?? auth()->id();

        if (!$adminId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Admin authentication required.',
            ], 403);
        }

        try {
            // Mark order as completed
            $order->markAsCompleted($adminId);

            return response()->json([
                'success' => true,
                'message' => 'Order marked as completed successfully.',
                'order' => [
                    'order_id' => $order->order_id,
                    'status' => $order->status,
                    'completed_at' => $order->completed_at,
                    'completed_by_admin_id' => $order->completed_by_admin_id,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error completing order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get order completion status
     */
    public function getOrderCompletionStatus($orderId)
    {
        $order = Order::findOrFail($orderId);

        return response()->json([
            'success' => true,
            'order_id' => $order->order_id,
            'status' => $order->status,
            'is_completed' => $order->isCompleted(),
            'completion_details' => $order->getCompletionDetails(),
        ], 200);
    }
}