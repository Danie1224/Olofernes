<template>
  <div>
    <NuxtLink to="/admin/products" class="text-primary-600 hover:underline mb-4 inline-block">
      ← Back to Products
    </NuxtLink>

    <div class="bg-white rounded-lg shadow p-6 max-w-4xl">
      <h2 class="text-xl font-bold mb-6">Product Details</h2>

      <form @submit.prevent="handleSubmit">
        <!-- Product Code & Name -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-1">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Product Code <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.product_code"
              type="text"
              required
              placeholder="e.g., PROD-001"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Product Name <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.name"
              type="text"
              required
              placeholder="Enter product name"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
          </div>
        </div>
        <p class="text-xs text-gray-500 mb-4">Must be unique. Use uppercase letters and numbers.</p>

        <!-- Price & Stock -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Price <span class="text-red-500">*</span>
            </label>
            <input
              v-model.number="form.price"
              type="number"
              step="0.01"
              min="0"
              required
              placeholder="0.00"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Stock Quantity <span class="text-red-500">*</span>
            </label>
            <input
              v-model.number="form.stock_quantity"
              type="number"
              min="0"
              required
              placeholder="0"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
          </div>
        </div>

        <!-- Category -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
          <select
            v-model="form.category"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white"
          >
            <option value="">-- Select Category --</option>
            <option value="Laptop">Laptop</option>
            <option value="Smartphone">Smartphone</option>
            <option value="Tablet">Tablet</option>
            <option value="Desktop">Desktop</option>
            <option value="Gaming">Gaming</option>
            <option value="Accessory">Accessory</option>
          </select>
        </div>

        <!-- Brand -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
          <select
            v-model="form.brand_id"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white"
          >
            <option value="">-- Select Brand --</option>
            <option v-for="brand in brands" :key="brand.brand_id" :value="brand.brand_id">
              {{ brand.name }}
            </option>
          </select>
        </div>

        <!-- Description -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
          <textarea
            v-model="form.description"
            rows="4"
            placeholder="Enter product description..."
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 resize-y"
          ></textarea>
        </div>

        <!-- Product Image -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
          <div 
            class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-primary-400 transition-colors"
            @click="triggerFileInput"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="handleDrop"
            :class="{ 'border-primary-500 bg-primary-50': isDragging }"
          >
            <input 
              ref="fileInput"
              type="file" 
              accept="image/jpeg,image/png,image/webp"
              class="hidden"
              @change="handleFileSelect"
            />
            
            <div v-if="imagePreview" class="space-y-3">
              <img :src="imagePreview" alt="Preview" class="max-h-40 mx-auto rounded-lg" />
              <p class="text-sm text-gray-600">{{ selectedFile?.name }}</p>
              <button 
                type="button" 
                @click.stop="removeImage"
                class="text-red-600 text-sm hover:underline"
              >
                Remove image
              </button>
            </div>
            
            <div v-else class="space-y-2">
              <div class="w-12 h-12 mx-auto bg-gray-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <p class="text-sm text-primary-600 font-medium">Drop image here or click to upload</p>
              <p class="text-xs text-gray-500">Supported formats: JPG, PNG, WebP (Max 2MB)</p>
            </div>
          </div>
        </div>

        <div v-if="success" class="bg-green-50 text-green-600 p-3 rounded-md mb-4 flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          {{ success }}
        </div>

        <div v-if="error" class="bg-red-50 text-red-600 p-3 rounded-md mb-4">
          {{ error }}
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3 pt-4 border-t">
          <NuxtLink 
            to="/admin/products"
            class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors"
          >
            Cancel
          </NuxtLink>
          <button
            type="submit"
            :disabled="processing"
            class="bg-primary-600 text-white px-6 py-2 rounded-md hover:bg-primary-700 disabled:opacity-50 flex items-center gap-2 transition-colors"
          >
            <svg v-if="!processing" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span v-if="processing" class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
            {{ processing ? 'Creating...' : 'Create Product' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'admin',
  middleware: 'admin'
})

const { post } = useApi()

const form = reactive({
  product_code: '',
  name: '',
  category: '',
  description: '',
  price: 0,
  stock_quantity: 0,
  brand_id: ''
})

const processing = ref(false)
const error = ref('')
const fileInput = ref<HTMLInputElement | null>(null)
const selectedFile = ref<File | null>(null)
const imagePreview = ref<string | null>(null)
const isDragging = ref(false)
const brands = ref<any[]>([])
const loadingBrands = ref(false)

const triggerFileInput = () => {
  fileInput.value?.click()
}

const handleFileSelect = (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  if (file) {
    processFile(file)
  }
}

const handleDrop = (event: DragEvent) => {
  isDragging.value = false
  const file = event.dataTransfer?.files?.[0]
  if (file && file.type.startsWith('image/')) {
    processFile(file)
  }
}

const processFile = (file: File) => {
  // Validate file size (max 2MB)
  if (file.size > 2 * 1024 * 1024) {
    error.value = 'Image size must be less than 2MB'
    return
  }
  
  // Validate file type
  const validTypes = ['image/jpeg', 'image/png', 'image/webp']
  if (!validTypes.includes(file.type)) {
    error.value = 'Only JPG, PNG, and WebP images are supported'
    return
  }
  
  selectedFile.value = file
  const reader = new FileReader()
  reader.onload = (e) => {
    imagePreview.value = e.target?.result as string
  }
  reader.readAsDataURL(file)
  error.value = ''
}

const removeImage = () => {
  selectedFile.value = null
  imagePreview.value = null
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

const success = ref('')

// Fetch brands on component mount
const fetchBrands = async () => {
  loadingBrands.value = true
  try {
    const config = useRuntimeConfig()
    const apiBase = config.public.apiBase
    const response: any = await $fetch(`${apiBase}/brands`)
    console.log('Brands API Response:', response)
    
    // Simple: if it's an array, use it directly
    if (Array.isArray(response)) {
      brands.value = response
      console.log('Brands loaded:', brands.value.length, 'brands')
    } else {
      console.warn('Unexpected response format:', response)
      brands.value = []
    }
  } catch (err: any) {
    console.error('Failed to fetch brands:', err?.message || err)
    brands.value = []
  } finally {
    loadingBrands.value = false
  }
}

onMounted(() => {
  fetchBrands()
})

const handleSubmit = async () => {
  processing.value = true
  error.value = ''
  success.value = ''

  try {
    const formData = new FormData()
    formData.append('product_code', form.product_code)
    formData.append('name', form.name)
    formData.append('category', form.category)
    formData.append('description', form.description)
    formData.append('price', String(form.price))
    formData.append('stock_quantity', String(form.stock_quantity))
    
    if (selectedFile.value) {
      formData.append('image', selectedFile.value)
    }
    if (form.brand_id) {
      formData.append('brand_id', String(form.brand_id))
    }

    await post('/products', formData)
    success.value = 'Product created successfully!'
    
    // Redirect after a short delay to show success message
    setTimeout(() => {
      navigateTo('/admin/products')
    }, 1500)
  } catch (err: any) {
    console.error('Create product error:', err)
    if (err.status === 401) {
      error.value = 'Unauthorized. Please log in again as admin.'
      setTimeout(() => navigateTo('/admin/login'), 2000)
      return
    }
    if (err.data?.errors && typeof err.data.errors === 'object') {
      error.value = Object.values(err.data.errors).flat().join(', ')
    } else {
      error.value = err.data?.message || 'Failed to create product'
    }
  } finally {
    processing.value = false
  }
}
</script>
