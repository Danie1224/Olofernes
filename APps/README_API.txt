TechStore API - Endpoints Overview
=================================

Base URL
- http://127.0.0.1:8000/api/techstore

Auth Model
- Public (no token): READ (GET index/show)
- Protected (Sanctum Bearer token): WRITE (POST/PUT/PATCH/DELETE)

Root
- GET /api/techstore
  - Description: API root index

Resources (RESTful)

1) Customers
- GET    /api/techstore/customers
- GET    /api/techstore/customers/{customer_id}
- POST   /api/techstore/customers           [auth:sanctum]
- PUT    /api/techstore/customers/{customer_id}   [auth:sanctum]
- PATCH  /api/techstore/customers/{customer_id}   [auth:sanctum]
- DELETE /api/techstore/customers/{customer_id}   [auth:sanctum]

2) Products
- GET    /api/techstore/products
- GET    /api/techstore/products/{product_id}
- POST   /api/techstore/products            [auth:sanctum]
- PUT    /api/techstore/products/{product_id}    [auth:sanctum]
- PATCH  /api/techstore/products/{product_id}    [auth:sanctum]
- DELETE /api/techstore/products/{product_id}    [auth:sanctum]

3) Orders
- GET    /api/techstore/orders
- GET    /api/techstore/orders/{order_id}
- POST   /api/techstore/orders              [auth:sanctum]
- PUT    /api/techstore/orders/{order_id}        [auth:sanctum]
- PATCH  /api/techstore/orders/{order_id}        [auth:sanctum]
- DELETE /api/techstore/orders/{order_id}        [auth:sanctum]

4) Order Items
- GET    /api/techstore/order-items
- GET    /api/techstore/order-items/{order_item_id}
- POST   /api/techstore/order-items         [auth:sanctum]
- PUT    /api/techstore/order-items/{order_item_id}   [auth:sanctum]
- PATCH  /api/techstore/order-items/{order_item_id}   [auth:sanctum]
- DELETE /api/techstore/order-items/{order_item_id}   [auth:sanctum]

5) Shippings
- GET    /api/techstore/shippings
- GET    /api/techstore/shippings/{shipping_id}
- POST   /api/techstore/shippings           [auth:sanctum]
- PUT    /api/techstore/shippings/{shipping_id}    [auth:sanctum]
- PATCH  /api/techstore/shippings/{shipping_id}    [auth:sanctum]
- DELETE /api/techstore/shippings/{shipping_id}    [auth:sanctum]

6) Vouchers
- GET    /api/techstore/vouchers
- GET    /api/techstore/vouchers/{voucher_id}
- POST   /api/techstore/vouchers            [auth:sanctum]
- PUT    /api/techstore/vouchers/{voucher_id}    [auth:sanctum]
- PATCH  /api/techstore/vouchers/{voucher_id}    [auth:sanctum]
- DELETE /api/techstore/vouchers/{voucher_id}    [auth:sanctum]

7) Add To Cart
- GET    /api/techstore/add-to-cart
- GET    /api/techstore/add-to-cart/{id}
- POST   /api/techstore/add-to-cart         [auth:sanctum]
- PUT    /api/techstore/add-to-cart/{id}    [auth:sanctum]
- PATCH  /api/techstore/add-to-cart/{id}    [auth:sanctum]
- DELETE /api/techstore/add-to-cart/{id}    [auth:sanctum]

Notes
- Defined in `APps/routes/api.php` under `Route::prefix('techstore')`.
- Use header: `Authorization: Bearer <token>` for protected endpoints.
- Generate a token (example): `$user->createToken('api')->plainTextToken`.


