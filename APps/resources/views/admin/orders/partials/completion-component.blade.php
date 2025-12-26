@php
    // Determine if order can be marked as completed
    $canBeCompleted = $order->status !== 'completed' && $order->status !== 'cancelled';
@endphp

<div id="order-completion-container" class="order-completion-container">
    @if($canBeCompleted)
        <div class="completion-card">
            <div class="completion-header">
                <h3>Mark Order as Completed</h3>
                <p class="completion-subtitle">Admin Action</p>
            </div>
            
            <div class="completion-body">
                <p class="completion-description">
                    Click the button below to mark this order as completed. This action will record the completion timestamp and the admin who marked it complete.
                </p>
                
                <button 
                    type="button" 
                    class="btn-complete-order" 
                    data-order-id="{{ $order->order_id }}"
                    id="complete-order-btn">
                    ✓ Mark as Completed
                </button>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div id="completion-modal" class="modal hidden" style="display: none;">
            <div class="modal-overlay"></div>
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Confirm Order Completion</h2>
                    <button type="button" class="modal-close" id="close-modal">&times;</button>
                </div>
                
                <div class="modal-body">
                    <p>Are you sure you want to mark <strong>Order #{{ $order->order_id }}</strong> as completed?</p>
                    <div class="order-summary">
                        <p><strong>Customer Name:</strong> {{ $order->customer->name ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $order->customer->email ?? 'N/A' }}</p>
                        <p><strong>Contact:</strong> {{ $order->customer->phone ?? 'N/A' }}</p>
                        <p><strong>Address:</strong> {{ $order->customer->address ?? 'N/A' }}</p>
                        <p><strong>Total:</strong> ₱{{ number_format($order->total_price, 2) }}</p>
                        <p><strong>Current Status:</strong> <span class="status-badge">{{ ucfirst($order->status) }}</span></p>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" id="cancel-modal">Cancel</button>
                    <button type="button" class="btn-confirm" id="confirm-completion">Yes, Mark as Completed</button>
                </div>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="completion-loading" class="loading-spinner hidden" style="display: none;">
            <div class="spinner"></div>
            <p>Processing order completion...</p>
        </div>

        <!-- Success Message -->
        <div id="completion-success" class="alert alert-success hidden" style="display: none;">
            <div class="alert-content">
                <span class="alert-icon">✓</span>
                <p id="success-message"></p>
            </div>
        </div>

        <!-- Error Message -->
        <div id="completion-error" class="alert alert-error hidden" style="display: none;">
            <div class="alert-content">
                <span class="alert-icon">✕</span>
                <p id="error-message"></p>
            </div>
        </div>
    @else
        <div class="completion-card disabled">
            <div class="completion-header">
                <h3>Order Already Completed</h3>
            </div>
            
            <div class="completion-body">
                @if($order->isCompleted())
                    <p class="completion-description">
                        This order was marked as completed by 
                        <strong>{{ $order->completedByAdmin->name ?? 'Admin' }}</strong>
                        on <strong>{{ $order->completed_at->format('M d, Y \a\t h:i A') }}</strong>
                    </p>
                @else
                    <p class="completion-description">
                        This order cannot be completed because it has been cancelled.
                    </p>
                @endif
            </div>
        </div>
    @endif
</div>

