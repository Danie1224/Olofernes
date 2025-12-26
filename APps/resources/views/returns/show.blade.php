@extends('layouts.app')

@section('title', 'Return Request Details - TechStore')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-between align-center mb-4">
        <h1>Return Request #{{ $returnRequest->request_id }}</h1>
        <a href="{{ route('returns.index') }}" class="btn btn-secondary">Back to Returns</a>
    </div>

    <style>
        .details-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .header-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 30px;
            align-items: start;
        }

        .header-info h2 {
            font-size: 1.4rem;
            margin-bottom: 15px;
        }

        .header-meta {
            display: grid;
            gap: 12px;
            font-size: 0.95rem;
        }

        .meta-row {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 10px;
        }

        .meta-label {
            font-weight: 600;
            opacity: 0.9;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            width: fit-content;
        }

        .status-pending {
            background: #fef08a;
            color: #92400e;
        }

        .status-approved {
            background: #dcfce7;
            color: #166534;
        }

        .status-refunded {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .info-section {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 18px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 12px;
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
        }

        .info-value {
            font-size: 1rem;
            color: #1f2937;
            font-weight: 500;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .items-table thead {
            background: #f9fafb;
        }

        .items-table th {
            padding: 12px;
            text-align: left;
            color: #1f2937;
            font-weight: 600;
            border-bottom: 2px solid #e5e7eb;
        }

        .items-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #e5e7eb;
            color: #1f2937;
        }

        .items-table tr:hover {
            background: #f9fafb;
        }

        .product-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .product-name {
            font-weight: 600;
            color: #1f2937;
        }

        .product-id {
            font-size: 0.85rem;
            color: #6b7280;
        }

        .amount {
            color: #10b981;
            font-weight: 600;
        }

        .summary-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
        }

        .summary-row.total {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border: none;
            font-weight: 600;
            font-size: 1.1rem;
            color: #667eea;
            padding: 20px 15px;
        }

        .admin-notes {
            background: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
        }

        .admin-notes-title {
            color: #166534;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .admin-notes-content {
            color: #15803d;
            line-height: 1.6;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #1f2937;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        .btn-danger {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background: #fecaca;
        }

        .timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline-item {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            position: relative;
        }

        .timeline-dot {
            width: 12px;
            height: 12px;
            background: #667eea;
            border-radius: 50%;
            margin-top: 6px;
            flex-shrink: 0;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .timeline-content {
            flex: 1;
        }

        .timeline-time {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .timeline-text {
            color: #6b7280;
            font-size: 0.95rem;
        }

        @media (max-width: 768px) {
            .header-card {
                grid-template-columns: 1fr;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .items-table {
                font-size: 0.9rem;
            }

            .items-table th,
            .items-table td {
                padding: 10px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-primary, .btn-secondary, .btn-danger {
                width: 100%;
                text-align: center;
            }
        }
    </style>

    <div class="details-container">
        <!-- Header Card with Status -->
        <div class="header-card">
            <div class="header-info">
                <h2>Return Request Details</h2>
                <div class="header-meta">
                    <div class="meta-row">
                        <span class="meta-label">Request ID:</span>
                        <span>#{{ $returnRequest->request_id }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Order ID:</span>
                        <span>#{{ $returnRequest->order_id }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Requested Date:</span>
                        <span>{{ $returnRequest->created_at->format('M d, Y \a\t g:i A') }}</span>
                    </div>
                </div>
            </div>
            <div>
                <span class="status-badge status-{{ $returnRequest->status }}">
                    {{ ucfirst($returnRequest->status) }}
                </span>
            </div>
        </div>

        <!-- Return Information -->
        <div class="info-section">
            <div class="section-title">📋 Return Information</div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        <span class="status-badge status-{{ $returnRequest->status }}">
                            {{ ucfirst($returnRequest->status) }}
                        </span>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Items Returned</span>
                    <span class="info-value">{{ $returnRequest->items->count() }} item(s)</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Total Refund Amount</span>
                    <span class="info-value amount">₱{{ number_format($totalRefund, 2) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Customer</span>
                    <span class="info-value">{{ $returnRequest->customer->name ?? 'N/A' }}</span>
                </div>
            </div>

            @if($returnRequest->admin)
                <div class="admin-notes">
                    <div class="admin-notes-title">✓ Processed By</div>
                    <div class="admin-notes-content">{{ $returnRequest->admin->name ?? 'Not yet assigned' }}</div>
                </div>
            @endif
        </div>

        <!-- Return Items -->
        <div class="info-section">
            <div class="section-title">🛍️ Returned Items</div>
            
            @if($returnRequest->items->count() > 0)
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th style="text-align: center;">Quantity</th>
                            <th style="text-align: center;">Unit Price</th>
                            <th style="text-align: right;">Refund Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($returnRequest->items as $item)
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <span class="product-name">{{ $item->product->name ?? 'Unknown Product' }}</span>
                                        <span class="product-id">Product ID: {{ $item->product_id }}</span>
                                    </div>
                                </td>
                                <td style="text-align: center;">{{ $item->quantity }}</td>
                                <td style="text-align: center;">₱{{ number_format($item->orderItem->unit_price ?? $item->refund_amount / $item->quantity, 2) }}</td>
                                <td style="text-align: right;">
                                    <span class="amount">₱{{ number_format($item->refund_amount, 2) }}</span>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $item->status }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="summary-row total">
                    <span>Total Refund Amount</span>
                    <span>₱{{ number_format($totalRefund, 2) }}</span>
                </div>

                @if($returnRequest->items->first()?->notes)
                    <div class="admin-notes">
                        <div class="admin-notes-title">📝 Item Notes</div>
                        <div class="admin-notes-content">
                            @foreach($returnRequest->items as $item)
                                @if($item->notes)
                                    <p><strong>{{ $item->product->name ?? 'Product' }}:</strong> {{ $item->notes }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            @else
                <div style="text-align: center; padding: 30px; color: #6b7280;">
                    No items found for this return request
                </div>
            @endif
        </div>

        <!-- Order Information -->
        <div class="info-section">
            <div class="section-title">📦 Order Information</div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Order Number</span>
                    <span class="info-value">#{{ $returnRequest->order->order_id }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Order Date</span>
                    <span class="info-value">{{ $returnRequest->order->order_date->format('M d, Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Order Status</span>
                    <span class="info-value">
                        <span class="status-badge" style="background: #dbeafe; color: #1e40af;">
                            {{ ucfirst($returnRequest->order->status) }}
                        </span>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Order Total</span>
                    <span class="info-value amount">₱{{ number_format($returnRequest->order->total_price, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('returns.index') }}" class="btn-secondary">Back to Returns</a>
            @if($returnRequest->status === 'pending')
                <form action="{{ route('returns.cancel', $returnRequest->request_id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger" onclick="return confirm('Are you sure you want to cancel this return request?');">
                        Cancel Request
                    </button>
                </form>
            @endif
        </div>

        <!-- Timeline (Optional - if you want to track status changes) -->
        <div class="info-section" style="margin-top: 20px;">
            <div class="section-title">📅 Timeline</div>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-time">Request Submitted</div>
                        <div class="timeline-text">{{ $returnRequest->created_at->format('M d, Y \a\t g:i A') }}</div>
                    </div>
                </div>

                @if($returnRequest->status !== 'pending')
                    <div class="timeline-item">
                        <div class="timeline-dot" style="background: #10b981;"></div>
                        <div class="timeline-content">
                            <div class="timeline-time">{{ ucfirst($returnRequest->status) }}</div>
                            <div class="timeline-text">{{ $returnRequest->updated_at->format('M d, Y \a\t g:i A') }}</div>
                        </div>
                    </div>
                @else
                    <div class="timeline-item" style="opacity: 0.5;">
                        <div class="timeline-dot" style="background: #d1d5db;"></div>
                        <div class="timeline-content">
                            <div class="timeline-time">Pending Review</div>
                            <div class="timeline-text">Our team will review your request within 24-48 hours</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
