<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\AddToCartController;
use App\Http\Controllers\TechstoreController;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ReturnRequestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;

use App\Http\Controllers\CheckoutController;

Route::prefix('techstore')->as('api.techstore.')->group(function () {
    // Root index for techstore
    Route::get('/', [TechstoreController::class, 'index'])->name('root');
    // curl -X GET http://127.0.0.1:8000/api/techstore/ \
    //      -H "Accept: application/json"
    
    // API auth routes for Postman (register/login) — create tokens on success
    Route::post('register', [ApiAuthController::class, 'register'])->name('auth.register');
    // curl -X POST http://127.0.0.1:8000/api/techstore/register \
    //      -H "Content-Type: application/json" \
    //      -d '{"name":"John Doe","email":"john@example.test","password":"secret123","password_confirmation":"secret123"}'
    
    Route::post('login', [ApiAuthController::class, 'login'])->name('auth.login');
    // curl -X POST http://127.0.0.1:8000/api/techstore/login \
    //      -H "Content-Type: application/json" \
    //      -d '{"email":"john@example.test","password":"secret123"}'
    
    Route::post('register-admin', [ApiAuthController::class, 'registerAdmin'])->name('auth.register-admin');
    // curl -X POST http://127.0.0.1:8000/api/techstore/register-admin \
    //      -H "Content-Type: application/json" \
    //      -d '{"name":"Admin User","email":"admin@example.test","password":"secret123","password_confirmation":"secret123"}'
    
    Route::post('admin-login', [ApiAuthController::class, 'adminLogin'])->name('auth.admin-login');
    // curl -X POST http://127.0.0.1:8000/api/techstore/admin-login \
    //      -H "Content-Type: application/json" \
    //      -d '{"email":"admin@example.test","password":"secret123"}'

    // PUBLIC READ ENDPOINTS (No authentication required)
    
    Route::apiResource('customers', CustomerController::class)->only(['index','show']);
    // GET: curl -X GET http://127.0.0.1:8000/api/techstore/customers \
    //           -H "Accept: application/json"
    // GET (single): curl -X GET http://127.0.0.1:8000/api/techstore/customers/CUST-001 \
    //                    -H "Accept: application/json"
    
    Route::apiResource('products', ProductController::class)->only(['index','show']);
    // GET: curl -X GET http://127.0.0.1:8000/api/techstore/products \
    //           -H "Accept: application/json"
    // GET (single): curl -X GET http://127.0.0.1:8000/api/techstore/products/1 \
    //                    -H "Accept: application/json"
    
    Route::get('orders/{orderId}', [OrderController::class, 'show'])->name('orders.show.public');
    // curl -X GET http://127.0.0.1:8000/api/techstore/orders/1 \
    //      -H "Accept: application/json"
    
    Route::apiResource('order-items', OrderItemController::class)->only(['index','show']);
    // GET: curl -X GET http://127.0.0.1:8000/api/techstore/order-items \
    //           -H "Accept: application/json"
    // GET (single): curl -X GET http://127.0.0.1:8000/api/techstore/order-items/1 \
    //                    -H "Accept: application/json"
    
    Route::apiResource('shippings', ShippingController::class)->only(['index','show']);
    // GET: curl -X GET http://127.0.0.1:8000/api/techstore/shippings \
    //           -H "Accept: application/json"
    // GET (single): curl -X GET http://127.0.0.1:8000/api/techstore/shippings/1 \
    //                    -H "Accept: application/json"
    
    Route::apiResource('vouchers', VoucherController::class)->only(['index','show']);
    // GET: curl -X GET http://127.0.0.1:8000/api/techstore/vouchers \
    //           -H "Accept: application/json"
    // GET (single): curl -X GET http://127.0.0.1:8000/api/techstore/vouchers/1 \
    //                    -H "Accept: application/json"
    
    Route::apiResource('brands', BrandController::class)->only(['index','show']);
    // GET: curl -X GET http://127.0.0.1:8000/api/techstore/brands \
    //           -H "Accept: application/json"
    // GET (single): curl -X GET http://127.0.0.1:8000/api/techstore/brands/1 \
    //                    -H "Accept: application/json"
    
    Route::get('vouchers/{voucherId}/users', [VoucherController::class, 'getVoucherUsers'])->name('vouchers.users');
    // curl -X GET http://127.0.0.1:8000/api/techstore/vouchers/1/users \
    //      -H "Accept: application/json"
    
    Route::apiResource('return-requests', ReturnRequestController::class)->only(['index','show']);
    // GET: curl -X GET http://127.0.0.1:8000/api/techstore/return-requests \
    //           -H "Accept: application/json"
    // GET (single): curl -X GET http://127.0.0.1:8000/api/techstore/return-requests/1 \
    //                    -H "Accept: application/json"
    
    Route::get('return-requests-stats', [ReturnRequestController::class, 'stats'])->name('return-requests.stats');
    // curl -X GET http://127.0.0.1:8000/api/techstore/return-requests-stats \
    //      -H "Accept: application/json"

    // PROTECTED ROUTES (Require authentication: Bearer token)
    Route::middleware('auth:sanctum')->group(function () {
        // User routes
        Route::get('get-user', [ApiAuthController::class, 'user'])->name('get-user');
        // curl -X GET http://127.0.0.1:8000/api/techstore/get-user \
        //      -H "Authorization: Bearer YOUR_TOKEN" \
        //      -H "Accept: application/json"
        
        Route::post('logout', [ApiAuthController::class, 'logout'])->name('logout');
        // curl -X POST http://127.0.0.1:8000/api/techstore/logout \
        //      -H "Authorization: Bearer YOUR_TOKEN" \
        //      -H "Accept: application/json"
        
        Route::get('/user', [TechstoreController::class, 'user'])->name('user');
        // curl -X GET http://127.0.0.1:8000/api/techstore/user \
        //      -H "Authorization: Bearer YOUR_TOKEN" \
        //      -H "Accept: application/json"
        
        // Orders - customer can only see their own orders
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        // curl -X GET http://127.0.0.1:8000/api/techstore/orders \
        //      -H "Authorization: Bearer YOUR_TOKEN" \
        //      -H "Accept: application/json"
        
        // Order cancellation endpoint
        Route::post('orders/{orderId}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        // curl -X POST http://127.0.0.1:8000/api/techstore/orders/1/cancel \
        //      -H "Authorization: Bearer YOUR_TOKEN" \
        //      -H "Accept: application/json"
        
        // Cart routes (all operations require authentication)
        Route::apiResource('add-to-cart', AddToCartController::class);
        // GET: curl -X GET http://127.0.0.1:8000/api/techstore/add-to-cart \
        //           -H "Authorization: Bearer YOUR_TOKEN" \
        //           -H "Accept: application/json"
        // POST: curl -X POST http://127.0.0.1:8000/api/techstore/add-to-cart \
        //            -H "Authorization: Bearer YOUR_TOKEN" \
        //            -H "Content-Type: application/json" \
        //            -d '{"customer_id":"CUST-001","product_id":1,"quantity":2}'
        
        // Protected write endpoints
        Route::apiResource('customers', CustomerController::class)->only(['store','update','destroy']);
        // POST: curl -X POST http://127.0.0.1:8000/api/techstore/customers \
        //            -H "Authorization: Bearer YOUR_TOKEN" \
        //            -H "Content-Type: application/json" \
        //            -d '{"customer_id":"CUST-001","name":"John Doe","email":"john@example.test","phone":"555-1234","address":"123 Main St","city":"Metropolis","state":"State","zip_code":"12345","country":"USA","gender":"male","status":"active"}'
        // PUT: curl -X PUT http://127.0.0.1:8000/api/techstore/customers/CUST-001 \
        //           -H "Authorization: Bearer YOUR_TOKEN" \
        //           -H "Content-Type: application/json" \
        //           -d '{"name":"Jane Doe","phone":"555-5678"}'
        // DELETE: curl -X DELETE http://127.0.0.1:8000/api/techstore/customers/CUST-001 \
        //              -H "Authorization: Bearer YOUR_TOKEN" \
        //              -H "Accept: application/json"
        
        Route::apiResource('products', ProductController::class)->only(['store','update','destroy']);
        // POST: curl -X POST http://127.0.0.1:8000/api/techstore/products \
        //            -H "Authorization: Bearer YOUR_TOKEN" \
        //            -H "Content-Type: application/json" \
        //            -d '{"product_code":"PRD-001","name":"Wireless Mouse","description":"High precision mouse","price":29.99,"stock_quantity":100,"category":"Accessory","admin_id":1}'
        // PUT: curl -X PUT http://127.0.0.1:8000/api/techstore/products/1 \
        //           -H "Authorization: Bearer YOUR_TOKEN" \
        //           -H "Content-Type: application/json" \
        //           -d '{"name":"Wireless Mouse Pro","price":39.99,"stock_quantity":95}'
        // DELETE: curl -X DELETE http://127.0.0.1:8000/api/techstore/products/1 \
        //              -H "Authorization: Bearer YOUR_TOKEN" \
        //              -H "Accept: application/json"
        
        Route::apiResource('orders', OrderController::class)->only(['store','update','destroy']);
        // POST: curl -X POST http://127.0.0.1:8000/api/techstore/orders \
        //            -H "Authorization: Bearer YOUR_TOKEN" \
        //            -H "Content-Type: application/json" \
        //            -d '{"customer_id":"CUST-001","product_id":1,"admin_id":1,"quantity":2,"total_price":4999.98,"payment_method":"cash","status":"pending"}'
        // PUT: curl -X PUT http://127.0.0.1:8000/api/techstore/orders/1 \
        //           -H "Authorization: Bearer YOUR_TOKEN" \
        //           -H "Content-Type: application/json" \
        //           -d '{"status":"processing"}'
        // DELETE: curl -X DELETE http://127.0.0.1:8000/api/techstore/orders/1 \
        //              -H "Authorization: Bearer YOUR_TOKEN" \
        //              -H "Accept: application/json"
        
        Route::apiResource('order-items', OrderItemController::class)->only(['store','update','destroy']);
        // POST: curl -X POST http://127.0.0.1:8000/api/techstore/order-items \
        //            -H "Authorization: Bearer YOUR_TOKEN" \
        //            -H "Content-Type: application/json" \
        //            -d '{"order_id":1,"product_id":2,"quantity":3,"unit_price":29.99,"subtotal":89.97}'
        // PUT: curl -X PUT http://127.0.0.1:8000/api/techstore/order-items/1 \
        //           -H "Authorization: Bearer YOUR_TOKEN" \
        //           -H "Content-Type: application/json" \
        //           -d '{"quantity":5,"subtotal":149.95}'
        // DELETE: curl -X DELETE http://127.0.0.1:8000/api/techstore/order-items/1 \
        //              -H "Authorization: Bearer YOUR_TOKEN" \
        //              -H "Accept: application/json"
        
        Route::apiResource('shippings', ShippingController::class)->only(['store','update','destroy']);
        // POST: curl -X POST http://127.0.0.1:8000/api/techstore/shippings \
        //            -H "Authorization: Bearer YOUR_TOKEN" \
        //            -H "Content-Type: application/json" \
        //            -d '{"order_id":1,"address":"123 Delivery St","city":"Metropolis","state":"State","zip_code":"12345","country":"USA","shipping_status":"pending"}'
        // PUT: curl -X PUT http://127.0.0.1:8000/api/techstore/shippings/1 \
        //           -H "Authorization: Bearer YOUR_TOKEN" \
        //           -H "Content-Type: application/json" \
        //           -d '{"shipping_status":"shipped","tracking_number":"SHIP-789012"}'
        // DELETE: curl -X DELETE http://127.0.0.1:8000/api/techstore/shippings/1 \
        //              -H "Authorization: Bearer YOUR_TOKEN" \
        //              -H "Accept: application/json"
        
        Route::apiResource('vouchers', VoucherController::class)->only(['store','update','destroy']);
        // POST: curl -X POST http://127.0.0.1:8000/api/techstore/vouchers \
        //            -H "Authorization: Bearer YOUR_TOKEN" \
        //            -H "Content-Type: application/json" \
        //            -d '{"code":"SAVE20","description":"20% off","discount_type":"percentage","discount_value":20,"usage_limit":100,"start_date":"2025-12-22","end_date":"2025-12-31","status":"active"}'
        // PUT: curl -X PUT http://127.0.0.1:8000/api/techstore/vouchers/1 \
        //           -H "Authorization: Bearer YOUR_TOKEN" \
        //           -H "Content-Type: application/json" \
        //           -d '{"status":"inactive"}'
        // DELETE: curl -X DELETE http://127.0.0.1:8000/api/techstore/vouchers/1 \
        //              -H "Authorization: Bearer YOUR_TOKEN" \
        //              -H "Accept: application/json"
        
        // Voucher claim endpoint
        Route::post('vouchers/claim', [VoucherController::class, 'claim'])->name('vouchers.claim');
        // curl -X POST http://127.0.0.1:8000/api/techstore/vouchers/claim \
        //      -H "Authorization: Bearer YOUR_TOKEN" \
        //      -H "Content-Type: application/json" \
        //      -d '{"voucher_code":"SAVE20"}'
        
        // User voucher endpoints
        Route::get('user/vouchers', [VoucherController::class, 'getUserVouchers'])->name('user.vouchers');
        // curl -X GET http://127.0.0.1:8000/api/techstore/user/vouchers \
        //      -H "Authorization: Bearer YOUR_TOKEN" \
        //      -H "Accept: application/json"
        
        Route::delete('user/vouchers/{userVoucherId}', [VoucherController::class, 'removeUserVoucher'])->name('user.vouchers.remove');
        // curl -X DELETE http://127.0.0.1:8000/api/techstore/user/vouchers/1 \
        //      -H "Authorization: Bearer YOUR_TOKEN" \
        //      -H "Accept: application/json"
        
        // Checkout API endpoint
        Route::post('checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
        // curl -X POST http://127.0.0.1:8000/api/techstore/checkout \
        //      -H "Authorization: Bearer YOUR_TOKEN" \
        //      -H "Content-Type: application/json" \
        //      -d '{"customer_id":"CUST-001","items":[{"product_id":1,"quantity":2,"price":2499.99}],"total_price":4999.98,"payment_method":"card","shipping_address":"123 Main St","shipping_city":"Metropolis","shipping_state":"State","shipping_zip":"12345","shipping_country":"USA"}'
        
        // Return request protected endpoints
        Route::post('return-requests', [ReturnRequestController::class, 'store'])->name('return-requests.store');
        // curl -X POST http://127.0.0.1:8000/api/techstore/return-requests \
        //      -H "Authorization: Bearer YOUR_TOKEN" \
        //      -H "Content-Type: application/json" \
        //      -d '{"order_id":1,"customer_id":"CUST-001","product_id":1,"reason":"Product arrived damaged","status":"pending"}'
        
        Route::post('return-requests/{requestId}/approve', [ReturnRequestController::class, 'approve'])->name('return-requests.approve');
        // curl -X POST http://127.0.0.1:8000/api/techstore/return-requests/1/approve \
        //      -H "Authorization: Bearer YOUR_TOKEN" \
        //      -H "Accept: application/json"
        
        Route::post('return-requests/{requestId}/reject', [ReturnRequestController::class, 'reject'])->name('return-requests.reject');
        // curl -X POST http://127.0.0.1:8000/api/techstore/return-requests/1/reject \
        //      -H "Authorization: Bearer YOUR_TOKEN" \
        //      -H "Accept: application/json"
        
        Route::post('return-requests/{requestId}/refund', [ReturnRequestController::class, 'refund'])->name('return-requests.refund');
        // curl -X POST http://127.0.0.1:8000/api/techstore/return-requests/1/refund \
        //      -H "Authorization: Bearer YOUR_TOKEN" \
        //      -H "Accept: application/json"
        
        Route::delete('return-requests/{requestId}', [ReturnRequestController::class, 'cancel'])->name('return-requests.cancel');
        // curl -X DELETE http://127.0.0.1:8000/api/techstore/return-requests/1 \
        //      -H "Authorization: Bearer YOUR_TOKEN" \
        //      -H "Accept: application/json"
        
        // Order completion endpoints (admin only)
        Route::post('orders/{orderId}/complete', [AdminController::class, 'completeOrder'])->name('orders.complete');
        // curl -X POST http://127.0.0.1:8000/api/techstore/orders/1/complete \
        //      -H "Authorization: Bearer ADMIN_TOKEN" \
        //      -H "Accept: application/json"
        
        Route::get('orders/{orderId}/completion-status', [AdminController::class, 'getOrderCompletionStatus'])->name('orders.completion-status');
        // curl -X GET http://127.0.0.1:8000/api/techstore/orders/1/completion-status \
        //      -H "Authorization: Bearer ADMIN_TOKEN" \
        //      -H "Accept: application/json"
    });
});


