# TechStore Nuxt Frontend

A Nuxt 3 frontend for the TechStore Laravel e-commerce application.

## Prerequisites

- Node.js 18+ 
- npm or pnpm
- Laravel backend running on `http://localhost:8000`

## Setup

1. Install dependencies:

```bash
npm install
```

2. Copy environment file:

```bash
cp .env.example .env
```

3. Update `.env` with your backend API URL if different:

```env
NUXT_PUBLIC_API_BASE=http://localhost:8000/api/techstore
```

## Development

Start the development server on `http://localhost:3000`:

```bash
npm run dev
```

## Production

Build the application for production:

```bash
npm run build
```

Preview the production build:

```bash
npm run preview
```

## Project Structure

```
nuxt-techstore/
├── pages/              # Route pages (file-based routing)
│   ├── index.vue       # Home page
│   ├── auth/           # Login & Register
│   ├── products/       # Product listing & details
│   ├── cart/           # Shopping cart
│   ├── checkout/       # Checkout flow
│   ├── orders/         # User orders
│   ├── returns/        # Return requests
│   ├── vouchers/       # User vouchers
│   └── admin/          # Admin dashboard
├── components/         # Reusable Vue components
├── composables/        # Composable functions (useAuth, useApi, etc.)
├── layouts/            # Page layouts (default, admin)
├── middleware/         # Route middleware (auth, admin)
├── assets/             # CSS and static assets
└── nuxt.config.ts      # Nuxt configuration
```

## Backend Requirements

Ensure your Laravel backend has:

1. **CORS configured** (`config/cors.php`) for `http://localhost:3000`
2. **Sanctum stateful domains** updated to include the frontend URL
3. **API endpoints** available at `/api/techstore/*`

See [CONVERT_README.md](../CONVERT_README.md) for full migration documentation.

## Features

### User Features
- 🛍️ Product browsing and search
- 🛒 Shopping cart management
- 💳 Checkout with multiple payment methods
- 📦 Order tracking
- 🔄 Return request submission
- 🎟️ Voucher management

### Admin Features
- 📊 Dashboard with statistics
- 📦 Product CRUD
- 👥 Customer management
- 📋 Order management with completion
- 🔄 Return request handling (approve/reject/refund)
- 🎟️ Voucher CRUD and distribution

## API Composables

- `useAuth()` - Authentication state and methods
- `useApi()` - API fetch wrapper with auth headers
- `useProducts()` - Product fetching and state
- `useCart()` - Cart operations
- `useOrders()` - Order operations
- `useReturns()` - Return request operations
- `useVouchers()` - Voucher operations
