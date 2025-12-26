@extends('layouts.admin')

@section('title', 'Manage Products - Admin')

@section('content')
<style>
    .products-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
    }

    .filter-pills {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .filter-pill {
        padding: 8px 16px;
        border-radius: 20px;
        background-color: #f3f4f6;
        border: none;
        cursor: pointer;
        font-size: 14px;
        color: #6b7280;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .filter-pill:hover {
        background-color: #e5e7eb;
        color: #374151;
    }

    .filter-pill.active {
        background-color: #2563eb;
        color: white;
    }

    .products-list {
        display: flex;
        flex-direction: column;
    }

    .products-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
    }

    .products-table thead {
        background-color: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .products-table thead th {
        padding: 16px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .products-table tbody tr {
        border-bottom: 1px solid #e5e7eb;
        transition: background-color 0.2s ease;
    }

    .products-table tbody tr:hover {
        background-color: #f9fafb;
    }

    .products-table tbody tr:last-child {
        border-bottom: none;
    }

    .products-table tbody td {
        padding: 16px;
        font-size: 14px;
        color: #374151;
    }

    .product-info {
        display: flex;
        flex-direction: column;
    }

    .product-name {
        font-weight: 600;
        color: #111827;
        margin-bottom: 4px;
    }

    .product-code {
        font-size: 12px;
        color: #9ca3af;
    }

    .stock-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
        min-width: 60px;
    }

    .stock-in {
        background-color: #d1fae5;
        color: #065f46;
    }

    .stock-low {
        background-color: #fef3c7;
        color: #92400e;
    }

    .stock-out {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .category-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 12px;
        background-color: #dbeafe;
        color: #1e40af;
        font-size: 13px;
        font-weight: 500;
    }

    .actions-cell {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    .action-button {
        padding: 8px 16px;
        background-color: #2563eb;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .action-button:hover {
        background-color: #1d4ed8;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .action-button.danger {
        background-color: #ef4444;
    }

    .action-button.danger:hover {
        background-color: #dc2626;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 24px;
        padding-top: 16px;
        border-top: 1px solid #e5e7eb;
    }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        text-align: center;
    }

    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }

    .empty-state-title {
        font-size: 20px;
        font-weight: 600;
        color: #111827;
        margin-bottom: 8px;
    }

    .empty-state-text {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 24px;
    }
</style>

<div class="container mt-4">
    <div class="products-header">
        <h1>📦 Manage Products</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-success">+ Add New Product</a>
    </div>

    @if($products->count() > 0)
        <div class="filter-pills">
            <a href="{{ route('admin.products.index') }}" class="filter-pill {{ $filter === null ? 'active' : '' }}">
                All Products
            </a>
            <a href="{{ route('admin.products.index', ['filter' => 'in_stock']) }}" class="filter-pill {{ $filter === 'in_stock' ? 'active' : '' }}">
                In Stock
            </a>
            <a href="{{ route('admin.products.index', ['filter' => 'low_stock']) }}" class="filter-pill {{ $filter === 'low_stock' ? 'active' : '' }}">
                Low Stock
            </a>
            <a href="{{ route('admin.products.index', ['filter' => 'out_of_stock']) }}" class="filter-pill {{ $filter === 'out_of_stock' ? 'active' : '' }}">
                Out of Stock
            </a>
        </div>

        <div class="products-list">
            <table class="products-table">
                <thead>
                    <tr>
                        <th style="width: 30%;">Product Info</th>
                        <th style="width: 15%;">Category</th>
                        <th style="width: 15%;">Price</th>
                        <th style="width: 15%;">Stock</th>
                        <th style="width: 25%; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>
                                <div class="product-info">
                                    @if($product->image)
                                        <div style="margin-bottom: 8px; overflow: hidden; border-radius: 4px; width: 60px; height: 60px;">
                                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    @endif
                                    <span class="product-name">{{ $product->name }}</span>
                                    <span class="product-code">SKU: {{ $product->product_code }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="category-badge">{{ $product->category ?: 'Uncategorized' }}</span>
                            </td>
                            <td>
                                <span style="font-weight: 600; color: #059669;">${{ number_format($product->price, 2) }}</span>
                            </td>
                            <td>
                                @if($product->stock_quantity > 10)
                                    <span class="stock-badge stock-in">{{ $product->stock_quantity }} units</span>
                                @elseif($product->stock_quantity > 0)
                                    <span class="stock-badge stock-low">{{ $product->stock_quantity }} units</span>
                                @else
                                    <span class="stock-badge stock-out">Out of Stock</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions-cell">
                                    <a href="{{ route('admin.products.edit', $product->product_id) }}" class="action-button">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="pagination-wrapper">
                {{ $products->links('pagination.custom') }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📦</div>
            <div class="empty-state-title">No Products Found</div>
            <div class="empty-state-text">Start by creating your first product to manage inventory.</div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-success">+ Add New Product</a>
        </div>
    @endif
</div>
@endsection
