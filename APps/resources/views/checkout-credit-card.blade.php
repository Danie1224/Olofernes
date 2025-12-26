@extends('layouts.app')

@section('title', 'Credit Card Payment - TechStore')

@section('content')
<div class="container mt-5">
    <div style="max-width: 600px; margin: 0 auto;">
        <!-- Back Button -->
        <div style="margin-bottom: 20px;">
            <a href="{{ route('checkout') }}" style="color: #667eea; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                ← Back to Checkout
            </a>
        </div>

        <!-- Payment Card -->
        <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
            <h1 style="font-size: 1.8rem; font-weight: 700; color: #111827; margin: 0 0 10px 0;">Credit Card Payment</h1>
            <p style="color: #6b7280; margin: 0 0 30px 0;">Enter your credit card details to complete the payment</p>

            <!-- Order Summary -->
            <div style="background: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                <div style="display: grid; grid-template-columns: 1fr auto; gap: 15px; align-items: center; padding-bottom: 15px; border-bottom: 1px solid #e5e7eb;">
                    <div>
                        <strong style="color: #111827; display: block; margin-bottom: 5px;" id="order-amount-label">Order Total</strong>
                        <small style="color: #6b7280;">Payment Amount</small>
                    </div>
                    <div style="font-size: 1.5rem; font-weight: 700; color: #667eea;" id="payment-amount">₱0.00</div>
                </div>
                <div style="margin-top: 15px; font-size: 0.9rem; color: #6b7280;">
                    <strong style="color: #111827;">Payment Method:</strong> Credit Card
                </div>
            </div>

            <!-- Credit Card Form -->
            <form id="credit-card-payment-form" style="display: grid; gap: 20px;">
                @csrf
                
                <!-- Card Number -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #111827;">
                        Card Number
                        <span style="color: #ef4444;">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="card-number" 
                        name="card_number"
                        placeholder="1234 5678 9012 3456" 
                        maxlength="19"
                        inputmode="numeric"
                        required
                        style="width: 100%; padding: 12px 15px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; font-weight: 500; transition: all 0.3s ease;"
                        onchange="validateCardNumber()"
                    />
                    <small id="card-number-error" style="color: #ef4444; display: none; margin-top: 5px;">Must be 16 digits</small>
                </div>

                <!-- Cardholder Name -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #111827;">
                        Cardholder Name
                        <span style="color: #ef4444;">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="cardholder-name" 
                        name="cardholder_name"
                        placeholder="John Doe" 
                        required
                        style="width: 100%; padding: 12px 15px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; font-weight: 500; transition: all 0.3s ease;"
                    />
                </div>

                <!-- Expiry and CVV -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #111827;">
                            Expiry Date
                            <span style="color: #ef4444;">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="card-expiry" 
                            name="card_expiry"
                            placeholder="MM/YY" 
                            maxlength="5"
                            required
                            style="width: 100%; padding: 12px 15px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; font-weight: 500; transition: all 0.3s ease;"
                            onchange="validateCardExpiry()"
                        />
                        <small id="card-expiry-error" style="color: #ef4444; display: none; margin-top: 5px;">Format: MM/YY</small>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #111827;">
                            CVV
                            <span style="color: #ef4444;">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="card-cvv" 
                            name="card_cvv"
                            placeholder="123" 
                            maxlength="4"
                            inputmode="numeric"
                            required
                            style="width: 100%; padding: 12px 15px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; font-weight: 500; transition: all 0.3s ease;"
                            onchange="validateCardCVV()"
                        />
                        <small id="card-cvv-error" style="color: #ef4444; display: none; margin-top: 5px;">3-4 digits</small>
                    </div>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    id="card-pay-button"
                    style="width: 100%; padding: 14px; background: #667eea; color: white; border: none; border-radius: 8px; font-size: 1.05rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease; margin-top: 20px;"
                    onmouseover="this.style.background='#5568d3'"
                    onmouseout="this.style.background='#667eea'"
                >
                    Pay <span id="card-amount-btn">₱0.00</span>
                </button>
            </form>

            <!-- Info Note -->
            <div style="background: #eff6ff; border-left: 4px solid #3b82f6; padding: 15px; border-radius: 6px; margin-top: 25px;">
                <strong style="color: #1e40af; display: block; margin-bottom: 5px;">ℹ️ Important</strong>
                <small style="color: #1e3a8a; line-height: 1.6;">
                    This is a demo payment system. In production, your card data would be processed through a secure payment gateway (Stripe, PayPal, etc.) with PCI compliance. Never store card data locally.
                </small>
            </div>
        </div>
    </div>
