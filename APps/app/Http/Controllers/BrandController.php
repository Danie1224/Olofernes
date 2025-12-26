<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of all brands
     */
    public function index(Request $request)
    {
        $brands = Brand::all();
        
        // Always return JSON for API
        return response()->json($brands);
    }

    /**
     * Display a specific brand
     */
    public function show(Request $request, int $brandId)
    {
        $brand = Brand::findOrFail($brandId);
        
        // If it's an API request, return JSON
        if ($request->expectsJson()) {
            return response()->json($brand);
        }
        
        return view('brands.show', compact('brand'));
    }

    /**
     * Store a new brand
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'description' => 'nullable|string',
        ]);

        $brand = Brand::create($validated);
        
        if (!$request->expectsJson()) {
            return redirect()->route('admin.brands.index')
                ->with('success', "Brand '{$brand->name}' created successfully!");
        }
        
        return response()->json($brand, 201);
    }

    /**
     * Update a brand
     */
    public function update(Request $request, int $brandId)
    {
        $brand = Brand::findOrFail($brandId);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:brands,name,' . $brandId . ',brand_id',
            'description' => 'nullable|string',
        ]);

        $brand->update($validated);
        
        if (!$request->expectsJson()) {
            return redirect()->route('admin.brands.index')
                ->with('success', "Brand '{$brand->name}' updated successfully!");
        }
        
        return response()->json($brand);
    }

    /**
     * Delete a brand
     */
    public function destroy(Request $request, int $brandId)
    {
        $brand = Brand::findOrFail($brandId);
        $brandName = $brand->name;
        $brand->delete();
        
        if (!$request->expectsJson()) {
            return redirect()->route('admin.brands.index')
                ->with('success', "Brand '{$brandName}' deleted successfully!");
        }
        
        return response()->json(['message' => "Brand '{$brandName}' deleted successfully!"]);
    }
}
