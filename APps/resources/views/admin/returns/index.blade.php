@extends('layouts.admin')

@section('title', 'Manage Return Requests - Admin')

@section('content')
<style>
    .returns-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }

    .returns-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 600;
        color: #1f2937;
        letter-spacing: -0.5px;
    }

    .stats-grid-mini {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }

    .stat-mini-card {
        background: white;
        border-radius: 8px;
        padding: 14px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    .stat-mini-label {
        font-size: 12px;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .stat-mini-value {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
    }

    .stat-mini-desc {
        font-size: 11px;
        color: #9ca3af;
        margin-top: 4px;
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

    .returns-list {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    }

    .returns-table-wrapper {
        overflow-x: auto;
    }

    .returns-table {
        width: 100%;
        border-collapse: collapse;
    }

    .returns-table thead {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .returns-table th {
        padding: 16px 20px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .returns-table th:last-child {
        text-align: right;
    }

    .returns-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: background-color 0.15s ease;
    }

    .returns-table tbody tr:hover {
        background-color: #f9fafb;
    }

    .returns-table tbody tr:last-child {
        border-bottom: none;
    }

    .returns-table td {
        padding: 16px 20px;
        font-size: 14px;
        color: #374151;
    }

    .return-details {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .return-id {
        font-weight: 600;
        color: #1f2937;
        font-size: 13px;
    }

    .return-order-id {
        color: #9ca3af;
        font-size: 12px;
    }

    .customer-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .customer-name {
        font-weight: 500;
        color: #1f2937;
        font-size: 14px;
    }

    .customer-email {
        color: #9ca3af;
        font-size: 12px;
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
        background: #fee2e2;
        color: #991b1b;
    }

    .status-approved {
        background: #fef3c7;
        color: #92400e;
    }

    .status-rejected {
        background: #f3f4f6;
        color: #374151;
    }

    .status-refunded {
        background: #d1fae5;
        color: #065f46;
    }

    .refund-amount {
        font-weight: 700;
        color: #059669;
        font-size: 15px;
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

    .returns-table td:last-child {
        text-align: right;
    }
</style>

<div class="returns-header">
    <h1>🔄 Return Requests</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Back to Dashboard</a>
</div>

<!-- Statistics Cards -->
<div class="stats-grid-mini">
    <div class="stat-mini-card">
        <div class="stat-mini-label">Total Requests</div>
        <div class="stat-mini-value">{{ $stats['total'] ?? 0 }}</div>
        <div class="stat-mini-desc">All return requests</div>
    </div>
    <div class="stat-mini-card">
        <div class="stat-mini-label">Pending</div>
        <div class="stat-mini-value" style="color: #991b1b;">{{ $stats['pending'] ?? 0 }}</div>
        <div class="stat-mini-desc">Requires attention</div>
    </div>
    <div class="stat-mini-card">
        <div class="stat-mini-label">Under Review</div>
        <div class="stat-mini-value" style="color: #92400e;">{{ $stats['approved'] ?? 0 }}</div>
        <div class="stat-mini-desc">In processing</div>
    </div>
    <div class="stat-mini-card">
        <div class="stat-mini-label">Completed</div>
        <div class="stat-mini-value" style="color: #065f46;">{{ $stats['refunded'] ?? 0 }}</div>
        <div class="stat-mini-desc">Refunded</div>
    </div>
</div>

<!-- Status Filter Pills -->
<div class="filter-pills">
    <a href="{{ route('admin.returns.index') }}" class="filter-pill {{ !request('status') ? 'active' : '' }}">All Requests</a>
    <a href="{{ route('admin.returns.index', ['status' => 'pending']) }}" class="filter-pill {{ request('status') === 'pending' ? 'active' : '' }}">Pending</a>
    <a href="{{ route('admin.returns.index', ['status' => 'approved']) }}" class="filter-pill {{ request('status') === 'approved' ? 'active' : '' }}">Under Review</a>
    <a href="{{ route('admin.returns.index', ['status' => 'rejected']) }}" class="filter-pill {{ request('status') === 'rejected' ? 'active' : '' }}">Rejected</a>
    <a href="{{ route('admin.returns.index', ['status' => 'refunded']) }}" class="filter-pill {{ request('status') === 'refunded' ? 'active' : '' }}">Completed</a>
</div>

<div class="returns-list">
    @if($returns->count() > 0)
        <div class="returns-table-wrapper">
            <table class="returns-table">
                <thead>
                    <tr>
                        <th style="width: 20%;">Return Details</th>
                        <th style="width: 15%;">Date Submitted</th>
                        <th style="width: 20%;">Customer</th>
                        <th style="width: 10%;">Items</th>
                        <th style="width: 15%; text-align: right;">Refund Value</th>
                        <th style="width: 12%; text-align: center;">Status</th>
                        <th style="width: 12%; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($returns as $returnRequest)
                        <tr>
                            <td>
                                <div class="return-details">
                                    <span class="return-id">RR-{{ str_pad($returnRequest->request_id, 5, '0', STR_PAD_LEFT) }}</span>
                                    <span class="return-order-id">Order #{{ $returnRequest->order_id }}</span>
                                </div>
                            </td>
                            <td>
                                {{ $returnRequest->created_at->format('M d, Y') }}
                            </td>
                            <td>
                                <div class="customer-info">
                                    <span class="customer-name">{{ $returnRequest->customer->name ?? 'N/A' }}</span>
                                    <span class="customer-email">{{ $returnRequest->customer->email ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td>
                                {{ $returnRequest->items->count() }}
                            </td>
                            <td>
                                <span class="refund-amount">₱{{ number_format($returnRequest->items->sum('refund_amount'), 2) }}</span>
                            </td>
                            <td style="text-align: center;">
                                @php
                                    $statusClass = match($returnRequest->status) {
                                        'pending' => 'status-pending',
                                        'approved' => 'status-approved',
                                        'rejected' => 'status-rejected',
                                        'refunded' => 'status-refunded',
                                        default => 'status-pending'
                                    };
                                @endphp
                                <span class="status-pill {{ $statusClass }}">{{ ucfirst($returnRequest->status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.returns.show', $returnRequest->request_id) }}" class="action-button">Review</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($returns->hasPages())
            <div class="pagination-wrapper">
                {{ $returns->links('pagination.custom') }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <h3>No Return Requests</h3>
            <p>There are no return requests matching your filter criteria.</p>
        </div>
    @endif
</div>
@endsection