@extends('layouts.app')

@section('title', 'Checkout - TechStore')

@section('content')
<div class="container mt-4">
    <style>
        .checkout-header {
            margin-bottom: 30px;
        }

        .checkout-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #111827;
            margin: 0 0 10px 0;
        }

        .checkout-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        .order-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .order-section h2 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #111827;
            margin: 0 0 20px 0;
            padding-bottom: 15px;
            border-bottom: 2px solid #f3f4f6;
        }

        .order-section h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #111827;
            margin: 20px 0 15px 0;
        }

        .checkout-item {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #f3f4f6;
            align-items: center;
        }

        .checkout-item:last-of-type {
            border-bottom: none;
        }

        .item-info strong {
            display: block;
            margin-bottom: 5px;
            color: #111827;
        }

        .item-info small {
            color: #6b7280;
        }

        .item-price {
            font-weight: 600;
            color: #667eea;
            font-size: 1.1rem;
        }

        .voucher-selector {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #f3f4f6;
        }

        .voucher-select-row {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 12px;
            align-items: center;
            margin-bottom: 15px;
        }

        .voucher-select-row label {
            font-weight: 600;
            color: #111827;
            font-size: 0.95rem;
        }

        .form-control {
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 0.95rem;
        }

        .order-totals {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            font-size: 0.95rem;
            color: #6b7280;
        }

        .total-row.total {
            padding: 15px 0;
            border-top: 2px solid #e5e7eb;
            border-bottom: 2px solid #e5e7eb;
            font-size: 1.3rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 10px;
        }

        .discount-row {
            color: #22c55e;
        }

        .summary-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #111827;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 0.95rem;
            font-family: inherit;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .btn-success {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            padding: 14px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 15px;
            font-size: 1rem;
        }

        .btn-success:hover {
            box-shadow: 0 8px 24px rgba(34, 197, 94, 0.3);
            transform: translateY(-2px);
        }

        .btn-success:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .empty-cart-alert {
            background: #fee2e2;
            color: #991b1b;
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #ef4444;
        }

        @media (max-width: 1024px) {
            .checkout-container {
                grid-template-columns: 1fr;
            }

            .summary-section {
                position: static;
            }

            .grid-2 {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .checkout-header h1 {
                font-size: 1.5rem;
            }

            .checkout-item {
                grid-template-columns: 1fr;
            }

            .voucher-select-row {
                grid-template-columns: 1fr;
            }
        }

        /* Payment Modals */
        .payment-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 450px;
            width: 90%;
            z-index: 1001;
        }

        .payment-modal.hidden {
            display: none !important;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .modal-overlay.hidden {
            display: none !important;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #f3f4f6;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
            color: #111827;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6b7280;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .modal-close:hover {
            background: #f3f4f6;
            color: #111827;
        }

        .modal-body {
            padding: 25px;
        }

        .payment-modal .form-group {
            margin-bottom: 15px;
        }

        .payment-modal .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #111827;
        }

        .payment-modal .form-group input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.95rem;
            font-family: inherit;
        }

        .payment-modal .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .payment-amount {
            background: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }

        .payment-amount p {
            margin: 0;
        }

        .secure-notice {
            background: #ecfdf5;
            padding: 10px;
            border-radius: 6px;
            margin: 15px 0;
            color: #047857;
            font-size: 0.9rem;
        }

        .payment-modal .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .payment-modal .form-group:last-child {
            display: flex;
            gap: 10px;
            margin-top: 25px;
            margin-bottom: 0;
        }

        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #111827;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #059669;
        }
    </style>

    <div class="checkout-header">
        <h1>🛒 Checkout</h1>
    </div>

    <div class="checkout-container">
        <!-- Order Summary Section -->
        <div class="order-section">
            <h2>Order Summary</h2>
            
            <div id="checkout-items">
                <!-- Items will be loaded here -->
            </div>

            <!-- Voucher Selection -->
            <div class="voucher-selector" id="voucher-selector-section" style="display: none;">
                <h3>💳 Apply Vouchers</h3>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 15px;">Select a voucher per product to apply discounts</p>
                <div id="voucher-selectors">
                    <!-- Dynamically filled -->
                </div>
            </div>

            <!-- Order Totals -->
            <div class="order-totals">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span id="subtotal">$0.00</span>
                </div>
                <div class="total-row">
                    <span>Shipping:</span>
                    <span>$10.00</span>
                </div>
                <div class="total-row discount-row" id="discount-row" style="display: none;">
                    <span>🎁 Discount:</span>
                    <span id="discount">-$0.00</span>
                </div>
                <div class="total-row total">
                    <span>Total:</span>
                    <strong id="total">$0.00</strong>
                </div>
            </div>
        </div>

        <!-- Customer Info Section -->
        <div class="summary-section">
            <h2 style="margin-top: 0;">Customer Info</h2>
            
            <form id="checkout-form">
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone *</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>

                <div class="form-group">
                    <label for="address">Address *</label>
                    <textarea id="address" name="address" required></textarea>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="city">City *</label>
                        <input type="text" id="city" name="city" required>
                    </div>
                    <div class="form-group">
                        <label for="zip">ZIP Code *</label>
                        <input type="text" id="zip" name="zip" required>
                    </div>
                </div>

                <h3 style="margin-top: 20px;">Payment Method</h3>
                <div class="form-group">
                    <select id="payment-method" required onchange="updateButtonText()">
                        <option value="">Select Payment Method</option>
                        <option value="cash">💵 Cash</option>
                        <option value="credit_card">💳 Credit Card</option>
                        <option value="gcash">📱 GCash</option>
                        <option value="paypal">🅿️ PayPal</option>
                    </select>
                </div>

                <button type="submit" id="checkout-button" class="btn-success">Complete Purchase</button>
            </form>
        </div>
    </div>

    <!-- GCash Payment Modal -->
    <div id="gcash-modal" class="payment-modal hidden" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>📱 GCash Payment</h2>
                <button type="button" class="modal-close" onclick="closeGCashModal()">✕</button>
            </div>
            <div class="modal-body">
                <form id="gcash-form">
                    <div class="form-group">
                        <label for="gcash-number">GCash Mobile Number *</label>
                        <input type="tel" id="gcash-number" name="gcash_number" placeholder="09123456789" required>
                        <small style="color: #6b7280;">Enter 11-digit GCash number</small>
                    </div>

                    <div class="form-group">
                        <label for="gcash-pin">GCash PIN *</label>
                        <input type="password" id="gcash-pin" name="gcash_pin" placeholder="••••" maxlength="4" required>
                        <small style="color: #6b7280;">Enter your 4-digit GCash PIN</small>
                    </div>

                    <div class="payment-amount" style="background: #f9fafb; padding: 15px; border-radius: 6px; margin: 20px 0;">
                        <p style="color: #6b7280; margin: 0 0 8px 0;">Amount to Pay:</p>
                        <p style="font-size: 1.5rem; font-weight: 700; color: #111827; margin: 0;" id="gcash-amount">₱0.00</p>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <button type="button" class="btn btn-secondary" onclick="closeGCashModal()">Cancel</button>
                        <button type="submit" class="btn btn-success">Confirm Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Credit Card Payment Modal -->
    <div id="credit-card-modal" class="payment-modal hidden" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>💳 Credit Card Payment</h2>
                <button type="button" class="modal-close" onclick="closeCreditCardModal()">✕</button>
            </div>
            <div class="modal-body">
                <form id="credit-card-form">
                    <div class="form-group">
                        <label for="card-number">Card Number *</label>
                        <input type="text" id="card-number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19" required>
                        <small style="color: #6b7280;">Enter 16-digit card number</small>
                    </div>

                    <div class="form-group">
                        <label for="cardholder-name">Cardholder Name *</label>
                        <input type="text" id="cardholder-name" name="cardholder_name" placeholder="John Doe" required>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="card-expiry">Expiry Date *</label>
                            <input type="text" id="card-expiry" name="card_expiry" placeholder="MM/YY" maxlength="5" required>
                        </div>
                        <div class="form-group">
                            <label for="card-cvv">CVV *</label>
                            <input type="text" id="card-cvv" name="card_cvv" placeholder="123" maxlength="4" required>
                        </div>
                    </div>

                    <div class="payment-amount" style="background: #f9fafb; padding: 15px; border-radius: 6px; margin: 20px 0;">
                        <p style="color: #6b7280; margin: 0 0 8px 0;">Amount to Pay:</p>
                        <p style="font-size: 1.5rem; font-weight: 700; color: #111827; margin: 0;" id="card-amount">₱0.00</p>
                    </div>

                    <div class="secure-notice" style="background: #ecfdf5; padding: 10px; border-radius: 6px; margin: 15px 0; color: #047857; font-size: 0.9rem;">
                        🔒 Your payment information is secure and encrypted
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <button type="button" class="btn btn-secondary" onclick="closeCreditCardModal()">Cancel</button>
                        <button type="submit" class="btn btn-success">Confirm Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- PayPal Payment Modal -->
    <div id="paypal-modal" class="payment-modal hidden" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>🅿️ PayPal Payment</h2>
                <button type="button" class="modal-close" onclick="closePayPalModal()">✕</button>
            </div>
            <div class="modal-body">
                <form id="paypal-form">
                    <div class="form-group">
                        <label for="paypal-email">PayPal Email/Username *</label>
                        <input type="text" id="paypal-email" name="paypal_email" placeholder="your.email@example.com" required>
                    </div>

                    <div class="payment-amount" style="background: #f9fafb; padding: 15px; border-radius: 6px; margin: 20px 0;">
                        <p style="color: #6b7280; margin: 0 0 8px 0;">Amount to Pay:</p>
                        <p style="font-size: 1.5rem; font-weight: 700; color: #111827; margin: 0;" id="paypal-amount">₱0.00</p>
                    </div>

                    <div class="form-group" style="background: #fef3c7; padding: 15px; border-radius: 6px; margin: 20px 0;">
                        <p style="color: #92400e; margin: 0; font-size: 0.95rem;">
                            ℹ️ <strong>You will be redirected to PayPal</strong> to complete your payment securely. Please don't close this window.
                        </p>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <button type="button" class="btn btn-secondary" onclick="closePayPalModal()">Cancel</button>
                        <button type="submit" class="btn btn-success">Continue to PayPal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Payment Modal Overlay -->
    <div id="payment-modal-overlay" class="modal-overlay hidden" style="display: none;" onclick="closeAllPaymentModals()"></div>
