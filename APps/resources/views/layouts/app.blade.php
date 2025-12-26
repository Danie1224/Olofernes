<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'TechStore - Your Technology Destination')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            /* Basic styling for the website */
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { font-family: 'Inter', sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
            .btn { display: inline-block; padding: 12px 24px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; border: none; cursor: pointer; }
            .btn:hover { background: #0056b3; }
            .btn-secondary { background: #6c757d; }
            .btn-secondary:hover { background: #545b62; }
            .btn-danger { background: #dc3545; }
            .btn-danger:hover { background: #c82333; }
            .btn-success { background: #28a745; }
            .btn-success:hover { background: #1e7e34; }
            .form-group { margin-bottom: 15px; }
            .form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
            .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
            .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
            .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
            .card { background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 20px; }
            .grid { display: grid; gap: 20px; }
            .grid-2 { grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); }
            .grid-3 { grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); }
            .grid-4 { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .mb-2 { margin-bottom: 10px; }
            .mb-3 { margin-bottom: 15px; }
            .mb-4 { margin-bottom: 20px; }
            .mt-2 { margin-top: 10px; }
            .mt-3 { margin-top: 15px; }
            .mt-4 { margin-top: 20px; }
            .p-3 { padding: 15px; }
            .p-4 { padding: 20px; }
            .d-flex { display: flex; }
            .justify-between { justify-content: space-between; }
            .align-center { align-items: center; }
            .gap-2 { gap: 10px; }
            .gap-3 { gap: 15px; }
            .w-full { width: 100%; }
            .h-auto { height: auto; }
            .rounded { border-radius: 4px; }
            .shadow { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
            .bg-light { background-color: #f8f9fa; }
            .bg-primary { background-color: #007bff; color: white; }
            .bg-success { background-color: #28a745; color: white; }
            .bg-danger { background-color: #dc3545; color: white; }
            .text-primary { color: #007bff; }
            .text-success { color: #28a745; }
            .text-danger { color: #dc3545; }
            .text-muted { color: #6c757d; }
            .border { border: 1px solid #dee2e6; }
            .border-top { border-top: 1px solid #dee2e6; }
            .border-bottom { border-bottom: 1px solid #dee2e6; }
            .price { font-size: 1.25rem; font-weight: 600; color: #28a745; }
            .stock { font-size: 0.875rem; color: #6c757d; }
            .badge { display: inline-block; padding: 0.25em 0.4em; font-size: 75%; font-weight: 700; line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: 0.25rem; }
            .badge-success { color: #fff; background-color: #28a745; }
            .badge-warning { color: #212529; background-color: #ffc107; }
            .badge-danger { color: #fff; background-color: #dc3545; }
            .navbar { background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 1rem 0; }
            .navbar-brand { font-size: 1.5rem; font-weight: 700; color: #007bff; text-decoration: none; }
            .navbar-nav { display: flex; list-style: none; gap: 2rem; }
            .navbar-nav a { color: #333; text-decoration: none; font-weight: 500; }
            .navbar-nav a:hover { color: #007bff; }
            .footer { background: #333; color: white; padding: 2rem 0; margin-top: 3rem; }
            .hero { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 4rem 0; text-align: center; }
            .hero h1 { font-size: 3rem; margin-bottom: 1rem; }
            .hero p { font-size: 1.25rem; margin-bottom: 2rem; }
            @media (max-width: 768px) {
                .navbar-nav { flex-direction: column; gap: 1rem; }
                .hero h1 { font-size: 2rem; }
                .hero p { font-size: 1rem; }
                .container { padding: 0 15px; }
            }
        </style>
    @endif
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container d-flex justify-between align-center">
            <a href="{{ route('home') }}" class="navbar-brand">TechStore</a>
            <ul class="navbar-nav">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('products.index') }}">Products</a></li>
                @if(Auth::guard('web')->check())
                    <li><a href="{{ route('cart.index') }}">Cart <span class="cart-count" id="cart-count" style="display: inline-block; background: #dc3545; color: white; border-radius: 50%; width: 20px; height: 20px; font-size: 0.75rem; text-align: center; line-height: 20px;">0</span></a></li>
                @endif
                @if(Auth::guard('admin')->check())
                    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li>
                        <span>Welcome, {{ Auth::guard('admin')->user()->name }}</span>
                        <form method="POST" action="{{ route('admin.logout') }}" style="display: inline; margin-left: 10px;">
                            @csrf
                            <button type="submit" style="background: none; border: none; color: #333; cursor: pointer; text-decoration: none; font-weight: 500;">Logout</button>
                        </form>
                    </li>
                @elseif(Auth::guard('web')->check())
                    <li><a href="{{ route('user.orders.index') }}">📦 My Orders</a></li>
                    <li><a href="{{ route('returns.index') }}">🔄 Returns</a></li>
                    <li><a href="{{ route('vouchers.index') }}">Vouchers</a></li>
                    <li>
                        <span>Welcome, {{ Auth::guard('web')->user()->name }}</span>
                        <form method="POST" action="{{ route('logout.web') }}" style="display: inline; margin-left: 10px;">
                            @csrf
                            <button type="submit" style="background: none; border: none; color: #333; cursor: pointer; text-decoration: none; font-weight: 500;">Logout</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}">Login</a></li>
                @endif
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <!-- Flash Notifications -->
        @if(session('success'))
            <div id="successNotification" style="position: fixed; top: 20px; right: 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 16px 24px; border-radius: 8px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); z-index: 9999; max-width: 400px; animation: slideIn 0.3s ease;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 1.2rem;">✅</span>
                    <div>
                        <strong>Success</strong>
                        <div style="font-size: 0.9rem; opacity: 0.95;">{{ session('success') }}</div>
                    </div>
                </div>
            </div>
            <script>
                setTimeout(function() {
                    const notification = document.getElementById('successNotification');
                    if (notification) {
                        notification.style.animation = 'slideOut 0.3s ease';
                        setTimeout(function() {
                            notification.remove();
                        }, 300);
                    }
                }, 4000);
            </script>
        @endif

        @if(session('error'))
            <div id="errorNotification" style="position: fixed; top: 20px; right: 20px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 16px 24px; border-radius: 8px; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); z-index: 9999; max-width: 400px; animation: slideIn 0.3s ease;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 1.2rem;">❌</span>
                    <div>
                        <strong>Error</strong>
                        <div style="font-size: 0.9rem; opacity: 0.95;">{{ session('error') }}</div>
                    </div>
                </div>
            </div>
            <script>
                setTimeout(function() {
                    const notification = document.getElementById('errorNotification');
                    if (notification) {
                        notification.style.animation = 'slideOut 0.3s ease';
                        setTimeout(function() {
                            notification.remove();
                        }, 300);
                    }
                }, 4000);
            </script>
        @endif

        <style>
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        </style>

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} TechStore. All rights reserved.</p>
                <p>Your Technology Destination</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Update cart count
     function updateCartCount(newCount) {
    // 1. Get the element (This might return null)
    const cartCountElement = document.getElementById('cart-count'); 

    // 2. Add a check (Guard Clause) before using it!
    if (cartCountElement) {
        // This code only runs if the element was successfully found
        cartCountElement.textContent = newCount; 
    } else {
        // You can log an error here to remind yourself to fix the HTML/Selector
        console.error("Cart count element not found in the DOM!");
    }
}
    </script>

    @stack('scripts')
</body>
</html>
