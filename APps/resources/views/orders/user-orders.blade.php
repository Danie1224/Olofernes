@extends('layouts.app')

@section('title', 'My Orders - TechStore')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-between align-center mb-4">
        <h1>My Orders</h1>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Continue Shopping</a>
    </div>  

    <!-- Orders Statistics -->
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            text-align: center;
        }

        .stat-card.green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .stat-card.blue {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .stat-card.orange {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            margin: 10px 0;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .filter-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .filter-section form {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-section select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.95rem;
        }

        .orders-list {
            display: grid;
            gap: 16px;
        }

        .order-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .order-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            border-color: #667eea;
        }

        .order-header {
            display: grid;
            grid-template-columns: 1fr auto auto;
            gap: 20px;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f3f4f6;
        }

        .order-info {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .order-id {
            font-weight: 600;
            color: #111827;
            font-size: 1.1rem;
        }

        .order-date {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-processing {
            background: #bfdbfe;
            color: #1e40af;
        }

        .status-shipped {
            background: #dbeafe;
            color: #0c4a6e;
        }

        .status-completed {
            background: #dcfce7;
            color: #166534;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #7f1d1d;
        }

        .order-details {
            display: grid;
            gap: 12px;
        }

        .order-items-table {
            width: 100%;
            margin-top: 12px;
            border-collapse: collapse;
        }

        .order-items-table thead {
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .order-items-table th {
            padding: 10px;
            text-align: left;
            font-size: 0.85rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .order-items-table td {
            padding: 10px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 0.9rem;
            color: #374151;
        }

        .item-name-col {
            font-weight: 500;
            color: #111827;
        }

        .item-qty-col {
            text-align: center;
            width: 80px;
        }

        .item-price-col {
            text-align: right;
            width: 100px;
        }

        .item-subtotal-col {
            text-align: right;
            width: 100px;
            font-weight: 600;
            color: #059669;
        }

        .completion-notification {
            display: flex;
            gap: 12px;
            padding: 12px 16px;
            background: #ecfdf5;
            border-left: 4px solid #10b981;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .completion-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #10b981;
            color: white;
            font-weight: bold;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .completion-content {
            display: flex;
            flex-direction: column;
            gap: 4px;
            font-size: 0.9rem;
            color: #065f46;
        }

        .completion-content strong {
            color: #047857;
            font-size: 0.95rem;
        }

        .completion-content p {
            margin: 0;
            color: #059669;
            font-size: 0.85rem;
        }

        .completion-content small {
            color: #10b981;
            font-size: 0.8rem;
        }

        .order-footer {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #f3f4f6;
        }

        .order-total {
            font-size: 1.2rem;
            font-weight: 700;
            color: #111827;
        }

        .order-total .label {
            color: #6b7280;
            font-size: 0.9rem;
            display: block;
        }

        .btn-view {
            display: inline-block;
            padding: 10px 16px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-view:hover {
            background: #5568d3;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: #f9fafb;
            border-radius: 8px;
            border: 2px dashed #e5e7eb;
        }

        .empty-state h3 {
            color: #111827;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #6b7280;
            margin-bottom: 20px;
        }

        .empty-state .btn {
            display: inline-block;
        }

        @media (max-width: 768px) {
            .order-header {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }

            .order-footer {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 15px;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            .filter-section form {
                flex-direction: column;
            }

            .filter-section select {
                width: 100%;
            }

            .order-items-table th,
            .order-items-table td {
                padding: 8px;
                font-size: 0.8rem;
            }

            .item-price-col,
            .item-subtotal-col {
                width: 70px;
            }

            .item-qty-col {
                width: 50px;
            }
        }
    </style>

    <!-- Stats Section -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Orders</div>
            <div class="stat-value" id="total-orders">0</div>
        </div>
        <div class="stat-card green">
            <div class="stat-label">Completed</div>
            <div class="stat-value" id="completed-orders">0</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-label">Pending</div>
            <div class="stat-value" id="pending-orders">0</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-label">Total Spent</div>
            <div class="stat-value" id="total-spent">$0</div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="{{ route('user.orders.index') }}">
            <select name="status" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Orders List -->
    @if($orders->count() > 0)
        <div class="orders-list">
            @foreach($orders as $order)
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-info">
                            <div class="order-id">Order #{{ $order->order_id }}</div>
                            <div class="order-date">{{ $order->order_date->format('M d, Y • H:i A') }}</div>
                        </div>
                        <span class="status-badge status-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        <div class="order-total">
                            <span class="label">Total:</span>
                            ₱{{ number_format($order->total_price, 2) }}
                        </div>
                    </div>

                    <!-- Completion Notification -->
                    @if($order->status === 'completed' && $order->completed_at)
                        <div class="completion-notification">
                            <div class="completion-icon">✓</div>
                            <div class="completion-content">
                                <strong>Order Completed</strong>
                                <p>Your order was marked as completed on {{ $order->completed_at->format('M d, Y • H:i A') }}</p>
                                @if($order->completed_by)
                                    <small>Processed by: Admin Team</small>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="order-details">
                        <div>
                            <strong>{{ $order->orderItems()->count() }} item{{ $order->orderItems()->count() !== 1 ? 's' : '' }}</strong> • Payment: <strong>{{ ucfirst($order->payment_method) }}</strong>
                        </div>
                        
                        <!-- Items Table -->
                        <table class="order-items-table">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th class="item-qty-col">Qty</th>
                                    <th class="item-price-col">Unit Price</th>
                                    <th class="item-subtotal-col">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td class="item-name-col">
                                            {{ $item->product->name ?? 'Unknown Product' }}
                                            @if($item->product)
                                                <br><small style="color: #9ca3af;">SKU: {{ $item->product->product_code }}</small>
                                            @endif
                                        </td>
                                        <td class="item-qty-col">{{ $item->quantity }}</td>
                                        <td class="item-price-col">₱{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="item-subtotal-col">₱{{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="order-footer">
                        <div>
                            <!-- Empty space for alignment -->
                        </div>
                        <a href="{{ route('user.orders.show', $order->order_id) }}" class="btn-view">View Full Details</a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="text-center mt-4">
            {{ $orders->links('pagination.custom') }}
        </div>
    @else
        <div class="empty-state">
            <h3>📦 No Orders Yet</h3>
            <p>You haven't placed any orders yet. Start shopping to see your orders here!</p>
            <a href="{{ route('products.index') }}" class="btn">Browse Products</a>
        </div>
    @endif
</div>

<script>
    // Load stats
    document.addEventListener('DOMContentLoaded', function() {
        fetch('{{ route("user.orders.stats") }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-orders').textContent = data.total_orders;
                document.getElementById('completed-orders').textContent = data.completed_orders;
                document.getElementById('pending-orders').textContent = data.pending_orders;
                document.getElementById('total-spent').textContent = '$' + parseFloat(data.total_spent).toFixed(2);
            })
            .catch(error => console.error('Error loading stats:', error));

        // Auto-refresh orders page every 30 seconds to sync status updates
        setInterval(function() {
            // Reload page silently in background to get latest order statuses
            fetch(window.location.href, { 
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest' 
                } 
            })
            .then(response => response.text())
            .then(html => {
                // Parse the new HTML and update only the orders list
                const parser = new DOMParser();
                const newDoc = parser.parseFromString(html, 'text/html');
                const newOrdersList = newDoc.querySelector('.orders-list');
                const currentOrdersList = document.querySelector('.orders-list');
                
                // Only update if orders list changed (status updates detected)
                if (newOrdersList && currentOrdersList && newOrdersList.innerHTML !== currentOrdersList.innerHTML) {
                    currentOrdersList.innerHTML = newOrdersList.innerHTML;
                    console.log('Order statuses updated');
                }
            })
            .catch(error => console.error('Error auto-refreshing orders:', error));
        }, 30000); // Refresh every 30 seconds
    });
</script>
@endsection
