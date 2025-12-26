@extends('layouts.app')

@section('title', 'My Vouchers - TechStore')

@section('content')
<div class="container mt-4">
    <style>
        .vouchers-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #111827;
            margin: 0 0 10px 0;
        }

        .page-header p {
            color: #6b7280;
            font-size: 1.1rem;
            margin: 0;
        }

        .alerts-section {
            margin-bottom: 25px;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border-left: 4px solid #22c55e;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f3f4f6;
        }

        .vouchers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .voucher-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            display: flex;
            flex-direction: column;
        }

        .voucher-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            border-color: #667eea;
            transform: translateY(-4px);
        }

        .voucher-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
        }

        .voucher-card.used .voucher-header {
            background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
            opacity: 0.7;
        }

        .voucher-card.expired .voucher-header {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            opacity: 0.8;
        }

        .voucher-card.not-started .voucher-header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .voucher-code {
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 8px;
            word-break: break-all;
        }

        .voucher-discount {
            font-size: 2rem;
            font-weight: 700;
        }

        .voucher-discount-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-top: 5px;
        }

        .voucher-body {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .voucher-description {
            color: #6b7280;
            font-size: 0.95rem;
            margin-bottom: 15px;
            min-height: 40px;
        }

        .voucher-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f3f4f6;
        }

        .detail-item {
            font-size: 0.85rem;
        }

        .detail-label {
            color: #9ca3af;
            display: block;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .detail-value {
            color: #111827;
            font-weight: 600;
        }

        .voucher-status {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 6px;
            background: #f3f4f6;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
        }

        .status-used {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-expired {
            background: #fecaca;
            color: #7f1d1d;
        }

        .status-not-started {
            background: #fed7aa;
            color: #92400e;
        }

        .expiry-warning {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px;
            background: #fef3c7;
            border-radius: 6px;
            color: #92400e;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .voucher-actions {
            display: flex;
            gap: 10px;
            margin-top: auto;
        }

        .btn-small {
            flex: 1;
            padding: 10px 12px;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }

        .btn-use {
            background: #667eea;
            color: white;
        }

        .btn-use:hover {
            background: #5568d3;
        }

        .btn-remove {
            background: #e5e7eb;
            color: #6b7280;
        }

        .btn-remove:hover {
            background: #d1d5db;
            color: #111827;
        }

        .btn-small:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .claim-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 40px;
        }

        .claim-section h2 {
            margin: 0 0 15px 0;
            font-size: 1.5rem;
        }

        .claim-section p {
            margin: 0 0 20px 0;
            font-size: 1rem;
            opacity: 0.95;
        }

        .claim-form {
            display: flex;
            gap: 10px;
        }

        .claim-input {
            flex: 1;
            padding: 12px 16px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            text-transform: uppercase;
        }

        .claim-input::placeholder {
            color: #999;
        }

        .claim-button {
            padding: 12px 24px;
            background: white;
            color: #667eea;
            border: none;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .claim-button:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: #f9fafb;
            border-radius: 12px;
            border: 2px dashed #e5e7eb;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 15px;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin: 0 0 10px 0;
        }

        .empty-state p {
            color: #6b7280;
            margin: 0 0 20px 0;
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.8rem;
            }

            .vouchers-grid {
                grid-template-columns: 1fr;
            }

            .claim-form {
                flex-direction: column;
            }
        }
    </style>

    <div class="vouchers-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>💳 My Vouchers</h1>
            <p>Claim and manage your discount vouchers</p>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alerts-section">
                <div class="alert alert-success">
                    <span>✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alerts-section">
                <div class="alert alert-error">
                    <span>✕</span>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="alerts-section">
                @foreach($errors->all() as $error)
                    <div class="alert alert-error">
                        <span>✕</span>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Claim Voucher Section -->
        <div class="claim-section">
            <h2>🎁 Have a Voucher Code?</h2>
            <p>Enter your voucher code below to claim and start saving!</p>
            <form method="POST" action="{{ route('vouchers.claim') }}" class="claim-form">
                @csrf
                <input 
                    type="text" 
                    name="voucher_code" 
                    class="claim-input" 
                    placeholder="Enter voucher code"
                    maxlength="50"
                    required
                >
                <button type="submit" class="claim-button">Claim Voucher</button>
            </form>
        </div>

        <!-- Claimed Vouchers Section -->
        <div>
            <h2 class="section-title">📦 Your Vouchers ({{ $voucherDetails->count() }})</h2>

            @if($voucherDetails->count() > 0)
                <div class="vouchers-grid">
                    @foreach($voucherDetails as $voucher)
                        <div class="voucher-card {{ $voucher['is_used'] ? 'used' : '' }} {{ $voucher['is_expired'] ? 'expired' : '' }} {{ $voucher['not_started'] ? 'not-started' : '' }}">
                            <!-- Voucher Header -->
                            <div class="voucher-header">
                                <div class="voucher-code">{{ $voucher['code'] }}</div>
                                <div class="voucher-discount">
                                    @if($voucher['discount_type'] === 'percentage')
                                        {{ (int)$voucher['discount_value'] }}%
                                    @else
                                        ${{ number_format($voucher['discount_value'], 2) }}
                                    @endif
                                </div>
                                <div class="voucher-discount-label">
                                    {{ $voucher['discount_type'] === 'percentage' ? 'Discount' : 'Off' }}
                                </div>
                            </div>

                            <!-- Voucher Body -->
                            <div class="voucher-body">
                                <p class="voucher-description">{{ $voucher['description'] }}</p>

                                <!-- Status Badge -->
                                <div class="voucher-status">
                                    @if($voucher['is_used'])
                                        <span class="status-badge status-used">✓ Used</span>
                                    @elseif($voucher['is_expired'])
                                        <span class="status-badge status-expired">Expired</span>
                                    @elseif($voucher['not_started'])
                                        <span class="status-badge status-not-started">Coming Soon</span>
                                    @else
                                        <span class="status-badge status-active">✓ Active</span>
                                    @endif
                                </div>

                                <!-- Expiry Warning -->
                                @if(!$voucher['is_used'] && !$voucher['is_expired'] && !$voucher['not_started'] && $voucher['days_until_expiry'] <= 7)
                                    <div class="expiry-warning">
                                        ⏰ Expires in <strong>{{ $voucher['days_until_expiry'] }} day{{ $voucher['days_until_expiry'] !== 1 ? 's' : '' }}</strong>
                                    </div>
                                @endif

                                <!-- Details -->
                                <div class="voucher-details">
                                    <div class="detail-item">
                                        <span class="detail-label">📅 Valid From</span>
                                        <span class="detail-value">{{ $voucher['start_date']->format('M d, Y') }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">⏳ Expires</span>
                                        <span class="detail-value">{{ $voucher['end_date']->format('M d, Y') }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">📝 Claimed</span>
                                        <span class="detail-value">{{ $voucher['claimed_at']->format('M d, Y') }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">💡 Type</span>
                                        <span class="detail-value">{{ ucfirst($voucher['discount_type']) }}</span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="voucher-actions">
                                    @if($voucher['is_active'])
                            
                                    @else
                                        <button class="btn-small btn-use" disabled>
                                            @if($voucher['is_used'])
                                                ✓ Already Used
                                            @elseif($voucher['is_expired'])
                                                ⏰ Expired
                                            @else
                                                ⏳ Coming Soon
                                            @endif
                                        </button>
                                    @endif

                                    <form method="POST" action="{{ route('vouchers.revoke', $voucher['user_voucher_id']) }}" style="flex: 1;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-small btn-remove" onclick="return confirm('Remove this voucher from your collection?');">
                                            🗑️ Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">🎁</div>
                    <h3>No Vouchers Yet</h3>
                    <p>Start claiming vouchers to save on your purchases! Use the form above to claim a voucher code.</p>
                    <p style="font-size: 0.9rem; color: #9ca3af;">Once claimed, vouchers appear here and can be used during checkout.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            console.log('Copied to clipboard:', text);
        }).catch(err => {
            alert('Failed to copy: ' + err);
        });
    }
</script>
@endsection
