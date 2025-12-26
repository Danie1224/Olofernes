<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin - TechStore')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <style>
        body { font-family: 'Inter', sans-serif; margin: 0; background: #f4f6f8; }
        .admin-wrapper { display: flex; min-height: 100vh; }
        .admin-sidebar { width: 260px; background: linear-gradient(180deg,#263238 0%,#1b2a2f 100%); color: #fff; padding: 22px 12px; }
        .brand { font-weight: 700; color: #00e5ff; text-align: center; margin-bottom: 18px; }
        .sidebar-nav { list-style: none; padding: 0; margin: 0; }
        .sidebar-nav li { margin: 8px 0; }
        .sidebar-nav a { color: #e6f7ff; text-decoration: none; display: flex; align-items: center; gap:10px; padding: 10px 14px; border-radius: 8px; font-weight:600; }
        .sidebar-nav a:hover { background: rgba(255,255,255,0.03); color: #fff; }
        .sidebar-nav .active { background: rgba(255,255,255,0.04); }
        .admin-main { flex: 1; padding: 20px; }
        .admin-top { display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px; }
        .admin-top h1 { margin: 0; font-size: 1.25rem; color: #333; }
        .admin-user { display:flex; align-items:center; gap:10px; }
        .admin-card { background: #fff; border-radius: 8px; padding: 18px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
        @media (max-width: 768px) { .admin-sidebar { display:none; } }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        @if(Auth::guard('admin')->check())
        <aside class="admin-sidebar">
            <div class="brand">
                <div style="font-size:20px;">TECHSTORE</div>
                <div style="font-size:12px; margin-top:6px;">ADMIN PANEL</div>
            </div>

            <ul class="sidebar-nav">
                <li><a href="{{ route('admin.dashboard') }}">🏠 Dashboard</a></li>
                <li><a href="{{ route('admin.orders.index') }}">📦 Placed Orders</a></li>
                <li><a href="{{ route('admin.products.index') }}">🛒 Products Management</a></li>
                <li><a href="{{ route('admin.customers.index') }}">👥 Customers</a></li>
                <li><a href="{{ route('admin.returns.index') }}">🔄 Return Requests</a></li>
                <li><a href="{{ route('admin.vouchers.index') }}">🎟️ Voucher Management</a></li>
            </ul>
        </aside>
        @endif

        <main class="admin-main">
            @yield('content')
        </main>
    </div>
</body>
</html>
