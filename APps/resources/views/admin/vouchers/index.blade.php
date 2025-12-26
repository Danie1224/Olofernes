@extends('layouts.admin')

@section('title', 'Voucher Management - Admin')

@section('content')
<style>
    .vouchers-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }

    .vouchers-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 600;
        color: #1f2937;
        letter-spacing: -0.5px;
    }

    .success-message, .error-message {
        padding: 16px 20px;
        border-radius: 8px;
        margin-bottom: 24px;
        font-size: 14px;
        font-weight: 500;
    }

    .success-message {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .error-message {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    .error-message ul {
        margin: 8px 0 0 20px;
        padding: 0;
    }

    .create-button {
        display: inline-block;
        padding: 12px 24px;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .create-button:hover {
        background: #1d4ed8;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .vouchers-list {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    }

    .vouchers-table-wrapper {
        overflow-x: auto;
    }

    .vouchers-table {
        width: 100%;
        border-collapse: collapse;
    }

    .vouchers-table thead {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .vouchers-table th {
        padding: 16px 20px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .vouchers-table th:last-child {
        text-align: right;
    }

    .vouchers-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: background-color 0.15s ease;
    }

    .vouchers-table tbody tr:hover {
        background-color: #f9fafb;
    }

    .vouchers-table tbody tr:last-child {
        border-bottom: none;
    }

    .vouchers-table td {
        padding: 16px 20px;
        font-size: 14px;
        color: #374151;
    }

    .voucher-code {
        font-weight: 600;
        color: #1f2937;
        font-family: 'Courier New', monospace;
        font-size: 13px;
        background: #f3f4f6;
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-block;
    }

    .discount-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
        white-space: nowrap;
    }

    .discount-percentage {
        background: #dbeafe;
        color: #1e40af;
    }

    .discount-fixed {
        background: #fce7f3;
        color: #831843;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
        white-space: nowrap;
    }

    .status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-inactive {
        background: #fef3c7;
        color: #92400e;
    }

    .distribution-count {
        font-weight: 600;
        color: #2563eb;
        font-size: 13px;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    .action-btn {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
        background: white;
        color: #374151;
    }

    .action-btn:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }

    .action-btn-delete {
        color: #dc2626;
        border-color: #fca5a5;
    }

    .action-btn-delete:hover {
        background: #fee2e2;
        border-color: #f87171;
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
        margin: 0 0 20px 0;
        color: #6b7280;
        font-size: 14px;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 24px;
        padding: 0 20px 20px;
    }
</style>

<div class="vouchers-header">
    <h1>Voucher Management</h1>
    <a href="{{ route('admin.vouchers.create') }}" class="create-button">
        ➕ Create New Voucher
    </a>
</div>

@if ($message = Session::get('success'))
    <div class="success-message">
        ✓ {{ $message }}
    </div>
@endif

@if ($errors->any())
    <div class="error-message">
        <strong>Errors:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="vouchers-list">
    @if ($vouchers->count() > 0)
        <div class="vouchers-table-wrapper">
            <table class="vouchers-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Discount</th>
                        <th>Status</th>
                        <th>Distributed</th>
                        <th>Usage</th>
                        <th>Valid Period</th>
                        <th style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vouchers as $voucher)
                        <tr>
                            <td>
                                <div class="voucher-code">{{ $voucher->code }}</div>
                            </td>
                            <td>
                                <div style="color: #6b7280; font-size: 13px;">
                                    {{ Str::limit($voucher->description ?? 'No description', 40) }}
                                </div>
                            </td>
                            <td>
                                <span class="discount-badge {{ $voucher->discount_type === 'percentage' ? 'discount-percentage' : 'discount-fixed' }}">
                                    @if ($voucher->discount_type === 'percentage')
                                        {{ $voucher->discount_value }}% off
                                    @else
                                        ₱{{ number_format($voucher->discount_value, 2) }}
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span class="status-badge {{ $voucher->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                    {{ ucfirst($voucher->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="distribution-count">{{ $voucher->user_vouchers_count }}</span> users
                            </td>
                            <td>
                                <span style="font-size: 13px; color: #6b7280;">
                                    {{ $voucher->usage_count ?? 0 }}
                                    @if ($voucher->usage_limit)
                                        <span style="color: #9ca3af;">/{{ $voucher->usage_limit }}</span>
                                    @else
                                        <span style="color: #9ca3af;">/∞</span>
                                    @endif
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 13px; color: #6b7280;">
                                    {{ $voucher->start_date->format('M d') }} - {{ $voucher->end_date->format('M d') }}
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.vouchers.show', $voucher->voucher_id) }}" class="action-btn" title="View">
                                        👁
                                    </a>
                                    <a href="{{ route('admin.vouchers.edit', $voucher->voucher_id) }}" class="action-btn" title="Edit">
                                        ✏️
                                    </a>
                                    <form action="{{ route('admin.vouchers.destroy', $voucher->voucher_id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete" title="Delete" onclick="return confirm('Delete this voucher?');">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $vouchers->links('pagination::simple-bootstrap-4') }}
        </div>
    @else
        <div class="empty-state">
            <h3>No Vouchers Yet</h3>
            <p>Create your first voucher to get started with promotions.</p>
            <a href="{{ route('admin.vouchers.create') }}" class="create-button" style="display: inline-block;">
                ➕ Create First Voucher
            </a>
        </div>
    @endif
</div>
@endsection
