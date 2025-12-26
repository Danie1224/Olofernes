# Customer Checkout Payment Methods - End-to-End Testing

## Test Overview
Full E2E testing for all payment method workflows in the checkout process.

**Test Date**: December 8, 2025  
**Tester**: QA Team  
**Status**: Testing Guide & Checklist

---

## Setup: Pre-Checkout Requirements

### Preconditions for All Tests
- Customer user is logged in
- Cart has at least one item (₱50.00 or more)
- Browser developer console is available (F12)
- Payment methods available: Cash, Credit Card, GCash, PayPal

### Test Data

#### Sample Cart
- Item 1: Sample Product - Qty: 2 - Unit Price: ₱29.99 - Subtotal: ₱59.98
- Total Items Value: ₱59.98
- Shipping: ₱10.00
- Grand Total: ₱69.98

#### Customer Info (for all tests)
- Full Name: John Doe
- Email: john.doe@example.com
- Phone: 09123456789
- Address: 123 Sample St, Metro Manila
- City: Manila
- ZIP: 1000

---

## Test Case 1: Cash Payment - Normal Flow

### Preconditions
- Customer is on checkout page
- Cart contains items
- All required fields empty

### Test Steps
1. Fill in customer details:
   - Name: "John Doe"
   - Email: "john.doe@example.com"
   - Phone: "09123456789"
   - Address: "123 Sample St, Metro Manila"
   - City: "Manila"
   - ZIP: "1000"
2. Select payment method: "💵 Cash"
3. Verify button text changes
4. Click submit button

