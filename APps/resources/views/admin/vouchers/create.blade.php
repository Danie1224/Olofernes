@extends('layouts.admin')

@section('title', 'Create Voucher - Admin')

@section('content')
<style>
    .form-page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 32px;
    }

    .form-page-header div:first-child h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 600;
        color: #1f2937;
        letter-spacing: -0.5px;
    }

    .form-page-header div:first-child p {
        margin: 8px 0 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .back-button {
        padding: 10px 16px;
        background: white;
        color: #374151;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .back-button:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }

    .error-alert {
        padding: 16px 20px;
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
        border-radius: 8px;
        margin-bottom: 24px;
        font-size: 14px;
    }

    .error-alert strong {
        font-weight: 600;
        display: block;
        margin-bottom: 8px;
    }

    .error-alert ul {
        margin: 0;
        padding-left: 20px;
    }

    .form-container {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 24px;
    }

    .form-card {
        background: white;
        border-radius: 8px;
        padding: 32px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    }

    .form-card h2 {
        margin: 0 0 24px 0;
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
    }

    .form-label .required {
        color: #dc2626;
    }

    .form-input, .form-textarea, .form-select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        color: #374151;
        transition: all 0.2s ease;
    }

    .form-input:focus, .form-textarea:focus, .form-select:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .form-input.error, .form-textarea.error, .form-select.error {
        border-color: #dc2626;
    }

    .form-input.error:focus, .form-textarea.error:focus, .form-select.error:focus {
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-help {
        margin-top: 6px;
        font-size: 12px;
        color: #6b7280;
    }

    .error-feedback {
        margin-top: 6px;
        font-size: 12px;
        color: #dc2626;
        font-weight: 500;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .input-group {
        display: flex;
        gap: 0;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        overflow: hidden;
    }

    .input-group .form-input {
        border: none;
        border-radius: 0;
        flex: 1;
        margin: 0;
    }

    .input-group .input-addon {
        padding: 10px 12px;
        background: #f9fafb;
        color: #6b7280;
        font-weight: 600;
        font-size: 13px;
        display: flex;
        align-items: center;
        border-left: 1px solid #e5e7eb;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid #e5e7eb;
    }

    .btn-cancel {
        padding: 10px 24px;
        background: white;
        color: #374151;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-cancel:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }

    .btn-submit {
        padding: 10px 24px;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-submit:hover {
        background: #1d4ed8;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .info-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        border-left: 4px solid #2563eb;
    }

    .info-card h3 {
        margin: 0 0 16px 0;
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
    }

    .info-card p {
        margin: 0 0 12px 0;
        font-size: 13px;
        color: #6b7280;
        line-height: 1.6;
    }

    .info-card p:last-child {
        margin-bottom: 0;
    }

    .info-card ol, .info-card ul {
        margin: 0;
        padding-left: 20px;
        font-size: 13px;
        color: #6b7280;
    }

    .info-card li {
        margin-bottom: 6px;
    }

    .info-card li:last-child {
        margin-bottom: 0;
    }

    .info-divider {
        height: 1px;
        background: #e5e7eb;
        margin: 16px 0;
    }

    .tips-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        border-left: 4px solid #f59e0b;
        margin-top: 16px;
    }

    .tips-card h3 {
        margin: 0 0 12px 0;
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
    }

    .tips-card ul {
        margin: 0;
        padding-left: 20px;
        font-size: 13px;
        color: #6b7280;
    }

    .tips-card li {
        margin-bottom: 8px;
    }

    .tips-card li:last-child {
        margin-bottom: 0;
    }
</style>

<div class="form-page-header">
    <div>
        <h1>Create New Voucher</h1>
        <p>Fill in the details to create a new voucher. It will be automatically distributed to all existing users.</p>
    </div>
    <a href="{{ route('admin.vouchers.index') }}" class="back-button">← Back to Vouchers</a>
</div>

@if ($errors->any())
    <div class="error-alert">
        <strong>Validation Errors</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-container">
    <div class="form-card">
        <h2>Voucher Details</h2>
        
        <form action="{{ route('admin.vouchers.store') }}" method="POST">
            @csrf

            <!-- Code -->
            <div class="form-group">
                <label for="code" class="form-label">
                    Voucher Code <span class="required">*</span>
                </label>
                <input type="text" class="form-input @error('code') error @enderror" 
                       id="code" name="code" placeholder="e.g., SUMMER20, WELCOME10"
                       value="{{ old('code') }}" required maxlength="50">
                <div class="form-help">Must be unique. Use uppercase letters and numbers.</div>
                @error('code')
                    <div class="error-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-textarea @error('description') error @enderror" 
                          id="description" name="description" 
                          placeholder="What is this voucher for?" maxlength="500">{{ old('description') }}</textarea>
                <div class="form-help">Optional. Helps you remember the voucher's purpose.</div>
                @error('description')
                    <div class="error-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Discount Type and Value -->
            <div class="form-row">
                <div>
                    <label for="discount_type" class="form-label">
                        Discount Type <span class="required">*</span>
                    </label>
                    <select class="form-select @error('discount_type') error @enderror" 
                            id="discount_type" name="discount_type" required>
                        <option value="">-- Select Type --</option>
                        <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>
                            Percentage (%)
                        </option>
                        <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>
                            Fixed Amount (₱)
                        </option>
                    </select>
                    @error('discount_type')
                        <div class="error-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="discount_value" class="form-label">
                        Discount Value <span class="required">*</span>
                    </label>
                    <div class="input-group">
                        <input type="number" class="form-input @error('discount_value') error @enderror" 
                               id="discount_value" name="discount_value" placeholder="0.00"
                               step="0.01" min="0" value="{{ old('discount_value') }}" required>
                        <span class="input-addon" id="discount_unit">%</span>
                    </div>
                    @error('discount_value')
                        <div class="error-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Usage Limit -->
            <div class="form-group">
                <label for="usage_limit" class="form-label">Usage Limit (Optional)</label>
                <input type="number" class="form-input @error('usage_limit') error @enderror" 
                       id="usage_limit" name="usage_limit" placeholder="Leave empty for unlimited uses"
                       min="1" value="{{ old('usage_limit') }}">
                <div class="form-help">Total times this voucher can be used across all users.</div>
                @error('usage_limit')
                    <div class="error-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Valid Period -->
            <div class="form-row">
                <div>
                    <label for="start_date" class="form-label">
                        Start Date <span class="required">*</span>
                    </label>
                    <input type="date" class="form-input @error('start_date') error @enderror" 
                           id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                    @error('start_date')
                        <div class="error-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="end_date" class="form-label">
                        End Date <span class="required">*</span>
                    </label>
                    <input type="date" class="form-input @error('end_date') error @enderror" 
                           id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                    @error('end_date')
                        <div class="error-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status" class="form-label">
                    Status <span class="required">*</span>
                </label>
                <select class="form-select @error('status') error @enderror" 
                        id="status" name="status" required>
                    <option value="">-- Select Status --</option>
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>
                        Active (Available for users)
                    </option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                        Inactive (Hidden from users)
                    </option>
                </select>
                @error('status')
                    <div class="error-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="form-actions">
                <a href="{{ route('admin.vouchers.index') }}" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit">➕ Create & Distribute Voucher</button>
            </div>
        </form>
    </div>

    <div>
        <!-- Information Card -->
        <div class="info-card">
            <h3>ℹ️ How It Works</h3>
            
            <p><strong>Distribution Process</strong></p>
            <p>When you click "Create & Distribute Voucher", the system will:</p>
            <ol>
                <li>Create the voucher with your specified details</li>
                <li>Find all active customer accounts</li>
                <li>Automatically assign the voucher to each user</li>
                <li>Mark each assignment as "available"</li>
            </ol>

            <div class="info-divider"></div>

            <p><strong>Usage Limits</strong></p>
            <p>"Usage Limit" controls how many times the voucher can be used <strong>in total</strong> across all users.</p>
            <p><em>Example: If limit is 100, only the first 100 customers to use it will succeed.</em></p>

            <div class="info-divider"></div>

            <p><strong>Valid Period</strong></p>
            <p>Vouchers are only usable between the start and end dates you specify. Customers cannot use expired vouchers.</p>
        </div>

        <!-- Tips Card -->
        <div class="tips-card">
            <h3>💡 Tips</h3>
            <ul>
                <li>Use clear, memorable codes (e.g., NEWYEAR2024)</li>
                <li>Add descriptions to track campaign purposes</li>
                <li>Set realistic dates for validity</li>
                <li>Consider usage limits to control costs</li>
                <li>You can edit or delete vouchers later</li>
            </ul>
        </div>
    </div>
</div>

<script>
document.getElementById('discount_type').addEventListener('change', function() {
    const unit = this.value === 'percentage' ? '%' : '₱';
    document.getElementById('discount_unit').textContent = unit;
});

// Set initial unit
document.addEventListener('DOMContentLoaded', function() {
    const type = document.getElementById('discount_type').value;
    if (type) {
        const unit = type === 'percentage' ? '%' : '₱';
        document.getElementById('discount_unit').textContent = unit;
    }
});
</script>
@endsection
