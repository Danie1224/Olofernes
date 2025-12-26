@extends('layouts.app')

@section('title', $product->name . ' - TechStore')

@php
    // Use Blade @auth in scripts to avoid facade resolution issues
@endphp

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="grid grid-2 gap-4">
            <div>
                <h1>{{ $product->name }}</h1>
                <p class="text-muted">Product Code: {{ $product->product_code }}</p>
                <div class="price mb-3">${{ number_format($product->price, 2) }}</div>
                <div class="stock mb-3 {{ $product->stock_quantity > 0 ? 'text-success' : 'text-danger' }}">
                    {{ $product->stock_quantity > 0 ? 'In Stock: ' . $product->stock_quantity . ' units' : 'Out of Stock' }}
                </div>
                @if($product->category)
                    <div class="badge badge-success mb-3">{{ $product->category }}</div>
                @endif
                <div class="mb-3">
                    <h3>Description</h3>
                    <p>{{ $product->description ?: 'No description available for this product.' }}</p>
                </div>
                <div class="mb-3">
                    <h3>Specifications</h3>
                    <div class="grid grid-2">
                        @php
                            $cat = strtolower($product->category ?? '');
                            $isDevice = in_array($cat, ['smartphone', 'laptop', 'tablet', 'desktop']);
                        @endphp
                        @if($isDevice)
                            <div class="text-muted">Chipset</div>
                            <div>{{ $product->chipset ?? 'N/A   ' }}</div>
                            <div class="text-muted">Memory</div>
                            <div>{{ $product->memory ?? 'N/A' }}</div>
                            <div class="text-muted">Storage</div>
                            <div>{{ $product->storage ?? 'N/A' }}</div>
                        @else
                            <div class="text-muted">Product Code</div>
                            <div>{{ $product->product_code }}</div>
                        @endif
                        <div class="text-muted">Category</div>
                        <div>{{ $product->category ?: 'N/A' }}</div>
                        <div class="text-muted">Brand</div>
                        <div>{{ optional($product->brand)->name ?: 'N/A' }}</div>
                        <div class="text-muted">Price</div>
                        <div>${{ number_format($product->price, 2) }}</div>
                        <div class="text-muted">Stock</div>
                        <div>{{ $product->stock_quantity }}</div>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    @if($product->stock_quantity > 0)
                        <button onclick="buyNow('{{ $product->product_id }}', '{{ addslashes($product->name) }}', {{ $product->price }})" class="btn btn-primary">Buy Now</button>
                        <button onclick="addToCart('{{ $product->product_id }}', '{{ addslashes($product->name) }}', {{ $product->price }})" class="btn btn-success">Add to Cart</button>
                    @else
                        <button disabled class="btn btn-secondary">Out of Stock</button>
                    @endif
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to Products</a>
                </div>
            </div>
            <div class="text-center">
                <div class="bg-light p-4 rounded">
                    <h3>Product Image</h3>
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="max-width: 100%; height: auto; border-radius: 8px;">
                    @else
                        <p class="text-muted">Image not available</p>
                        <div class="bg-primary text-white p-4 rounded">
                            {{ $product->name }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Check if user is logged in (server-evaluated)
    const userLoggedIn = @auth true @else false @endauth;

    // Add to cart function
    function addToCart(productId, name, price) {
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
    
    // Buy now function
    function buyNow(productId, name, price) {
        if (!userLoggedIn) {
            alert('Please login first to purchase items!');
            window.location.href = '/login';
            return;
        }
        
        // Clear cart and add only this item
        let cart = [{
            product_id: productId,
            name: name,
            price: price,
            quantity: 1
        }];
        
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
        
        // Redirect to checkout
        window.location.href = '/checkout';
    }
</script>
@endpush