### Expected Results
✓ Form submission:
  - Button text: "Complete Purchase"
  - No modal appears (cash doesn't need modal)
  - Order is created immediately
  - Success message: "✅ Order placed successfully! Order ID: [ID]"
  - Redirects to homepage
  - Cart cleared from localStorage

### Actual Results
- [ ] Button shows "Complete Purchase"
- [ ] No modal displayed
- [ ] Order created in database
- [ ] Success message displays
- [ ] Redirect works
- [ ] Cart emptied

---

## Test Case 2: Credit Card Payment - Modal Display

### Preconditions
- Customer is on checkout page
- Cart contains items
- Customer details filled (use data from Test Case 1)

### Test Steps
1. Fill in all customer details (as in Test Case 1)
2. Select payment method: "💳 Credit Card"
3. Verify button text changes to "Pay through Credit Card"
4. Click submit button
5. Observe modal appearance

### Expected Results
✓ Credit Card Modal displays:
  - Modal title: "💳 Credit Card Payment"
  - Close button (X) visible
  - Modal overlay appears (dark background)
  - Form fields visible:
    * Card Number input (placeholder: "1234 5678 9012 3456")
    * Cardholder Name input (placeholder: "John Doe")
    * Expiry Date input (placeholder: "MM/YY")
    * CVV input (placeholder: "123")
  - Amount to Pay: "₱69.98"
  - "🔒 Your payment information is secure and encrypted" notice
  - Two buttons: "Cancel" and "Confirm Payment"

### Actual Results
- [ ] Modal appears with overlay
- [ ] All input fields visible
- [ ] Modal title correct
- [ ] Amount displays correctly (₱69.98)
- [ ] Close button works
- [ ] Secure notice displayed

---

## Test Case 3: Credit Card Validation - Valid Input

### Preconditions
- Test Case 2 completed
- Credit Card modal is open

### Test Steps
1. Enter Card Number: "4532123456789010"
2. Enter Cardholder Name: "John Doe"
3. Enter Expiry: "12/25"
4. Enter CVV: "123"
5. Click "Confirm Payment"
6. Verify form submission

### Expected Results
✓ Form validates and submits:
  - No error messages
  - Modal closes
  - Order is created
  - Success message displays
  - Redirects to homepage
  - All card details are captured in order record

### Actual Results
- [ ] All inputs accepted
- [ ] No validation errors
- [ ] Modal closes smoothly
- [ ] Order created
- [ ] Success message shows
- [ ] Redirect occurs

---

## Test Case 4: Credit Card Validation - Invalid Card Number

### Preconditions
- Credit Card modal open
- Other fields filled with valid data

### Test Steps
1. Enter Card Number: "1234" (only 4 digits - invalid)
2. Enter Cardholder Name: "John Doe"
3. Enter Expiry: "12/25"
4. Enter CVV: "123"
5. Click "Confirm Payment"

### Expected Results
✓ Validation error:
  - Alert appears: "Card number must be exactly 16 digits"
  - Modal stays open
  - Form not submitted
  - User can correct and retry

### Actual Results
- [ ] Error alert displays
- [ ] Correct error message shows
- [ ] Modal remains open
- [ ] Form not submitted
- [ ] No order created

---

## Test Case 5: Credit Card Validation - Invalid Expiry

### Preconditions
- Credit Card modal open
- Other fields valid

### Test Steps
1. Enter Card Number: "4532123456789010"
2. Enter Cardholder Name: "John Doe"
3. Enter Expiry: "13/25" (invalid month)
4. Enter CVV: "123"
5. Click "Confirm Payment"

### Expected Results
✓ Validation error:
  - Alert appears: "Invalid month in expiry date"
  - Modal stays open
  - User can correct and retry

### Actual Results
- [ ] Error message for invalid month
- [ ] Modal stays open
- [ ] Form not submitted

---

## Test Case 6: Credit Card Validation - Missing CVV

### Preconditions
- Credit Card modal open

### Test Steps
1. Fill all fields except CVV
2. Leave CVV empty
3. Click "Confirm Payment"

### Expected Results
✓ Validation error:
  - Alert: "CVV is required"
  - Modal stays open

### Actual Results
- [ ] Error message shows
- [ ] Modal remains open

---

## Test Case 7: GCash Payment - Modal Display

### Preconditions
- Customer on checkout page
- Cart has items
- Customer details filled

### Test Steps
1. Fill customer details
2. Select payment method: "📱 GCash"
3. Verify button text: "Pay through GCash"
4. Click submit

### Expected Results
✓ GCash Modal displays:
  - Modal title: "📱 GCash Payment"
  - GCash Mobile Number input (placeholder: "09123456789")
  - GCash PIN input (masked, placeholder: "••••")
  - Amount to Pay: "₱69.98"
  - Two buttons: "Cancel" and "Confirm Payment"

### Actual Results
- [ ] Modal appears correctly
- [ ] All fields visible
- [ ] Amount correct
- [ ] PIN field is password type (hidden)

---

## Test Case 8: GCash Validation - Valid Input

### Preconditions
- GCash modal open

### Test Steps
1. Enter GCash Number: "09123456789"
2. Enter GCash PIN: "1234"
3. Click "Confirm Payment"

### Expected Results
✓ Form validates and submits:
  - No error messages
  - Modal closes
  - Order created
  - Success message

### Actual Results
- [ ] Inputs accepted
- [ ] Modal closes
- [ ] Order created
- [ ] Success message

---

## Test Case 9: GCash Validation - Invalid Number Format

### Preconditions
- GCash modal open

### Test Steps
1. Enter GCash Number: "0912345" (only 7 digits)
2. Enter PIN: "1234"
3. Click "Confirm Payment"

### Expected Results
✓ Validation error:
  - Alert: "GCash number must be exactly 11 digits"
  - Modal stays open
  - User can retry

### Actual Results
- [ ] Error message correct
- [ ] Modal stays open
- [ ] Form not submitted

---

## Test Case 10: GCash Validation - Invalid PIN

### Preconditions
- GCash modal open

### Test Steps
1. Enter GCash Number: "09123456789"
2. Enter PIN: "12" (only 2 digits)
3. Click "Confirm Payment"

### Expected Results
✓ Validation error:
  - Alert: "GCash PIN must be exactly 4 digits"
  - Modal stays open

### Actual Results
- [ ] Error message shows
- [ ] Modal remains open

---

## Test Case 11: PayPal Payment - Modal Display

### Preconditions
- Customer on checkout page
- Cart has items
- Customer details filled

### Test Steps
1. Fill customer details
2. Select payment method: "🅿️ PayPal"
3. Verify button text: "Pay through PayPal"
4. Click submit

### Expected Results
✓ PayPal Modal displays:
  - Modal title: "🅿️ PayPal Payment"
  - PayPal Email/Username input (placeholder: "your.email@example.com")
  - Amount to Pay: "₱69.98"
  - Warning box: "ℹ️ You will be redirected to PayPal to complete your payment securely. Please don't close this window."
  - Two buttons: "Cancel" and "Continue to PayPal"

### Actual Results
- [ ] Modal displays correctly
- [ ] All content visible
- [ ] Warning message clear
- [ ] Amount correct

---

## Test Case 12: PayPal Validation - Valid Email

### Preconditions
- PayPal modal open

### Test Steps
1. Enter PayPal Email: "customer@paypal.com"
2. Click "Continue to PayPal"

### Expected Results
✓ Form validates:
  - No error message
  - Order created with PayPal email
  - Success message
  - Redirect occurs

### Actual Results
- [ ] Email accepted
- [ ] Order created
- [ ] Success message

---

## Test Case 13: PayPal Validation - Invalid Email

### Preconditions
- PayPal modal open

### Test Steps
1. Enter PayPal Email: "notanemail" (no @ symbol)
2. Click "Continue to PayPal"

### Expected Results
✓ Validation error:
  - Alert: "Please enter a valid email address"
  - Modal stays open
  - User can correct

### Actual Results
- [ ] Error message displays
- [ ] Modal remains open
- [ ] Form not submitted

---

## Test Case 14: PayPal Validation - Missing Email

### Preconditions
- PayPal modal open

### Test Steps
1. Leave email field empty
2. Click "Confirm Payment"

### Expected Results
✓ Validation error:
  - Alert: "PayPal email/username is required"
  - Modal stays open

### Actual Results
- [ ] Error shows
- [ ] Modal open for retry

---

## Test Case 15: Modal Closure - Cancel Button

### Preconditions
- Any payment modal open (Credit Card, GCash, or PayPal)

### Test Steps
1. Click "Cancel" button in modal
2. Observe modal behavior

### Expected Results
✓ Modal closes:
  - Modal disappears
  - Overlay disappears
  - User returns to checkout form
  - No order created
  - Form fields still filled

### Actual Results
- [ ] Modal closes smoothly
- [ ] Overlay removed
- [ ] No order created
- [ ] Can proceed with different payment method

---

## Test Case 16: Modal Closure - Close Button (X)

### Preconditions
- Any payment modal open

### Test Steps
1. Click X button (close button) on modal header
2. Observe modal behavior

### Expected Results
✓ Modal closes:
  - Modal disappears
  - Overlay disappears
  - No order created

### Actual Results
- [ ] Modal closes via X button
- [ ] Overlay removed

---

## Test Case 17: Button Text Dynamics

### Preconditions
- Checkout page with fresh form

### Test Steps
1. Initially, payment method not selected
2. Button shows: "Complete Purchase"
3. Select Cash → Button shows: "Complete Purchase"
4. Select Credit Card → Button shows: "Pay through Credit Card"
5. Select GCash → Button shows: "Pay through GCash"
6. Select PayPal → Button shows: "Pay through PayPal"
7. Select Cash again → Button shows: "Complete Purchase"

### Expected Results
✓ Button text changes dynamically:
  - Cash: "Complete Purchase"
  - Credit Card: "Pay through Credit Card"
  - GCash: "Pay through GCash"
  - PayPal: "Pay through PayPal"

### Actual Results
- [ ] Cash payment button text correct
- [ ] Credit Card button text correct
- [ ] GCash button text correct
- [ ] PayPal button text correct
- [ ] Dynamic changes work smoothly

---

## Test Case 18: Form Validation - Missing Customer Details

### Preconditions
- Customer on checkout page
- Some customer fields empty

### Test Steps
1. Leave Name field empty
2. Select payment method: "Cash"
3. Click submit

### Expected Results
✓ Browser validation:
  - HTML5 validation triggers
  - Error message: "Please fill out this field"
  - Form not submitted
  - User cannot proceed without all required fields

### Actual Results
- [ ] HTML5 validation triggers
- [ ] Error message shows
- [ ] Form submission blocked

---

## Test Case 19: Order Record - Payment Method Storage

### Preconditions
- Multiple orders created with different payment methods

### Test Steps
1. Complete order with Cash payment
2. Complete order with Credit Card payment
3. Complete order with GCash payment
4. Complete order with PayPal payment
5. Check database/admin panel for each order
6. Verify payment_method field is correct

### Expected Results
✓ Database contains:
  - Order 1: payment_method = "cash"
  - Order 2: payment_method = "credit_card"
  - Order 3: payment_method = "gcash"
  - Order 4: payment_method = "paypal"

### Actual Results
- [ ] Cash order recorded
- [ ] Credit Card order recorded
- [ ] GCash order recorded
- [ ] PayPal order recorded
- [ ] All payment methods correct in DB

---

## Test Case 20: Console Validation - No Errors

### Preconditions
- All previous tests completed
- Browser DevTools open (F12)

### Test Steps
1. Open Console tab
2. Perform all payment method workflows
3. Check for errors, warnings, deprecations
4. Verify all fetch requests are successful

### Expected Results
✓ Clean console:
  - No red errors
  - No undefined variables
  - No failed AJAX calls
  - All fetch requests return 200-201
  - CSRF tokens present
  - Proper headers set

### Actual Results
- [ ] No JavaScript errors
- [ ] No failed network requests
- [ ] Console clean
- [ ] CSRF tokens working
- [ ] Proper Content-Type headers

---

## Test Results Summary

| Test Case | Payment Method | Status | Notes |
|-----------|---|--------|-------|
| 1. Cash - Normal Flow | Cash | ☐ PASS ☐ FAIL | |
| 2. CC - Modal Display | Credit Card | ☐ PASS ☐ FAIL | |
| 3. CC - Valid Input | Credit Card | ☐ PASS ☐ FAIL | |
| 4. CC - Invalid Card # | Credit Card | ☐ PASS ☐ FAIL | |
| 5. CC - Invalid Expiry | Credit Card | ☐ PASS ☐ FAIL | |
| 6. CC - Missing CVV | Credit Card | ☐ PASS ☐ FAIL | |
| 7. GCash - Modal Display | GCash | ☐ PASS ☐ FAIL | |
| 8. GCash - Valid Input | GCash | ☐ PASS ☐ FAIL | |
| 9. GCash - Invalid Number | GCash | ☐ PASS ☐ FAIL | |
| 10. GCash - Invalid PIN | GCash | ☐ PASS ☐ FAIL | |
| 11. PayPal - Modal Display | PayPal | ☐ PASS ☐ FAIL | |
| 12. PayPal - Valid Email | PayPal | ☐ PASS ☐ FAIL | |
| 13. PayPal - Invalid Email | PayPal | ☐ PASS ☐ FAIL | |
| 14. PayPal - Missing Email | PayPal | ☐ PASS ☐ FAIL | |
| 15. Modal - Cancel Button | All | ☐ PASS ☐ FAIL | |
| 16. Modal - Close Button | All | ☐ PASS ☐ FAIL | |
| 17. Button Text Dynamics | All | ☐ PASS ☐ FAIL | |
| 18. Form Validation | All | ☐ PASS ☐ FAIL | |
| 19. Order Record Storage | All | ☐ PASS ☐ FAIL | |
| 20. Console Validation | All | ☐ PASS ☐ FAIL | |

---

## Summary by Payment Method

### Cash Payment
- Tests: 1, 17, 18, 20
- Expected Pass Rate: 100%
- Actual Pass Rate: ___%

### Credit Card Payment
- Tests: 2, 3, 4, 5, 6, 15, 16, 17, 18, 19, 20
- Expected Pass Rate: 100%
- Actual Pass Rate: ___%

### GCash Payment
- Tests: 7, 8, 9, 10, 15, 16, 17, 18, 19, 20
- Expected Pass Rate: 100%
- Actual Pass Rate: ___%

### PayPal Payment
- Tests: 11, 12, 13, 14, 15, 16, 17, 18, 19, 20
- Expected Pass Rate: 100%
- Actual Pass Rate: ___%

---

## Overall Result

**Total Tests**: 20  
**Passed**: ___  
**Failed**: ___  
**Pass Rate**: ___%

**Ready for Production**: ☐ YES ☐ NO

---

## Issues Found

### Critical Issues (Must Fix)
1. 
2. 

### Major Issues (Should Fix)
1. 
2. 

### Minor Issues (Nice to Fix)
1. 

---

## Sign-Off

**Tested By**: _______________  
**Date**: _______________  
**Approved By**: _______________  
**Date**: _______________

