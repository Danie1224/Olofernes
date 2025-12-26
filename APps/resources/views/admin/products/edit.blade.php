@extends('layouts.admin')

@section('title', 'Edit Product - Admin')

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

    .success-alert {
        padding: 16px 20px;
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
        border-radius: 8px;
        margin-bottom: 24px;
        font-size: 14px;
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
        <h1>Edit Product</h1>
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
        
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Product Code & Name -->
            <div class="form-row">
                <div>
                    <label for="product_code" class="form-label">
                        Product Code <span class="required">*</span>
                    </label>
                    <input type="text" class="form-input" 
                           id="product_code" name="product_code" placeholder="e.g., PROD-001"
                           value="{{ $product->product_code }}" disabled>
                    <div class="form-help">Product code cannot be changed</div>
                </div>

                <div>
                    <label for="name" class="form-label">
                        Product Name <span class="required">*</span>
                    </label>
                    <input type="text" class="form-input @error('name') error @enderror" 
                           id="name" name="name" placeholder="Enter product name"
                           value="{{ old('name', $product->name) }}" required>
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
                           step="0.01" min="0" value="{{ old('price', $product->price) }}" required>
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
                           min="0" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
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
                    <option value="Laptop" {{ old('category', $product->category) === 'Laptop' ? 'selected' : '' }}>Laptop</option>
                    <option value="Smartphone" {{ old('category', $product->category) === 'Smartphone' ? 'selected' : '' }}>Smartphone</option>
                    <option value="Tablet" {{ old('category', $product->category) === 'Tablet' ? 'selected' : '' }}>Tablet</option>
                    <option value="Desktop" {{ old('category', $product->category) === 'Desktop' ? 'selected' : '' }}>Desktop</option>
                    <option value="Gaming" {{ old('category', $product->category) === 'Gaming' ? 'selected' : '' }}>Gaming</option>
                    <option value="Accessory" {{ old('category', $product->category) === 'Accessory' ? 'selected' : '' }}>Accessory</option>
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
                        <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
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
                          placeholder="Enter product description...">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="error-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Product Image -->
            <div class="form-group">
                <label class="form-label">Product Image</label>
                
                @if ($product->image)
                    <div class="image-preview">
                        <img id="previewImg" src="{{ asset($product->image) }}" alt="Product preview">
                        <div class="image-preview-actions">
                            <button type="button" class="btn-remove-image" onclick="removeCurrentImage()">Remove Current Image</button>
                        </div>
                    </div>
                @endif

                <div class="image-upload-zone" id="imageUploadZone" style="{{ $product->image ? 'display: none;' : '' }}">
                    <div class="image-upload-icon">📷</div>
                    <h3>Drop image here or click to upload</h3>
                    <p>Supported formats: JPG, PNG, WebP (Max 2MB)</p>
                    <input type="file" id="image" name="image" class="image-upload-input" 
                           accept="image/jpeg,image/png,image/webp" data-max-size="2097152">
                </div>

                @error('image')
                    <div class="error-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="form-actions">
                <a href="{{ route('admin.products.index') }}" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit">✅ Update Product</button>
            </div>
        </form>
    </div>

    <div>
        <!-- Info Card -->
        <div class="info-card">
            <h3>ℹ️ Editing Tips</h3>
            
            <p><strong>Product Code</strong></p>
            <p>This cannot be changed to maintain inventory tracking accuracy.</p>

            <div class="info-divider"></div>

            <p><strong>Pricing & Stock</strong></p>
            <p>Update prices and stock quantities as needed. Changes take effect immediately.</p>

            <div class="info-divider"></div>

            <p><strong>Product Image</strong></p>
            <p>Upload a new image to replace the current one, or keep the existing image by not uploading.</p>

            <div class="info-divider"></div>

            <p><strong>Tips</strong></p>
            <ul>
                <li>Always verify prices before saving</li>
                <li>Keep descriptions clear and detailed</li>
                <li>Use high-quality images</li>
                <li>Review all changes before submitting</li>
            </ul>
        </div>
    </div>
</div>

<script>
    const imageUploadZone = document.getElementById('imageUploadZone');
    const imageInput = document.getElementById('image');

    if (imageUploadZone) {
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
    }

    function displayImagePreview(file) {
        const maxSize = parseInt(imageInput.dataset.maxSize);
        
        if (file.size > maxSize) {
            alert('File size must not exceed 2MB');
            imageInput.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            let preview = document.querySelector('.image-preview');
            if (!preview) {
                preview = document.createElement('div');
                preview.className = 'image-preview';
                imageUploadZone.parentNode.insertBefore(preview, imageUploadZone);
            }
            
            const img = preview.querySelector('img') || document.createElement('img');
            img.id = 'previewImg';
            img.src = e.target.result;
            img.alt = 'Product preview';
            
            if (!preview.querySelector('img')) {
                preview.appendChild(img);
            }
            
            let actions = preview.querySelector('.image-preview-actions');
            if (!actions) {
                actions = document.createElement('div');
                actions.className = 'image-preview-actions';
                actions.innerHTML = '<button type="button" class="btn-remove-image" onclick="removeImage()">Remove Image</button>';
                preview.appendChild(actions);
            }
            
            preview.style.display = 'block';
            imageUploadZone.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    function removeImage() {
        imageInput.value = '';
        const preview = document.querySelector('.image-preview');
        if (preview && !preview.querySelector('img[src*="' + window.location.origin + '"]')) {
            preview.style.display = 'none';
        }
        imageUploadZone.style.display = 'block';
    }

    function removeCurrentImage() {
        // Show upload zone again
        imageUploadZone.style.display = 'block';
        // Hide current image
        const preview = document.querySelector('.image-preview');
        if (preview) {
            preview.style.display = 'none';
        }
        // Set image to null when form submits
        imageInput.value = '';
    }
</script>
@endsection
    }

    .form-section {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 18px 0;
        padding-bottom: 12px;
        border-bottom: 2px solid #f3f4f6;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control,
    .form-select {
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-family: inherit;
        font-size: 0.95rem;
        color: #111827;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control:focus,
    .form-select:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .form-control:disabled,
    .form-select:disabled {
        background: #f9fafb;
        color: #9ca3af;
        cursor: not-allowed;
    }

    .form-select {
        cursor: pointer;
    }

    .textarea {
        padding: 12px;
        min-height: 120px;
        font-family: inherit;
        resize: vertical;
    }

    .textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .full-width {
        grid-column: 1 / -1;
    }

    .form-note {
        font-size: 0.85rem;
        color: #6b7280;
        margin-top: 6px;
    }

    .button-group {
        display: flex;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 2px solid #f3f4f6;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
    }

    .btn-primary {
        background: #2563eb;
        color: white;
        flex: 1;
    }

    .btn-primary:hover {
        background: #1d4ed8;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-secondary {
        background: #e5e7eb;
        color: #374151;
        flex: 1;
    }

    .btn-secondary:hover {
        background: #d1d5db;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .error-message {
        color: #dc2626;
        font-size: 0.85rem;
        margin-top: 6px;
    }

    .success-message {
        background: #d1fae5;
        color: #065f46;
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 0.95rem;
        border: 1px solid #a7f3d0;
    }

    .info-box {
        background: #dbeafe;
        border: 1px solid #93c5fd;
        border-radius: 6px;
        padding: 12px 16px;
        color: #1e40af;
        font-size: 0.9rem;
        margin-bottom: 16px;
    }
</style>

<a href="{{ route('admin.products.index') }}" class="back-button">← Back to Products</a>

<div class="edit-header">
    <h1>Edit Product</h1>
    <div class="product-info">
        <strong>{{ $product->name }}</strong>
        <div class="product-sku">SKU: {{ $product->product_code }}</div>
    </div>
</div>

@if ($errors->any())
    <div class="error-message" style="background: #fee2e2; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #fecaca;">
        <strong>Please fix the following errors:</strong>
        <ul style="margin: 8px 0 0 20px; padding: 0;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.products.update', $product->product_id) }}">
    @csrf
    @method('PUT')

    <!-- Basic Information -->
    <div class="form-section">
        <h2 class="section-title">Basic Information</h2>
        <div class="form-grid">
            <div class="form-group">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="product_code" class="form-label">Product Code (SKU)</label>
                <input type="text" id="product_code" name="product_code" class="form-control" value="{{ $product->product_code }}" disabled>
                <span class="form-note">Auto-generated, cannot be changed</span>
            </div>

            <div class="form-group">
                <label for="category" class="form-label">Category</label>
                <select id="category" name="category" class="form-select @error('category') is-invalid @enderror" required>
                    <option value="">Select a category</option>
                    <option value="Laptop" {{ old('category', $product->category) == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                    <option value="Smartphone" {{ old('category', $product->category) == 'Smartphone' ? 'selected' : '' }}>Smartphone</option>
                    <option value="Tablet" {{ old('category', $product->category) == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                    <option value="Desktop" {{ old('category', $product->category) == 'Desktop' ? 'selected' : '' }}>Desktop</option>
                    <option value="Gaming" {{ old('category', $product->category) == 'Gaming' ? 'selected' : '' }}>Gaming</option>
                    <option value="Accessory" {{ old('category', $product->category) == 'Accessory' ? 'selected' : '' }}>Accessory</option>
                </select>
                @error('category')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Pricing & Stock -->
    <div class="form-section">
        <h2 class="section-title">Pricing & Stock</h2>
        <div class="form-grid">
            <div class="form-group">
                <label for="price" class="form-label">Unit Price (₱)</label>
                <input type="number" id="price" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" step="0.01" required>
                @error('price')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="stock_quantity" class="form-label">Stock Quantity</label>
                <input type="number" id="stock_quantity" name="stock_quantity" class="form-control @error('stock_quantity') is-invalid @enderror" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                @error('stock_quantity')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Description -->
    <div class="form-section">
        <h2 class="section-title">Description</h2>
        <div class="form-group">
            <label for="description" class="form-label">Product Description</label>
            <textarea id="description" name="description" class="form-control textarea @error('description') is-invalid @enderror" required>{{ old('description', $product->description) }}</textarea>
            <span class="form-note">Provide a detailed description of the product</span>
            @error('description')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="form-section" style="background: #f9fafb; border: none;">
        <div class="button-group">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">✓ Save Changes</button>
        </div>
    </div>
</form>
@endsection
