@extends('layouts.app')

@section('title', 'PayPal Payment - TechStore')

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
            <h1 style="font-size: 1.8rem; font-weight: 700; color: #111827; margin: 0 0 10px 0;">PayPal Payment</h1>
            <p style="color: #6b7280; margin: 0 0 30px 0;">Enter your PayPal email to complete the payment</p>

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
                    <strong style="color: #111827;">Payment Method:</strong> PayPal
                </div>
            </div>

            <!-- PayPal Form -->
            <form id="paypal-payment-form" style="display: grid; gap: 20px;">
                @csrf
                
                <!-- PayPal Email -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #111827;">
                        PayPal Email Address
                        <span style="color: #ef4444;">*</span>
                    </label>
                    <input 
                        type="email" 
                        id="paypal-email" 
                        name="paypal_email"
                        placeholder="your.email@example.com" 
                        required
                        style="width: 100%; padding: 12px 15px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; font-weight: 500; transition: all 0.3s ease;"
                        onchange="validatePayPalEmail()"
                    />
                    <small id="paypal-email-error" style="color: #ef4444; display: none; margin-top: 5px;">Please enter a valid email address</small>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    id="paypal-pay-button"
                    style="width: 100%; padding: 14px; background: #667eea; color: white; border: none; border-radius: 8px; font-size: 1.05rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease; margin-top: 20px;"
                    onmouseover="this.style.background='#5568d3'"
                    onmouseout="this.style.background='#667eea'"
                >
                    Continue to PayPal
                </button>
            </form>

            <!-- Info Note -->
            <div style="background: #eff6ff; border-left: 4px solid #3b82f6; padding: 15px; border-radius: 6px; margin-top: 25px;">
                <strong style="color: #1e40af; display: block; margin-bottom: 5px;">ℹ️ Important</strong>
                <small style="color: #1e3a8a; line-height: 1.6;">
                    You will be redirected to PayPal to complete the payment. Make sure you have an active PayPal account with the email address you provide above. Your payment will be processed securely by PayPal.
                </small>
            </div>
        </div>
    </div>
</div>

<script>
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
        }
    });

    // Validation function
    function validatePayPalEmail() {
        const input = document.getElementById('paypal-email');
        const error = document.getElementById('paypal-email-error');
        const value = input.value.trim();

        // Simple email validation
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            error.style.display = 'block';
            input.style.borderColor = '#ef4444';
            return false;
        }

        error.style.display = 'none';
        input.style.borderColor = '#22c55e';
        return true;
    }

    // Form submission
    document.getElementById('paypal-payment-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        // Validate email
        if (!validatePayPalEmail()) {
            alert('Please enter a valid PayPal email address');
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
            payment_method: 'paypal',
            paypal_email: document.getElementById('paypal-email').value,
        };

        // Submit payment
        const button = document.getElementById('paypal-pay-button');
        button.disabled = true;
        button.textContent = 'Redirecting to PayPal...';

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
            button.textContent = 'Continue to PayPal';
        }
    });
</script>
@endsection
