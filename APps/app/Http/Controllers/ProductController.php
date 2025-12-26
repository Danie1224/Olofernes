<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        // Category filter
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }
        
        // Sort functionality
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'stock':
                    $query->orderBy('stock_quantity', 'desc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }
        } else {
            $query->latest();
        }
        
        $limit = $request->get('limit', 15);
        $products = $query->paginate($limit);
        
        // If it's an API request, return JSON
        if ($request->expectsJson()) {
            return response()->json($products);
        }
        
        // Otherwise return view
        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_code' => 'required|string|unique:products,product_code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'brand_id' => 'nullable|integer|exists:brands,brand_id',
            'admin_id' => 'nullable|integer|exists:admins,admin_id',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'product_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('products', $filename, 'public');
            $validated['image'] = 'storage/' . $path;
        }

        $product = Product::create($validated);
        
        // Redirect with success message for form submission
        if (!$request->expectsJson()) {
            return redirect()->route('admin.products.index')
                ->with('success', "Product '{$product->name}' created successfully!");
        }
        
        // Return JSON for API requests
        $responseData = [
            'product_id' => $product->id,
            'product_code' => $product->product_code,
            'name' => $product->name,
            'description' => $product->description,
            'price' => number_format($product->price, 2, '.', ''),
            'stock_quantity' => (int)$product->stock_quantity,
            'category' => $product->category,
            'image' => $product->image,
            'brand_id' => (int)$product->brand_id,
            'created_at' => $product->created_at->toDateTimeString(),
            'updated_at' => $product->updated_at->toDateTimeString(),
        ];

        return response()->json($responseData, 201);
    }

    public function show(Request $request, string $productId)
    {
        $product = Product::with('brand')->where('product_id', $productId)->firstOrFail();
        
        // If it's an API request, return JSON
        if ($request->expectsJson()) {
            return response()->json($product);
        }
        
        // Otherwise return view
        return view('products.show', compact('product'));
    }

    public function update(Request $request, string $productId)
    {
        $product = Product::where('product_id', $productId)->firstOrFail();

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock_quantity' => 'sometimes|required|integer|min:0',
            'category' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'brand_id' => 'nullable|integer|exists:brands,brand_id',
            'admin_id' => 'nullable|integer|exists:admins,admin_id',
        ]);

        // Handle image upload/replacement
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($product->image && Storage::disk('public')->exists(str_replace('storage/', '', $product->image))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $product->image));
            }
            
            // Store new image
            $file = $request->file('image');
            $filename = 'product_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('products', $filename, 'public');
            $validated['image'] = 'storage/' . $path;
        }

        $product->update($validated);
        
        // Redirect with success message for form submission
        if (!$request->expectsJson()) {
            return redirect()->route('admin.products.index')
                ->with('success', "Product '{$product->name}' updated successfully!");
        }
        
        return response()->json($product);
    }

    public function destroy(string $productId)
    {
        $product = Product::where('product_id', $productId)->firstOrFail();
        $product->delete();
        return response()->json(null, 204);
    }
}


