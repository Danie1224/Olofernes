@extends('layouts.admin')

@section('title', 'Add New Product - Admin')

@section('content')
<style>
    .form-page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 32px;
    }

    .form-page-header h1 {
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

    .image-upload-zone {
        border: 2px dashed #e5e7eb;
        border-radius: 8px;
        padding: 32px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #f9fafb;
    }

    .image-upload-zone:hover {
        border-color: #2563eb;
        background: #f0f9ff;
    }

    .image-upload-zone.dragover {
        border-color: #2563eb;
        background: #f0f9ff;
    }

    .image-upload-icon {
        font-size: 32px;
        margin-bottom: 12px;
    }

    .image-upload-zone h3 {
        margin: 0 0 4px 0;
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
    }

    .image-upload-zone p {
        margin: 0;
        font-size: 12px;
        color: #6b7280;
    }

    .image-upload-input {
        display: none;
    }

    .image-preview {
        margin-top: 16px;
        border-radius: 8px;
        overflow: hidden;
        background: #f9fafb;
    }

    .image-preview img {
        max-width: 100%;
        height: auto;
        display: block;
    }

    .image-preview-actions {
        display: flex;
        gap: 8px;
        padding: 12px;
        border-top: 1px solid #e5e7eb;
    }

    .btn-remove-image {
        padding: 6px 12px;
        background: #fee2e2;
        color: #dc2626;
        border: none;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        flex: 1;
    }

    .btn-remove-image:hover {
        background: #fca5a5;
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

    .info-card ul {
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
</style>

<div class="form-page-header">
    <div>
        <h1>Add New Product</h1>
    </div>
    <a href="{{ route('admin.products.index') }}" class="back-button">← Back to Products</a>
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
        <h2>Product Details</h2>
        
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Product Code & Name -->
            <div class="form-row">
                <div>
                    <label for="product_code" class="form-label">
                        Product Code <span class="required">*</span>
                    </label>
                    <input type="text" class="form-input @error('product_code') error @enderror" 
                           id="product_code" name="product_code" placeholder="e.g., PROD-001"
                           value="{{ old('product_code') }}" required>
                    <div class="form-help">Must be unique. Use uppercase letters and numbers.</div>
                    @error('product_code')
                        <div class="error-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="name" class="form-label">
                        Product Name <span class="required">*</span>
                    </label>
                    <input type="text" class="form-input @error('name') error @enderror" 
                           id="name" name="name" placeholder="Enter product name"
                           value="{{ old('name') }}" required>
                    @error('name')
                        <div class="error-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Price & Stock -->
            <div class="form-row">
                <div>
                    <label for="price" class="form-label">
                        Price <span class="required">*</span>
                    </label>
                    <input type="number" class="form-input @error('price') error @enderror" 
                           id="price" name="price" placeholder="0.00"
                           step="0.01" min="0" value="{{ old('price') }}" required>
                    @error('price')
                        <div class="error-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="stock_quantity" class="form-label">
                        Stock Quantity <span class="required">*</span>
                    </label>
                    <input type="number" class="form-input @error('stock_quantity') error @enderror" 
                           id="stock_quantity" name="stock_quantity" placeholder="0"
                           min="0" value="{{ old('stock_quantity') }}" required>
                    @error('stock_quantity')
                        <div class="error-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Category -->
            <div class="form-group">
                <label for="category" class="form-label">Category</label>
                <select class="form-select @error('category') error @enderror" 
                        id="category" name="category">
                    <option value="">-- Select Category --</option>
                    <option value="Laptop" {{ old('category') === 'Laptop' ? 'selected' : '' }}>Laptop</option>
                    <option value="Smartphone" {{ old('category') === 'Smartphone' ? 'selected' : '' }}>Smartphone</option>
                    <option value="Tablet" {{ old('category') === 'Tablet' ? 'selected' : '' }}>Tablet</option>
                    <option value="Desktop" {{ old('category') === 'Desktop' ? 'selected' : '' }}>Desktop</option>
                    <option value="Gaming" {{ old('category') === 'Gaming' ? 'selected' : '' }}>Gaming</option>
                    <option value="Accessory" {{ old('category') === 'Accessory' ? 'selected' : '' }}>Accessory</option>
                </select>
                @error('category')
                    <div class="error-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Brand -->
            <div class="form-group">
                <label for="brand_id" class="form-label">Brand</label>
                <select class="form-select @error('brand_id') error @enderror" 
                        id="brand_id" name="brand_id">
                    <option value="">-- Select Brand --</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
                @error('brand_id')
                    <div class="error-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-textarea @error('description') error @enderror" 
                          id="description" name="description" 
                          placeholder="Enter product description...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="error-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Product Image -->
            <div class="form-group">
                <label class="form-label">Product Image</label>
                <div class="image-upload-zone" id="imageUploadZone">
                    <div class="image-upload-icon">📷</div>
                    <h3>Drop image here or click to upload</h3>
                    <p>Supported formats: JPG, PNG, WebP (Max 2MB)</p>
                    <input type="file" id="image" name="image" class="image-upload-input" 
                           accept="image/jpeg,image/png,image/webp" data-max-size="2097152">
                </div>

                <div id="imagePreview" class="image-preview" style="display: none;">
                    <img id="previewImg" src="" alt="Product preview">
                    <div class="image-preview-actions">
                        <button type="button" class="btn-remove-image" onclick="removeImage()">Remove Image</button>
                    </div>
                </div>

                @error('image')
                    <div class="error-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="form-actions">
                <a href="{{ route('admin.products.index') }}" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit">✅ Create Product</button>
            </div>
        </form>
    </div>

    <div>
        <!-- Info Card -->
        <div class="info-card">
            <h3>ℹ️ Product Guidelines</h3>
            
            <p><strong>Product Code</strong></p>
            <p>Use a unique identifier like PROD-001. This helps track inventory and orders.</p>

            <div class="info-divider"></div>

            <p><strong>Pricing</strong></p>
            <p>Set competitive prices. You can adjust them later if needed.</p>

            <div class="info-divider"></div>

            <p><strong>Stock Management</strong></p>
            <p>Keep accurate stock counts. The system will alert when items run low.</p>

            <div class="info-divider"></div>

            <p><strong>Product Image</strong></p>
            <p>Upload a clear, professional image of your product. This is visible to customers and helps with sales.</p>

            <div class="info-divider"></div>

            <p><strong>Tips</strong></p>
            <ul>
                <li>Write clear, detailed descriptions</li>
                <li>Use high-quality product images</li>
                <li>Set accurate categories</li>
                <li>Review before publishing</li>
            </ul>
        </div>
    </div>
</div>

<script>
    const imageUploadZone = document.getElementById('imageUploadZone');
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    // Handle click to upload
    imageUploadZone.addEventListener('click', () => imageInput.click());

    // Handle drag and drop
    imageUploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        imageUploadZone.classList.add('dragover');
    });

    imageUploadZone.addEventListener('dragleave', () => {
        imageUploadZone.classList.remove('dragover');
    });

    imageUploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        imageUploadZone.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            imageInput.files = files;
            displayImagePreview(files[0]);
        }
    });

    // Handle file selection
    imageInput.addEventListener('change', (e) => {
        const files = e.target.files;
        if (files.length > 0) {
            displayImagePreview(files[0]);
        }
    });

    function displayImagePreview(file) {
        const maxSize = parseInt(imageInput.dataset.maxSize);
        
        if (file.size > maxSize) {
            alert('File size must not exceed 2MB');
            imageInput.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
            imagePreview.style.display = 'block';
            imageUploadZone.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    function removeImage() {
        imageInput.value = '';
        imagePreview.style.display = 'none';
        imageUploadZone.style.display = 'block';
    }
</script>
@endsection