</div>

<script>
    // Format card number input
    document.getElementById('card-number').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '');
        let formattedValue = value.replace(/(\d{4})/g, '$1 ').trim();
        e.target.value = formattedValue;
    });

    // Format expiry date input
    document.getElementById('card-expiry').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.slice(0, 2) + '/' + value.slice(2, 4);
        }
        e.target.value = value;
    });

    // Retrieve checkout data from localStorage or parent window
    function getCheckoutData() {
        const stored = localStorage.getItem('checkout-data');
        if (stored) {
            return JSON.parse(stored);
        }
        
        try {
            if (window.parent !== window && window.parent.checkoutFormData) {
                return window.parent.checkoutFormData;
            }
        } catch (e) {
            console.warn('Cannot access parent window data');
        }

        return null;
    }

    // Display payment amount on page load
    document.addEventListener('DOMContentLoaded', function() {
        const checkoutData = getCheckoutData();
        
        if (checkoutData) {
            const amount = checkoutData.total || 0;
            const formattedAmount = '₱' + parseFloat(amount).toFixed(2);
            document.getElementById('payment-amount').textContent = formattedAmount;
            document.getElementById('card-amount-btn').textContent = formattedAmount;
        }
    });

    // Validation functions
    function validateCardNumber() {
        const input = document.getElementById('card-number');
        const error = document.getElementById('card-number-error');
        const value = input.value.replace(/\s/g, '');

        if (!/^\d{16}$/.test(value)) {
            error.style.display = 'block';
            input.style.borderColor = '#ef4444';
            return false;
        }

        error.style.display = 'none';
        input.style.borderColor = '#22c55e';
        return true;
    }

    function validateCardExpiry() {
        const input = document.getElementById('card-expiry');
        const error = document.getElementById('card-expiry-error');
        const value = input.value;

        if (!/^\d{2}\/\d{2}$/.test(value)) {
            error.style.display = 'block';
            input.style.borderColor = '#ef4444';
            return false;
        }

        error.style.display = 'none';
        input.style.borderColor = '#22c55e';
        return true;
    }

    function validateCardCVV() {
        const input = document.getElementById('card-cvv');
        const error = document.getElementById('card-cvv-error');
        const value = input.value;

        if (!/^\d{3,4}$/.test(value)) {
            error.style.display = 'block';
            input.style.borderColor = '#ef4444';
            return false;
        }

        error.style.display = 'none';
        input.style.borderColor = '#22c55e';
        return true;
    }

    // Form submission
    document.getElementById('credit-card-payment-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        // Validate all fields
        const isNumberValid = validateCardNumber();
        const isExpiryValid = validateCardExpiry();
        const isCVVValid = validateCardCVV();

        if (!isNumberValid || !isExpiryValid || !isCVVValid) {
            alert('Please fix the errors in the form');
            return;
        }

        // Get checkout data
        const checkoutData = getCheckoutData();
        if (!checkoutData) {
            alert('Error: No checkout data found. Please go back and try again.');
            window.location.href = '{{ route("checkout") }}';
            return;
        }

        // Prepare payment data
        const paymentData = {
            ...checkoutData,
            payment_method: 'credit_card',
            card_number: document.getElementById('card-number').value.replace(/\s/g, ''),
            cardholder_name: document.getElementById('cardholder-name').value,
            card_expiry: document.getElementById('card-expiry').value,
            card_cvv: document.getElementById('card-cvv').value,
        };

        // Submit payment
        const button = document.getElementById('card-pay-button');
        button.disabled = true;
        button.textContent = 'Processing...';

        try {
            const response = await fetch('{{ route("checkout.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(paymentData)
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Payment processing failed');
            }

            // Success
            alert('✅ Order placed successfully!\nOrder ID: ' + result.order_id);
            localStorage.removeItem('checkout-data');
            window.location.href = '/';

        } catch (error) {
            console.error('Payment Error:', error);
            alert('❌ Payment failed: ' + error.message);
            button.disabled = false;
            button.textContent = 'Pay ₱' + checkoutData.total.toFixed(2);
        }
    });
</script>
@endsection
