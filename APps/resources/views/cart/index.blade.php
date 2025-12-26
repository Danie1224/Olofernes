@extends('layouts.app')

@section('title', 'Shopping Cart - TechStore')

@section('content')
<div class="container mt-4">
    <h1>Shopping Cart</h1>
    
    <div id="cart-container">
        <!-- Cart items will be loaded here -->
    </div>
    
    <div id="empty-cart" class="text-center" style="display: none;">
        <div class="card">
            <h2>Your cart is empty</h2>
            <p class="text-muted">Add some products to get started!</p>
            <a href="/products" class="btn btn-primary">Browse Products</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function loadCart() {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const container = document.getElementById('cart-container');
        const emptyCart = document.getElementById('empty-cart');
        
        if (cart.length === 0) {
            container.style.display = 'none';
            emptyCart.style.display = 'block';
            return;
        }
        
        container.style.display = 'block';
        emptyCart.style.display = 'none';
        
        let total = 0;
        let html = `
            <div class="card">
                <h2>Cart Items</h2>
                <div class="grid grid-1 gap-3">
        `;
        
        cart.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;
            
            html += `
                <div class="card d-flex justify-between align-center">
                    <div>
                        <h3>${item.name}</h3>
                        <p class="price">$${item.price}</p>
                    </div>
                    <div class="d-flex align-center gap-3">
                        <div class="d-flex align-center gap-2">
                            <button onclick="updateQuantity(${index}, ${item.quantity - 1})" class="btn btn-secondary">-</button>
                            <span class="p-3">${item.quantity}</span>
                            <button onclick="updateQuantity(${index}, ${item.quantity + 1})" class="btn btn-secondary">+</button>
                        </div>
                        <div class="price">$${itemTotal.toFixed(2)}</div>
                        <button onclick="removeItem(${index})" class="btn btn-danger">Remove</button>
                    </div>
                </div>
            `;
        });
        
        html += `
                </div>
                <div class="border-top mt-4 pt-4">
                    <div class="d-flex justify-between align-center">
                        <h2>Total: <span class="price">$${total.toFixed(2)}</span></h2>
                        <div class="d-flex gap-2">
                            <button onclick="clearCart()" class="btn btn-secondary">Clear Cart</button>
                            <button onclick="checkout()" class="btn btn-success">Checkout</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        container.innerHTML = html;
        updateCartCount();
    }
    
    function updateQuantity(index, newQuantity) {
        if (newQuantity < 0) return;
        
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        if (newQuantity === 0) {
            cart.splice(index, 1);
        } else {
            cart[index].quantity = newQuantity;
        }
        
        localStorage.setItem('cart', JSON.stringify(cart));
        loadCart();
    }
    
    function removeItem(index) {
        if (confirm('Are you sure you want to remove this item from your cart?')) {
            let cart = JSON.parse(localStorage.getItem('cart') || '[]');
            cart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            loadCart();
        }
    }
    
    function clearCart() {
        if (confirm('Are you sure you want to clear your entire cart?')) {
            localStorage.removeItem('cart');
            loadCart();
        }
    }
    
    function checkout() {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        if (cart.length === 0) {
            alert('Your cart is empty!');
            return;
        }
        
        // Redirect to checkout page
        window.location.href = '/checkout';
    }
    
    // Load cart on page load
    document.addEventListener('DOMContentLoaded', loadCart);
</script>
@endpush
