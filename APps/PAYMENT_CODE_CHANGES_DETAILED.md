# 📋 Exact Code Changes - Payment Modal to Page Redirect

## Summary of Changes

**Total Files Modified**: 3  
**Total Files Created**: 3  
**Total Lines Added**: ~1,200  

---

## 1️⃣ routes/web.php

### Lines Added (After line 30)

```php
// Payment method dedicated pages
Route::get('/checkout/gcash-details', [App\Http\Controllers\CheckoutController::class, 'showGCashDetails'])->name('checkout.gcash-details');
Route::get('/checkout/credit-card-details', [App\Http\Controllers\CheckoutController::class, 'showCreditCardDetails'])->name('checkout.credit-card-details');
Route::get('/checkout/paypal-details', [App\Http\Controllers\CheckoutController::class, 'showPayPalDetails'])->name('checkout.paypal-details');
```

**Full Context (Lines 25-40)**:
```php
Route::middleware('auth')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [App\Http\Controllers\CheckoutController::class, 'processCheckout'])->name('checkout.process');
    
    // Payment method dedicated pages
    Route::get('/checkout/gcash-details', [App\Http\Controllers\CheckoutController::class, 'showGCashDetails'])->name('checkout.gcash-details');
    Route::get('/checkout/credit-card-details', [App\Http\Controllers\CheckoutController::class, 'showCreditCardDetails'])->name('checkout.credit-card-details');
    Route::get('/checkout/paypal-details', [App\Http\Controllers\CheckoutController::class, 'showPayPalDetails'])->name('checkout.paypal-details');
    
    // User order items dashboard routes
    Route::get('/orders', [App\Http\Controllers\UserOrderItemController::class, 'index'])->name('user.orders.index');
    // ... rest of routes ...
```

---

## 2️⃣ app/Http/Controllers/CheckoutController.php

### Lines Added (Before closing brace at end of file)

```php
    /**
     * Display GCash payment details page
     */
    public function showGCashDetails()
    {
        return view('checkout-gcash');
    }

    /**
     * Display Credit Card payment details page
     */
    public function showCreditCardDetails()
    {
        return view('checkout-credit-card');
    }

    /**
     * Display PayPal payment details page
     */
    public function showPayPalDetails()
    {
        return view('checkout-paypal');
    }
```

**Location**: After `processCheckout()` method, before final closing brace

---

## 3️⃣ resources/views/checkout.blade.php

### CHANGE #1: handleCheckoutIntent() Function (Lines ~900-950)

#### OLD CODE:
```javascript
function handleCheckoutIntent(e) {
    if (e) e.preventDefault();
    
    // Collect voucher selections per item
    const voucherSelections = [];
    document.querySelectorAll('.voucher-select').forEach(sel => {
        if (sel.value) {
            voucherSelections.push({
                product_id: sel.getAttribute('data-product-id'),
                voucher_id: sel.value,
            });
        }
    });

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
        vouchers: voucherSelections,
        total: parseFloat(document.getElementById('total').textContent.replace('$', '').replace('₱', '')),
    };

    // Store formData globally for use in payment modal submissions
    window.checkoutFormData = formData;

    // Show appropriate modal based on payment method
    if (paymentMethod === 'gcash') {
        showGCashModal();
        return;
    } else if (paymentMethod === 'credit_card') {
        showCreditCardModal();
        return;
    } else if (paymentMethod === 'paypal') {
        showPayPalModal();
        return;
    }

    // For cash payment, submit directly
    submitCheckout(formData);
}
```

#### NEW CODE:
```javascript
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
```

**Key Changes:**
- Extracted `collectVoucherSelections()` as separate function
- Replaced `showGCashModal()`, `showCreditCardModal()`, `showPayPalModal()` calls with `window.location.href = '{{ route(...) }}'`
- Added `localStorage.setItem('checkout-data', JSON.stringify(formData))`
- Kept global `window.checkoutFormData` for backward compatibility
- Cash payment flow remains unchanged

### CHANGE #2: getButtonLabel() Function (New) (Around line ~960)

