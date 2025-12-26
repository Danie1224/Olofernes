@extends('layouts.app')

@section('title', 'TechStore - Your Technology Destination')

@php
    $user = session('user');
    $isLoggedIn = isset($user['loggedIn']) && $user['loggedIn'] === true;
@endphp

@section('content')
<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Welcome to TechStore</h1>
        <p>Discover the latest technology products at unbeatable prices</p>
        <a href="{{ route('products.index') }}" class="btn">Shop Now</a>
    </div>
</section>

<!-- Featured Products Section -->
<section class="container mt-4">
    <h2 class="text-center mb-4">Featured Products</h2>
    <div class="grid grid-3">
        @foreach($featuredProducts as $product)
            <div class="card">
                <h3>{{ $product->name }}</h3>
                <div class="price">${{ number_format($product->price, 2) }}</div>
                <div class="stock {{ $product->stock_quantity > 0 ? 'text-success' : 'text-danger' }}">
                    {{ $product->stock_quantity > 0 ? 'Stock: ' . $product->stock_quantity : 'Out of Stock' }}
                </div>
                <div class="mt-3">
                    <a href="{{ route('products.show', $product->product_id) }}" class="btn">View Details</a>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- Categories Section -->
<section class="container mt-4">
    <h2 class="text-center mb-4">Shop by Category</h2>
    <div class="grid grid-4">
        <div class="card text-center">
            <h3>Laptops</h3>
            <p>High-performance laptops for work and play</p>
            <a href="{{ route('products.index') }}?category=Laptop" class="btn btn-secondary">Browse</a>
        </div>
        <div class="card text-center">
            <h3>Smartphones</h3>
            <p>Latest smartphones with cutting-edge features</p>
            <a href="{{ route('products.index') }}?category=Smartphone" class="btn btn-secondary">Browse</a>
        </div>
        <div class="card text-center">
            <h3>Accessories</h3>
            <p>Essential accessories for your devices</p>
            <a href="{{ route('products.index') }}?category=Accessory" class="btn btn-secondary">Browse</a>
        </div>
        <div class="card text-center">
            <h3>Gaming</h3>
            <p>Gaming equipment for the ultimate experience</p>
            <a href="{{ route('products.index') }}?category=Gaming" class="btn btn-secondary">Browse</a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Check if user is logged in
    const userLoggedIn = {{ $isLoggedIn ? 'true' : 'false' }};

    // Add to cart function with authentication check
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
</script>
@endpush
