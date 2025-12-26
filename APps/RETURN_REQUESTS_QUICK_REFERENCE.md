# Return Request System - Quick Reference

## 📍 File Structure

```
app/Http/Controllers/
└── UserReturnRequestController.php (NEW)
    ├── index()
    ├── create()
    ├── store()
    ├── show()
    ├── cancel()
    └── stats()

resources/views/returns/
├── index.blade.php (Dashboard)
├── create.blade.php (Form)
└── show.blade.php (Details)

app/Models/
├── ReturnRequest.php (ENHANCED)
└── ReturnRequestItem.php (Enhanced)
```

---

## 🔗 Routes at a Glance

| Method | Route | Name | Purpose |
|--------|-------|------|---------|
| GET | `/returns` | `returns.index` | View all returns |
| GET | `/returns/create` | `returns.create` | Show form |
| POST | `/returns` | `returns.store` | Submit return |
| GET | `/returns/{id}` | `returns.show` | View details |
| DELETE | `/returns/{id}` | `returns.cancel` | Cancel request |
| GET | `/returns-stats` | `returns.stats` | Get stats JSON |

---

## 🎯 Controller Methods

### index()
```php
// Displays dashboard with filtering
Route::get('/returns', [UserReturnRequestController::class, 'index'])
     ->name('returns.index');
```

### create()
```php
// Shows form with user's completed orders
Route::get('/returns/create', [UserReturnRequestController::class, 'create'])
     ->name('returns.create');
```

### store()
```php
// Creates new return request
Route::post('/returns', [UserReturnRequestController::class, 'store'])
     ->name('returns.store');
```

### show()
```php
// Shows return details
Route::get('/returns/{requestId}', [UserReturnRequestController::class, 'show'])
     ->name('returns.show');
```

### cancel()
```php
// Cancels pending return
Route::delete('/returns/{requestId}', [UserReturnRequestController::class, 'cancel'])
     ->name('returns.cancel');
```

### stats()
```php
// Returns JSON statistics
Route::get('/returns-stats', [UserReturnRequestController::class, 'stats'])
     ->name('returns.stats');
```

---

## 🔑 Key Models & Relationships

### ReturnRequest
```php
class ReturnRequest extends Model {
    public function customer() { return $this->belongsTo(Customer::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function items() { return $this->hasMany(ReturnRequestItem::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function admin() { return $this->belongsTo(Admin::class); }
}
```

### ReturnRequestItem
```php
class ReturnRequestItem extends Model {
    public function returnRequest() { return $this->belongsTo(ReturnRequest::class); }
    public function orderItem() { return $this->belongsTo(OrderItem::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
```

---

## 🎨 Views Created

### 1. Dashboard (returns.index)
- Statistics cards
- Filter buttons
- Return request cards
- Pagination
- Empty state

### 2. Create Form (returns.create)
- Order selector
- Item checkboxes
- Quantity inputs
- Reason dropdowns
- Notes textarea
- Form validation

### 3. Details (returns.show)
- Header with status
- Item table
- Order info
- Timeline
- Action buttons

---

## 📊 Status Values

```
pending   → Initial state
approved  → Accepted for return
rejected  → Not accepted
refunded  → Refund processed
```

---

## 🔐 Authorization

All methods check:
```php
$customer = Customer::where('user_id', Auth::id())->first();
// Only show/modify own returns
```

---

## ✅ Validation Rules

```php
'order_id' => 'required|exists:orders,order_id',
'items' => 'required|array|min:1',
'items.*.order_item_id' => 'required|exists:order_items,order_item_id',
'items.*.quantity' => 'required|integer|min:1',
'items.*.reason' => 'required|string|max:255',
'items.*.notes' => 'nullable|string|max:500',
```

---

## 🔍 Query Examples

```php
// Get user's pending returns
$pending = ReturnRequest::where('customer_id', $customerId)
    ->where('status', 'pending')
    ->get();

// Get return items for a request
$items = ReturnRequest::find($requestId)->items;

// Calculate total refund
$total = ReturnRequest::find($requestId)->items->sum('refund_amount');

// Get recent returns (last 7 days)
$recent = ReturnRequest::where('created_at', '>=', now()->subDays(7))->get();
```

---

## 🎯 Common Tasks

### Display Return in Dashboard
```blade
@foreach($returnRequests as $request)
    <div class="return-card">
        <h3>Return Request #{{ $request->request_id }}</h3>
        <p>Status: {{ ucfirst($request->status) }}</p>
        <p>Items: {{ $request->items->count() }}</p>
        <p>Refund: ₱{{ $request->items->sum('refund_amount') }}</p>
    </div>
@endforeach
```

### Check if User Has Pending Returns
```php
$hasPending = ReturnRequest::where('customer_id', $customerId)
    ->where('status', 'pending')
    ->exists();
```

### Update Return Status
```php
$return = ReturnRequest::find($requestId);
$return->update(['status' => 'approved']);
```

---

## 📱 Responsive Breakpoints

- Desktop: Full grid layout
- Tablet: Adjusted grid (2-3 columns)
- Mobile: Single column layout
- All buttons touch-friendly

---

## 🎯 URL Paths

```
/returns                    → Dashboard
/returns/create            → Create form
/returns/1                 → Details
/returns-stats            → JSON stats
```

---

## 🔄 Return Flow

```
User Creates Request
    ↓
Creates ReturnRequest record
    ↓
Creates ReturnRequestItem records
    ↓
Stored as "pending"
    ↓
Admin Reviews & Updates Status
    ↓
User Sees Update in Dashboard
    ↓
Process Complete
```

---

## 💡 Tips

1. **Quantity Validation**: System prevents returning more than ordered
2. **Auto Refund**: Refund amount calculated automatically (unit_price × qty)
3. **Delete Cascade**: Deleting return auto-deletes items
4. **User Isolation**: Users only see their own returns
5. **Timestamps**: All requests auto-timestamped

---

## 🧪 Quick Test

```php
// In tinker
$user = User::first();
$customer = Customer::where('user_id', $user->id)->first();
$returns = ReturnRequest::where('customer_id', $customer->customer_id)->get();
$returns->count(); // Should show number of returns
```

---

## 📚 Documentation Files

1. `RETURN_REQUEST_ITEMS_README.md` - Complete documentation
2. `RETURN_REQUEST_IMPLEMENTATION_SUMMARY.md` - Implementation summary
3. This file - Quick reference

---

**Version:** 1.0  
**Status:** Production Ready  
**Database Changes:** None
