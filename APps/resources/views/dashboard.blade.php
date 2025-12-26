@extends('layouts.admin')

@section('title', 'Admin Dashboard - TechStore')

@section('content')
<div class="container mt-4">
    <h1>Admin Dashboard</h1>
    
    @php
        $totalUsers = \App\Models\User::count();
        $totalProducts = \App\Models\Product::count();
        $totalOrders = \App\Models\Order::count();
        $totalCustomers = \App\Models\Customer::count();
        
        $totalSales = \App\Models\Order::sum('total_price');
        $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
        $completedOrders = \App\Models\Order::where('status', 'completed')->count();
    @endphp
    
    <!-- Analytics Section (minimal, attractive cards) -->
    <style>
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-top: 16px; }
        .stat-card { display: flex; align-items: center; gap: 14px; background: #ffffff; border-radius: 12px; padding: 16px; box-shadow: 0 6px 18px rgba(24,24,24,0.06); }
        .stat-icon { width: 56px; height: 56px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 22px; flex-shrink: 0; }
        .stat-body h3 { margin: 0; font-size: 0.95rem; color: #6b7280; font-weight: 600; }
        .stat-number { margin: 6px 0; font-size: 1.6rem; font-weight: 800; color: #111827; }
        .stat-desc { color: #9ca3af; font-size: 0.85rem; }
        .accent-blue { background: linear-gradient(135deg,#3b82f6,#60a5fa); }
        .accent-green { background: linear-gradient(135deg,#10b981,#34d399); }
        .accent-amber { background: linear-gradient(135deg,#f59e0b,#fbbf24); }
        .accent-red { background: linear-gradient(135deg,#ef4444,#f97316); }
        .accent-indigo { background: linear-gradient(135deg,#6366f1,#818cf8); }
        .accent-teal { background: linear-gradient(135deg,#06b6d4,#34d399); }
        @media (max-width:600px){ .stat-number{ font-size:1.25rem; } .stat-icon{ width:48px;height:48px;font-size:18px } }
    </style>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon accent-blue">👥</div>
            <div class="stat-body">
                <h3>Total Users</h3>
                <div class="stat-number">{{ $totalUsers }}</div>
                <div class="stat-desc">Registered users in the system</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon accent-green">🛍️</div>
            <div class="stat-body">
                <h3>Total Products</h3>
                <div class="stat-number">{{ $totalProducts }}</div>
                <div class="stat-desc">Products in catalog</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon accent-amber">📦</div>
            <div class="stat-body">
                <h3>Total Orders</h3>
                <div class="stat-number">{{ $totalOrders }}</div>
                <div class="stat-desc">All-time orders</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon accent-red">💰</div>
            <div class="stat-body">
                <h3>Total Sales</h3>
                <div class="stat-number">${{ number_format($totalSales, 2) }}</div>
                <div class="stat-desc">Total revenue generated</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon accent-indigo">⏳</div>
            <div class="stat-body">
                <h3>Pending Orders</h3>
                <div class="stat-number">{{ $pendingOrders }}</div>
                <div class="stat-desc">Orders awaiting processing</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon accent-teal">✅</div>
            <div class="stat-body">
                <h3>Completed Orders</h3>
                <div class="stat-number">{{ $completedOrders }}</div>
                <div class="stat-desc">Successfully completed orders</div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions removed to avoid duplication with sidebar -->
</div>
@endsection
