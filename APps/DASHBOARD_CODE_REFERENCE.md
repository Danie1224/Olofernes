# Order Dashboard - Code Reference

## Controller Structure

### UserOrderItemController.php
Located: `app/Http/Controllers/UserOrderItemController.php`

```php
namespace App\Http\Controllers;

class UserOrderItemController extends Controller
{
    // GET /orders
    public function index(Request $request)
    // Returns: View with paginated orders, filterable by status
    // Retrieves: User's orders with related items and products
    
    // GET /orders/{orderId}
    public function show(string $orderId)
    // Returns: Order detail view with all items
    // Safety: Validates order belongs to user's customer_id
    
    // GET /orders-stats
    public function stats()
    // Returns: JSON with order statistics
    // Data: total_orders, total_spent, completed_orders, pending_orders
}
```

---

## Route Definitions

### In routes/web.php

```php
Route::middleware('auth')->group(function () {
    // User order dashboard
    Route::get('/orders', [UserOrderItemController::class, 'index'])
        ->name('user.orders.index');
    
    // Order details
    Route::get('/orders/{orderId}', [UserOrderItemController::class, 'show'])
        ->name('user.orders.show');
    
    // Stats endpoint (JSON)
    Route::get('/orders-stats', [UserOrderItemController::class, 'stats'])
        ->name('user.orders.stats');
});
```

---

## View Structure

### user-orders.blade.php (Dashboard)
```
📊 Statistics Section
├─ Total Orders Card
├─ Completed Orders Card
├─ Pending Orders Card
└─ Total Spent Card

🔍 Filter Section
└─ Status Filter Dropdown

📋 Orders List
├─ Order Card 1
│  ├─ Order ID & Date
│  ├─ Status Badge
│  ├─ Total Price
│  ├─ Item Count
│  └─ View Details Link
├─ Order Card 2
├─ Order Card 3
└─ ... (paginated)

📄 Pagination
└─ Page Navigation

Empty State (if no orders)
```

### order-details.blade.php (Details)
```
← Back Link

📦 Order Header
├─ Order ID
├─ Status Badge
├─ Order Date
├─ Payment Method
└─ Customer Name

📋 Order Items (Left Column)
├─ Item 1
│  ├─ Product Image
│  ├─ Product Name & SKU
│  ├─ Quantity & Unit Price
│  └─ Subtotal
├─ Item 2
└─ Item 3

📊 Order Summary (Right Column)
├─ Subtotal
├─ Total
├─ Shipping Address
├─ Payment Method Info
└─ Print & Back Buttons
```

---

## Data Models Used

### Order Model Relationship
```php
// app/Models/Order.php
public function customer()
{
    return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
}

public function orderItems()
{
    return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
}
```

### OrderItem Model Relationship
```php
// app/Models/OrderItem.php
public function order()
{
    return $this->belongsTo(Order::class, 'order_id', 'order_id');
}

public function product()
{
    return $this->belongsTo(Product::class, 'product_id', 'product_id');
}
```

### Customer Model Relationship
```php
// app/Models/Customer.php
public function user()
{
    return $this->belongsTo(User::class, 'email', 'email');
}
```

---

## Database Schema Used

### orders table
```
order_id (PK)
customer_id (FK)
product_id (FK)
admin_id (FK)
quantity
total_price
payment_method (cash, card, gcash, paypal)
status (pending, processing, shipped, completed, cancelled)
order_date
created_at
updated_at
```

### order_items table
```
order_item_id (PK)
order_id (FK)
product_id (FK)
quantity
unit_price
subtotal
created_at
updated_at
```

### customers table
```
customer_id (PK)
name
email
phone
address
city
state
zip_code
country
date_of_birth
gender
status
created_at
updated_at
```

---

## Key Queries Used

### Fetch User's Orders
```php
$user = Auth::user();
$customer = Customer::where('email', $user->email)->first();

$orders = Order::where('customer_id', $customer->customer_id)
    ->with(['orderItems.product', 'customer'])
    ->latest('order_date')
    ->paginate(10);
```