<style>
    .order-completion-container {
        margin-top: 24px;
    }

    .completion-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        transition: all 0.2s ease;
    }

    .completion-card:hover:not(.disabled) {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: #d1d5db;
    }

    .completion-card.disabled {
        background: #f9fafb;
        opacity: 0.7;
    }

    .completion-header {
        padding: 16px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .completion-header h3 {
        margin: 0 0 4px 0;
        font-size: 16px;
        font-weight: 600;
    }

    .completion-subtitle {
        margin: 0;
        font-size: 12px;
        opacity: 0.9;
    }

    .completion-body {
        padding: 20px;
    }

    .completion-description {
        margin: 0 0 16px 0;
        font-size: 14px;
        color: #4b5563;
        line-height: 1.5;
    }

    .btn-complete-order {
        display: inline-block;
        padding: 10px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }

    .btn-complete-order:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.4);
    }

    .btn-complete-order:active {
        transform: translateY(0);
    }

    .btn-complete-order:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* Modal Styles */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.2s ease;
    }

    .modal.hidden {
        display: none;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        position: relative;
        background: white;
        border-radius: 8px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        width: 90%;
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            transform: translateY(10px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        padding: 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #6b7280;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .modal-close:hover {
        background: #f3f4f6;
        color: #1f2937;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-body p {
        margin: 0 0 16px 0;
        font-size: 14px;
        color: #4b5563;
    }

    .order-summary {
        background: #f9fafb;
        padding: 12px 16px;
        border-radius: 6px;
        border-left: 3px solid #667eea;
    }

    .order-summary p {
        margin: 8px 0;
        font-size: 13px;
        display: flex;
        justify-content: space-between;
    }

    .order-summary strong {
        color: #1f2937;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        background: #dbeafe;
        color: #1e40af;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .modal-footer {
        padding: 16px 20px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .btn-cancel,
    .btn-confirm {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-cancel {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-cancel:hover {
        background: #e5e7eb;
    }

    .btn-confirm {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }

    .btn-confirm:hover {
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-confirm:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Loading Spinner */
    .loading-spinner {
        text-align: center;
        padding: 24px;
        background: #f9fafb;
        border-radius: 8px;
        margin-top: 12px;
    }

    .loading-spinner.hidden {
        display: none;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #e5e7eb;
        border-top-color: #667eea;
        border-radius: 50%;
        margin: 0 auto 12px;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .loading-spinner p {
        margin: 0;
        font-size: 13px;
        color: #6b7280;
    }

    /* Alert Styles */
    .alert {
        padding: 16px;
        border-radius: 8px;
        margin-top: 12px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        animation: slideDown 0.3s ease;
    }

    .alert.hidden {
        display: none;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-10px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .alert-success {
        background: #d1fae5;
        border: 1px solid #6ee7b7;
        color: #065f46;
    }

    .alert-error {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        color: #991b1b;
    }

    .alert-icon {
        font-size: 18px;
        font-weight: bold;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .alert-content {
        flex: 1;
    }

    .alert-content p {
        margin: 0;
        font-size: 14px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderId = document.querySelector('[data-order-id]')?.dataset.orderId;
    const completeBtn = document.getElementById('complete-order-btn');
    const modal = document.getElementById('completion-modal');
    const closeModalBtn = document.getElementById('close-modal');
    const cancelModalBtn = document.getElementById('cancel-modal');
    const confirmBtn = document.getElementById('confirm-completion');
    const loadingSpinner = document.getElementById('completion-loading');
    const successAlert = document.getElementById('completion-success');
    const errorAlert = document.getElementById('completion-error');

    if (!orderId) return;

    // Open modal
    completeBtn?.addEventListener('click', function() {
        modal.style.display = 'flex';
    });

    // Close modal
    const closeModal = () => {
        modal.style.display = 'none';
        successAlert.classList.add('hidden');
        errorAlert.classList.add('hidden');
    };

    closeModalBtn?.addEventListener('click', closeModal);
    cancelModalBtn?.addEventListener('click', closeModal);

    // Confirm completion
    confirmBtn?.addEventListener('click', async function() {
        try {
            completeBtn.disabled = true;
            confirmBtn.disabled = true;
            loadingSpinner.classList.remove('hidden');
            loadingSpinner.style.display = 'block';

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            const response = await fetch(`/admin/orders/${orderId}/complete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({})
            });

            loadingSpinner.classList.add('hidden');
            loadingSpinner.style.display = 'none';

            if (response.ok) {
                const data = await response.json();
                successAlert.classList.remove('hidden');
                successAlert.style.display = 'block';
                document.getElementById('success-message').textContent = data.message || 'Order marked as completed successfully!';
                
                // Reload page after 2 seconds
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                const error = await response.json();
                errorAlert.classList.remove('hidden');
                errorAlert.style.display = 'block';
                document.getElementById('error-message').textContent = error.message || 'Failed to complete order';
                completeBtn.disabled = false;
                confirmBtn.disabled = false;
            }
        } catch (error) {
            loadingSpinner.classList.add('hidden');
            loadingSpinner.style.display = 'none';
            errorAlert.classList.remove('hidden');
            errorAlert.style.display = 'block';
            document.getElementById('error-message').textContent = 'An error occurred: ' + error.message;
            completeBtn.disabled = false;
            confirmBtn.disabled = false;
            console.error('Error:', error);
        }
    });

    // Close modal when clicking overlay
    document.querySelector('.modal-overlay')?.addEventListener('click', closeModal);
});
</script>
