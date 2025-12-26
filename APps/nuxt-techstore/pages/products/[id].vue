<template>
  <div class="max-w-6xl mx-auto">
    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
    </div>

    <div v-else-if="currentProduct" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Left Column - Product Info -->
      <div class="bg-white rounded-lg shadow p-6">
        <!-- Product Name -->
        <h1 class="text-3xl font-bold text-gray-900 mb-1">{{ currentProduct.name }}</h1>
        
        <!-- Product Code -->
        <p class="text-gray-500 text-sm mb-2">Product Code: {{ currentProduct.product_code || 'N/A' }}</p>
        
        <!-- Price -->
        <p class="text-2xl font-bold text-green-600 mb-2">${{ formatPrice(currentProduct.price) }}</p>
        
        <!-- Stock Status -->
        <p class="text-sm mb-3" :class="currentProduct.stock_quantity > 0 ? 'text-green-600' : 'text-red-600'">
          {{ currentProduct.stock_quantity > 0 ? `In Stock: ${currentProduct.stock_quantity} units` : 'Out of Stock' }}
        </p>
        
        <!-- Category Badge -->
        <span 
          v-if="currentProduct.category"
          class="inline-block px-3 py-1 text-xs font-semibold rounded bg-blue-500 text-white mb-4"
        >
          {{ currentProduct.category }}
        </span>
        
        <!-- Description -->
        <div class="mb-6">
          <h2 class="text-lg font-bold text-gray-900 mb-2">Description</h2>
          <p class="text-gray-600">{{ currentProduct.description || 'No description available' }}</p>
        </div>
        
        <!-- Specifications -->
        <div class="mb-6">
          <h2 class="text-lg font-bold text-gray-900 mb-3">Specifications</h2>
          <div class="space-y-3">
            <div>
              <p class="text-gray-500 text-sm">Category</p>
              <p class="text-gray-900">{{ currentProduct.category || 'N/A' }}</p>
            </div>
            <div>
              <p class="text-gray-500 text-sm">Brand</p>
              <p class="text-gray-900">{{ currentProduct.brand?.name || 'N/A' }}</p>
            </div>
            <div>
              <p class="text-gray-500 text-sm">Price</p>
              <p class="text-gray-900">${{ formatPrice(currentProduct.price) }}</p>
            </div>
            <div>
              <p class="text-gray-500 text-sm">Stock</p>
              <p class="text-gray-900">{{ currentProduct.stock_quantity }}</p>
            </div>
          </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-3">
          <button
            v-if="currentProduct.stock_quantity > 0"
            @click="handleBuyNow"
            :disabled="processing"
            class="px-6 py-2 bg-blue-500 text-white font-medium rounded hover:bg-blue-600 transition-colors"
          >
            Buy Now
          </button>
          <button
            v-if="currentProduct.stock_quantity > 0"
            @click="handleAddToCart"
            :disabled="adding"
            class="px-6 py-2 bg-green-500 text-white font-medium rounded hover:bg-green-600 transition-colors"
          >
            {{ adding ? 'Adding...' : 'Add to Cart' }}
          </button>
          <NuxtLink
            to="/products"
            class="px-6 py-2 bg-gray-500 text-white font-medium rounded hover:bg-gray-600 transition-colors"
          >
            Back to Products
          </NuxtLink>
        </div>
        
        <!-- Success Message -->
        <div v-if="added" class="mt-4 bg-green-50 text-green-600 p-3 rounded-md flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          Added to cart successfully!
        </div>
      </div>

      <!-- Right Column - Product Image -->
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold text-center text-gray-900 mb-2">Product Image</h2>
        
        <div class="bg-gray-50 rounded-lg p-4 flex items-center justify-center min-h-[200px] mb-4">
          <img
            v-if="currentProduct.image"
            :src="getImageUrl(currentProduct.image_url || currentProduct.image)"
            :alt="currentProduct.name"
            class="max-h-64 max-w-full object-contain"
            @error="handleImageError"
          />
          <p v-else class="text-gray-400">Image not available</p>
        </div>
        
        <!-- Product Name Button -->
        <button 
          class="w-full py-3 bg-blue-500 text-white font-medium rounded hover:bg-blue-600 transition-colors"
        >
          {{ currentProduct.name }}
        </button>
      </div>
    </div>

    <div v-else class="text-center py-12 text-gray-600">
      Product not found.
    </div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute()
const router = useRouter()
const { currentProduct, loading, fetchProduct } = useProducts()
const { addToCart } = useCart()
const { setBuyNowItem } = useBuyNow()
const { isAuthenticated } = useAuth()

const quantity = ref(1)
const adding = ref(false)
const processing = ref(false)
const added = ref(false)

const placeholderImage = 'data:image/svg+xml,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="300" height="300" viewBox="0 0 300 300"><rect width="300" height="300" fill="#f3f4f6"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#9ca3af" font-family="sans-serif" font-size="16">No Image</text></svg>')

const getImageUrl = (url?: string) => {
  if (!url || url === '') return placeholderImage
  
  // If it's already a full URL, return as is
  if (url.startsWith('http://') || url.startsWith('https://')) {
    return url
  }
  
  // If it starts with /storage, convert to Laravel backend URL
  if (url.startsWith('/storage/')) {
    return `http://localhost:8000${url}`
  }
  
  // If it starts with storage/ (no leading slash), convert to Laravel backend URL
  if (url.startsWith('storage/')) {
    return `http://localhost:8000/${url}`
  }
  
  // If it's a relative path, convert to Laravel backend URL
  return `http://localhost:8000/storage/products/${url}`
}

const handleImageError = (e: Event) => {
  const img = e.target as HTMLImageElement
  img.src = placeholderImage
}

const formatPrice = (price: number) => {
  return price?.toLocaleString('en-US', { minimumFractionDigits: 2 }) || '0.00'
}

const handleAddToCart = async () => {
  if (!currentProduct.value) return
  
  // Check if user is logged in
  if (!isAuthenticated.value) {
    // Store the product info for after login
    sessionStorage.setItem('pendingCartItem', JSON.stringify({
      product_id: currentProduct.value.product_id,
      quantity: quantity.value
    }))
    router.push('/auth/login?redirect=' + encodeURIComponent(route.fullPath))
    return
  }
  
  adding.value = true
  try {
    await addToCart(currentProduct.value.product_id, quantity.value)
    added.value = true
    setTimeout(() => { added.value = false }, 3000)
  } catch (error) {
    console.error('Failed to add to cart:', error)
    alert('Failed to add to cart. Please try again.')
  } finally {
    adding.value = false
  }
}

const handleBuyNow = () => {
  if (!currentProduct.value) return
  
  // Check if user is logged in
  if (!isAuthenticated.value) {
    // Store the product info for after login
    sessionStorage.setItem('pendingBuyNow', JSON.stringify({
      product_id: currentProduct.value.product_id,
      name: currentProduct.value.name,
      price: currentProduct.value.price,
      image: currentProduct.value.image,
      quantity: quantity.value
    }))
    router.push('/auth/login?redirect=/checkout?mode=buynow')
    return
  }
  
  // Set the buy now item and navigate to checkout
  setBuyNowItem({
    product_id: currentProduct.value.product_id,
    name: currentProduct.value.name,
    price: currentProduct.value.price,
    image: currentProduct.value.image
  }, quantity.value)
  
  navigateTo('/checkout?mode=buynow')
}

onMounted(async () => {
  await fetchProduct(route.params.id as string)
})
</script>
