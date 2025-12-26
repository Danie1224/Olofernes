@extends('layouts.app')

@section('title', 'Order #' . $order->order_id . ' Details - TechStore')

@section('content')
<div class="container mt-4">
    <a href="{{ route('user.orders.index') }}" class="btn btn-secondary" style="margin-bottom: 20px;">← Back to Orders</a>

    <style>
        .order-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .order-header h1 {
            margin: 0 0 10px 0;
            font-size: 2rem;
        }

        .order-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .meta-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 6px;
            backdrop-filter: blur(10px);
        }

        .meta-label {
            font-size: 0.85rem;
            opacity: 0.9;
            display: block;
            margin-bottom: 5px;
        }

        .meta-value {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.2);
        }

        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        .order-items-section,
        .order-summary-section {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #111827;
            margin: 0 0 20px 0;
            padding-bottom: 15px;
            border-bottom: 2px solid #f3f4f6;
        }

        .order-item {
            display: grid;
            grid-template-columns: 80px 1fr auto;
            gap: 15px;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 80px;
            height: 80px;
            background: #f3f4f6;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            overflow: hidden;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-details {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .item-name {
            font-weight: 600;
            color: #111827;
            font-size: 0.95rem;
        }

        .item-sku {
            color: #9ca3af;
            font-size: 0.85rem;
        }

        .item-quantity {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .item-price {
            display: flex;
            flex-direction: column;
            gap: 5px;
            text-align: right;
            min-width: 100px;
        }

        .unit-price {
            color: #6b7280;
            font-size: 0.85rem;
        }

        .subtotal {
            font-weight: 700;
            color: #111827;
            font-size: 1rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            font-size: 0.95rem;
        }

        .summary-row.total {
            padding: 15px 0;
            border-top: 2px solid #e5e7eb;
            border-bottom: 2px solid #e5e7eb;
            font-size: 1.2rem;
            font-weight: 700;
            color: #111827;
        }

        .summary-label {
            color: #6b7280;
        }

        .summary-value {
            color: #111827;
            font-weight: 600;
        }

        .shipping-info,
        .payment-info {
            background: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
            font-size: 0.9rem;
        }

        .shipping-info h4,
        .payment-info h4 {
            color: #111827;
            margin: 0 0 8px 0;
            font-weight: 600;
        }

        .shipping-info p,
        .payment-info p {
            margin: 5px 0;
            color: #6b7280;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #111827;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }

            .order-header {
                padding: 20px;
            }

            .order-header h1 {
                font-size: 1.5rem;
            }

            .order-meta {
                grid-template-columns: 1fr;
            }

            .order-item {
                grid-template-columns: 60px 1fr auto;
            }

            .item-image {
                width: 60px;
                height: 60px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>

    <!-- Order Header -->
    <div class="order-header">
        <h1>Order #{{ $order->order_id }}</h1>
        <div class="order-meta">
            <div class="meta-item">
                <span class="meta-label">Status</span>
                <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Order Date</span>
                <span class="meta-value">{{ $order->order_date->format('M d, Y') }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Payment Method</span>
                <span class="meta-value">{{ ucfirst($order->payment_method) }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Customer</span>
                <span class="meta-value">{{ $order->customer->name ?? 'N/A' }}</span>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Order Items Section -->
        <div class="order-items-section">
            <h2 class="section-title">Order Items ({{ $orderItems->count() }})</h2>

            @foreach($orderItems as $item)
                <div class="order-item">
                    <div class="item-image">
                        @if($item->product && $item->product->image_url)
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->product_name }}">
                        @else
                            📦
                        @endif
                    </div>
                    <div class="item-details">
                        <div class="item-name">{{ $item->product->name ?? 'Unknown Product' }}</div>
                        <div class="item-sku">SKU: {{ $item->product->product_code ?? 'N/A' }}</div>
                        <div class="item-quantity">Quantity: <strong>{{ $item->quantity }} × ₱{{ number_format($item->unit_price, 2) }}</strong></div>
                    </div>
                    <div class="item-price">
                        <div class="unit-price">Unit Price</div>
                        <div class="subtotal">${{ number_format($item->subtotal, 2) }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Order Summary Section -->
        <div class="order-summary-section">
            <h2 class="section-title">Order Summary</h2>

            <div class="summary-row">
                <span class="summary-label">Subtotal</span>
                <span class="summary-value">${{ number_format($orderItems->sum('subtotal'), 2) }}</span>
            </div>

            <div class="summary-row total">
                <span>Total</span>
                <span>${{ number_format($order->total_price, 2) }}</span>
            </div>

            <!-- Shipping Information -->
            @if($order->shipping)
                <div class="shipping-info">
                    <h4>📦 Shipping Address</h4>
                    <p>{{ $order->shipping->shipping_address ?? 'N/A' }}</p>
                    <p>{{ $order->shipping->city ?? '' }}, {{ $order->shipping->state ?? '' }} {{ $order->shipping->zip_code ?? '' }}</p>
                    @if($order->shipping->tracking_number)
                        <p><strong>Tracking:</strong> {{ $order->shipping->tracking_number }}</p>
                    @endif
                </div>
            @else
                <div class="shipping-info">
                    <h4>📦 Shipping Address</h4>
                    <p>{{ $order->customer->address ?? 'N/A' }}</p>
                    <p>{{ $order->customer->city ?? '' }}, {{ $order->customer->state ?? '' }} {{ $order->customer->zip_code ?? '' }}</p>
                </div>
            @endif

            <!-- Payment Information -->
            <div class="payment-info">
                <h4>💳 Payment Information</h4>
                <p><strong>Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                <p><strong>Amount:</strong> ${{ number_format($order->total_price, 2) }}</p>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('user.orders.index') }}" class="btn btn-secondary">← Back to Orders</a>
            </div>
        </div>
    </div>
</div>

<style>
    .status-pending {
        background: #fef3c7 !important;
        color: #92400e !important;
    }

    .status-processing {
        background: #bfdbfe !important;
        color: #1e40af !important;
    }

    .status-shipped {
        background: #dbeafe !important;
        color: #0c4a6e !important;
    }

    .status-completed {
        background: #dcfce7 !important;
        color: #166534 !important;
    }

    .status-cancelled {
        background: #fee2e2 !important;
        color: #7f1d1d !important;
    }
</style>
@endsection
