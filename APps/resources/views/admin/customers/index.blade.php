@extends('layouts.admin')

@section('title', 'Manage Customers - Admin')

@section('content')
<style>
    .customers-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }

    .customers-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 600;
        color: #1f2937;
        letter-spacing: -0.5px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 16px;
        margin-top: 32px;
    }

    .customers-list {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        margin-bottom: 24px;
    }

    .customers-table-wrapper {
        overflow-x: auto;
    }

    .customers-table {
        width: 100%;
        border-collapse: collapse;
    }

    .customers-table thead {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .customers-table th {
        padding: 16px 20px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .customers-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: background-color 0.15s ease;
    }

    .customers-table tbody tr:hover {
        background-color: #f9fafb;
    }

    .customers-table tbody tr:last-child {
        border-bottom: none;
    }

    .customers-table td {
        padding: 16px 20px;
        font-size: 14px;
        color: #374151;
    }

    .user-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .user-name {
        font-weight: 500;
        color: #1f2937;
    }

    .user-email {
        color: #6b7280;
        font-size: 13px;
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

    .status-verified {
        background: #d1fae5;
        color: #065f46;
    }

    .status-unverified {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-inactive {
        background: #f3f4f6;
        color: #6b7280;
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

    .filter-pills {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
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
</style>

<div class="customers-header">
    <h1>👥 Customers Management</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Back to Dashboard</a>
</div>

<!-- Registered Users Section -->
<h2 class="section-title">👤 Registered Users</h2>

<!-- User Status Filter Pills -->
<div class="filter-pills">
    <a href="{{ route('admin.customers.index') }}" class="filter-pill {{ $userStatus === null ? 'active' : '' }}">
        All Users
    </a>
    <a href="{{ route('admin.customers.index', ['user_status' => 'verified']) }}" class="filter-pill {{ $userStatus === 'verified' ? 'active' : '' }}">
        Verified
    </a>
    <a href="{{ route('admin.customers.index', ['user_status' => 'unverified']) }}" class="filter-pill {{ $userStatus === 'unverified' ? 'active' : '' }}">
        Unverified
    </a>
</div>

<div class="customers-list">
    @if($users->count() > 0)
        <div class="customers-table-wrapper">
            <table class="customers-table">
                <thead>
                    <tr>
                        <th style="width: 10%;">ID</th>
                        <th style="width: 30%;">Name</th>
                        <th style="width: 35%;">Email</th>
                        <th style="width: 15%;">Registered</th>
                        <th style="width: 10%;">Verified</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>#{{ $user->id }}</td>
                            <td>
                                <div class="user-name">{{ $user->name }}</div>
                            </td>
                            <td>
                                <div class="user-email">{{ $user->email }}</div>
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <span class="status-badge {{ $user->email_verified_at ? 'status-verified' : 'status-unverified' }}">
                                    {{ $user->email_verified_at ? '✓ Yes' : '✗ No' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="pagination-wrapper">
                {{ $users->links('pagination.custom') }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <h3>No Users Yet</h3>
            <p>Registered users will appear here.</p>
        </div>
    @endif
</div>

<!-- Customer Profiles Section -->
@if($customers->count() > 0)
    <h2 class="section-title">📦 Customer Profiles</h2>

    <!-- Customer Status Filter Pills -->
    <div class="filter-pills">
        <a href="{{ route('admin.customers.index') }}" class="filter-pill {{ $customerStatus === null ? 'active' : '' }}">
            All Customers
        </a>
        <a href="{{ route('admin.customers.index', ['customer_status' => 'active']) }}" class="filter-pill {{ $customerStatus === 'active' ? 'active' : '' }}">
            Active
        </a>
        <a href="{{ route('admin.customers.index', ['customer_status' => 'inactive']) }}" class="filter-pill {{ $customerStatus === 'inactive' ? 'active' : '' }}">
            Inactive
        </a>
    </div>

    <div class="customers-list">
        <div class="customers-table-wrapper">
            <table class="customers-table">
                <thead>
                    <tr>
                        <th style="width: 10%;">ID</th>
                        <th style="width: 30%;">Name</th>
                        <th style="width: 35%;">Email</th>
                        <th style="width: 15%;">Phone</th>
                        <th style="width: 10%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                        <tr>
                            <td>#{{ $customer->customer_id }}</td>
                            <td>
                                <div class="user-name">{{ $customer->name }}</div>
                            </td>
                            <td>
                                <div class="user-email">{{ $customer->email }}</div>
                            </td>
                            <td>{{ $customer->phone ?: '—' }}</td>
                            <td>
                                <span class="status-badge {{ $customer->status === 'Active' ? 'status-active' : 'status-inactive' }}">
                                    {{ $customer->status ?: 'Active' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