### Filter by Status
```php
$orders = Order::where('customer_id', $customer->customer_id)
    ->where('status', $request->status)
    ->with(['orderItems.product', 'customer'])
    ->latest('order_date')
    ->paginate(10);
```

### Get Single Order (with authorization)
```php
$order = Order::where('order_id', $orderId)
    ->where('customer_id', $customer->customer_id)
    ->with(['orderItems.product', 'customer'])
    ->firstOrFail();
```

### Calculate Statistics
```php
$totalOrders = Order::where('customer_id', $customer->customer_id)->count();
$totalSpent = Order::where('customer_id', $customer->customer_id)->sum('total_price');
$completedOrders = Order::where('customer_id', $customer->customer_id)
    ->where('status', 'completed')->count();
$pendingOrders = Order::where('customer_id', $customer->customer_id)
    ->where('status', 'pending')->count();
```

---

## API Response Example

### GET /orders-stats
```json
{
  "total_orders": 5,
  "total_spent": 1299.95,
  "completed_orders": 3,
  "pending_orders": 1
}
```

---

## Authentication Flow

```
User logs in
  ↓
Laravel authenticates via Auth::user()
  ↓
All routes protected by 'auth' middleware
  ↓
Controller gets Auth::user()->email
  ↓
Find Customer with matching email
  ↓
Query Orders where customer_id = found customer
  ↓
Return only user's orders
```

---

## Frontend JavaScript

### Load Statistics on Page Load
```javascript
document.addEventListener('DOMContentLoaded', function() {
    fetch('/orders-stats')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-orders').textContent = data.total_orders;
            document.getElementById('completed-orders').textContent = data.completed_orders;
            document.getElementById('pending-orders').textContent = data.pending_orders;
            document.getElementById('total-spent').textContent = '$' + parseFloat(data.total_spent).toFixed(2);
        })
        .catch(error => console.error('Error loading stats:', error));
});
```

### Status Filter
```html
<select name="status" onchange="this.form.submit()">
    <option value="">All Statuses</option>
    <option value="pending">Pending</option>
    <option value="processing">Processing</option>
    <option value="shipped">Shipped</option>
    <option value="completed">Completed</option>
    <option value="cancelled">Cancelled</option>
</select>
```

### Print Functionality
```javascript
window.print();
```

---

## CSS Styling Highlights

### Statistics Cards
- Gradient backgrounds (purple, green, blue, orange)
- Responsive grid layout
- Hover shadow effects

### Order Cards
- Clean white backgrounds
- Border effects
- Hover elevation
- Mobile-responsive grid

### Status Badges
- Color-coded by status
- Consistent styling
- Readable text

### Responsive Design
- Desktop: Full layout
- Tablet: Adjusted columns
- Mobile: Stacked layout

---

## Performance Considerations

✅ **Optimizations Included**
- Pagination (10 items per page)
- Eager loading (with(['orderItems.product', 'customer']))
- Latest ordering (newest orders first)
- Efficient queries (no N+1 problems)

⚡ **For Large Datasets**
- Pagination limits query results
- Eager loading reduces queries
- Status filtering reduces results
- Indexes on foreign keys (customer_id, order_id)

---

## Error Handling

### User Not Found
```php
$customer = Customer::where('email', $user->email)->first();
if (!$customer) {
    return view('orders.user-orders', ['orders' => collect()]);
}
```

### Order Not Found
```php
$order = Order::where('order_id', $orderId)
    ->where('customer_id', $customer->customer_id)
    ->with(['orderItems.product', 'customer'])
    ->firstOrFail();  // Throws 404 if not found
```

### Authorization Check
```php
// Ensures order belongs to customer
->where('customer_id', $customer->customer_id)
```

---

**Last Updated:** November 25, 2025  
**Status:** Complete and Production Ready