#### REPLACED:
```javascript
// Simple submit state toggler to avoid double submissions
let mainButtonOriginalText = 'Complete Purchase';
function setSubmitting(isSubmitting, context = '') {
    const mainBtn = document.getElementById('checkout-button');
    if (mainBtn) {
        if (!mainButtonOriginalText) {
            mainButtonOriginalText = mainBtn.textContent || 'Complete Purchase';
        }
        mainBtn.disabled = isSubmitting;
        mainBtn.textContent = isSubmitting ? 'Processing...' : mainButtonOriginalText;
    }
    // ... rest of function ...
}
```

#### WITH:
```javascript
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
    // ... rest of function ...
}
```

**Key Changes:**
- Removed static `mainButtonOriginalText` variable
- Added dynamic `getButtonLabel()` function
- Button text now updates based on selected payment method
- Properly restores correct button text after each submission

### CHANGE #3: ensureCheckoutData() Function (Around line ~990)

#### CHANGED from:
```javascript
vouchers: [],
```

#### TO:
```javascript
vouchers: collectVoucherSelections(),
```

**Context (Full Function)**:
```javascript
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
        vouchers: collectVoucherSelections(),  // CHANGED
        total: parseFloat(document.getElementById('total').textContent.replace('$', '').replace('₱', '')),
    };
    window.checkoutFormData = fallback;
    return fallback;
}
```

**Key Change:**
- Ensures vouchers are collected even in fallback scenario

---

## 4️⃣ resources/views/checkout-gcash.blade.php

### NEW FILE - 270 lines

**Key Components:**
```blade
@extends('layouts.app')

@section('title', 'GCash Payment - TechStore')

@section('content')
<div class="container mt-5">
    <!-- Back Button -->
    <a href="{{ route('checkout') }}">← Back to Checkout</a>
    
    <!-- Payment Card -->
    <div>
        <h1>GCash Payment</h1>
        
        <!-- Order Summary -->
        <div id="payment-amount">₱0.00</div>
        
        <!-- GCash Form -->
        <form id="gcash-payment-form">
            @csrf
            <input type="text" id="gcash-number" placeholder="09XXXXXXXXX (11 digits)" />
            <input type="password" id="gcash-pin" placeholder="0000 (4 digits)" />
            <button type="submit">Pay <span id="gcash-amount-btn">₱0.00</span></button>
        </form>
    </div>
</div>

<script>
    // Retrieve checkout data from localStorage
    function getCheckoutData() {
        const stored = localStorage.getItem('checkout-data');
        if (stored) return JSON.parse(stored);
        
        try {
            if (window.parent !== window && window.parent.checkoutFormData) {
                return window.parent.checkoutFormData;
            }
        } catch (e) {
            console.warn('Cannot access parent window data');
        }
        return null;
    }

    // Display payment amount
    document.addEventListener('DOMContentLoaded', function() {
        const checkoutData = getCheckoutData();
        if (checkoutData) {
            const formattedAmount = '₱' + parseFloat(checkoutData.total).toFixed(2);
            document.getElementById('payment-amount').textContent = formattedAmount;
            document.getElementById('gcash-amount-btn').textContent = formattedAmount;
        }
    });

    // Validation
    function validateGCashNumber() { /* ... */ }
    function validateGCashPIN() { /* ... */ }

    // Form submission
    document.getElementById('gcash-payment-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Validate
        const isNumberValid = validateGCashNumber();
        const isPINValid = validateGCashPIN();
        if (!isNumberValid || !isPINValid) return;

        // Get checkout data
        const checkoutData = getCheckoutData();
        if (!checkoutData) {
            alert('Error: No checkout data found');
            window.location.href = '{{ route("checkout") }}';
            return;
        }

        // Prepare data
        const paymentData = {
            ...checkoutData,
            payment_method: 'gcash',
            gcash_number: document.getElementById('gcash-number').value,
            gcash_pin: document.getElementById('gcash-pin').value,
        };

        // Disable button
        const button = document.getElementById('gcash-pay-button');
        button.disabled = true;
        button.textContent = 'Processing...';

        try {
            // POST to backend
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
                throw new Error(result.message || 'Payment failed');
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
```

**Total Size**: ~270 lines (includes styling, HTML, and JavaScript)

---

