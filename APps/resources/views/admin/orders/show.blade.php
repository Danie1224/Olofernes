@extends('layouts.admin')

@section('title', 'Order #'.$order->order_id.' - Admin')

@section('content')
<style>
    .order-details-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .order-details-header h1 {
        margin: 0 0 15px 0;
        font-size: 2rem;
    }

    .order-meta-grid {
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
        font-size: 1.1rem;
        font-weight: 600;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-pending { background: #fef3c7; color: #92400e; }
    .status-processing { background: #dbeafe; color: #1e40af; }
    .status-shipped { background: #d1fae5; color: #065f46; }
    .status-completed { background: #d1fae5; color: #065f46; }
    .status-cancelled { background: #fee2e2; color: #991b1b; }

    .payment-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 600;
        width: fit-content;
    }

    .payment-paid {
        background: #d1fae5;
        color: #065f46;
    }

    .payment-paid::before {
        content: "✓";
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 16px;
        height: 16px;
        background: #059669;
        color: white;
        border-radius: 50%;
        font-size: 12px;
        font-weight: 700;
    }

    .payment-unpaid {
        background: #fef3c7;
        color: #92400e;
    }

    .payment-unpaid::before {
        content: "!";
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 16px;
        height: 16px;
        background: #d97706;
        color: white;
        border-radius: 50%;
        font-size: 12px;
        font-weight: 700;
    }

    .content-section {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .section-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 20px 0;
        padding-bottom: 15px;
        border-bottom: 2px solid #f3f4f6;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 6px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 1rem;
        color: #111827;
        font-weight: 500;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .items-table thead {
        background: #f9fafb;
        border-bottom: 2px solid #e5e7eb;
    }

    .items-table th {
        padding: 12px;
        text-align: left;
        font-size: 0.85rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .items-table td {
        padding: 12px;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
    }

    .items-table tbody tr:hover {
        background-color: #f9fafb;
    }

    .product-name {
        font-weight: 600;
        color: #111827;
    }

    .amount {
        font-weight: 600;
        color: #059669;
    }

    .back-button {
        display: inline-block;
        padding: 10px 16px;
        background: #6b7280;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .back-button:hover {
        background: #4b5563;
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
    }
</style>

<a href="{{ route('admin.orders.index') }}" class="back-button">← Back to Orders</a>

<div class="order-details-header">
    <h1>Order #{{ $order->order_id }}</h1>
    <div class="order-meta-grid">
        <div class="meta-item">
            <span class="meta-label">Customer</span>
            <span class="meta-value">{{ $order->customer->name ?? 'N/A' }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Order Date</span>
            <span class="meta-value">{{ $order->order_date?->format('M d, Y') ?? $order->created_at->format('M d, Y') }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Total</span>
            <span class="meta-value">₱{{ number_format($order->total_price, 2) }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Status</span>
            <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
        </div>
    </div>
</div>

<!-- Order Summary -->
<div class="content-section">
    <h2 class="section-title">Order Summary</h2>
    <div class="info-grid">
        <div class="info-item">
            <span class="info-label">Payment Method</span>
            <span class="info-value">
                @php
                    $paymentLabel = match($order->payment_method) {
                        'cash' => 'Cash on Delivery',
                        'credit_card' => 'Credit Card',
                        'gcash' => 'GCash',
                        'paypal' => 'PayPal',
                        default => ucfirst($order->payment_method)
                    };
                @endphp
                {{ $paymentLabel }}
            </span>
        </div>
        <div class="info-item">
            <span class="info-label">Payment Status</span>
            @php
                $isPaid = $order->payment_method && $order->payment_method !== 'cash';
            @endphp
            <span class="payment-status-badge {{ $isPaid ? 'payment-paid' : 'payment-unpaid' }}">
                {{ $isPaid ? 'Paid' : 'Unpaid (COD)' }}
            </span>
        </div>
        <div class="info-item">
            <span class="info-label">Items Count</span>
            <span class="info-value">{{ $order->orderItems->count() }} item(s)</span>
        </div>
        <div class="info-item">
            <span class="info-label">Order Status</span>
            <span class="status-badge status-{{ $order->status }}" style="width: fit-content;">{{ ucfirst($order->status) }}</span>
        </div>
    </div>
</div>

<!-- Order Completion Section -->
@include('admin.orders.partials.completion-component')

<!-- Order Items -->
<div class="content-section">
    <h2 class="section-title">Order Items</h2>
    <table class="items-table">
        <thead>
            <tr>
                <th>Product</th>
                <th style="width: 80px; text-align: center;">Qty</th>
                <th style="width: 100px; text-align: right;">Unit Price</th>
                <th style="width: 100px; text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td>
                        <span class="product-name">{{ $item->product->name ?? 'Unknown Product' }}</span>
                        @if($item->product)
                            <br><small style="color: #9ca3af;">SKU: {{ $item->product->product_code }}</small>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">₱{{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align: right;"><span class="amount">₱{{ number_format($item->subtotal, 2) }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div style="display: flex; justify-content: flex-end; margin-top: 20px; padding-top: 20px; border-top: 2px solid #e5e7eb;">
        <div style="text-align: right;">
            <div style="color: #6b7280; font-size: 0.9rem;">Total Amount</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #667eea;">₱{{ number_format($order->total_price, 2) }}</div>
        </div>
    </div>
</div>
@endsection



