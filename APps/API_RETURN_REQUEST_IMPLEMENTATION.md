# API Return Request Implementation - Complete

## ✅ What Was Added

The API now has **complete return request functionality** exposed as REST endpoints in addition to the web interface.

---

## 🔌 API Endpoints Added

### Public Endpoints (No Authentication Required)

#### **List All Return Requests**
```
GET /api/techstore/return-requests
```
- Returns paginated list (15 per page)
- Includes customer, order, and items relationships
- Response: JSON array with return request data

#### **Get Specific Return Request**
```
GET /api/techstore/return-requests/{requestId}
```
- Returns detailed view of single return request
- Includes all related data (customer, order, items, products)
- Response: JSON object with complete data

#### **Get Return Request Statistics**
```
GET /api/techstore/return-requests-stats
```
- Returns aggregated statistics:
  - Total requests
  - Pending count
  - Approved count
  - Rejected count
  - Refunded count
  - Total pending refund amount (₱)
  - Total refunded amount (₱)
- Response: JSON object with metrics

---

### Protected Endpoints (Authentication Required with Sanctum Token)

#### **Create Return Request**
```
POST /api/techstore/return-requests
Authorization: Bearer {token}
```
**Request Body:**
```json
{
  "order_id": 123,
  "reason": "Items were damaged during shipment",
  "items": [
    {
      "order_item_id": 456,
      "return_quantity": 2,
      "reason": "damaged",
      "unit_price": 1500.00
    }
  ]
}
```
**Reason Options:** `damaged`, `defective`, `wrong_item`, `not_as_described`, `other`

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Return request created successfully",
  "data": {
    "return_request_id": 1,
    "order_id": 123,
    "customer_id": "C123",
    "status": "pending",
    "total_refund_amount": 3000.00,
    "items": [...]
  }
}
```

---

#### **Approve Return Request**
```
POST /api/techstore/return-requests/{requestId}/approve
Authorization: Bearer {token}
```
**Request Body (Optional):**
```json
{
  "admin_notes": "Approved - customer has valid proof of damage"
}
```
**Response:**
```json
{
  "success": true,
  "message": "Return request approved successfully",
  "data": {
    "return_request_id": 1,
    "status": "approved",
    "processed_by": 1,
    "admin_notes": "..."
  }
}
```

---

#### **Reject Return Request**
```
POST /api/techstore/return-requests/{requestId}/reject
Authorization: Bearer {token}
```
**Request Body (Required):**
```json
{
  "rejection_reason": "Photo evidence does not show damage clearly as claimed"
}
```
**Response:**
```json
{
  "success": true,
  "message": "Return request rejected successfully",
  "data": {
    "return_request_id": 1,
    "status": "rejected",
    "processed_by": 1,
    "admin_notes": "Photo evidence does not show damage clearly as claimed"
  }
}
```

---

#### **Mark Return as Refunded**
```
POST /api/techstore/return-requests/{requestId}/refund
Authorization: Bearer {token}
```
**Response:**
```json
{
  "success": true,
  "message": "Return request marked as refunded",
  "data": {
    "return_request_id": 1,
    "status": "refunded"
  }
}
```

---

#### **Cancel Return Request**
```
DELETE /api/techstore/return-requests/{requestId}
Authorization: Bearer {token}
```
**Response:**
```json
{
  "success": true,
  "message": "Return request cancelled successfully"
}
```

---

## 📊 Response Structure

All endpoints return consistent JSON responses:

### Success Response
```json
{
  "success": true,
  "message": "Action completed successfully",
  "data": { ... }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error description"
}
```

---

## 🔐 Authentication

All protected endpoints require Bearer token authentication:

```
Authorization: Bearer {token}
```

**Get Token:**
1. Register: `POST /api/techstore/register`
2. Login: `POST /api/techstore/login`
3. Use returned token in Authorization header

---

## 📋 Data Relationships

### Return Request Object
```json
{
  "return_request_id": 1,
  "order_id": 123,
  "customer_id": "C123",
  "status": "pending|approved|rejected|refunded",
  "notes": "Customer notes",
  "admin_notes": "Admin notes",
  "total_refund_amount": 3000.00,
  "processed_by": 5,
  "created_at": "2024-11-25T10:30:00Z",
  "updated_at": "2024-11-25T10:30:00Z",
  "customer": { ... },
  "order": { ... },
  "returnItems": [ ... ]
}
```

### Return Request Item
```json
{
  "return_request_item_id": 1,
  "return_request_id": 1,
  "order_item_id": 456,
  "product_id": 789,
  "return_quantity": 2,
  "reason": "damaged",
  "unit_price": 1500.00,
  "refund_amount": 3000.00,
  "product": { ... }
}
```

---

## 🧪 Testing with Postman

### 1. Get Token
**Request:**
```
POST http://localhost:8000/api/techstore/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}
```

### 2. List Returns
**Request:**
```
GET http://localhost:8000/api/techstore/return-requests
Authorization: Bearer {token}
```

### 3. Create Return
**Request:**
```
POST http://localhost:8000/api/techstore/return-requests
Authorization: Bearer {token}
Content-Type: application/json

