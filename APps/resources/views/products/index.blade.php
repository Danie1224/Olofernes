@extends('layouts.app')

@section('title', 'Products - TechStore')

@php
    // View content does not need user object; use Blade @auth in scripts instead
@endphp

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-between align-center mb-4">
        <h1>Products</h1>
        <div class="d-flex gap-2">
            <form method="GET" action="{{ route('products.index') }}" class="d-flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="form-control" style="width: 300px;">
                <select name="category" class="form-control" style="width: 200px;">
                    <option value="">All Categories</option>
                    <option value="Laptop" {{ request('category') == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                    <option value="Smartphone" {{ request('category') == 'Smartphone' ? 'selected' : '' }}>Smartphone</option>
                    <option value="Accessory" {{ request('category') == 'Accessory' ? 'selected' : '' }}>Accessory</option>
                    <option value="Gaming" {{ request('category') == 'Gaming' ? 'selected' : '' }}>Gaming</option>
                    <option value="Tablet" {{ request('category') == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                    <option value="Desktop" {{ request('category') == 'Desktop' ? 'selected' : '' }}>Desktop</option>
                </select>
                <select name="sort" class="form-control" style="width: 150px;">
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Sort by Name</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="stock" {{ request('sort') == 'stock' ? 'selected' : '' }}>Stock Available</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
    </div>

    @if($products->count() > 0)
        <div class="grid grid-3">
            @foreach($products as $product)
                <div class="card">
                    @if($product->image)
                        <div style="margin: -16px -16px 16px -16px; overflow: hidden; border-radius: 8px 8px 0 0;">
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: 200px; object-fit: cover;">
                        </div>
                    @endif
                    <h3>{{ $product->name }}</h3>
                    <p class="text-muted">{{ $product->product_code }}</p>
                    <p>{{ $product->description ?: 'No description available' }}</p>
                    <div class="price">${{ number_format($product->price, 2) }}</div>
                    <div class="stock {{ $product->stock_quantity > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $product->stock_quantity > 0 ? 'Stock: ' . $product->stock_quantity : 'Out of Stock' }}
                    </div>
                    @if($product->category)
                        <div class="badge badge-success mt-2">{{ $product->category }}</div>
                    @endif
                    <div class="mt-3">
                        <a href="{{ route('products.show', $product->product_id) }}" class="btn">View Details</a>
                        @if($product->stock_quantity > 0)
                            <button onclick="addToCart('{{ $product->product_id }}', '{{ addslashes($product->name) }}', {{ $product->price }})" class="btn btn-success">Add to Cart</button>
                        @else
                            <button disabled class="btn btn-secondary">Out of Stock</button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($products->hasPages())
            <div class="text-center mt-4">
                {{ $products->links('pagination.custom') }}
            </div>
        @endif
    @else
        <div class="card text-center">
            <h2>No Products Found</h2>
            <p class="text-muted">No products match your search criteria.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">View All Products</a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Check if user is logged in (server-evaluated)
    const userLoggedIn = @auth true @else false @endauth;

    // Add to cart function
    function addToCart(productId, name, price) {
        // Check authentication
        if (!userLoggedIn) {
            alert('Please login first to add items to cart!');
            window.location.href = '/login';
            return;
        }
        
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const existingItem = cart.find(item => item.product_id === productId);
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                product_id: productId,
                name: name,
                price: price,
                quantity: 1
            });
        }
        
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
        
        // Show success message
        alert('Product added to cart!');
    }
</script>
@endpush
