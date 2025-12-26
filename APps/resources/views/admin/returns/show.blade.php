@extends('layouts.admin')

@section('title', 'Review Return Request - Admin')

@section('content')
<style>
    .return-details-header {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        padding: 30px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .return-details-header h1 {
        margin: 0 0 10px 0;
        font-size: 2rem;
    }

    .return-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .meta-item {
        background: rgba(255, 255, 255, 0.1);
        padding: 12px;
        border-radius: 6px;
        backdrop-filter: blur(10px);
    }

    .meta-label {
        font-size: 0.8rem;
        opacity: 0.9;
        display: block;
        margin-bottom: 4px;
    }

    .meta-value {
        font-size: 1rem;
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
    .status-approved { background: #dbeafe; color: #1e40af; }
    .status-rejected { background: #fee2e2; color: #991b1b; }
    .status-refunded { background: #d1fae5; color: #065f46; }

    .content-section {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 16px 0;
        padding-bottom: 12px;
        border-bottom: 2px solid #f3f4f6;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 0.8rem;
        color: #6b7280;
        margin-bottom: 6px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 0.95rem;
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
        font-size: 0.8rem;
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

    .action-button {
        width: 100%;
        padding: 12px 16px;
        border: none;
        border-radius: 6px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 12px;
    }

    .btn-approve {
        background: #10b981;
        color: white;
    }

    .btn-approve:hover {
        background: #059669;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-reject {
        background: #ef4444;
        color: white;
    }

    .btn-reject:hover {
        background: #dc2626;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-refund {
        background: #2563eb;
        color: white;
    }

    .btn-refund:hover {
        background: #1d4ed8;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-family: inherit;
        font-size: 0.95rem;
        color: #111827;
        resize: vertical;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .timeline {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .timeline-item {
        padding: 16px;
        border-bottom: 1px solid #f3f4f6;
        position: relative;
        padding-left: 30px;
    }

    .timeline-item:last-child {
        border-bottom: none;
    }

    .timeline-item:before {
        content: '•';
        position: absolute;
        left: 8px;
        font-size: 1.5rem;
        color: #f59e0b;
        font-weight: bold;
    }

    .timeline-title {
        font-weight: 600;
        color: #111827;
        margin-bottom: 4px;
    }

    .timeline-time {
        font-size: 0.85rem;
        color: #6b7280;
    }

    .notes-box {
        background: #fef3c7;
        border: 1px solid #fcd34d;
        border-radius: 6px;
        padding: 12px;
        color: #92400e;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .two-column {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }

    @media (max-width: 1024px) {
        .two-column {
            grid-template-columns: 1fr;
        }
    }
</style>

<a href="{{ route('admin.returns.index') }}" class="back-button">← Back to Returns</a>

<div class="return-details-header">
    <h1>Return Request RR-{{ str_pad($returnRequest->request_id, 5, '0', STR_PAD_LEFT) }}</h1>
    <div class="return-meta-grid">
        <div class="meta-item">
            <span class="meta-label">Order</span>
            <span class="meta-value">ORD-{{ str_pad($returnRequest->order_id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Requested</span>
            <span class="meta-value">{{ $returnRequest->created_at->format('M d, Y') }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Status</span>
            <span class="status-badge status-{{ $returnRequest->status }}">{{ ucfirst($returnRequest->status) }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Refund Total</span>
            <span class="meta-value">₱{{ number_format($totalRefund, 2) }}</span>
        </div>
    </div>
</div>

<div class="two-column">
    <!-- Left Column: Details -->
    <div>
        <!-- Customer Information -->
        <div class="content-section">
            <h2 class="section-title">Customer Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Name</span>
                    <span class="info-value">
                        @php
                            $customerName = $returnRequest->customer?->name ?? $returnRequest->order?->customer?->name ?? 'N/A';
                        @endphp
                        {{ $customerName }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value">
                        @php
                            $customerEmail = $returnRequest->customer?->email ?? $returnRequest->order?->customer?->email ?? 'N/A';
                        @endphp
                        {{ $customerEmail }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Customer ID</span>
                    <span class="info-value">
                        @php
                            $customerId = $returnRequest->customer?->customer_id ?? $returnRequest->order?->customer?->customer_id ?? 'N/A';
                        @endphp
                        {{ $customerId }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Request Information -->
        <div class="content-section">
            <h2 class="section-title">Request Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Requested Date</span>
                    <span class="info-value">{{ $returnRequest->created_at->format('M d, Y') }}</span>
                    <small style="color: #6b7280;">{{ $returnRequest->created_at->format('h:i A') }}</small>
                </div>
                <div class="info-item">
                    <span class="info-label">Last Updated</span>
                    <span class="info-value">{{ $returnRequest->updated_at->format('M d, Y') }}</span>
                    <small style="color: #6b7280;">{{ $returnRequest->updated_at->format('h:i A') }}</small>
                </div>
            </div>
        </div>

        <!-- Return Items -->
        <div class="content-section">
            <h2 class="section-title">Return Items ({{ $returnRequest->items->count() }})</h2>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th style="width: 70px; text-align: center;">Qty</th>
                        <th style="width: 100px; text-align: right;">Unit Price</th>
                        <th style="width: 150px;">Reason</th>
                        <th style="width: 120px; text-align: right;">Refund Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($returnRequest->items as $item)
                        <tr>
                            <td>
                                <span class="product-name">{{ $item->product->name ?? 'Unknown Product' }}</span>
                                <br><small style="color: #9ca3af;">SKU: {{ $item->product->product_code ?? 'N/A' }}</small>
                            </td>
                            <td style="text-align: center;">{{ $item->quantity }}</td>
                            <td style="text-align: right;">₱{{ number_format($item->orderItem->unit_price ?? 0, 2) }}</td>
                            <td>
                                <span style="background: #dbeafe; color: #1e40af; padding: 4px 10px; border-radius: 4px; font-size: 0.85rem; font-weight: 500;">
                                    {{ $item->reason ?? 'N/A' }}
                                </span>
                                @if($item->notes)
                                    <div style="font-size: 0.85rem; color: #6b7280; margin-top: 6px; font-style: italic;">
                                        {{ $item->notes }}
                                    </div>
                                @endif
                            </td>
                            <td style="text-align: right;"><span class="amount">₱{{ number_format($item->refund_amount, 2) }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="display: flex; justify-content: flex-end; margin-top: 16px; padding-top: 16px; border-top: 2px solid #e5e7eb;">
                <div style="text-align: right;">
                    <div style="color: #6b7280; font-size: 0.9rem;">Total Refund Amount</div>
                    <div style="font-size: 1.5rem; font-weight: 700; color: #059669;">₱{{ number_format($totalRefund, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Customer Notes -->
        @if($returnRequest->notes)
            <div class="content-section">
                <h2 class="section-title">Customer Notes</h2>
                <div class="notes-box">
                    {{ $returnRequest->notes }}
                </div>
            </div>
        @endif
    </div>

    <!-- Right Column: Actions & Timeline -->
    <div>
        <!-- Actions Card -->
        @if($returnRequest->status === 'pending')
            <div class="content-section" style="border: 2px solid #f59e0b;">
                <h2 class="section-title" style="border-color: #f59e0b; color: #f59e0b;">Action Required</h2>
                
                <!-- Approve Section -->
                <form action="{{ route('admin.returns.approve', $returnRequest->request_id) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Add Notes (Optional)</label>
                        <textarea name="admin_notes" class="form-control" rows="3" placeholder="Optional: Add approval notes..."></textarea>
                    </div>
                    <button type="submit" class="action-button btn-approve">✓ Approve Return</button>
                </form>

                <!-- Reject Section -->
                <form action="{{ route('admin.returns.reject', $returnRequest->request_id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Rejection Reason (Required)</label>
                        <textarea name="admin_notes" class="form-control" rows="3" placeholder="Explain why this request is being rejected..." required></textarea>
                    </div>
                    <button type="submit" onclick="return confirm('Are you sure you want to reject this request?')" class="action-button btn-reject">✗ Reject Return</button>
                </form>
            </div>
        @elseif($returnRequest->status === 'approved')
            <div class="content-section" style="border: 2px solid #10b981;">
                <h2 class="section-title" style="border-color: #10b981; color: #10b981;">Refund Action</h2>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 16px;">This return has been approved. Process the refund to complete the request.</p>
                <form action="{{ route('admin.returns.refund', $returnRequest->request_id) }}" method="POST">
                    @csrf
                    <button type="submit" onclick="return confirm('Mark this return as refunded?')" class="action-button btn-refund">💰 Mark as Refunded</button>
                </form>
            </div>
        @else
            <div class="content-section">
                <h2 class="section-title">Status: {{ ucfirst($returnRequest->status) }}</h2>
                <div style="padding: 12px; background: #f3f4f6; border-radius: 6px; color: #6b7280; text-align: center;">
                    This request is no longer pending and cannot be modified.
                </div>
            </div>
        @endif

        <!-- Timeline Card -->
        <div class="content-section">
            <h2 class="section-title">Timeline</h2>
            <ul class="timeline">
                <!-- Created -->
                <li class="timeline-item">
                    <div class="timeline-title">📝 Request Created</div>
                    <div class="timeline-time">{{ $returnRequest->created_at->format('M d, Y h:i A') }}</div>
                </li>

                <!-- Status Update -->
                <li class="timeline-item">
                    <div class="timeline-title">
                        @if($returnRequest->status === 'pending')
                            ⏳ Pending Review
                        @elseif($returnRequest->status === 'approved')
                            ✓ Approved
                        @elseif($returnRequest->status === 'rejected')
                            ✗ Rejected
                        @else
                            💰 Refunded
                        @endif
                    </div>
                    <div class="timeline-time">
                        @if($returnRequest->status !== 'pending')
                            {{ $returnRequest->updated_at->format('M d, Y h:i A') }}
                        @else
                            Awaiting review
                        @endif
                    </div>
                </li>

                <!-- Admin Notes -->
                @if($returnRequest->admin_notes)
                    <li class="timeline-item">
                        <div class="timeline-title">📌 Admin Notes</div>
                        <div style="background: #f9fafb; padding: 10px; border-radius: 4px; margin-top: 6px; color: #374151; font-size: 0.9rem; line-height: 1.5;">
                            {{ $returnRequest->admin_notes }}
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
@endsection

