@extends('layouts.admin')

@section('title', 'Manage Orders - Admin')

@section('content')
<style>
    .orders-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }

    .orders-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 600;
        color: #1f2937;
        letter-spacing: -0.5px;
    }

    .filter-pills {
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .filter-pill {
        padding: 8px 16px;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .filter-pill:hover {
        background: #e5e7eb;
        border-color: #d1d5db;
    }

    .filter-pill.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }

    .orders-list {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    }

    .orders-table-wrapper {
        overflow-x: auto;
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }

    .orders-table thead {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .orders-table th {
        padding: 16px 20px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .orders-table th:last-child {
        text-align: right;
    }

    .orders-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: background-color 0.15s ease;
    }

    .orders-table tbody tr:hover {
        background-color: #f9fafb;
    }

    .orders-table tbody tr:last-child {
        border-bottom: none;
    }

    .orders-table td {
        padding: 16px 20px;
        font-size: 14px;
        color: #374151;
    }

    .order-details {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .order-id {
        font-weight: 600;
        color: #1f2937;
        font-size: 13px;
    }

    .customer-name {
        color: #4b5563;
        font-size: 14px;
        font-weight: 500;
    }

    .status-pill {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
        white-space: nowrap;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-processing {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-shipped {
        background: #d1fae5;
        color: #065f46;
    }

    .status-completed {
        background: #d1fae5;
        color: #065f46;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .payment-status {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 500;
        white-space: nowrap;
    }

    .payment-paid {
        color: #059669;
    }

    .payment-paid::before {
        content: "✓";
        display: inline-block;
        width: 18px;
        height: 18px;
        background: #d1fae5;
        color: #059669;
        border-radius: 50%;
        text-align: center;
        line-height: 18px;
        font-weight: 700;
        font-size: 11px;
    }

    .payment-pending {
        color: #d97706;
    }

    .payment-pending::before {
        content: "!";
        display: inline-block;
        width: 18px;
        height: 18px;
        background: #fef3c7;
        color: #d97706;
        border-radius: 50%;
        text-align: center;
        line-height: 18px;
        font-weight: 700;
        font-size: 12px;
    }

    .total-amount {
        font-weight: 700;
        color: #1f2937;
        font-size: 15px;
    }

    .order-date {
        color: #6b7280;
        font-size: 13px;
    }

    .action-button {
        display: inline-block;
        padding: 8px 16px;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .action-button:hover {
        background: #1d4ed8;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .empty-state {
        text-align: center;
        padding: 48px 20px;
        background: #f9fafb;
    }

    .empty-state h3 {
        margin: 0 0 8px 0;
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
    }

    .empty-state p {
        margin: 0;
        font-size: 14px;
        color: #6b7280;
    }

    .pagination-wrapper {
        padding: 20px;
        display: flex;
        justify-content: center;
        border-top: 1px solid #f3f4f6;
    }

    .orders-table td:last-child {
        text-align: right;
    }
</style>

<div class="orders-header">
    <h1>📦 Placed Orders</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Back to Dashboard</a>
</div>

<!-- Status Filter Pills -->
<div class="filter-pills">
    <a href="{{ route('admin.orders.index') }}" class="filter-pill {{ !request('status') ? 'active' : '' }}">All Orders</a>
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="filter-pill {{ request('status') === 'pending' ? 'active' : '' }}">Pending</a>
    <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="filter-pill {{ request('status') === 'processing' ? 'active' : '' }}">Processing</a>
    <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="filter-pill {{ request('status') === 'shipped' ? 'active' : '' }}">Shipped</a>
    <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="filter-pill {{ request('status') === 'completed' ? 'active' : '' }}">Completed</a>
    <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="filter-pill {{ request('status') === 'cancelled' ? 'active' : '' }}">Cancelled</a>
</div>

<div class="orders-list">
    @if($orders->count() > 0)
        <div class="orders-table-wrapper">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th style="width: 20%;">Order Details</th>
                        <th style="width: 12%;">Date</th>
                        <th style="width: 13%;">Status</th>
                        <th style="width: 13%;">Payment</th>
                        <th style="width: 13%; text-align: right;">Total</th>
                        <th style="width: 15%; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>
                                <div class="order-details">
                                    <span class="customer-name">{{ $order->customer->name ?? 'N/A' }}</span>
                                    <span class="order-id">Order #{{ $order->order_id }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="order-date">{{ $order->created_at->format('M d, Y') }}</span>
                            </td>
                            <td>
                                @php
                                    $statusClass = match($order->status) {
                                        'completed' => 'status-completed',
                                        'cancelled' => 'status-cancelled',
                                        'shipped' => 'status-shipped',
                                        'processing' => 'status-processing',
                                        'pending' => 'status-pending',
                                        default => 'status-pending'
                                    };
                                @endphp
                                <span class="status-pill {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td>
                                @php
                                    $isPaid = $order->payment_method && $order->payment_method !== 'cash';
                                    $paymentLabel = match($order->payment_method) {
                                        'cash' => 'Cash on Delivery',
                                        'credit_card' => 'Credit Card',
                                        'gcash' => 'GCash',
                                        'paypal' => 'PayPal',
                                        default => 'Unknown'
                                    };
                                @endphp
                                <div class="payment-status {{ $isPaid ? 'payment-paid' : 'payment-pending' }}">
                                    <span>{{ $isPaid ? 'Paid' : 'Unpaid' }}</span>
                                </div>
                                <span style="font-size: 12px; color: #6b7280; display: block; margin-top: 4px;">{{ $paymentLabel }}</span>
                            </td>
                            <td>
                                <span class="total-amount">${{ number_format($order->total_price, 2) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->order_id) }}" class="action-button">View Items</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="pagination-wrapper">
                {{ $orders->links('pagination.custom') }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <h3>No Orders Yet</h3>
            <p>Orders will appear here once customers make purchases.</p>
        </div>
    @endif
</div>
@endsection
