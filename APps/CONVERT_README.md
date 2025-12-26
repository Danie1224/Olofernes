# TechStore: Nuxt.js Frontend Migration Guide

> **Purpose**: This document maps the current Laravel Blade frontend to a Nuxt.js SPA, providing a complete migration roadmap without modifying the database or existing API (unless necessary—changes will be communicated first).

---

## Table of Contents

1. [Project Overview](#project-overview)
2. [Current System Architecture](#current-system-architecture)
3. [Feature Inventory](#feature-inventory)
4. [API Endpoints Reference](#api-endpoints-reference)
5. [Blade → Nuxt Page Mapping](#blade--nuxt-page-mapping)
6. [Proposed Nuxt Project Structure](#proposed-nuxt-project-structure)
7. [CORS & Authentication Setup](#cors--authentication-setup)
8. [Migration Checklist](#migration-checklist)
9. [Potential API Gaps](#potential-api-gaps)
10. [Related Documentation](#related-documentation)

---

## Project Overview

**TechStore** is a Laravel 12 e-commerce application with:
- **User-facing store**: Products, cart, checkout (multiple payment methods), orders, returns, vouchers
- **Admin dashboard**: Product management, customer management, order management/completion, return request handling, voucher distribution
- **REST API**: Full CRUD under `/api/techstore/*` with Sanctum authentication

### Tech Stack (Current)
| Layer | Technology |
|-------|------------|
| Backend | Laravel 12, PHP 8.2+ |
| Auth | Laravel Sanctum (tokens + sessions) |
| Frontend | Blade templates + Bootstrap |
| Database | MySQL/SQLite |

### Tech Stack (Target)
| Layer | Technology |
|-------|------------|
| Backend | Laravel 12 (unchanged) |
| Auth | Laravel Sanctum (token-based for SPA) |
| Frontend | **Nuxt 3** + Vue 3 + Tailwind CSS (or your choice) |
| State | Pinia stores |

---

## Current System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        LARAVEL BACKEND                          │
├─────────────────────────────────────────────────────────────────┤
│  Routes                                                         │
│  ├── routes/web.php     → Blade views (user + admin)           │
│  └── routes/api.php     → REST API (/api/techstore/*)          │
├─────────────────────────────────────────────────────────────────┤
│  Controllers                                                    │
│  ├── AuthController, AdminAuthController    → Auth flows       │
│  ├── ProductController, CartController      → Catalog/Cart     │
│  ├── CheckoutController                     → Checkout flows   │
│  ├── UserOrderItemController                → User orders      │
│  ├── UserReturnRequestController            → User returns     │
│  ├── UserVoucherController                  → User vouchers    │
│  ├── AdminController                        → Admin dashboard  │
│  ├── AdminReturnRequestController           → Admin returns    │
│  ├── AdminVoucherController                 → Admin vouchers   │
│  └── Api/*Controller                        → API endpoints    │
├─────────────────────────────────────────────────────────────────┤
│  Models                                                         │
│  ├── User, Admin, Customer                                     │
│  ├── Product, Brand                                            │
│  ├── Order, OrderItem, Payment, Shipping                       │
│  ├── AddToCart (cart items)                                    │
│  ├── Voucher, UserVoucher, VoucherUsage                        │
│  └── ReturnRequest, ReturnRequestItem                          │
└─────────────────────────────────────────────────────────────────┘
```

---

## Feature Inventory

### User Features
| Feature | Blade Views | Routes | API Available? |
|---------|-------------|--------|----------------|
| Home | `home.blade.php` | `/` | ✅ Products API |
| Auth (Login/Register) | `auth/login.blade.php`, `auth/register.blade.php` | `/login`, `/register` | ✅ `/api/techstore/login`, `/register` |
| Products List | `products/index.blade.php` | `/products` | ✅ `/api/techstore/products` |
| Product Detail | `products/show.blade.php` | `/products/{id}` | ✅ `/api/techstore/products/{id}` |
| Cart | `cart/index.blade.php` | `/cart` | ✅ `/api/techstore/add-to-cart` |
| Checkout | `checkout.blade.php` | `/checkout` | ⚠️ Partial (see gaps) |
| Checkout - GCash | `checkout-gcash.blade.php` | `/checkout/gcash-details` | ⚠️ Needs API |
| Checkout - Credit Card | `checkout-credit-card.blade.php` | `/checkout/credit-card-details` | ⚠️ Needs API |
| Checkout - PayPal | `checkout-paypal.blade.php` | `/checkout/paypal-details` | ⚠️ Needs API |
| My Orders | `orders/user-orders.blade.php` | `/orders` | ✅ `/api/techstore/orders` |
| Order Detail | `orders/order-details.blade.php` | `/orders/{id}` | ✅ `/api/techstore/orders/{id}` |
| My Returns | `returns/index.blade.php` | `/returns` | ✅ `/api/techstore/return-requests` |
| Create Return | `returns/create.blade.php` | `/returns/create` | ✅ `POST /api/techstore/return-requests` |
| Return Detail | `returns/show.blade.php` | `/returns/{id}` | ✅ `/api/techstore/return-requests/{id}` |
| My Vouchers | `vouchers/index.blade.php` | `/vouchers` | ✅ `/api/techstore/vouchers` |

### Admin Features
| Feature | Blade Views | Routes | API Available? |
|---------|-------------|--------|----------------|
| Admin Login | `admin/auth/login.blade.php` | `/admin/login` | ✅ `/api/techstore/admin-login` |
| Dashboard | `admin/dashboard.blade.php` | `/admin` | ⚠️ Stats needed |
| Products CRUD | `admin/products/*.blade.php` | `/admin/products/*` | ✅ Full CRUD |
| Customers List | `admin/customers/index.blade.php` | `/admin/customers` | ✅ `/api/techstore/customers` |
| Orders List | `admin/orders/index.blade.php` | `/admin/orders` | ✅ `/api/techstore/orders` |
| Order Detail | `admin/orders/show.blade.php` | `/admin/orders/{id}` | ✅ `/api/techstore/orders/{id}` |
| Complete Order | (button in order detail) | `POST /admin/orders/{id}/complete` | ✅ `POST /api/techstore/orders/{id}/complete` |
| Returns List | `admin/returns/index.blade.php` | `/admin/returns` | ✅ `/api/techstore/return-requests` |
| Return Detail | `admin/returns/show.blade.php` | `/admin/returns/{id}` | ✅ + approve/reject/refund |
| Vouchers CRUD | `admin/vouchers/*.blade.php` | `/admin/vouchers/*` | ✅ Full CRUD |

---

## API Endpoints Reference

### Base URL
```
/api/techstore
```

### Authentication Endpoints
| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/register` | Public | User registration |
| POST | `/login` | Public | User login → returns Sanctum token |
| POST | `/register-admin` | Public | Admin registration |
| POST | `/admin-login` | Public | Admin login → returns Sanctum token |
| GET | `/get-user` | 🔒 | Get authenticated user |
| POST | `/logout` | 🔒 | Logout (revoke token) |

### Public Read Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/customers` | List customers |
| GET | `/customers/{id}` | Customer detail |
| GET | `/products` | List products |
| GET | `/products/{id}` | Product detail |
| GET | `/orders` | List orders |
| GET | `/orders/{id}` | Order detail |
| GET | `/order-items` | List order items |
| GET | `/shippings` | List shippings |
| GET | `/vouchers` | List vouchers |
| GET | `/add-to-cart` | List cart items |
| GET | `/return-requests` | List return requests |
| GET | `/return-requests-stats` | Return request statistics |

### Protected Write Endpoints (🔒 Sanctum Required)
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST/PUT/DELETE | `/customers/*` | Customer CRUD |
| POST/PUT/DELETE | `/products/*` | Product CRUD |
| POST/PUT/DELETE | `/orders/*` | Order CRUD |
| POST/PUT/DELETE | `/order-items/*` | Order item CRUD |
| POST/PUT/DELETE | `/shippings/*` | Shipping CRUD |
| POST/PUT/DELETE | `/vouchers/*` | Voucher CRUD |
| POST/PUT/DELETE | `/add-to-cart/*` | Cart CRUD |
| POST | `/return-requests` | Create return request |
| POST | `/return-requests/{id}/approve` | Approve return (admin) |
| POST | `/return-requests/{id}/reject` | Reject return (admin) |
| POST | `/return-requests/{id}/refund` | Process refund (admin) |
| DELETE | `/return-requests/{id}` | Cancel return request |
| POST | `/orders/{id}/complete` | Mark order complete (admin) |
| GET | `/orders/{id}/completion-status` | Get completion status |

---

## Blade → Nuxt Page Mapping

### User Pages
| Current Blade | Nuxt Page | Route |
|---------------|-----------|-------|
| `home.blade.php` | `pages/index.vue` | `/` |
| `auth/login.blade.php` | `pages/auth/login.vue` | `/auth/login` |
| `auth/register.blade.php` | `pages/auth/register.vue` | `/auth/register` |
| `products/index.blade.php` | `pages/products/index.vue` | `/products` |
| `products/show.blade.php` | `pages/products/[id].vue` | `/products/:id` |
| `cart/index.blade.php` | `pages/cart/index.vue` | `/cart` |
| `checkout.blade.php` | `pages/checkout/index.vue` | `/checkout` |
| `checkout-gcash.blade.php` | `pages/checkout/gcash.vue` | `/checkout/gcash` |
| `checkout-credit-card.blade.php` | `pages/checkout/credit-card.vue` | `/checkout/credit-card` |
| `checkout-paypal.blade.php` | `pages/checkout/paypal.vue` | `/checkout/paypal` |
| `orders/user-orders.blade.php` | `pages/orders/index.vue` | `/orders` |
| `orders/order-details.blade.php` | `pages/orders/[id].vue` | `/orders/:id` |
| `returns/index.blade.php` | `pages/returns/index.vue` | `/returns` |
| `returns/create.blade.php` | `pages/returns/create.vue` | `/returns/create` |
| `returns/show.blade.php` | `pages/returns/[id].vue` | `/returns/:id` |
| `vouchers/index.blade.php` | `pages/vouchers/index.vue` | `/vouchers` |
| `dashboard.blade.php` | `pages/dashboard.vue` | `/dashboard` |

### Admin Pages
| Current Blade | Nuxt Page | Route |
|---------------|-----------|-------|
| `admin/auth/login.blade.php` | `pages/admin/login.vue` | `/admin/login` |
| `admin/dashboard.blade.php` | `pages/admin/index.vue` | `/admin` |
| `admin/products/index.blade.php` | `pages/admin/products/index.vue` | `/admin/products` |
| `admin/products/create.blade.php` | `pages/admin/products/create.vue` | `/admin/products/create` |
| `admin/products/edit.blade.php` | `pages/admin/products/[id]/edit.vue` | `/admin/products/:id/edit` |
| `admin/customers/index.blade.php` | `pages/admin/customers/index.vue` | `/admin/customers` |
| `admin/orders/index.blade.php` | `pages/admin/orders/index.vue` | `/admin/orders` |
| `admin/orders/show.blade.php` | `pages/admin/orders/[id].vue` | `/admin/orders/:id` |
| `admin/returns/index.blade.php` | `pages/admin/returns/index.vue` | `/admin/returns` |
| `admin/returns/show.blade.php` | `pages/admin/returns/[id].vue` | `/admin/returns/:id` |
| `admin/vouchers/index.blade.php` | `pages/admin/vouchers/index.vue` | `/admin/vouchers` |
| `admin/vouchers/create.blade.php` | `pages/admin/vouchers/create.vue` | `/admin/vouchers/create` |
| `admin/vouchers/show.blade.php` | `pages/admin/vouchers/[id].vue` | `/admin/vouchers/:id` |
| `admin/vouchers/edit.blade.php` | `pages/admin/vouchers/[id]/edit.vue` | `/admin/vouchers/:id/edit` |

---

## Proposed Nuxt Project Structure

```
nuxt-techstore/
├── nuxt.config.ts
├── app.vue
├── pages/
│   ├── index.vue                    # Home
│   ├── dashboard.vue                # User dashboard
│   ├── auth/
│   │   ├── login.vue
│   │   └── register.vue
│   ├── products/
│   │   ├── index.vue
│   │   └── [id].vue
│   ├── cart/
│   │   └── index.vue
│   ├── checkout/
│   │   ├── index.vue
│   │   ├── gcash.vue
│   │   ├── credit-card.vue
│   │   └── paypal.vue
│   ├── orders/
│   │   ├── index.vue
│   │   └── [id].vue
│   ├── returns/
│   │   ├── index.vue
│   │   ├── create.vue
│   │   └── [id].vue
│   ├── vouchers/
│   │   └── index.vue
│   └── admin/
│       ├── login.vue
│       ├── index.vue                # Admin dashboard
│       ├── products/
│       │   ├── index.vue
│       │   ├── create.vue
│       │   └── [id]/
│       │       └── edit.vue
│       ├── customers/
│       │   └── index.vue
│       ├── orders/
│       │   ├── index.vue
│       │   └── [id].vue
│       ├── returns/
│       │   ├── index.vue
│       │   └── [id].vue
│       └── vouchers/
│           ├── index.vue
│           ├── create.vue
│           ├── [id].vue
│           └── [id]/
│               └── edit.vue
├── components/
│   ├── layout/
│   │   ├── Header.vue
│   │   ├── Footer.vue
│   │   ├── Sidebar.vue
│   │   └── AdminLayout.vue
│   ├── products/
│   │   ├── ProductCard.vue
│   │   └── ProductGrid.vue
│   ├── cart/
│   │   ├── CartItem.vue
│   │   └── CartSummary.vue
│   ├── orders/
│   │   ├── OrderCard.vue
│   │   └── OrderStatusBadge.vue
│   ├── returns/
│   │   ├── ReturnForm.vue
│   │   └── ReturnStatusBadge.vue
│   ├── vouchers/
│   │   └── VoucherCard.vue
│   └── ui/
│       ├── Button.vue
│       ├── Input.vue
│       ├── Modal.vue
│       └── Alert.vue
├── composables/
│   ├── useAuth.ts                   # Auth state & methods
│   ├── useApi.ts                    # API fetch wrapper
│   ├── useCart.ts                   # Cart operations
│   ├── useProducts.ts               # Product fetching
│   ├── useOrders.ts                 # Order operations
│   ├── useReturns.ts                # Return request operations
│   └── useVouchers.ts               # Voucher operations
├── stores/
│   ├── auth.ts                      # Pinia auth store
│   ├── cart.ts                      # Pinia cart store
│   └── ui.ts                        # UI state (modals, toasts)
├── middleware/
│   ├── auth.ts                      # Protect user routes
│   └── admin.ts                     # Protect admin routes
├── plugins/
│   └── api.ts                       # Global $api setup
├── types/
│   ├── user.ts
│   ├── product.ts
│   ├── order.ts
│   ├── cart.ts
│   ├── return.ts
│   └── voucher.ts
└── utils/
    ├── formatters.ts                # Date, currency formatters
    └── validators.ts                # Form validation
```

---

## CORS & Authentication Setup

### ⚠️ CRITICAL: CORS Configuration Required

The current Laravel backend **does not have a `config/cors.php` file**. You must create one for Nuxt to communicate with the API.

#### Step 1: Create `config/cors.php`

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:3000',      // Nuxt dev server
        'http://127.0.0.1:3000',
        env('FRONTEND_URL', 'http://localhost:3000'),
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,  // Required for Sanctum cookies
];
```

#### Step 2: Update `config/sanctum.php`

Add your Nuxt URL to stateful domains:

```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s%s',
    'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
    Sanctum::currentApplicationUrlWithPort(),
    ','.env('FRONTEND_URL', 'http://localhost:3000'),
))),
```

#### Step 3: Update `.env`

```env
SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000
FRONTEND_URL=http://localhost:3000
SESSION_DOMAIN=localhost
```

#### Step 4: Register CORS Middleware

In `bootstrap/app.php`, add CORS to the API middleware:

```php
->withMiddleware(function (Middleware $middleware): void {
    // Add CORS middleware for API routes
    $middleware->api([
        \Illuminate\Http\Middleware\HandleCors::class,
    ]);
    
    // ... existing web middleware
})
```

### Nuxt Auth Flow (Token-Based)

```typescript
// composables/useAuth.ts
export const useAuth = () => {
  const config = useRuntimeConfig()
  const token = useCookie('auth_token')
  const user = useState('user', () => null)

  const login = async (email: string, password: string) => {
    const { data } = await $fetch(`${config.public.apiBase}/login`, {
      method: 'POST',
      body: { email, password }
    })
    token.value = data.token
    user.value = data.user
  }

  const logout = async () => {
    await $fetch(`${config.public.apiBase}/logout`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${token.value}` }
    })
    token.value = null
    user.value = null
  }

  return { user, token, login, logout }
}
```

---

## Migration Checklist

### Phase 1: Setup & Infrastructure
- [ ] Create new Nuxt 3 project (`npx nuxi init nuxt-techstore`)
- [ ] Install dependencies (Pinia, Tailwind CSS, etc.)
- [ ] Configure `nuxt.config.ts` with API base URL
- [ ] **Create `config/cors.php` in Laravel** (see above)
- [ ] Update `config/sanctum.php` stateful domains
- [ ] Update Laravel `.env` for CORS
- [ ] Test API connectivity from Nuxt

### Phase 2: Core Pages (User)
- [ ] Layout components (Header, Footer)
- [ ] Home page with featured products
- [ ] Auth pages (login, register)
- [ ] Products list & detail
- [ ] Cart page with CRUD
- [ ] Checkout flow (all payment methods)
- [ ] Orders list & detail
- [ ] Returns list, create, detail
- [ ] Vouchers page

### Phase 3: Admin Pages
- [ ] Admin layout/sidebar
- [ ] Admin login
- [ ] Admin dashboard
- [ ] Products CRUD
- [ ] Customers list
- [ ] Orders list & detail with completion
- [ ] Returns management (approve/reject/refund)
- [ ] Vouchers CRUD & distribution

### Phase 4: Polish & Testing
- [ ] Error handling & validation
- [ ] Loading states
- [ ] Toast notifications
- [ ] Responsive design
- [ ] E2E testing
- [ ] Performance optimization

---

## Potential API Gaps

> **⚠️ I will inform you before making any changes to the database or API.**

### Identified Gaps

| Feature | Issue | Proposed Solution |
|---------|-------|-------------------|
| Checkout Process | No dedicated checkout API endpoint | Add `POST /api/techstore/checkout` to process orders |
| User's Own Cart | Cart API returns all carts, not user-specific | Add filter or new endpoint `GET /api/techstore/my-cart` |
| User's Own Orders | Orders API returns all orders | Add `GET /api/techstore/my-orders` |
| User's Own Vouchers | Need user-specific voucher endpoint | Add `GET /api/techstore/my-vouchers` |
| Admin Dashboard Stats | No stats API for admin dashboard | Add `GET /api/techstore/admin/stats` |
| Voucher Claim | Web-only claim flow | Add `POST /api/techstore/vouchers/claim` |
| Voucher Validation | Web-only validation | Add `POST /api/techstore/vouchers/validate` |

### Current Workarounds
Some features exist in web routes but not API:
- `/me/vouchers` - User's active vouchers (web only)
- `/vouchers/claim` - Claim voucher (web only)
- `/vouchers/validate` - Validate at checkout (web only)

**Action Required**: Before implementing Nuxt checkout/voucher features, these endpoints need API equivalents. I will confirm with you before adding them.

---

## Related Documentation

### Feature Deep Dives
- [ADMIN_RETURNS_IMPLEMENTATION.md](ADMIN_RETURNS_IMPLEMENTATION.md) - Admin returns system
- [ORDER_COMPLETION_GUIDE.md](ORDER_COMPLETION_GUIDE.md) - Order completion workflow
- [VOUCHER_DISTRIBUTION_FEATURE_COMPLETE.md](VOUCHER_DISTRIBUTION_FEATURE_COMPLETE.md) - Voucher system
- [PAYMENT_MODAL_TO_PAGE_CONVERSION.md](PAYMENT_MODAL_TO_PAGE_CONVERSION.md) - Payment flows

### Quick References
- [ADMIN_RETURNS_QUICK_REFERENCE.md](ADMIN_RETURNS_QUICK_REFERENCE.md)
- [ORDER_COMPLETION_QUICK_REFERENCE.md](ORDER_COMPLETION_QUICK_REFERENCE.md)
- [VOUCHER_QUICK_START.md](VOUCHER_QUICK_START.md)
- [RETURN_REQUESTS_QUICK_REFERENCE.md](RETURN_REQUESTS_QUICK_REFERENCE.md)

### Implementation Status
- [FINAL_STATUS_REPORT.md](FINAL_STATUS_REPORT.md)
- [IMPLEMENTATION_CHECKLIST.md](IMPLEMENTATION_CHECKLIST.md)
- [TESTING_SUMMARY.md](TESTING_SUMMARY.md)

---

## What Has Been Created

### Laravel Backend Updates
- ✅ `config/cors.php` - CORS configuration for Nuxt frontend
- ✅ `bootstrap/app.php` - Updated with CORS middleware for API routes
- ✅ `config/sanctum.php` - Updated stateful domains for Nuxt

### Nuxt Frontend (`nuxt-techstore/`)
- ✅ Project configuration (`nuxt.config.ts`, `package.json`, `tailwind.config.js`)
- ✅ Layouts (`default.vue`, `admin.vue`)
- ✅ Composables (`useAuth`, `useApi`, `useProducts`, `useCart`, `useOrders`, `useReturns`, `useVouchers`)
- ✅ Middleware (`auth.ts`, `admin.ts`)
- ✅ Components (`Header`, `Footer`, `ProductCard`)
- ✅ User Pages (Home, Auth, Products, Cart, Checkout, Orders, Returns, Vouchers)
- ✅ Admin Pages (Login, Dashboard, Products, Orders, Returns, Vouchers, Customers)

---

## Next Steps

1. **Install Nuxt dependencies**:
   ```bash
   cd nuxt-techstore
   npm install
   ```

2. **Start Laravel backend**:
   ```bash
   php artisan serve
   ```

3. **Start Nuxt development server**:
   ```bash
   cd nuxt-techstore
   npm run dev
   ```

4. **Test the connection** by visiting `http://localhost:3000`

5. **Address API gaps** as you encounter them during testing

---

*Last updated: December 11, 2025*
