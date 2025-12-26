@extends('layouts.admin')

@section('title', 'Edit Voucher - Admin')

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

    .info-card .stat-row {
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e5e7eb;
    }

    .info-card .stat-row:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .stat-label {
        font-size: 12px;
        color: #9ca3af;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 15px;
        font-weight: 600;
        color: #1f2937;
    }

    .danger-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        border-left: 4px solid #dc2626;
        margin-top: 16px;
    }

    .danger-card h3 {
        margin: 0 0 12px 0;
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
    }

    .danger-card p {
        margin: 0 0 16px 0;
        font-size: 13px;
        color: #6b7280;
        line-height: 1.6;
    }

    .btn-delete {
        padding: 10px 16px;
        background: #dc2626;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        width: 100%;
    }

    .btn-delete:hover {
        background: #b91c1c;
    }

    .voucher-code-badge {
        display: inline-block;
        padding: 6px 12px;
        background: #f3f4f6;
        color: #1f2937;
        border-radius: 4px;
        font-weight: 600;
        font-family: 'Courier New', monospace;
        font-size: 13px;
    }
</style>

<div class="form-page-header">
    <div>
        <h1>Edit Voucher: <span class="voucher-code-badge">{{ $voucher->code }}</span></h1>
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
        <h2>Edit Voucher Details</h2>
        
        <form action="{{ route('admin.vouchers.update', $voucher->voucher_id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Code -->
            <div class="form-group">
                <label for="code" class="form-label">
                    Voucher Code <span class="required">*</span>
                </label>
                <input type="text" class="form-input @error('code') error @enderror" 
                       id="code" name="code" placeholder="e.g., SUMMER20, WELCOME10"
                       value="{{ old('code', $voucher->code) }}" required maxlength="50">
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
                          placeholder="What is this voucher for?" maxlength="500">{{ old('description', $voucher->description) }}</textarea>
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
                        <option value="percentage" {{ old('discount_type', $voucher->discount_type) === 'percentage' ? 'selected' : '' }}>
                            Percentage (%)
                        </option>
                        <option value="fixed" {{ old('discount_type', $voucher->discount_type) === 'fixed' ? 'selected' : '' }}>
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
                               step="0.01" min="0" value="{{ old('discount_value', $voucher->discount_value) }}" required>
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
                       min="1" value="{{ old('usage_limit', $voucher->usage_limit) }}">
                <div class="form-help">Total times this voucher can be used across all users. Currently used: <strong>{{ $voucher->usage_count ?? 0 }}</strong> times.</div>
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
                           id="start_date" name="start_date" value="{{ old('start_date', $voucher->start_date?->format('Y-m-d')) }}" required>
                    @error('start_date')
                        <div class="error-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="end_date" class="form-label">
                        End Date <span class="required">*</span>
                    </label>
                    <input type="date" class="form-input @error('end_date') error @enderror" 
                           id="end_date" name="end_date" value="{{ old('end_date', $voucher->end_date?->format('Y-m-d')) }}" required>
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
                    <option value="active" {{ old('status', $voucher->status) === 'active' ? 'selected' : '' }}>
                        Active (Available for users)
                    </option>
                    <option value="inactive" {{ old('status', $voucher->status) === 'inactive' ? 'selected' : '' }}>
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
                <button type="submit" class="btn-submit">💾 Save Changes</button>
            </div>
        </form>
    </div>

    <div>
        <!-- Information Card -->
        <div class="info-card">
            <h3>ℹ️ Voucher Info</h3>
            
            <div class="stat-row">
                <div class="stat-label">Created</div>
                <div class="stat-value">{{ $voucher->created_at->format('M d, Y H:i') }}</div>
            </div>

            <div class="stat-row">
                <div class="stat-label">Last Updated</div>
                <div class="stat-value">{{ $voucher->updated_at->format('M d, Y H:i') }}</div>
            </div>

            <div class="stat-row">
                <div class="stat-label">Users with Voucher</div>
                <div class="stat-value">{{ $voucher->userVouchers()->count() }} users</div>
            </div>

            <div class="stat-row">
                <div class="stat-label">Times Used</div>
                <div class="stat-value">
                    {{ $voucher->usage_count ?? 0 }}
                    @if ($voucher->usage_limit)
                        / {{ $voucher->usage_limit }}
                    @else
                        / ∞
                    @endif
                </div>
            </div>

            <p style="margin: 0; padding-top: 16px; border-top: 1px solid #e5e7eb;">Editing a voucher only affects new applications. Users who already have this voucher keep their copy.</p>
        </div>

        <!-- Danger Card -->
        <div class="danger-card">
            <h3>⚠️ Danger Zone</h3>
            <p>Delete this voucher and all its user distributions. This action cannot be undone.</p>
            <form action="{{ route('admin.vouchers.destroy', $voucher->voucher_id) }}" method="POST" style="display:inline; width: 100%;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete" onclick="return confirm('Are you sure? This will delete the voucher and all distributions to users.');">
                    🗑️ Delete This Voucher
                </button>
            </form>
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
