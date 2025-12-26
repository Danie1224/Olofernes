<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Arial', sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #f9fafb; padding: 20px; border-radius: 8px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
        .content { background: white; padding: 25px; border-radius: 0 0 8px 8px; border-bottom: 3px solid #667eea; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 1.1rem; font-weight: 600; color: #1f2937; margin-bottom: 12px; border-bottom: 2px solid #667eea; padding-bottom: 8px; }
        .info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e5e7eb; }
        .info-label { font-weight: 600; color: #6b7280; }
        .info-value { color: #1f2937; }
        .highlight { background: #fef3c7; padding: 15px; border-radius: 6px; border-left: 4px solid #f59e0b; margin-bottom: 20px; }
        .highlight-value { font-size: 1.3rem; font-weight: bold; color: #d97706; margin-top: 8px; }
        .action-btn { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; margin-top: 15px; }
        .footer { text-align: center; color: #6b7280; font-size: 0.85rem; padding-top: 20px; border-top: 1px solid #e5e7eb; margin-top: 20px; }
        .status-pending { background: #fef08a; color: #92400e; padding: 8px 12px; border-radius: 6px; font-weight: 600; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔄 New Return Request Submitted</h1>
            <p style="margin: 0; opacity: 0.9;">Action Required</p>
        </div>

        <div class="content">
            <div class="highlight">
                <strong>⚠️ A customer has submitted a return request for review</strong>
                <div class="highlight-value">Request #{{ $returnRequest->request_id }}</div>
            </div>

            <!-- Customer Information -->
            <div class="section">
                <div class="section-title">👤 Customer Information</div>
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $customer->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $customer->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">{{ $customer->phone ?? 'Not provided' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Address:</span>
                    <span class="info-value">{{ $customer->address ?? 'Not provided' }}</span>
                </div>
            </div>

            <!-- Order Information -->
            <div class="section">
                <div class="section-title">📦 Order Information</div>
                <div class="info-row">
                    <span class="info-label">Order ID:</span>
                    <span class="info-value">#{{ $returnRequest->order_id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Return Items:</span>
                    <span class="info-value">{{ $itemsCount }} item(s)</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="status-pending">PENDING REVIEW</span>
                </div>
            </div>

            <!-- Refund Information -->
            <div class="section">
                <div class="section-title">💰 Refund Amount</div>
                <div class="highlight">
                    <strong>Total Refund Requested:</strong>
                    <div class="highlight-value">₱{{ number_format($totalRefund, 2) }}</div>
                </div>
            </div>

            <!-- Reason -->
            <div class="section">
                <div class="section-title">📝 Customer's Reason</div>
                <div style="background: #f3f4f6; padding: 12px; border-radius: 6px; font-style: italic;">
                    {{ $returnRequest->reason }}
                </div>
            </div>

            <!-- Action -->
            <div style="text-align: center; margin-top: 25px;">
                <a href="{{ route('admin.returns.show', $returnRequest->request_id) }}" class="action-btn">
                    Review Return Request →
                </a>
            </div>

            <div class="footer">
                <p style="margin: 0;">TechStore Return Management System</p>
                <p style="margin: 5px 0 0 0;">Please review this request and take appropriate action within 24-48 hours.</p>
            </div>
        </div>
    </div>
</body>
</html>