## 5️⃣ resources/views/checkout-credit-card.blade.php

### NEW FILE - 310 lines

**Key Differences from GCash:**
```blade
<!-- Card Number with auto-formatting -->
<input id="card-number" placeholder="1234 5678 9012 3456" />

<!-- Cardholder Name -->
<input id="cardholder-name" placeholder="John Doe" />

<!-- Expiry and CVV -->
<input id="card-expiry" placeholder="MM/YY" maxlength="5" />
<input id="card-cvv" placeholder="123" maxlength="4" />

<script>
    // Auto-format card number
    document.getElementById('card-number').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '');
        let formatted = value.replace(/(\d{4})/g, '$1 ').trim();
        e.target.value = formatted;
    });

    // Auto-format expiry
    document.getElementById('card-expiry').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.slice(0, 2) + '/' + value.slice(2, 4);
        }
        e.target.value = value;
    });

    // Validation functions for card fields
    function validateCardNumber() { /* ... */ }
    function validateCardExpiry() { /* ... */ }
    function validateCardCVV() { /* ... */ }

    // Form submission
    // (Same structure as GCash)
</script>
```

**Total Size**: ~310 lines

---

## 6️⃣ resources/views/checkout-paypal.blade.php

### NEW FILE - 220 lines

**Key Differences:**
```blade
<!-- Simple email field -->
<input type="email" id="paypal-email" placeholder="your.email@example.com" />

<!-- Submit button text -->
<button>Continue to PayPal</button>

<script>
    // Simple email validation
    function validatePayPalEmail() { /* ... */ }

    // Form submission
    // (Same structure as GCash)
</script>
```

**Total Size**: ~220 lines

---

## Summary of Changes

### Files Created (3)
| File | Lines | Purpose |
|------|-------|---------|
| `checkout-gcash.blade.php` | 270 | GCash payment details page |
| `checkout-credit-card.blade.php` | 310 | Credit Card payment details page |
| `checkout-paypal.blade.php` | 220 | PayPal payment details page |

### Files Modified (3)
| File | Changes | Lines |
|------|---------|-------|
| `routes/web.php` | Added 3 routes | +4 |
| `CheckoutController.php` | Added 3 methods | +15 |
| `checkout.blade.php` | Updated payment flow | +5 net (modified functions) |

### Total Impact
- **New Code**: ~800 lines
- **Modified Code**: ~20 lines
- **Deleted Code**: 0 lines
- **Net Change**: +820 lines

### Backward Compatibility
- ✅ Modal functions still exist in `checkout.blade.php` (unused but not deleted)
- ✅ All existing checkout logic preserved
- ✅ Cash payment flow unchanged
- ✅ All existing APIs unchanged
- ✅ Voucher system fully integrated

---

## Testing the Changes

### Quick Test (GCash)
```bash
1. Navigate to /checkout
2. Fill customer info
3. Select "GCash" payment
4. Click "Pay through GCash"
5. Should redirect to /checkout/gcash-details
6. Form should display with order total
7. Enter GCash number: 09171234567
8. Enter PIN: 1234
9. Click "Pay ₱[amount]"
10. Should process and redirect home
```

### Verify Routes
```bash
php artisan route:list | grep checkout
# Should show:
# GET /checkout (checkout)
# GET /checkout/gcash-details (checkout.gcash-details)
# GET /checkout/credit-card-details (checkout.credit-card-details)
# GET /checkout/paypal-details (checkout.paypal-details)
# POST /checkout/process (checkout.process)
```

---

## Rollback Instructions

If you need to revert to modal-based system:

1. **Restore checkout.blade.php** to before this commit
   - Remove redirect lines from `handleCheckoutIntent()`
   - Restore `showGCashModal()`, etc. calls
   - Remove localStorage code

2. **Delete new files**
   - `checkout-gcash.blade.php`
   - `checkout-credit-card.blade.php`
   - `checkout-paypal.blade.php`

3. **Revert routes/web.php**
   - Remove 3 new payment detail routes

4. **Revert CheckoutController.php**
   - Remove 3 new methods

---

**Implementation Date**: December 8, 2025  
**Status**: ✅ Complete and Ready for Testing