{
  "order_id": 1,
  "reason": "Damaged items",
  "items": [
    {
      "order_item_id": 1,
      "return_quantity": 1,
      "reason": "damaged",
      "unit_price": 1500
    }
  ]
}
```

### 4. Approve Return
**Request:**
```
POST http://localhost:8000/api/techstore/return-requests/1/approve
Authorization: Bearer {token}
Content-Type: application/json

{
  "admin_notes": "Approved"
}
```

---

## ✅ Complete Workflow via API

```
1. User logs in
   POST /api/techstore/login
   → Get token
   
2. User views their return requests
   GET /api/techstore/return-requests
   → See list
   
3. User creates return request
   POST /api/techstore/return-requests
   → Return created with status: pending
   
4. Admin views all returns
   GET /api/techstore/return-requests
   → See all requests
   
5. Admin approves return
   POST /api/techstore/return-requests/{id}/approve
   → Status changes to: approved
   
6. Admin processes refund
   POST /api/techstore/return-requests/{id}/refund
   → Status changes to: refunded
   
7. Get statistics
   GET /api/techstore/return-requests-stats
   → See aggregated data
```

---

## 🗺️ Route Summary

| Method | Endpoint | Auth | Purpose |
|--------|----------|------|---------|
| GET | `/return-requests` | No | List all returns |
| POST | `/return-requests` | Yes | Create return |
| GET | `/return-requests/{id}` | No | View details |
| POST | `/return-requests/{id}/approve` | Yes | Approve return |
| POST | `/return-requests/{id}/reject` | Yes | Reject return |
| POST | `/return-requests/{id}/refund` | Yes | Mark refunded |
| DELETE | `/return-requests/{id}` | Yes | Cancel return |
| GET | `/return-requests-stats` | No | Get statistics |

---

## 💾 Database Integration

✅ Uses existing tables:
- `return_requests` - Main return records
- `return_request_items` - Return items
- `orders` - Order information
- `customers` - Customer data
- `products` - Product information

✅ No new tables created
✅ No schema modifications
✅ All data relationships maintained

---

## 🔍 Error Handling

### 404 Not Found
```json
{
  "success": false,
  "message": "Return request not found"
}
```

### 400 Bad Request
```json
{
  "success": false,
  "message": "Only pending requests can be approved"
}
```

### 422 Validation Error
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "order_id": ["The order_id field is required"]
  }
}
```

### 500 Server Error
```json
{
  "success": false,
  "message": "Error creating return request: [error details]"
}
```

---

## 📁 Files Added/Modified

**New Files:**
- `app/Http/Controllers/ReturnRequestController.php` (API controller)

**Modified Files:**
- `routes/api.php` (Added 8 new routes)

**Controllers Used:**
- ReturnRequestController (NEW)
- ReturnRequest model
- ReturnRequestItem model

---

## ✨ Features

✅ Full CRUD operations for returns
✅ Approval/rejection workflow
✅ Refund processing
✅ Statistics aggregation
✅ Relationship eager loading
✅ Validation on all endpoints
✅ Transaction support for consistency
✅ Error handling with proper HTTP status codes
✅ Pagination support
✅ Sanctum authentication
✅ Consistent JSON responses

---

## 🚀 API is Now Complete

Your API now supports:
- ✅ Complete return request management
- ✅ Full approval workflow
- ✅ Statistics and reporting
- ✅ User and admin operations
- ✅ All existing functionality + new returns feature

**The website now has all necessary API endpoints to run properly!**

---

## 📝 Next Steps (Optional)

1. Add rate limiting to API endpoints
2. Add response caching for statistics
3. Add webhook notifications on status changes
4. Add export functionality (CSV/PDF)
5. Add advanced filtering (date range, amount range)
6. Add API documentation (Swagger/OpenAPI)

---

**Status:** ✅ COMPLETE - All API endpoints implemented and verified
