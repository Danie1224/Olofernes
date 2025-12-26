@extends('layouts.app')

@section('title', 'Create Return Request - TechStore')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-between align-center mb-4">
        <h1>Create Return Request</h1>
        <a href="{{ route('returns.index') }}" class="btn btn-secondary">Back to Returns</a>
    </div>

    <!-- Validation Errors -->
    @if($errors->any())
        <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #991b1b;">
            <strong>⚠️ Please fix the following errors:</strong>
            <ul style="margin-top: 10px; margin-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Flash Messages -->
    @if($message = Session::get('success'))
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #047857;">
            <strong>{{ $message }}</strong>
        </div>
    @endif

    @if($message = Session::get('error'))
        <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #991b1b;">
            <strong>{{ $message }}</strong>
        </div>
    @endif

    <style>
        .create-return-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .form-section {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
        }

        .form-section h3 {
            color: #1f2937;
            margin-bottom: 20px;
            font-size: 1.2rem;
            border-bottom: 2px solid #667eea;
            padding-bottom: 12px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #1f2937;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23667eea' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }

        .help-text {
            display: block;
            font-size: 0.85rem;
            color: #6b7280;
            margin-top: 6px;
        }

        .items-section {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
        }

        .item-selector {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 15px;
            align-items: center;
        }

        .item-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .item-info {
            display: grid;
            gap: 8px;
        }

        .item-name {
            font-weight: 600;
            color: #1f2937;
        }

        .item-details {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            font-size: 0.9rem;
            color: #6b7280;
        }

        .item-detail {
            display: flex;
            flex-direction: column;
        }

        .item-detail-label {
            font-weight: 600;
            color: #1f2937;
        }

        .item-inputs {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
        }

        .input-group label {
            font-size: 0.85rem;
            margin-bottom: 6px;
            color: #6b7280;
            font-weight: 500;
        }

        .input-group input,
        .input-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.9rem;
            font-family: inherit;
        }

        .input-group input:focus,
        .input-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .item-actions {
            display: flex;
            gap: 8px;
        }

        .btn-remove {
            background: #fee2e2;
            color: #991b1b;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-remove:hover {
            background: #fecaca;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 12px 28px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-cancel {
            background: #e5e7eb;
            color: #1f2937;
            padding: 12px 28px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #d1d5db;
        }

        .order-header {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-radius: 8px;
        }

        .order-info h4 {
            color: #1f2937;
            margin-bottom: 6px;
        }

        .order-date {
            font-size: 0.9rem;
            color: #6b7280;
        }

        .info-box {
            background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%);
            border-left: 4px solid #3b82f6;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .info-box strong {
            color: #1e40af;
            display: block;
            margin-bottom: 6px;
        }

        .info-box p {
            color: #1e40af;
            margin: 0;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .no-orders {
            text-align: center;
            padding: 40px 20px;
            background: #f9fafb;
            border-radius: 8px;
            border: 2px dashed #e5e7eb;
        }

        .no-orders-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .item-selector {
                grid-template-columns: 1fr;
            }

            .item-inputs {
                grid-template-columns: 1fr;
            }

            .item-details {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }
        }
    </style>

    <div class="create-return-container">
        <div class="info-box">
            <strong>📋 How Return Requests Work:</strong>
            <p>Select the completed order containing the damaged product, then specify which items you'd like to return with a reason. Our team will review your request within 24-48 hours.</p>
        </div>

        @if($orders->count() > 0)
            <form action="{{ route('returns.store') }}" method="POST" id="returnForm">
                @csrf

                <!-- Order Selection -->
                <div class="form-section">
                    <h3>📦 Select Order</h3>
                    <div class="form-group">
                        <label for="order_id">Order <span style="color: #ef4444;">*</span></label>
                        <select name="order_id" id="order_id" class="form-control form-select" onchange="displayOrderItems()" required>
                            <option value="">Choose an order...</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->order_id }}" 
                                    data-order-date="{{ $order->order_date->format('M d, Y') }}"
                                    data-total="{{ $order->total_price }}">
                                    Order #{{ $order->order_id }} - ₱{{ number_format($order->total_price, 2) }} ({{ $order->order_date->format('M d, Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Items Selection -->
                <div class="form-section">
                    <h3>🛍️ Select Items to Return</h3>
                    <div class="items-section" id="itemsContainer" style="display: none;">
                        <div id="itemsList"></div>
                    </div>
                    <div id="noItemsMessage" style="text-align: center; color: #6b7280; padding: 20px;">
                        Select an order to view items
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('returns.index') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-submit" id="submitBtn" disabled>Submit Return Request</button>
                </div>
            </form>

            <script>
                const ordersData = {!! json_encode($orders->mapWithKeys(function($order) {
                    return [$order->order_id => $order->orderItems->map(function($item) {
                        return [
                            'order_item_id' => $item->order_item_id,
                            'product_id' => $item->product_id,
                            'product_name' => $item->product->name ?? 'Unknown Product',
                            'quantity' => $item->quantity,
                            'unit_price' => $item->unit_price,
                            'subtotal' => $item->subtotal,
                        ];
                    })->toArray()];
                })) !!};

                function displayOrderItems() {
                    const orderId = document.getElementById('order_id').value;
                    const itemsList = document.getElementById('itemsList');
                    const itemsContainer = document.getElementById('itemsContainer');
                    const noItemsMessage = document.getElementById('noItemsMessage');
                    const submitBtn = document.getElementById('submitBtn');

                    itemsList.innerHTML = '';
                    submitBtn.disabled = true;

                    if (!orderId || !ordersData[orderId]) {
                        itemsContainer.style.display = 'none';
                        noItemsMessage.style.display = 'block';
                        return;
                    }

                    const items = ordersData[orderId];
                    itemsContainer.style.display = 'block';
                    noItemsMessage.style.display = 'none';

                    items.forEach((item, index) => {
                        const itemHtml = `
                            <div class="item-selector" data-item-index="${index}">
                                <input type="checkbox" class="item-checkbox" id="item_${index}">
                                <div class="item-info">
                                    <div class="item-name">${item.product_name}</div>
                                    <div class="item-details">
                                        <div class="item-detail">
                                            <span class="item-detail-label">Quantity Available:</span>
                                            ${item.quantity}
                                        </div>
                                        <div class="item-detail">
                                            <span class="item-detail-label">Unit Price:</span>
                                            ₱${parseFloat(item.unit_price).toFixed(2)}
                                        </div>
                                        <div class="item-detail">
                                            <span class="item-detail-label">Total:</span>
                                            ₱${parseFloat(item.subtotal).toFixed(2)}
                                        </div>
                                    </div>
                                </div>
                                <div class="item-inputs" style="display: none;" id="inputs_${index}">
                                    <div class="input-group">
                                        <label>Return Qty</label>
                                        <input type="number" name="items[${index}][quantity]" 
                                            min="1" max="${item.quantity}" value="1" class="qty-input item-field" disabled>
                                    </div>
                                    <div class="input-group">
                                        <label>Reason</label>
                                        <select name="items[${index}][reason]" class="form-control form-select reason-select item-field" disabled>
                                            <option value="">Select reason...</option>
                                            <option value="Damaged During Shipment">Damaged During Shipment</option>
                                            <option value="Defective Product">Defective Product</option>
                                            <option value="Wrong Item Received">Wrong Item Received</option>
                                            <option value="Not as Described">Not as Described</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <label>Notes (Optional)</label>
                                        <textarea name="items[${index}][notes]" 
                                            class="item-field"
                                            placeholder="Describe the issue..." style="height: 60px;" disabled></textarea>
                                    </div>
                                </div>
                                <!-- Hidden fields - enabled only when the item is selected -->
                                <input type="hidden" class="item-field" name="items[${index}][order_item_id]" value="${item.order_item_id}" disabled>
                                <input type="hidden" class="item-field" name="items[${index}][product_id]" value="${item.product_id}" disabled>
                            </div>
                        `;
                        itemsList.innerHTML += itemHtml;
                    });

                    initializeItemControls();
                }

                function initializeItemControls() {
                    document.querySelectorAll('.item-selector').forEach((selector, idx) => {
                        const checkbox = selector.querySelector('.item-checkbox');
                        const inputsContainer = selector.querySelector(`#inputs_${idx}`);

                        checkbox.addEventListener('change', function () {
                            toggleItemFields(selector, this.checked);
                            if (inputsContainer) {
                                inputsContainer.style.display = this.checked ? 'grid' : 'none';
                            }
                            updateItemSelection();
                        });
                    });

                    document.querySelectorAll('.reason-select').forEach((select) => {
                        select.addEventListener('change', updateItemSelection);
                    });
                }

                function toggleItemFields(selector, isEnabled) {
                    const fields = selector.querySelectorAll('.item-field');
                    fields.forEach((field) => {
                        field.disabled = !isEnabled;
                        if (field.classList.contains('reason-select')) {
                            field.required = isEnabled;
                            if (!isEnabled) {
                                field.value = '';
                            }
                        }
                        if (!isEnabled && field.tagName === 'TEXTAREA') {
                            field.value = '';
                        }
                        if (!isEnabled && field.type === 'number') {
                            field.value = 1;
                        }
                    });
                }

                function updateItemSelection() {
                    const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
                    const submitBtn = document.getElementById('submitBtn');
                    submitBtn.disabled = checkedCount === 0;

                    let allValid = true;
                    document.querySelectorAll('.item-checkbox:checked').forEach((checkbox) => {
                        const index = checkbox.id.replace('item_', '');
                        const reasonSelect = document.querySelector(`select[name="items[${index}][reason]"]`);
                        if (!reasonSelect || !reasonSelect.value) {
                            allValid = false;
                        }
                    });

                    submitBtn.disabled = !allValid || checkedCount === 0;
                }

                document.getElementById('returnForm').addEventListener('submit', function(e) {
                    const checkedItems = document.querySelectorAll('.item-checkbox:checked');
                    if (checkedItems.length === 0) {
                        e.preventDefault();
                        alert('Please select at least one item to return');
                        return false;
                    }

                    let hasError = false;
                    checkedItems.forEach((checkbox) => {
                        const index = checkbox.id.replace('item_', '');
                        const reasonSelect = document.querySelector(`select[name="items[${index}][reason]"]`);
                        if (!reasonSelect || !reasonSelect.value || reasonSelect.value === '') {
                            hasError = true;
                        }
                    });

                    if (hasError) {
                        e.preventDefault();
                        alert('Please select a reason for all items');
                        return false;
                    }
                    
                    return true;
                });
            </script>
        @else
            <div class="no-orders">
                <div class="no-orders-icon">📭</div>
                <h3 style="color: #1f2937; margin-bottom: 8px;">No Completed Orders</h3>
                <p style="color: #6b7280; margin-bottom: 20px;">You need to have completed orders to create a return request. Start by placing an order.</p>
                <a href="{{ route('products.index') }}" class="btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                    Browse Products
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
