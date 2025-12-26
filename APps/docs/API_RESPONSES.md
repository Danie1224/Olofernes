# Example API Responses

## Authentication

### Login Success
```json
{
    "user": {
        "id": 1,
        "name": "Alice Example",
        "email": "alice+api@example.test",
        "created_at": "2025-10-29T00:00:00.000000Z",
        "updated_at": "2025-10-29T00:00:00.000000Z"
    },
    "token": "1|laravel_sanctum_token..."
}
```

### Register Success
```json
{
    "user": {
        "name": "Alice Example",
        "email": "alice+api@example.test",
        "updated_at": "2025-10-29T10:00:00.000000Z",
        "created_at": "2025-10-29T10:00:00.000000Z",
        "id": 1
    },
    "token": "2|laravel_sanctum_token..."
}
```

## Products

### List Products
```json
{
    "data": [
        {
            "product_id": 1,
            "product_code": "PRD-001",
            "name": "USB-C Charger",
            "description": "Fast charger 45W",
            "price": "29.99",
            "stock_quantity": 100,
            "category": "Chargers",
            "brand_id": 3,
            "created_at": "2025-10-29T00:00:00.000000Z",
            "updated_at": "2025-10-29T00:00:00.000000Z"
        }
    ]
}
```

### Create Product Success
```json
{
    "data": {
        "product_id": 2,
        "product_code": "PRD-002",
        "name": "Wireless Mouse",
        "description": "Ergonomic wireless mouse",
        "price": "25.00",
        "stock_quantity": 150,
        "category": "Accessories",
        "brand_id": 2,
        "created_at": "2025-10-29T10:00:00.000000Z",
        "updated_at": "2025-10-29T10:00:00.000000Z"
    },
    "message": "Product created successfully"
}
```

## Orders

### List Orders
```json
{
    "data": [
        {
            "order_id": 1,
            "customer_id": "CUST-1001",
            "total_price": "59.98",
            "status": "pending",
            "payment_method": "credit_card",
            "order_date": "2025-10-29T10:00:00.000000Z",
            "created_at": "2025-10-29T10:00:00.000000Z",
            "updated_at": "2025-10-29T10:00:00.000000Z"
        }
    ]
}
```

### Create Order Success
```json
{
    "data": {
        "order_id": 1,
        "customer_id": "CUST-1001",
        "total_price": "59.98",
        "status": "pending",
        "payment_method": "credit_card",
        "order_date": "2025-10-29T10:00:00.000000Z",
        "created_at": "2025-10-29T10:00:00.000000Z",
        "updated_at": "2025-10-29T10:00:00.000000Z"
    },
    "message": "Order created successfully"
}
```

## Order Items

### Create Order Item Success
```json
{
    "data": {
        "order_item_id": 1,
        "order_id": 1,
        "product_id": 1,
        "quantity": 2,
        "unit_price": "29.99",
        "subtotal": "59.98",
        "created_at": "2025-10-29T10:00:00.000000Z",
        "updated_at": "2025-10-29T10:00:00.000000Z"
    },
    "message": "Order item created successfully"
}
```

## Shipping

### Create Shipping Success
```json
{
    "data": {
        "shipping_id": 1,
        "order_id": 1,
        "address": "123 Delivery St",
        "city": "Metropolis",
        "state": "State",
        "zip_code": "12345",
        "country": "USA",
        "tracking_number": "SHIP-123456",
        "shipping_status": "pending",
        "created_at": "2025-10-29T10:00:00.000000Z",
        "updated_at": "2025-10-29T10:00:00.000000Z"
    },
    "message": "Shipping created successfully"
}
```

## Vouchers

### Create Voucher Success
```json
{
    "data": {
        "voucher_id": 1,
        "code": "SAVE20",
        "description": "20% off on all items",
        "discount_type": "percentage",
        "discount_value": "20.00",
        "usage_limit": 100,
        "usage_count": 0,
        "start_date": "2025-10-29",
        "end_date": "2025-12-31",
        "status": "active",
        "created_at": "2025-10-29T10:00:00.000000Z",
        "updated_at": "2025-10-29T10:00:00.000000Z"
    },
    "message": "Voucher created successfully"
}
```

## Add to Cart

### Create Cart Item Success
```json
{
    "data": {
        "cart_id": 1,
        "customer_id": "CUST-1001",
        "product_id": 2,
        "quantity": 1,
        "created_at": "2025-10-29T10:00:00.000000Z",
        "updated_at": "2025-10-29T10:00:00.000000Z"
    },
    "message": "Item added to cart successfully"
}
```

## Common Error Responses

### 401 Unauthorized
```json
{
    "message": "Unauthenticated."
}
```

### 422 Validation Error
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "The email field is required."
        ],
        "password": [
            "The password field is required."
        ]
    }
}
```

### 404 Not Found
```json
{
    "message": "Resource not found."
}
```