</div>
@endsection

@push('scripts')
<script>
    let cart = [];
    let availableVouchers = [];
    
    // Update button text based on selected payment method
    function updateButtonText() {
        const paymentMethod = document.getElementById('payment-method').value;
        const button = document.getElementById('checkout-button');
        
        if (!paymentMethod) {
            button.textContent = 'Complete Purchase';
            return;
        }
        
        const methodLabels = {
            'cash': 'Complete Purchase',
            'credit_card': 'Pay through Credit Card',
            'gcash': 'Pay through GCash',
            'paypal': 'Pay through PayPal'
        };
        
        button.textContent = methodLabels[paymentMethod] || 'Complete Purchase';
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        @guest
            alert('Please login first to checkout!');
            window.location.href = '/login';
            return;
        @endguest
        
        cart = JSON.parse(localStorage.getItem('cart') || '[]');
        
        if (cart.length === 0) {
            document.querySelector('.checkout-container').innerHTML = '<div class="empty-cart-alert"><strong>Your cart is empty!</strong><br>Add items before checking out.</div>';
            return;
        }

        // Fetch available vouchers for current user
        fetch('/me/vouchers-available')
            .then(r => r.json())
            .then(data => {
                availableVouchers = Array.isArray(data) ? data : [];
                loadCheckoutItems();
                calculateTotals();
            })
            .catch(err => {
                console.error('Error loading vouchers:', err);
                loadCheckoutItems();
                calculateTotals();
            });
        
        // Pre-fill user data if available
        @auth
            const user = {!! json_encode(Auth::user()) !!};
            document.getElementById('email').value = user.email || '';
            document.getElementById('name').value = user.name || '';
        @endauth
    });
    
    function voucherOptionsHtml() {
        const opts = ['<option value="">No voucher</option>'];
        availableVouchers.forEach(v => {
            const discount = v.discount_type === 'percentage' 
                ? v.discount_value + '%' 
                : '$' + parseFloat(v.discount_value).toFixed(2);
            const label = `${v.code} - ${discount}`;
            opts.push(`<option value="${v.voucher_id}" data-type="${v.discount_type}" data-value="${v.discount_value}">${label}</option>`);
        });
        return opts.join('');
    }

    function loadCheckoutItems() {
        const container = document.getElementById('checkout-items');
        const voucherContainer = document.getElementById('voucher-selectors');
        
        container.innerHTML = cart.map(item => `
            <div class="checkout-item">
                <div class="item-info">
                    <strong>${item.name}</strong>
                    <small>Qty: ${item.quantity} × $${parseFloat(item.price).toFixed(2)}</small>
                </div>
                <div class="item-price">$${(item.price * item.quantity).toFixed(2)}</div>
            </div>
        `).join('');

        // Build voucher selectors
        if (availableVouchers.length > 0) {
            document.getElementById('voucher-selector-section').style.display = 'block';
            voucherContainer.innerHTML = cart.map(item => `
                <div class="voucher-select-row">
                    <label>${item.name}:</label>
                    <select class="form-control voucher-select" data-product-id="${item.product_id}" onchange="calculateTotals()">
                        ${voucherOptionsHtml()}
                    </select>
                </div>
            `).join('');
        }
    }
    
    function calculateTotals() {
        const subtotal = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
        const shipping = 10;
        
        let discount = 0;
        const voucherSelects = document.querySelectorAll('.voucher-select');
        
        voucherSelects.forEach(sel => {
            if (!sel.value) return;
            
            const selected = sel.options[sel.selectedIndex];
            const type = selected.getAttribute('data-type');
            const value = parseFloat(selected.getAttribute('data-value')) || 0;
            const productId = sel.getAttribute('data-product-id');
            const cartItem = cart.find(i => i.product_id == productId);
            
            if (cartItem) {
                const lineTotal = cartItem.price * cartItem.quantity;
                discount += type === 'percentage' ? (lineTotal * (value / 100)) : value;
            }
        });
        
        const total = subtotal + shipping - discount;
        
        document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('discount').textContent = `-$${discount.toFixed(2)}`;
        document.getElementById('total').textContent = `$${total.toFixed(2)}`;
        
        const discountRow = document.getElementById('discount-row');
        if (discount > 0) {
            discountRow.style.display = 'flex';
        } else {
            discountRow.style.display = 'none';
        }
    }
    
    // Handle form submission
    // Payment modal functions
    function showGCashModal() {
        const total = document.getElementById('total').textContent;
        document.getElementById('gcash-amount').textContent = total;
        document.getElementById('gcash-modal').style.display = 'block';
        document.getElementById('payment-modal-overlay').style.display = 'block';
    }

    function closeGCashModal() {
        document.getElementById('gcash-modal').style.display = 'none';
        if (!document.getElementById('credit-card-modal').style.display.includes('block') &&
            !document.getElementById('paypal-modal').style.display.includes('block')) {
            document.getElementById('payment-modal-overlay').style.display = 'none';
        }
    }

    function showCreditCardModal() {
        const total = document.getElementById('total').textContent;
        document.getElementById('card-amount').textContent = total;
        document.getElementById('credit-card-modal').style.display = 'block';
        document.getElementById('payment-modal-overlay').style.display = 'block';
    }

    function closeCreditCardModal() {
        document.getElementById('credit-card-modal').style.display = 'none';
        if (!document.getElementById('gcash-modal').style.display.includes('block') &&
            !document.getElementById('paypal-modal').style.display.includes('block')) {
            document.getElementById('payment-modal-overlay').style.display = 'none';
        }
    }

    function showPayPalModal() {
        const total = document.getElementById('total').textContent;
        document.getElementById('paypal-amount').textContent = total;
        document.getElementById('paypal-modal').style.display = 'block';
        document.getElementById('payment-modal-overlay').style.display = 'block';
    }

    function closePayPalModal() {
        document.getElementById('paypal-modal').style.display = 'none';
        if (!document.getElementById('gcash-modal').style.display.includes('block') &&
            !document.getElementById('credit-card-modal').style.display.includes('block')) {
            document.getElementById('payment-modal-overlay').style.display = 'none';
        }
    }

    function closeAllPaymentModals() {
        document.getElementById('gcash-modal').style.display = 'none';
        document.getElementById('credit-card-modal').style.display = 'none';
        document.getElementById('paypal-modal').style.display = 'none';
        document.getElementById('payment-modal-overlay').style.display = 'none';
    }

    // Validation functions
    function validateGCashForm() {
        const number = document.getElementById('gcash-number').value.trim();
        const pin = document.getElementById('gcash-pin').value.trim();
        const errors = [];

        if (!number) {
            errors.push('GCash number is required');
        } else if (!/^\d{11}$/.test(number)) {
            errors.push('GCash number must be exactly 11 digits');
        }

        if (!pin) {
            errors.push('GCash PIN is required');
        } else if (!/^\d{4}$/.test(pin)) {
            errors.push('GCash PIN must be exactly 4 digits');
        }

        if (errors.length > 0) {
            alert('❌ Please fix the following errors:\n\n' + errors.join('\n'));
            return false;
        }
        return true;
    }

    function validateCreditCardForm() {
        const cardNumber = document.getElementById('card-number').value.trim().replace(/\s/g, '');
        const cardholderName = document.getElementById('cardholder-name').value.trim();
        const expiry = document.getElementById('card-expiry').value.trim();
        const cvv = document.getElementById('card-cvv').value.trim();
        const errors = [];

        if (!cardNumber) {
            errors.push('Card number is required');
        } else if (!/^\d{16}$/.test(cardNumber)) {
            errors.push('Card number must be exactly 16 digits');
        }

        if (!cardholderName) {
            errors.push('Cardholder name is required');
        }

        if (!expiry) {
            errors.push('Expiry date is required');
        } else if (!/^\d{2}\/\d{2}$/.test(expiry)) {
            errors.push('Expiry date must be in MM/YY format');
        } else {
            const [month, year] = expiry.split('/');
            if (parseInt(month) > 12 || parseInt(month) < 1) {
                errors.push('Invalid month in expiry date');
            }
        }

        if (!cvv) {
            errors.push('CVV is required');
        } else if (!/^\d{3,4}$/.test(cvv)) {
            errors.push('CVV must be 3 or 4 digits');
        }

        if (errors.length > 0) {
            alert('❌ Please fix the following errors:\n\n' + errors.join('\n'));
            return false;
        }
        return true;
    }

    function validatePayPalForm() {
        const email = document.getElementById('paypal-email').value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const errors = [];

        if (!email) {
            errors.push('PayPal email/username is required');
        } else if (!emailRegex.test(email)) {
            errors.push('Please enter a valid email address');
        }

        if (errors.length > 0) {
            alert('❌ Please fix the following errors:\n\n' + errors.join('\n'));
            return false;
        }
        return true;
    }

    // Collect all voucher selections
    function collectVoucherSelections() {
        const voucherSelections = [];
        document.querySelectorAll('.voucher-select').forEach(sel => {
            if (sel.value) {
                voucherSelections.push({
                    product_id: sel.getAttribute('data-product-id'),
                    voucher_id: sel.value,
                });
            }
        });
        return voucherSelections;
    }

    function handleCheckoutIntent(e) {
        if (e) e.preventDefault();
        
        const paymentMethod = document.getElementById('payment-method').value;
        if (!paymentMethod) {
            alert('Please select a payment method before proceeding.');
            return;
        }

        const formData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            address: document.getElementById('address').value,
            city: document.getElementById('city').value,
            zip: document.getElementById('zip').value,
            payment_method: paymentMethod,
            items: cart,
            vouchers: collectVoucherSelections(),
            total: parseFloat(document.getElementById('total').textContent.replace('$', '').replace('₱', '')),
        };

        // Store formData in localStorage for access on payment pages
        localStorage.setItem('checkout-data', JSON.stringify(formData));
        
        // Also keep in global for backward compatibility
        window.checkoutFormData = formData;

        // Redirect to appropriate payment page based on payment method
        if (paymentMethod === 'gcash') {
            window.location.href = '{{ route("checkout.gcash-details") }}';
            return;
        } else if (paymentMethod === 'credit_card') {
            window.location.href = '{{ route("checkout.credit-card-details") }}';
            return;
        } else if (paymentMethod === 'paypal') {
            window.location.href = '{{ route("checkout.paypal-details") }}';
            return;
        }

        // For cash payment, submit directly
        submitCheckout(formData);
    }

    // Attach both form submit and button click to the same handler
    document.getElementById('checkout-form').addEventListener('submit', handleCheckoutIntent);
    document.getElementById('checkout-button').addEventListener('click', handleCheckoutIntent);

    // Get correct button label based on payment method
    function getButtonLabel() {
        const paymentMethod = document.getElementById('payment-method').value;
        const methodLabels = {
            'cash': 'Complete Purchase',
            'credit_card': 'Pay through Credit Card',
            'gcash': 'Pay through GCash',
            'paypal': 'Pay through PayPal'
        };
        return methodLabels[paymentMethod] || 'Complete Purchase';
    }

    function setSubmitting(isSubmitting, context = '') {
        const mainBtn = document.getElementById('checkout-button');
        if (mainBtn) {
            mainBtn.disabled = isSubmitting;
            mainBtn.textContent = isSubmitting ? 'Processing...' : getButtonLabel();
        }

        const contexts = {
            gcash: document.querySelector('#gcash-form button[type="submit"]'),
            credit_card: document.querySelector('#credit-card-form button[type="submit"]'),
            paypal: document.querySelector('#paypal-form button[type="submit"]'),
        };

        const btn = contexts[context];
        if (btn) {
            btn.disabled = isSubmitting;
            btn.textContent = isSubmitting ? 'Processing...' : (context === 'paypal' ? 'Continue to PayPal' : 'Confirm Payment');
        }
    }

    // Ensure we have base checkout data before modal submission
    function ensureCheckoutData() {
        if (window.checkoutFormData) return window.checkoutFormData;

        const paymentMethod = document.getElementById('payment-method').value;
        const fallback = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            address: document.getElementById('address').value,
            city: document.getElementById('city').value,
            zip: document.getElementById('zip').value,
            payment_method: paymentMethod,
            items: cart,
            vouchers: collectVoucherSelections(),
            total: parseFloat(document.getElementById('total').textContent.replace('$', '').replace('₱', '')),
        };
        window.checkoutFormData = fallback;
        return fallback;
    }

    // GCash form submission
    document.getElementById('gcash-form').addEventListener('submit', function(e) {
        e.preventDefault();
        if (!validateGCashForm()) return;
        const data = ensureCheckoutData();
        data.payment_method = 'gcash';
        data.gcash_number = document.getElementById('gcash-number').value;
        data.gcash_pin = document.getElementById('gcash-pin').value;
        submitCheckout(data, 'gcash');
    });

    // Credit Card form submission
    document.getElementById('credit-card-form').addEventListener('submit', function(e) {
        e.preventDefault();
        if (!validateCreditCardForm()) return;
        const data = ensureCheckoutData();
        data.payment_method = 'credit_card';
        data.card_number = document.getElementById('card-number').value.replace(/\s/g, '');
        data.cardholder_name = document.getElementById('cardholder-name').value;
        data.card_expiry = document.getElementById('card-expiry').value;
        data.card_cvv = document.getElementById('card-cvv').value;
        submitCheckout(data, 'credit_card');
    });

    // PayPal form submission
    document.getElementById('paypal-form').addEventListener('submit', function(e) {
        e.preventDefault();
        if (!validatePayPalForm()) return;
        const data = ensureCheckoutData();
        data.payment_method = 'paypal';
        data.paypal_email = document.getElementById('paypal-email').value;
        submitCheckout(data, 'paypal');
    });

    // Main checkout submission function
    function submitCheckout(formData, context = '') {
        setSubmitting(true, context);
        fetch('{{ route("checkout.process") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        })
        .then(async response => {
            const data = await response.json().catch(() => ({ success:false, message:'Invalid response'}));
            if (!response.ok || data.success === false) {
                throw new Error(data.message || 'Checkout failed');
            }
            alert('✅ Order placed successfully!\nOrder ID: ' + data.order_id);
            localStorage.removeItem('cart');
            updateCartCount();
            closeAllPaymentModals();
            // Reset all forms
            document.getElementById('gcash-form').reset();
            document.getElementById('credit-card-form').reset();
            document.getElementById('paypal-form').reset();
            window.location.href = '/';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ ' + (error.message || 'Error processing order. Please try again.'));
            setSubmitting(false, context);
        });
    }
</script>
@endpush
