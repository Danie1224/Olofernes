@extends('layouts.admin')

@section('title', 'Voucher Details - Admin')

@section('content')
<style>
    .details-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 32px;
    }

    .details-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 600;
        color: #1f2937;
        letter-spacing: -0.5px;
    }

    .header-actions {
        display: flex;
        gap: 12px;
    }

    .action-button {
        padding: 10px 16px;
        background: white;
        color: #374151;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .action-button:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }

    .action-button.primary {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }

    .action-button.primary:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
    }

    .success-message {
        padding: 16px 20px;
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
        border-radius: 8px;
        margin-bottom: 24px;
        font-size: 14px;
        font-weight: 500;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        border-left: 4px solid #2563eb;
    }

    .stat-card.success {
        border-left-color: #059669;
    }

    .stat-card.warning {
        border-left-color: #f59e0b;
    }

    .stat-card.info {
        border-left-color: #1e40af;
    }

    .stat-label {
        font-size: 12px;
        color: #9ca3af;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 600;
        color: #1f2937;
    }

    .stat-subtext {
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
    }

    .details-container {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 24px;
    }

    .info-card {
        background: white;
        border-radius: 8px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        height: fit-content;
    }

    .info-card h2 {
        margin: 0 0 20px 0;
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
    }

    .info-row {
        margin-bottom: 16px;
    }

    .info-row:last-child {
        margin-bottom: 0;
    }

    .info-label {
        font-size: 11px;
        color: #9ca3af;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 14px;
        font-weight: 500;
        color: #1f2937;
    }

    .voucher-code {
        display: inline-block;
        padding: 6px 12px;
        background: #f3f4f6;
        color: #1f2937;
        border-radius: 4px;
        font-weight: 600;
        font-family: 'Courier New', monospace;
        font-size: 12px;
    }

    .list-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    }

    .list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .list-header h2 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
    }

    .redistribute-btn {
        padding: 8px 16px;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .redistribute-btn:hover {
        background: #1d4ed8;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
    }

    .users-table thead {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .users-table th {
        padding: 16px 24px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .users-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: background-color 0.15s ease;
    }

    .users-table tbody tr:hover {
        background-color: #f9fafb;
    }

    .users-table tbody tr:last-child {
        border-bottom: none;
    }

    .users-table td {
        padding: 16px 24px;
        font-size: 13px;
        color: #374151;
    }

    .user-name {
        font-weight: 600;
        color: #1f2937;
    }

    .user-email {
        color: #6b7280;
        font-size: 12px;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-align: center;
        white-space: nowrap;
    }

    .status-available {
        background: #d1fae5;
        color: #065f46;
    }

    .status-used {
        background: #f3f4f6;
        color: #6b7280;
    }

    .empty-state {
        text-align: center;
        padding: 48px 24px;
        background: #f9fafb;
    }

    .empty-state p {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        padding: 24px;
        border-top: 1px solid #e5e7eb;
    }
</style>

<div class="details-header">
    <div>
        <h1>Voucher Details: <span class="voucher-code">{{ $voucher->code }}</span></h1>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.vouchers.edit', $voucher->voucher_id) }}" class="action-button primary">
            ✏️ Edit
        </a>
        <a href="{{ route('admin.vouchers.index') }}" class="action-button">
            ← Back
        </a>
    </div>
</div>

@if ($message = Session::get('success'))
    <div class="success-message">
        ✓ {{ $message }}
    </div>
@endif

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Status</div>
        <div class="stat-value">
            @if ($voucher->status === 'active')
                <span style="color: #059669;">✓</span>
            @else
                <span style="color: #f59e0b;">⏸</span>
            @endif
            {{ ucfirst($voucher->status) }}
        </div>
    </div>

    <div class="stat-card info">
        <div class="stat-label">Discount</div>
        <div class="stat-value" style="color: #059669;">
            @if ($voucher->discount_type === 'percentage')
                {{ $voucher->discount_value }}%
            @else
                ₱{{ number_format($voucher->discount_value, 2) }}
            @endif
        </div>
        <div class="stat-subtext">{{ $voucher->discount_type === 'percentage' ? 'Percentage' : 'Fixed Amount' }}</div>
    </div>

    <div class="stat-card success">
        <div class="stat-label">Distributed To</div>
        <div class="stat-value">{{ $voucher->user_vouchers_count }}</div>
        <div class="stat-subtext">users have this voucher</div>
    </div>

    <div class="stat-card warning">
        <div class="stat-label">Usage</div>
        <div class="stat-value">
            {{ $voucher->usage_count ?? 0 }}
            @if ($voucher->usage_limit)
                <span style="font-size: 14px; color: #9ca3af;">/ {{ $voucher->usage_limit }}</span>
            @else
                <span style="font-size: 14px; color: #9ca3af;">/ ∞</span>
            @endif
        </div>
        <div class="stat-subtext">times redeemed</div>
    </div>
</div>

<div class="details-container">
    <!-- Left Column: Voucher Info -->
    <div class="info-card">
        <h2>ℹ️ Voucher Information</h2>
        
        <div class="info-row">
            <div class="info-label">Code</div>
            <div class="info-value"><span class="voucher-code">{{ $voucher->code }}</span></div>
        </div>

        <div class="info-row">
            <div class="info-label">Description</div>
            <div class="info-value">{{ $voucher->description ?? '—' }}</div>
        </div>

        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 16px 0;">

        <div class="info-row">
            <div class="info-label">Discount</div>
            <div class="info-value">
                @if ($voucher->discount_type === 'percentage')
                    {{ $voucher->discount_value }}% Off
                @else
                    ₱{{ number_format($voucher->discount_value, 2) }} Off
                @endif
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Usage Limit</div>
            <div class="info-value">
                @if ($voucher->usage_limit)
                    {{ $voucher->usage_limit }} total uses<br>
                    <span style="font-size: 11px; color: #6b7280;">
                        Used: {{ $voucher->usage_count ?? 0 }} ({{ round(($voucher->usage_count ?? 0) / $voucher->usage_limit * 100, 1) }}%)
                    </span>
                @else
                    Unlimited
                @endif
            </div>
        </div>

        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 16px 0;">

        <div class="info-row">
            <div class="info-label">Valid From</div>
            <div class="info-value">{{ $voucher->start_date->format('M d, Y') }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Valid Until</div>
            <div class="info-value">{{ $voucher->end_date->format('M d, Y') }}</div>
        </div>

        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 16px 0;">

        <div class="info-row">
            <div class="info-label">Created</div>
            <div class="info-value" style="font-size: 12px;">{{ $voucher->created_at->format('M d, Y H:i A') }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Last Updated</div>
            <div class="info-value" style="font-size: 12px;">{{ $voucher->updated_at->format('M d, Y H:i A') }}</div>
        </div>
    </div>

    <!-- Right Column: Distribution List -->
    <div class="list-card">
        <div class="list-header">
            <h2>Users with This Voucher ({{ $voucher->user_vouchers_count }})</h2>
            <button class="redistribute-btn" title="Redistribute to new users" onclick="redistributeVoucher()">
                ↻ Redistribute
            </button>
        </div>
        
        @if ($distributedUsers->count() > 0)
            <table class="users-table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Received</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($distributedUsers as $userVoucher)
                        <tr>
                            <td><span style="color: #9ca3af; font-size: 12px;">{{ $userVoucher->user_id }}</span></td>
                            <td><span class="user-name">{{ $userVoucher->user?->name ?? 'Unknown' }}</span></td>
                            <td><span class="user-email">{{ $userVoucher->user?->email ?? 'N/A' }}</span></td>
                            <td>
                                @if ($userVoucher->status === 'available')
                                    <span class="status-badge status-available">✓ Available</span>
                                @elseif ($userVoucher->status === 'used')
                                    <span class="status-badge status-used">✓✓ Used</span>
                                @else
                                    <span class="status-badge status-used">{{ ucfirst($userVoucher->status) }}</span>
                                @endif
                            </td>
                            <td><span style="font-size: 12px; color: #9ca3af;">{{ $userVoucher->created_at->format('M d') }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination">
                {{ $distributedUsers->links('pagination::simple-bootstrap-4') }}
            </div>
        @else
            <div class="empty-state">
                <p>ℹ️ This voucher has not been distributed to any users yet.</p>
            </div>
        @endif
    </div>
</div>

<!-- Redistribute Form (Hidden) -->
<form id="redistributeForm" action="{{ route('admin.vouchers.redistribute', $voucher->voucher_id) }}" method="POST" style="display:none;">
    @csrf
</form>

<script>
function redistributeVoucher() {
    if (confirm('Redistribute this voucher to users who don\'t have it yet?')) {
        document.getElementById('redistributeForm').submit();
    }
}
</script>
@endsection
