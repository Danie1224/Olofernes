@extends('layouts.app')

@section('title', 'Return Requests - TechStore')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-between align-center mb-4">
        <h1>My Return Requests</h1>
        <a href="{{ route('returns.create') }}" class="btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
            + Request Return
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border: none; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px;">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <!-- Return Requests Statistics -->
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
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card.orange {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .stat-card.green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .stat-card.red {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
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

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fef08a;
            color: #92400e;
        }

        .status-approved {
            background: #dcfce7;
            color: #166534;
        }

        .status-refunded {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .return-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            align-items: start;
        }

        .return-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }

        .return-info h3 {
            font-size: 1.1rem;
            margin-bottom: 8px;
            color: #1f2937;
        }

        .return-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 12px;
            font-size: 0.9rem;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
        }

        .meta-label {
            color: #6b7280;
            font-size: 0.85rem;
            margin-bottom: 4px;
        }

        .meta-value {
            color: #1f2937;
            font-weight: 600;
        }

        .return-actions {
            display: flex;
            gap: 10px;
            flex-direction: column;
        }

        .return-actions a,
        .return-actions button {
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            font-size: 0.9rem;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-view {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
        }

        .btn-cancel {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-cancel:hover {
            background: #fecaca;
        }

        .filter-group {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 8px 16px;
            border: 2px solid #e5e7eb;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            color: #6b7280;
        }

        .filter-btn:hover {
            border-color: #667eea;
            color: #667eea;
        }

        .filter-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            background: #f9fafb;
            border-radius: 8px;
            border: 2px dashed #e5e7eb;
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .empty-state h3 {
            color: #1f2937;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #6b7280;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .return-card {
                grid-template-columns: 1fr;
            }

            .return-meta {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Returns</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-label">Pending</div>
            <div class="stat-value">{{ $stats['pending'] }}</div>
        </div>
        <div class="stat-card green">
            <div class="stat-label">Approved</div>
            <div class="stat-value">{{ $stats['approved'] }}</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">
            <div class="stat-label">Refunded</div>
            <div class="stat-value">{{ $stats['refunded'] }}</div>
        </div>
    </div>

    <!-- Filter Buttons -->
    <div class="filter-group">
        <a href="{{ route('returns.index') }}" class="filter-btn {{ !$status ? 'active' : '' }}">
            All Returns
        </a>
        <a href="{{ route('returns.index', ['status' => 'pending']) }}" class="filter-btn {{ $status === 'pending' ? 'active' : '' }}">
            Pending
        </a>
        <a href="{{ route('returns.index', ['status' => 'approved']) }}" class="filter-btn {{ $status === 'approved' ? 'active' : '' }}">
            Approved
        </a>
        <a href="{{ route('returns.index', ['status' => 'refunded']) }}" class="filter-btn {{ $status === 'refunded' ? 'active' : '' }}">
            Refunded
        </a>
    </div>

    <!-- Return Requests List -->
    @if($returnRequests->count() > 0)
        @foreach($returnRequests as $request)
            <div class="return-card">
                <div class="return-info">
                    <h3>Return Request #{{ $request->request_id }}</h3>
                    <div class="return-meta">
                        <div class="meta-item">
                            <span class="meta-label">Order ID</span>
                            <span class="meta-value">#{{ $request->order_id }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Items Count</span>
                            <span class="meta-value">{{ $request->items->count() }} item(s)</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Requested Date</span>
                            <span class="meta-value">{{ $request->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Total Refund</span>
                            <span class="meta-value" style="color: #10b981;">₱{{ number_format($request->items->sum('refund_amount'), 2) }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Status</span>
                            <span class="status-badge status-{{ $request->status }}">{{ ucfirst($request->status) }}</span>
                        </div>
                    </div>
                </div>
                <div class="return-actions">
                    <a href="{{ route('returns.show', $request->request_id) }}" class="btn-view">View Details</a>
                    @if($request->status === 'pending')
                        <form action="{{ route('returns.cancel', $request->request_id) }}" method="POST" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-cancel" onclick="return confirm('Are you sure you want to cancel this return request?');">Cancel Request</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach

        <!-- Pagination -->
        <div class="text-center mt-4">
            {{ $returnRequests->appends(request()->query())->links('pagination.custom') }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📦</div>
            <h3>No Return Requests</h3>
            <p>You haven't submitted any return requests yet. If you received a damaged product, we're here to help!</p>
            <a href="{{ route('returns.create') }}" class="btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                Create Return Request
            </a>
        </div>
    @endif
</div>
@endsection
