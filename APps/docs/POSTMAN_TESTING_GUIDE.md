# Techstore API Testing Guide (Postman)

## Prerequisites
1. Start the Laravel server:
```powershell
cd C:\Users\ERICA\Downloads\ITE--18\ITE--18\APps
php artisan serve
```

2. Import Postman files:
- Import `Techstore_Postman_Collection.json`
- Import `Techstore_Postman_Environment.json`
- Select "Techstore Local" environment

3. Get Authentication Token:

POST http://127.0.0.1:8000/api/techstore/register
BOdy
{
"name": "Alice Example",
"email": "alice@example.test",
"password": "secret123",
"password_confirmation": "secret123"
}
```

POST http://127.0.0.1:8000/api/techstore/login
Body:
{
    "email": "your.email@example.com",
    "password": "your-password"
}
```
Copy the returned token to your Postman environment variable `token`.

## 1. Customers Testing Flow

### 1.1 List Customers (Public)
```
GET http://127.0.0.1:8000/api/techstore/customers
Headers:
- Accept: application/json
```

### 1.2 Create Customer (Protected)
```
POST http://127.0.0.1:8000/api/techstore/customers
Headers:
- Authorization: Bearer {{token}}
- Content-Type: application/json
- Accept:    application/json

Body:
{
    "customer_id": "CUST-1001",
    "name": "Alice Example",
    "email": "alice@example.test",
    "phone": "555-0101",
    "address": "123 Main St",
    "city": "Metropolis",
    "state": "State",
    "zip_code": "12345",
    "country": "Country",
    "date_of_birth": "1990-01-01",
    "gender": "female",
    "status": "active"
}
```

## 2. Products Testing Flow

### 2.1 List Products (Public)
```
GET http://127.0.0.1:8000/api/techstore/products
Headers:
- Accept: application/json
```

### 2.2 Create Product (Protected)
```
POST http://127.0.0.1:8000/api/techstore/products
Headers:
- Authorization: Bearer {{token}}
- Content-Type: application/json

Body:
{
    "product_code": "PRD-002",
    "name": "USB-C Charger",
    "description": "Fast charger 45W",
    "price": 29.99,
    "stock_quantity": 100,
    "category": "Chargers",
    "admin_id": 1,
    "brand_id": 3
}
```

### 2.3 Update Product (Protected)
```
PUT http://127.0.0.1:8000/api/techstore/products/{{product_id}}
Headers:
- Authorization: Bearer {{token}}
- Content-Type: application/json

Body:
{
    "name": "USB-C Charger (Updated)",
    "price": 34.99,
    "stock_quantity": 95
}
```

## 3. Orders Testing Flow

### 3.1 List Orders (Public)
```
GET http://127.0.0.1:8000/api/techstore/orders
```

### 3.2 Create Order (Protected)
```
POST http://127.0.0.1:8000/api/techstore/orders
Headers:
- Authorization: Bearer {{token}}
- Content-Type: application/json

Body:
{
    "customer_id": "CUST-1001",
    "product_id": 1,
    "quantity": 2,
    "total_price": 59.98,
    "status": "pending",
    "payment_method": "credit_card",
    "order_date": "2025-10-29T10:00:00Z"
}
```

## 4. Order Items Testing Flow

### 4.1 List Order Items (Public)
```
GET http://127.0.0.1:8000/api/techstore/order-items
```

### 4.2 Create Order Item (Protected)
```
POST http://127.0.0.1:8000/api/techstore/order-items
Headers:
- Authorization: Bearer {{token}}
- Content-Type: application/json

Body:
{
    "order_id": 1,
    "product_id": 1,
    "quantity": 2,
    "unit_price": 29.99,
    "subtotal": 59.98
}
```

## 5. Shipping Testing Flow

### 5.1 List Shippings (Public)
```
GET http://127.0.0.1:8000/api/techstore/shippings
```

### 5.2 Create Shipping (Protected)
```
POST http://127.0.0.1:8000/api/techstore/shippings
Headers:
- Authorization: Bearer {{token}}
- Content-Type: application/json

Body:
{
    "order_id": 1,
    "address": "123 Delivery St",
    "city": "Metropolis",
    "state": "State",
    "zip_code": "12345",
    "country": "USA",
    "tracking_number": "SHIP-123456",
    "shipping_status": "pending"
}
```

## 6. Vouchers Testing Flow

### 6.1 List Vouchers (Public)
```
GET http://127.0.0.1:8000/api/techstore/vouchers
```

### 6.2 Create Voucher (Protected)
```
POST http://127.0.0.1:8000/api/techstore/vouchers
Headers:
- Authorization: Bearer {{token}}
- Content-Type: application/json

Body:
{
    "code": "SAVE20",
    "description": "20% off on all items",
    "discount_type": "percentage",
    "discount_value": 20.00,
    "usage_limit": 100,
    "usage_count": 0,
    "start_date": "2025-10-29",
    "end_date": "2025-12-31",
    "status": "active"
}
```

## 7. Add to Cart Testing Flow

### 7.1 List Cart Items (Public)
```
GET http://127.0.0.1:8000/api/techstore/add-to-cart
```

### 7.2 Add Item to Cart (Protected)
```
POST http://127.0.0.1:8000/api/techstore/add-to-cart
Headers:
- Authorization: Bearer {{token}}
- Content-Type: application/json

Body:
{
    "customer_id": "CUST-1001",
    "product_id": 2,
    "quantity": 1
}
```

## Testing Order (Recommended Flow)
1. Register/Login to get token
2. Create a customer
3. Create a product
4. Add product to cart
5. Create an order
6. Create order items for the order
7. Create shipping for the order
8. Create a voucher (optional)

## Common Issues & Solutions

### 401 Unauthorized
- Check if token is set in environment
- Verify token hasn't expired
- Try logging in again to get a new token

### 404 Not Found
- Verify server is running (`php artisan serve`)
- Check URL is correct (should start with http://127.0.0.1:8000/api/techstore/)
- Verify resource ID exists when updating/showing single items

### 422 Validation Error
- Check required fields are provided
- Verify data types (numbers, dates, etc.)
- Ensure relationships exist (e.g., customer_id exists when creating order)

## Environment Variables
Make sure these are set in your Postman environment:
- base_url: http://127.0.0.1:8000/api/techstore
- token: (your auth token)
- product_id: (for testing single product operations)
- voucher_id: (for testing single voucher operations)